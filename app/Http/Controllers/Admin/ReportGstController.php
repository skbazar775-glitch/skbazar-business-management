<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GstExport;
use Illuminate\Support\Facades\Log;

class ReportGstController extends Controller
{
    public function index(Request $request)
    {
        // Validate inputs
        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date|before_or_equal:end_date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'gst_percent' => 'nullable|in:0,1,2,3', // 0%, 12%, 18%, 28%
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Default date range: last 30 days
        $start_date = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $end_date = $request->input('end_date', now()->format('Y-m-d'));
        $gst_percent = $request->input('gst_percent', null); // Null means all GST percentages

        // Initialize GST data
        $dailyGst = [];
        $monthlyGst = [];
        $gstWise = [
            '0' => ['total_gst' => 0, 'cgst' => 0, 'sgst' => 0, 'count' => 0],
            '1' => ['total_gst' => 0, 'cgst' => 0, 'sgst' => 0, 'count' => 0],
            '2' => ['total_gst' => 0, 'cgst' => 0, 'sgst' => 0, 'count' => 0],
            '3' => ['total_gst' => 0, 'cgst' => 0, 'sgst' => 0, 'count' => 0],
        ];

        // Base query
        $invoiceQuery = Invoice::query()
            ->with(['items' => function ($query) {
                $query->select('invoice_id', 'gst_percent', 'gst_value');
            }])
            ->whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);

        if ($gst_percent !== null) {
            $invoiceQuery->whereHas('items', function ($query) use ($gst_percent) {
                $query->where('gst_percent', $gst_percent);
            });
        }

        $invoices = $invoiceQuery->get();

        // Calculate aggregates
        $totalGst = 0;
        $totalCgst = 0;
        $totalSgst = 0;
        $totalInvoices = 0;

        foreach ($invoices as $invoice) {
            $invoiceGst = $invoice->total_gst ?? 0;
            $invoiceCgst = $invoice->cgst ?? ($invoiceGst / 2);
            $invoiceSgst = $invoice->sgst ?? ($invoiceGst / 2);
            $totalInvoices++;

            $totalGst += $invoiceGst;
            $totalCgst += $invoiceCgst;
            $totalSgst += $invoiceSgst;

            // Daily GST
            $dateKey = $invoice->created_at->format('Y-m-d');
            $dailyGst[$dateKey] = [
                'total_gst' => ($dailyGst[$dateKey]['total_gst'] ?? 0) + $invoiceGst,
                'cgst' => ($dailyGst[$dateKey]['cgst'] ?? 0) + $invoiceCgst,
                'sgst' => ($dailyGst[$dateKey]['sgst'] ?? 0) + $invoiceSgst,
            ];

            // Monthly GST
            $monthKey = $invoice->created_at->format('Y-m');
            $monthlyGst[$monthKey] = [
                'total_gst' => ($monthlyGst[$monthKey]['total_gst'] ?? 0) + $invoiceGst,
                'cgst' => ($monthlyGst[$monthKey]['cgst'] ?? 0) + $invoiceCgst,
                'sgst' => ($monthlyGst[$monthKey]['sgst'] ?? 0) + $invoiceSgst,
            ];

            // GST-wise breakdown
            foreach ($invoice->items as $item) {
                $gstPercent = $item->gst_percent;
                if (isset($gstWise[$gstPercent])) {
                    $gstWise[$gstPercent]['total_gst'] += $item->gst_value;
                    $gstWise[$gstPercent]['cgst'] += $item->gst_value / 2;
                    $gstWise[$gstPercent]['sgst'] += $item->gst_value / 2;
                    $gstWise[$gstPercent]['count']++;
                }
            }
        }

        // Log for debugging
        Log::debug('GST Report Data', [
            'total_gst' => $totalGst,
            'total_cgst' => $totalCgst,
            'total_sgst' => $totalSgst,
            'total_invoices' => $totalInvoices,
            'daily_gst' => $dailyGst,
            'monthly_gst' => $monthlyGst,
            'gst_wise' => $gstWise,
            'filters' => $request->all()
        ]);

        // Prepare data for view
        $gstData = [
            'total_gst' => number_format($totalGst, 2),
            'total_cgst' => number_format($totalCgst, 2),
            'total_sgst' => number_format($totalSgst, 2),
            'total_invoices' => $totalInvoices,
            'average_gst' => $totalInvoices > 0 ? number_format($totalGst / $totalInvoices, 2) : '0.00',
            'daily_gst' => $dailyGst,
            'monthly_gst' => $monthlyGst,
            'gst_wise' => $gstWise,
        ];

        // Check if no data found
        if ($totalInvoices == 0) {
            return view('admin.report.gst.index', [
                'gstData' => $gstData,
                'invoices' => collect([])->paginate(10),
            ])->with('warning', 'No invoices found for the selected filters.');
        }

        // Paginate invoices for table
        $invoices = $invoiceQuery->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.report.gst.index', compact('gstData', 'invoices'));
    }

    public function export(Request $request)
    {
        // Validate inputs
        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date|before_or_equal:end_date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'gst_percent' => 'nullable|in:0,1,2,3',
        ]);

        if ($validator->fails()) {
            Log::error('GST Export Validation Failed', ['errors' => $validator->errors()]);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $filename = 'gst_report_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
            return Excel::download(new GstExport($request), $filename);
        } catch (\Exception $e) {
            Log::error('GST Export Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'filters' => $request->all()
            ]);
            return redirect()->back()->with('error', 'Failed to export GST report. Please try again.');
        }
    }
}
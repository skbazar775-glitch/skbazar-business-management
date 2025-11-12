<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TurnoverExport;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ReportTurnoverController extends Controller
{
    public function index(Request $request)
    {
        // Validate inputs
        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date|before_or_equal:end_date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'order_status' => 'nullable|in:0,1,2,3,4,5', // Pending, Confirmed, Packed, Shipped, Delivered, Canceled
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Default date range: last 30 days
        $start_date = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $end_date = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $order_status = $request->input('order_status', 4); // Default: Delivered

        // Initialize revenue data
        $dailyRevenue = [];
        $monthlyRevenue = [];
        $yearlyRevenue = [];
        $orderRevenueTotal = 0;
        $invoiceRevenueTotal = 0;
        $totalOrders = 0;
        $totalInvoices = 0;

        // Query orders
        $orderQuery = Order::query()
            ->where('status', $order_status)
            ->whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);

        $orders = $orderQuery->get();

        // Query invoices
        $invoiceQuery = Invoice::query()
            ->with(['items' => function ($query) {
                $query->select('invoice_id', 'gst_percent', 'gst_value');
            }])
            ->whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);

        $invoices = $invoiceQuery->get();

        // Calculate order revenue
        foreach ($orders as $order) {
            $orderRevenue = $order->total_amount ?? 0;
            $orderRevenueTotal += $orderRevenue;
            $totalOrders++;

            // Daily revenue
            $dateKey = $order->created_at->format('Y-m-d');
            $dailyRevenue[$dateKey]['order_revenue'] = ($dailyRevenue[$dateKey]['order_revenue'] ?? 0) + $orderRevenue;
            $dailyRevenue[$dateKey]['total_revenue'] = ($dailyRevenue[$dateKey]['total_revenue'] ?? 0) + $orderRevenue;

            // Monthly revenue
            $monthKey = $order->created_at->format('Y-m');
            $monthlyRevenue[$monthKey]['order_revenue'] = ($monthlyRevenue[$monthKey]['order_revenue'] ?? 0) + $orderRevenue;
            $monthlyRevenue[$monthKey]['total_revenue'] = ($monthlyRevenue[$monthKey]['total_revenue'] ?? 0) + $orderRevenue;

            // Yearly revenue
            $yearKey = $order->created_at->format('Y');
            $yearlyRevenue[$yearKey]['order_revenue'] = ($yearlyRevenue[$yearKey]['order_revenue'] ?? 0) + $orderRevenue;
            $yearlyRevenue[$yearKey]['total_revenue'] = ($yearlyRevenue[$yearKey]['total_revenue'] ?? 0) + $orderRevenue;
        }

        // Calculate invoice revenue and GST breakdown
        $gstWise = [
            '0' => ['total_gst' => 0, 'cgst' => 0, 'sgst' => 0, 'count' => 0],
            '1' => ['total_gst' => 0, 'cgst' => 0, 'sgst' => 0, 'count' => 0],
            '2' => ['total_gst' => 0, 'cgst' => 0, 'sgst' => 0, 'count' => 0],
            '3' => ['total_gst' => 0, 'cgst' => 0, 'sgst' => 0, 'count' => 0],
        ];

        foreach ($invoices as $invoice) {
            $invoiceRevenue = $invoice->final_amount ?? 0;
            $invoiceRevenueTotal += $invoiceRevenue;
            $totalInvoices++;

            // Daily revenue
            $dateKey = $invoice->created_at->format('Y-m-d');
            $dailyRevenue[$dateKey]['invoice_revenue'] = ($dailyRevenue[$dateKey]['invoice_revenue'] ?? 0) + $invoiceRevenue;
            $dailyRevenue[$dateKey]['total_revenue'] = ($dailyRevenue[$dateKey]['total_revenue'] ?? 0) + $invoiceRevenue;

            // Monthly revenue
            $monthKey = $invoice->created_at->format('Y-m');
            $monthlyRevenue[$monthKey]['invoice_revenue'] = ($monthlyRevenue[$monthKey]['invoice_revenue'] ?? 0) + $invoiceRevenue;
            $monthlyRevenue[$monthKey]['total_revenue'] = ($monthlyRevenue[$monthKey]['total_revenue'] ?? 0) + $invoiceRevenue;

            // Yearly revenue
            $yearKey = $invoice->created_at->format('Y');
            $yearlyRevenue[$yearKey]['invoice_revenue'] = ($yearlyRevenue[$yearKey]['invoice_revenue'] ?? 0) + $invoiceRevenue;
            $yearlyRevenue[$yearKey]['total_revenue'] = ($yearlyRevenue[$yearKey]['total_revenue'] ?? 0) + $invoiceRevenue;

            // GST breakdown
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
        Log::debug('Turnover Report Data', [
            'total_revenue' => $orderRevenueTotal + $invoiceRevenueTotal,
            'order_revenue' => $orderRevenueTotal,
            'invoice_revenue' => $invoiceRevenueTotal,
            'total_orders' => $totalOrders,
            'total_invoices' => $totalInvoices,
            'daily_revenue' => $dailyRevenue,
            'monthly_revenue' => $monthlyRevenue,
            'yearly_revenue' => $yearlyRevenue,
            'gst_wise' => $gstWise,
            'filters' => $request->all()
        ]);

        // Prepare data for view
        $revenueData = [
            'total_revenue' => number_format($orderRevenueTotal + $invoiceRevenueTotal, 2),
            'order_revenue' => number_format($orderRevenueTotal, 2),
            'invoice_revenue' => number_format($invoiceRevenueTotal, 2),
            'total_orders' => $totalOrders,
            'total_invoices' => $totalInvoices,
            'average_revenue' => ($totalOrders + $totalInvoices) > 0 ? number_format(($orderRevenueTotal + $invoiceRevenueTotal) / ($totalOrders + $totalInvoices), 2) : '0.00',
            'daily_revenue' => $dailyRevenue,
            'monthly_revenue' => $monthlyRevenue,
            'yearly_revenue' => $yearlyRevenue,
            'gst_wise' => $gstWise,
        ];

        // Check if no data found
        if ($totalOrders == 0 && $totalInvoices == 0) {
            return view('admin.report.turnover.index', [
                'revenueData' => $revenueData,
                'orders' => collect([])->paginate(10),
                'invoices' => collect([])->paginate(10),
            ])->with('warning', 'No orders or invoices found for the selected filters.');
        }

        // Paginate orders and invoices
        $orders = $orderQuery->orderBy('created_at', 'desc')->paginate(10, ['*'], 'orders_page');
        $invoices = $invoiceQuery->orderBy('created_at', 'desc')->paginate(10, ['*'], 'invoices_page');

        return view('admin.report.turnover.index', compact('revenueData', 'orders', 'invoices'));
    }

    public function export(Request $request)
    {
        // Validate inputs
        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date|before_or_equal:end_date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'order_status' => 'nullable|in:0,1,2,3,4,5',
        ]);

        if ($validator->fails()) {
            Log::error('Turnover Export Validation Failed', ['errors' => $validator->errors()]);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $filename = 'turnover_report_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
            return Excel::download(new TurnoverExport($request), $filename);
        } catch (\Exception $e) {
            Log::error('Turnover Export Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'filters' => $request->all()
            ]);
            return redirect()->back()->with('error', 'Failed to export turnover report. Please try again.');
        }
    }
}
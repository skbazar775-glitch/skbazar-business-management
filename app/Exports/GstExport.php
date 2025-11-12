<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GstExport implements FromCollection, WithHeadings, WithStyles
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $start_date = $this->request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $end_date = $this->request->input('end_date', now()->format('Y-m-d'));
        $gst_percent = $this->request->input('gst_percent', null);

        // Initialize data arrays
        $dailyGst = [];
        $monthlyGst = [];
        $gstWise = [
            '0' => ['total_gst' => 0, 'cgst' => 0, 'sgst' => 0, 'count' => 0],
            '1' => ['total_gst' => 0, 'cgst' => 0, 'sgst' => 0, 'count' => 0],
            '2' => ['total_gst' => 0, 'cgst' => 0, 'sgst' => 0, 'count' => 0],
            '3' => ['total_gst' => 0, 'cgst' => 0, 'sgst' => 0, 'count' => 0],
        ];
        $invoiceDetails = [];

        // Query invoices
        $invoiceQuery = Invoice::query()
            ->with(['items' => function ($query) {
                $query->select('invoice_id', 'product_name', 'quantity', 'gst_percent', 'gst_value', 'total_price');
            }])
            ->whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);

        if ($gst_percent !== null) {
            $invoiceQuery->whereHas('items', function ($query) use ($gst_percent) {
                $query->where('gst_percent', $gst_percent);
            });
        }

        $invoices = $invoiceQuery->get();

        // Calculate aggregates
        foreach ($invoices as $invoice) {
            $invoiceGst = $invoice->total_gst ?? 0;
            $invoiceCgst = $invoice->cgst ?? ($invoiceGst / 2);
            $invoiceSgst = $invoice->sgst ?? ($invoiceGst / 2);

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

            // Invoice details
            $invoiceDetails[] = [
                'invoice_number' => $invoice->invoice_number,
                'customer_name' => $invoice->customer_name,
                'date' => $invoice->created_at->format('d M Y'),
                'total_price' => number_format($invoice->total_price, 2),
                'total_gst' => number_format($invoice->total_gst, 2),
                'cgst' => number_format($invoice->cgst ?? ($invoice->total_gst / 2), 2),
                'sgst' => number_format($invoice->sgst ?? ($invoice->total_gst / 2), 2),
                'payment_terms' => $invoice->payment_terms_text,
                'payment_mode' => $invoice->payment_mode_text,
            ];
        }

        // Log for debugging
        Log::debug('GST Export Data', [
            'daily_gst' => $dailyGst,
            'monthly_gst' => $monthlyGst,
            'gst_wise' => $gstWise,
            'invoice_count' => count($invoiceDetails),
            'filters' => $this->request->all()
        ]);

        // Combine data for export
        $exportData = [];

        // Daily GST
        $exportData[] = ['Daily GST', '', '', ''];
        $exportData[] = ['Date', 'Total GST (₹)', 'CGST (₹)', 'SGST (₹)'];
        foreach ($dailyGst as $date => $gst) {
            $exportData[] = [
                $date,
                number_format($gst['total_gst'], 2),
                number_format($gst['cgst'], 2),
                number_format($gst['sgst'], 2),
            ];
        }
        $exportData[] = ['', '', '', ''];

        // Monthly GST
        $exportData[] = ['Monthly GST', '', '', ''];
        $exportData[] = ['Month', 'Total GST (₹)', 'CGST (₹)', 'SGST (₹)'];
        foreach ($monthlyGst as $month => $gst) {
            $exportData[] = [
                \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y'),
                number_format($gst['total_gst'], 2),
                number_format($gst['cgst'], 2),
                number_format($gst['sgst'], 2),
            ];
        }
        $exportData[] = ['', '', '', ''];

        // GST-wise Breakdown
        $exportData[] = ['GST-wise Breakdown', '', '', '', ''];
        $exportData[] = ['GST Percent', 'Total GST (₹)', 'CGST (₹)', 'SGST (₹)', 'Item Count'];
        foreach ($gstWise as $percent => $data) {
            $exportData[] = [
                match ($percent) { '0' => '0%', '1' => '12%', '2' => '18%', '3' => '28%', default => 'Unknown' },
                number_format($data['total_gst'], 2),
                number_format($data['cgst'], 2),
                number_format($data['sgst'], 2),
                $data['count'],
            ];
        }
        $exportData[] = ['', '', '', '', ''];

        // Invoice Details
        $exportData[] = ['Invoice Details', '', '', '', '', '', '', ''];
        $exportData[] = ['Invoice Number', 'Customer Name', 'Date', 'Total Price (₹)', 'Total GST (₹)', 'CGST (₹)', 'SGST (₹)', 'Payment Terms', 'Payment Mode'];
        foreach ($invoiceDetails as $detail) {
            $exportData[] = [
                $detail['invoice_number'],
                $detail['customer_name'],
                $detail['date'],
                $detail['total_price'],
                $detail['total_gst'],
                $detail['cgst'],
                $detail['sgst'],
                $detail['payment_terms'],
                $detail['payment_mode'],
            ];
        }

        // If no data, return empty message
        if (empty($dailyGst) && empty($monthlyGst) && empty($invoiceDetails)) {
            $exportData[] = ['No data available for the selected filters.', '', '', '', '', '', '', ''];
        }

        return collect($exportData);
    }

    public function headings(): array
    {
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        $styles = [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FFCCCCCC']]],
            2 => ['font' => ['bold' => true]],
        ];

        // Dynamically find section headers
        $collection = $this->collection();
        $monthlyIndex = $collection->search(['Monthly GST', '', '', '']);
        if ($monthlyIndex !== false) {
            $styles[$monthlyIndex + 1] = ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FFCCCCCC']]];
            $styles[$monthlyIndex + 2] = ['font' => ['bold' => true]];
        }

        $gstWiseIndex = $collection->search(['GST-wise Breakdown', '', '', '', '']);
        if ($gstWiseIndex !== false) {
            $styles[$gstWiseIndex + 1] = ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FFCCCCCC']]];
            $styles[$gstWiseIndex + 2] = ['font' => ['bold' => true]];
        }

        $detailsIndex = $collection->search(['Invoice Details', '', '', '', '', '', '', '']);
        if ($detailsIndex !== false) {
            $styles[$detailsIndex + 1] = ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FFCCCCCC']]];
            $styles[$detailsIndex + 2] = ['font' => ['bold' => true]];
        }

        return $styles;
    }
}
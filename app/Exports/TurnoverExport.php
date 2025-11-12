<?php

namespace App\Exports;

use App\Models\Invoice;
use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TurnoverExport implements FromCollection, WithHeadings, WithStyles
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $start_date = $this->request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $end_date = $this->request->input('end_date', Carbon::now()->format('Y-m-d'));
        $order_status = $this->request->input('order_status', 4);

        // Initialize data arrays
        $dailyRevenue = [];
        $monthlyRevenue = [];
        $yearlyRevenue = [];
        $gstWise = [
            '0' => ['total_gst' => 0, 'cgst' => 0, 'sgst' => 0, 'count' => 0],
            '1' => ['total_gst' => 0, 'cgst' => 0, 'sgst' => 0, 'count' => 0],
            '2' => ['total_gst' => 0, 'cgst' => 0, 'sgst' => 0, 'count' => 0],
            '3' => ['total_gst' => 0, 'cgst' => 0, 'sgst' => 0, 'count' => 0],
        ];
        $orderDetails = [];
        $invoiceDetails = [];

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

            // Order details
            $orderDetails[] = [
                'unique_order_id' => $order->unique_order_id,
                'customer_name' => $order->user ? $order->user->name : 'N/A',
                'date' => $order->created_at->format('d M Y'),
                'total_amount' => number_format($order->total_amount, 2),
                'payment_status' => $order->payment_status_text,
                'order_status' => $order->status_text,
            ];
        }

        // Calculate invoice revenue and GST breakdown
        foreach ($invoices as $invoice) {
            $invoiceRevenue = $invoice->final_amount ?? 0;

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

            // Invoice details
            $invoiceDetails[] = [
                'invoice_number' => $invoice->invoice_number,
                'customer_name' => $invoice->customer_name,
                'date' => $invoice->created_at->format('d M Y'),
                'final_amount' => number_format($invoice->final_amount, 2),
                'total_gst' => number_format($invoice->total_gst, 2),
                'cgst' => number_format($invoice->cgst ?? ($invoice->total_gst / 2), 2),
                'sgst' => number_format($invoice->sgst ?? ($invoice->total_gst / 2), 2),
                'payment_terms' => $invoice->payment_terms_text,
                'payment_mode' => $invoice->payment_mode_text,
            ];
        }

        // Log for debugging
        Log::debug('Turnover Export Data', [
            'daily_revenue' => $dailyRevenue,
            'monthly_revenue' => $monthlyRevenue,
            'yearly_revenue' => $yearlyRevenue,
            'gst_wise' => $gstWise,
            'order_count' => count($orderDetails),
            'invoice_count' => count($invoiceDetails),
            'filters' => $this->request->all()
        ]);

        // Combine data for export
        $exportData = [];

        // Daily Revenue
        $exportData[] = ['Daily Revenue', '', '', ''];
        $exportData[] = ['Date', 'Order Revenue (₹)', 'Invoice Revenue (₹)', 'Total Revenue (₹)'];
        foreach ($dailyRevenue as $date => $revenue) {
            $exportData[] = [
                $date,
                number_format($revenue['order_revenue'] ?? 0, 2),
                number_format($revenue['invoice_revenue'] ?? 0, 2),
                number_format($revenue['total_revenue'], 2),
            ];
        }
        $exportData[] = ['', '', '', ''];

        // Monthly Revenue
        $exportData[] = ['Monthly Revenue', '', '', ''];
        $exportData[] = ['Month', 'Order Revenue (₹)', 'Invoice Revenue (₹)', 'Total Revenue (₹)'];
        foreach ($monthlyRevenue as $month => $revenue) {
            $exportData[] = [
                \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y'),
                number_format($revenue['order_revenue'] ?? 0, 2),
                number_format($revenue['invoice_revenue'] ?? 0, 2),
                number_format($revenue['total_revenue'], 2),
            ];
        }
        $exportData[] = ['', '', '', ''];

        // Yearly Revenue
        $exportData[] = ['Yearly Revenue', '', '', ''];
        $exportData[] = ['Year', 'Order Revenue (₹)', 'Invoice Revenue (₹)', 'Total Revenue (₹)'];
        foreach ($yearlyRevenue as $year => $revenue) {
            $exportData[] = [
                $year,
                number_format($revenue['order_revenue'] ?? 0, 2),
                number_format($revenue['invoice_revenue'] ?? 0, 2),
                number_format($revenue['total_revenue'], 2),
            ];
        }
        $exportData[] = ['', '', '', ''];

        // GST-wise Breakdown
        $exportData[] = ['GST-wise Breakdown (Invoices)', '', '', '', ''];
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

        // Order Details
        $exportData[] = ['Order Details', '', '', '', '', ''];
        $exportData[] = ['Order ID', 'Customer Name', 'Date', 'Total Amount (₹)', 'Payment Status', 'Order Status'];
        foreach ($orderDetails as $detail) {
            $exportData[] = [
                $detail['unique_order_id'],
                $detail['customer_name'],
                $detail['date'],
                $detail['total_amount'],
                $detail['payment_status'],
                $detail['order_status'],
            ];
        }
        $exportData[] = ['', '', '', '', '', ''];

        // Invoice Details
        $exportData[] = ['Invoice Details', '', '', '', '', '', '', ''];
        $exportData[] = ['Invoice Number', 'Customer Name', 'Date', 'Final Amount (₹)', 'Total GST (₹)', 'CGST (₹)', 'SGST (₹)', 'Payment Terms', 'Payment Mode'];
        foreach ($invoiceDetails as $detail) {
            $exportData[] = [
                $detail['invoice_number'],
                $detail['customer_name'],
                $detail['date'],
                $detail['final_amount'],
                $detail['total_gst'],
                $detail['cgst'],
                $detail['sgst'],
                $detail['payment_terms'],
                $detail['payment_mode'],
            ];
        }

        // If no data, return empty message
        if (empty($dailyRevenue) && empty($monthlyRevenue) && empty($yearlyRevenue)) {
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
        $monthlyIndex = $collection->search(['Monthly Revenue', '', '', '']);
        if ($monthlyIndex !== false) {
            $styles[$monthlyIndex + 1] = ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FFCCCCCC']]];
            $styles[$monthlyIndex + 2] = ['font' => ['bold' => true]];
        }

        $yearlyIndex = $collection->search(['Yearly Revenue', '', '', '']);
        if ($yearlyIndex !== false) {
            $styles[$yearlyIndex + 1] = ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FFCCCCCC']]];
            $styles[$yearlyIndex + 2] = ['font' => ['bold' => true]];
        }

        $gstWiseIndex = $collection->search(['GST-wise Breakdown (Invoices)', '', '', '', '']);
        if ($gstWiseIndex !== false) {
            $styles[$gstWiseIndex + 1] = ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FFCCCCCC']]];
            $styles[$gstWiseIndex + 2] = ['font' => ['bold' => true]];
        }

        $orderDetailsIndex = $collection->search(['Order Details', '', '', '', '', '']);
        if ($orderDetailsIndex !== false) {
            $styles[$orderDetailsIndex + 1] = ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FFCCCCCC']]];
            $styles[$orderDetailsIndex + 2] = ['font' => ['bold' => true]];
        }

        $invoiceDetailsIndex = $collection->search(['Invoice Details', '', '', '', '', '', '', '']);
        if ($invoiceDetailsIndex !== false) {
            $styles[$invoiceDetailsIndex + 1] = ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FFCCCCCC']]];
            $styles[$invoiceDetailsIndex + 2] = ['font' => ['bold' => true]];
        }

        return $styles;
    }
}   
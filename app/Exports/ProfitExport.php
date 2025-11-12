<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProfitExport implements FromCollection, WithHeadings, WithStyles
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
        $status = $this->request->input('status', null);

        // Initialize data arrays
        $dayWiseProfit = [];
        $monthWiseProfit = [];
        $yearWiseProfit = [];
        $orderDetails = [];

        // Query orders
        $orderQuery = Order::query()
            ->with(['orderItems' => function ($query) {
                $query->select('order_id', 'product_id', 'quantity');
            }])
            ->whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);

        if ($status !== null) {
            $orderQuery->where('status', $status);
        }

        $orders = $orderQuery->get();

        // Calculate profits
        foreach ($orders as $order) {
            $orderCost = 0;
            foreach ($order->orderItems as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $orderCost += $item->quantity * $product->price_p;
                }
            }

            $orderProfit = $order->total_amount - $orderCost;

            if ($orderProfit >= 0) {
                // Day-wise
                $dateKey = $order->created_at->format('Y-m-d');
                $dayWiseProfit[$dateKey] = ($dayWiseProfit[$dateKey] ?? 0) + $orderProfit;

                // Month-wise
                $monthKey = $order->created_at->format('Y-m');
                $monthWiseProfit[$monthKey] = ($monthWiseProfit[$monthKey] ?? 0) + $orderProfit;

                // Year-wise
                $yearKey = $order->created_at->format('Y');
                $yearWiseProfit[$yearKey] = ($yearWiseProfit[$yearKey] ?? 0) + $orderProfit;

                // Order details
                $orderDetails[] = [
                    'order_id' => $order->unique_order_id ?? $order->id,
                    'date' => $order->created_at->format('d M Y'),
                    'total_amount' => number_format($order->total_amount, 2),
                    'cost' => number_format($orderCost, 2),
                    'profit' => number_format($orderProfit, 2),
                    'status' => match ($order->status) {
                        0 => 'Active',
                        1 => 'Inactive',
                        2 => 'Out of Stock',
                        3 => 'Bestseller',
                        4 => 'Offer',
                        5 => 'New',
                        default => 'Unknown',
                    },
                ];
            }
        }

        // Log for debugging
        Log::debug('Profit Export Data', [
            'day_wise_profit' => $dayWiseProfit,
            'month_wise_profit' => $monthWiseProfit,
            'year_wise_profit' => $yearWiseProfit,
            'order_count' => count($orderDetails),
            'filters' => $this->request->all()
        ]);

        // Combine data for export
        $exportData = [];

        // Day-Wise Profit
        $exportData[] = ['Day-Wise Profit', ''];
        $exportData[] = ['Date', 'Profit (₹)'];
        foreach ($dayWiseProfit as $date => $profit) {
            $exportData[] = [$date, number_format($profit, 2)];
        }
        $exportData[] = ['', ''];

        // Month-Wise Profit
        $exportData[] = ['Month-Wise Profit', ''];
        $exportData[] = ['Month', 'Profit (₹)'];
        foreach ($monthWiseProfit as $month => $profit) {
            $exportData[] = [\Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y'), number_format($profit, 2)];
        }
        $exportData[] = ['', ''];

        // Year-Wise Profit
        $exportData[] = ['Year-Wise Profit', ''];
        $exportData[] = ['Year', 'Profit (₹)'];
        foreach ($yearWiseProfit as $year => $profit) {
            $exportData[] = [$year, number_format($profit, 2)];
        }
        $exportData[] = ['', ''];

        // Order Details
        $exportData[] = ['Order Details', '', '', '', '', ''];
        $exportData[] = ['Order ID', 'Date', 'Total Amount (₹)', 'Cost (₹)', 'Profit (₹)', 'Status'];
        foreach ($orderDetails as $detail) {
            $exportData[] = [
                $detail['order_id'],
                $detail['date'],
                $detail['total_amount'],
                $detail['cost'],
                $detail['profit'],
                $detail['status'],
            ];
        }

        return collect($exportData);
    }

    public function headings(): array
    {
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FFCCCCCC']]],
            2 => ['font' => ['bold' => true]],
            ($this->collection()->search(['Month-Wise Profit', '']) + 1) => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FFCCCCCC']]],
            ($this->collection()->search(['Month-Wise Profit', '']) + 2) => ['font' => ['bold' => true]],
            ($this->collection()->search(['Year-Wise Profit', '']) + 1) => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FFCCCCCC']]],
            ($this->collection()->search(['Year-Wise Profit', '']) + 2) => ['font' => ['bold' => true]],
            ($this->collection()->search(['Order Details', '', '', '', '', '']) + 1) => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FFCCCCCC']]],
            ($this->collection()->search(['Order Details', '', '', '', '', '']) + 2) => ['font' => ['bold' => true]],
        ];
    }
}
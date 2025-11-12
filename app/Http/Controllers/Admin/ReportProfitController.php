<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProfitExport;
use Illuminate\Support\Facades\Log;

class ReportProfitController extends Controller
{
    public function index(Request $request)
    {
        // Validate inputs
        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date|before_or_equal:end_date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|numeric|in:0,1,2,3,4', // Only valid order statuses
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Default date range: last 30 days, default status: 4 (Delivered)
        $start_date = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $end_date = $request->input('end_date', now()->format('Y-m-d'));
        $status = $request->input('status', 4); // Default to Delivered

        // Initialize profit data arrays
        $dayWiseProfit = [];
        $monthWiseProfit = [];
        $yearWiseProfit = [];

        // Base query for orders, exclude status = 5 (Canceled)
        $orderQuery = Order::query()
            ->with(['orderItems' => function ($query) {
                $query->select('order_id', 'product_id', 'quantity');
            }])
            ->where('status', '!=', 5) // Exclude Canceled orders
            ->whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);

        if ($status !== null) {
            $orderQuery->where('status', $status);
        }

        // Get orders for profit calculation
        $orders = $orderQuery->get();

        // Calculate profits
        $totalProfit = 0;
        $totalOrders = 0;

        foreach ($orders as $order) {
            $orderCost = 0;

            // Calculate cost for each item
            foreach ($order->orderItems as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $orderCost += $item->quantity * $product->price_p;
                }
            }

            // Calculate profit for this order
            $orderProfit = $order->total_amount - $orderCost;

            if ($orderProfit >= 0) {
                $totalProfit += $orderProfit;
                $totalOrders++;

                // Day-wise profit
                $dateKey = $order->created_at->format('Y-m-d');
                $dayWiseProfit[$dateKey] = ($dayWiseProfit[$dateKey] ?? 0) + $orderProfit;

                // Month-wise profit
                $monthKey = $order->created_at->format('Y-m');
                $monthWiseProfit[$monthKey] = ($monthWiseProfit[$monthKey] ?? 0) + $orderProfit;

                // Year-wise profit
                $yearKey = $order->created_at->format('Y');
                $yearWiseProfit[$yearKey] = ($yearWiseProfit[$yearKey] ?? 0) + $orderProfit;
            }
        }

        // Log for debugging
        Log::debug('Profit Report Data', [
            'total_profit' => $totalProfit,
            'total_orders' => $totalOrders,
            'day_wise_profit' => $dayWiseProfit,
            'month_wise_profit' => $monthWiseProfit,
            'year_wise_profit' => $yearWiseProfit,
            'filters' => $request->all()
        ]);

        // Prepare data for view
        $profitData = [
            'total_profit' => number_format($totalProfit, 2),
            'total_orders' => $totalOrders,
            'average_profit' => $totalOrders > 0 ? number_format($totalProfit / $totalOrders, 2) : '0.00', // Fixed key name
            'day_wise_profit' => $dayWiseProfit,
            'month_wise_profit' => $monthWiseProfit,
            'year_wise_profit' => $yearWiseProfit,
        ];

        // Create a separate query for paginated orders
        $paginatedOrdersQuery = Order::query()
            ->with(['orderItems' => function ($query) {
                $query->select('order_id', 'product_id', 'quantity');
            }])
            ->where('status', '!=', 5) // Exclude Canceled orders
            ->whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);

        if ($status !== null) {
            $paginatedOrdersQuery->where('status', $status);
        }

        // Paginate orders for table
        $orders = $paginatedOrdersQuery->orderBy('created_at', 'desc')->paginate(10);

        // Check if no data found
        if ($totalOrders == 0) {
            return view('admin.report.profit.index', [
                'profitData' => $profitData,
                'orders' => $orders,
            ])->with('warning', 'No orders found for the selected filters.');
        }

        return view('admin.report.profit.index', compact('profitData', 'orders'));
    }

    public function export(Request $request)
    {
        // Validate inputs
        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date|before_or_equal:end_date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|numeric|in:0,1,2,3,4',
        ]);

        if ($validator->fails()) {
            Log::error('Export Validation Failed', ['errors' => $validator->errors()]);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $filename = 'profit_report_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
            return Excel::download(new ProfitExport($request), $filename);
        } catch (\Exception $e) {
            Log::error('Profit Export Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'filters' => $request->all()
            ]);
            return redirect()->back()->with('error', 'Failed to export profit report. Please try again.');
        }
    }
}
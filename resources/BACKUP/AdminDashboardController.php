<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\BookedService;
use App\Models\Stock;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function dashboard()
    {
        // Define date ranges
        $now = Carbon::now();
        $startOfCurrentMonth = $now->startOfMonth();
        $endOfCurrentMonth = $now->endOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();
        $startOfCurrentWeek = $now->startOfWeek();
        $endOfCurrentWeek = $now->endOfWeek();
        $startOfLastWeek = $now->copy()->subWeek()->startOfWeek();
        $endOfLastWeek = $now->copy()->subWeek()->endOfWeek();

        // Helper function to calculate sales, profit, and loss for a given date range
        $calculateFinancials = function ($start, $end, $status = 4) {
            // Sales: Sum of order total_amount (status = 4) and invoice final_amount
            $sales = Order::where('status', $status)
                ->whereBetween('created_at', [$start, $end])
                ->sum('total_amount') +
                Invoice::whereBetween('created_at', [$start, $end])
                ->sum('final_amount');

            // Profit and Loss: Calculate based on order items
            $orderItems = Order::where('status', $status)
                ->whereBetween('created_at', [$start, $end])
                ->with(['orderItems' => function ($query) {
                    $query->select('order_id', 'product_id', 'quantity');
                }])
                ->get();

            $profit = 0;
            $loss = 0;

            foreach ($orderItems as $order) {
                $orderCost = 0;
                foreach ($order->orderItems as $item) {
                    $product = Product::where('id', $item->product_id)->first();
                    if ($product) {
                        $orderCost += $item->quantity * $product->price_p;
                    }
                }
                $orderProfit = $order->total_amount - $orderCost;
                if ($orderProfit >= 0) {
                    $profit += $orderProfit;
                } else {
                    $loss += abs($orderProfit);
                }
            }

            return [
                'sales' => (float) $sales,
                'profit' => (float) $profit,
                'loss' => (float) $loss,
            ];
        };

        // Total Sales, Profit, and Loss (current month vs last month)
        $currentMonthFinancials = $calculateFinancials($startOfCurrentMonth, $endOfCurrentMonth);
        $lastMonthFinancials = $calculateFinancials($startOfLastMonth, $endOfLastMonth);

        $currentMonthSales = $currentMonthFinancials['sales'];
        $lastMonthSales = $lastMonthFinancials['sales'];
        $salesPercentageChange = $lastMonthSales > 0
            ? round((($currentMonthSales - $lastMonthSales) / $lastMonthSales) * 100, 1)
            : ($currentMonthSales > 0 ? 100 : 0);

        $currentMonthProfit = $currentMonthFinancials['profit'];
        $lastMonthProfit = $lastMonthFinancials['profit'];
        $profitPercentageChange = $lastMonthProfit > 0
            ? round((($currentMonthProfit - $lastMonthProfit) / $lastMonthProfit) * 100, 1)
            : ($currentMonthProfit > 0 ? 100 : 0);

        $currentMonthLoss = $currentMonthFinancials['loss'];
        $lastMonthLoss = $lastMonthFinancials['loss'];
        $lossPercentageChange = $lastMonthLoss > 0
            ? round((($lastMonthLoss - $currentMonthLoss) / $lastMonthLoss) * 100, 1)
            : ($currentMonthLoss > 0 ? -100 : 0);

        // Pending and Confirmed Orders (current week vs last week)
        $currentWeekPendingOrders = Order::where('status', 0)
            ->whereBetween('created_at', [$startOfCurrentWeek, $endOfCurrentWeek])
            ->count();
        $currentWeekConfirmedOrders = Order::where('status', 1)
            ->whereBetween('created_at', [$startOfCurrentWeek, $endOfCurrentWeek])
            ->count();
        $lastWeekPendingOrders = Order::where('status', 0)
            ->whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])
            ->count();
        $lastWeekConfirmedOrders = Order::where('status', 1)
            ->whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])
            ->count();

        $currentWeekOrders = $currentWeekPendingOrders + $currentWeekConfirmedOrders;
        $lastWeekOrders = $lastWeekPendingOrders + $lastWeekConfirmedOrders;
        $pendingOrdersPercentageChange = $lastWeekOrders > 0
            ? round((($currentWeekOrders - $lastWeekOrders) / $lastWeekOrders) * 100, 1)
            : ($currentWeekOrders > 0 ? 100 : 0);

        // Service Bookings (current week vs last week)
        $currentWeekServices = BookedService::whereIn('status', [0, 1])
            ->whereBetween('created_at', [$startOfCurrentWeek, $endOfCurrentWeek])
            ->count();
        $lastWeekServices = BookedService::whereIn('status', [0, 1])
            ->whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])
            ->count();
        $servicesPercentageChange = $lastWeekServices > 0
            ? round((($currentWeekServices - $lastWeekServices) / $lastWeekServices) * 100, 1)
            : ($currentWeekServices > 0 ? 100 : 0);

        // Other existing metrics
        $orderSales = Order::where('status', 4)->sum('total_amount');
        $invoiceSales = Invoice::sum('final_amount');
        $totalSales = $orderSales + $invoiceSales;
        $pendingServices = BookedService::where('status', 0)->count();
        $confirmedServices = BookedService::where('status', 1)->count();
        $totalServices = $pendingServices + $confirmedServices;
        $pendingOrders = Order::where('status', 0)->count();
        $confirmedOrders = Order::where('status', 1)->count();
        $lowStockItems = Stock::where('stock_quantity', '<', 5)
            ->join('products', 'stocks.product_id', '=', 'products.id')
            ->select('products.name', 'stocks.stock_quantity')
            ->get();

        // Recent Orders (latest 4)
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        // Recent Service Bookings (latest 4)
        $recentServiceBookings = BookedService::with(['service', 'staff'])
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        // Sales, Profit, and Loss Analytics Data
        // Monthly (last 12 months)
        $monthlySalesData = [];
        $monthlyProfitData = [];
        $monthlyLossData = [];
        $monthlyLabels = [];
        for ($i = 11; $i >= 0; $i--) {
            $monthStart = $now->copy()->subMonths($i)->startOfMonth();
            $monthEnd = $monthStart->copy()->endOfMonth();
            $monthLabel = $monthStart->format('M Y');
            $financials = $calculateFinancials($monthStart, $monthEnd);
            $monthlyLabels[] = $monthLabel;
            $monthlySalesData[] = $financials['sales'];
            $monthlyProfitData[] = $financials['profit'];
            $monthlyLossData[] = $financials['loss'];
        }

        // Quarterly (last 8 quarters)
        $quarterlySalesData = [];
        $quarterlyProfitData = [];
        $quarterlyLossData = [];
        $quarterlyLabels = [];
        for ($i = 7; $i >= 0; $i--) {
            $quarterStart = $now->copy()->subQuarters($i)->startOfQuarter();
            $quarterEnd = $quarterStart->copy()->endOfQuarter();
            $quarterLabel = 'Q' . $quarterStart->quarter . ' ' . $quarterStart->year;
            $financials = $calculateFinancials($quarterStart, $quarterEnd);
            $quarterlyLabels[] = $quarterLabel;
            $quarterlySalesData[] = $financials['sales'];
            $quarterlyProfitData[] = $financials['profit'];
            $quarterlyLossData[] = $financials['loss'];
        }

        // Yearly (last 5 years)
        $yearlySalesData = [];
        $yearlyProfitData = [];
        $yearlyLossData = [];
        $yearlyLabels = [];
        for ($i = 4; $i >= 0; $i--) {
            $yearStart = $now->copy()->subYears($i)->startOfYear();
            $yearEnd = $yearStart->copy()->endOfYear();
            $yearLabel = $yearStart->year;
            $financials = $calculateFinancials($yearStart, $yearEnd);
            $yearlyLabels[] = $yearLabel;
            $yearlySalesData[] = $financials['sales'];
            $yearlyProfitData[] = $financials['profit'];
            $yearlyLossData[] = $financials['loss'];
        }

        // Daily (last 30 days)
        $dailySalesData = [];
        $dailyProfitData = [];
        $dailyLossData = [];
        $dailyLabels = [];
        for ($i = 29; $i >= 0; $i--) {
            $dayStart = $now->copy()->subDays($i)->startOfDay();
            $dayEnd = $dayStart->copy()->endOfDay();
            $dayLabel = $dayStart->format('d M');
            $financials = $calculateFinancials($dayStart, $dayEnd);
            $dailyLabels[] = $dayLabel;
            $dailySalesData[] = $financials['sales'];
            $dailyProfitData[] = $financials['profit'];
            $dailyLossData[] = $financials['loss'];
        }

        return view('admin.dashboard', compact(
            'totalSales',
            'orderSales',
            'invoiceSales',
            'totalServices',
            'pendingServices',
            'confirmedServices',
            'pendingOrders',
            'confirmedOrders',
            'lowStockItems',
            'salesPercentageChange',
            'profitPercentageChange',
            'lossPercentageChange',
            'pendingOrdersPercentageChange',
            'servicesPercentageChange',
            'recentOrders',
            'recentServiceBookings',
            'monthlySalesData',
            'monthlyProfitData',
            'monthlyLossData',
            'monthlyLabels',
            'quarterlySalesData',
            'quarterlyProfitData',
            'quarterlyLossData',
            'quarterlyLabels',
            'yearlySalesData',
            'yearlyProfitData',
            'yearlyLossData',
            'yearlyLabels',
            'dailySalesData',
            'dailyProfitData',
            'dailyLossData',
            'dailyLabels'
        ));
    }
}
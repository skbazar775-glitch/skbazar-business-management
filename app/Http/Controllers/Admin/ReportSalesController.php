<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InvoicesExport;
use App\Exports\OrdersExport;


class ReportSalesController extends Controller
{
    public function index(Request $request)
    {
        // Initialize query for invoices
        $invoiceQuery = Invoice::query()
            ->with(['user', 'items'])
            ->select('invoices.*')
            ->leftJoin('users', 'invoices.user_id', '=', 'users.id');

        // Search by customer name or invoice number
        if ($request->filled('search')) {
            $search = $request->input('search');
            $invoiceQuery->where(function ($q) use ($search) {
                $q->where('invoices.customer_name', 'like', "%{$search}%")
                  ->orWhere('invoices.invoice_number', 'like', "%{$search}%");
            });
        }

        // Date range filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $invoiceQuery->whereBetween('invoices.created_at', [
                $request->input('start_date'),
                $request->input('end_date') . ' 23:59:59'
            ]);
        }

        // Payment status filter
        if ($request->filled('payment_status')) {
            $invoiceQuery->where('invoices.payment_mode', $request->input('payment_status'));
        }

        // Calculate aggregates for invoices
        $totalSales = $invoiceQuery->sum('final_amount');
        $totalGst = $invoiceQuery->sum('total_gst');
        $totalDiscount = $invoiceQuery->sum('discount');
        $averageSale = $invoiceQuery->avg('final_amount');

        // Paginate invoice results
        $invoices = $invoiceQuery->orderBy('created_at', 'desc')->paginate(10);

        // Format invoice data for view
        $salesData = [
            'total_sales' => number_format($totalSales, 2),
            'total_gst' => number_format($totalGst, 2),
            'total_discount' => number_format($totalDiscount, 2),
            'average_sale' => number_format($averageSale, 2),
            'total_invoices' => $invoices->total(),
        ];

        return view('admin.report.sales.index', compact('invoices', 'salesData'));
    }

    public function orders(Request $request)
    {
        // Initialize query for orders
        $orderQuery = Order::query()
            ->with(['user', 'orderItems'])
            ->select('orders.*')
            ->leftJoin('users', 'orders.user_id', '=', 'users.id');

        // Search by unique order ID
        if ($request->filled('search')) {
            $search = $request->input('search');
            $orderQuery->where('orders.unique_order_id', 'like', "%{$search}%");
        }

        // Date range filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $orderQuery->whereBetween('orders.created_at', [
                $request->input('start_date'),
                $request->input('end_date') . ' 23:59:59'
            ]);
        }

        // Payment status filter
        if ($request->filled('payment_status')) {
            $orderQuery->where('orders.payment_status', $request->input('payment_status'));
        }

        // Order status filter
        if ($request->filled('order_status')) {
            $orderQuery->where('orders.status', $request->input('order_status'));
        }

        // Calculate aggregates for orders
        $totalOrderSales = $orderQuery->sum('total_amount');
        $averageOrderSale = $orderQuery->avg('total_amount');
        $totalOrders = $orderQuery->count();

        // Calculate totals by status
        $statusTotals = Order::query()
            ->select(
                'status',
                DB::raw('COUNT(*) as order_count'),
                DB::raw('SUM(total_amount) as total_sales')
            )
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) {
                $statusText = match ($item->status) {
                    0 => 'Pending',
                    1 => 'Confirmed',
                    2 => 'Packed',
                    3 => 'Shipped',
                    4 => 'Delivered',
                    5 => 'Canceled',
                    default => 'Unknown',
                };
                return [
                    $item->status => [
                        'status_text' => $statusText,
                        'order_count' => $item->order_count,
                        'total_sales' => number_format($item->total_sales, 2),
                    ]
                ];
            });

        // Paginate order results
        $orders = $orderQuery->orderBy('created_at', 'desc')->paginate(10);

        // Format order data for view
        $orderData = [
            'total_sales' => number_format($totalOrderSales, 2),
            'average_sale' => number_format($averageOrderSale, 2),
            'total_orders' => $totalOrders,
            'status_totals' => $statusTotals,
        ];

        return view('admin.report.orders.index', compact('orders', 'orderData'));
    }

public function export(Request $request)
{
    $type = $request->route()->named('admin.report.sales.export') ? 'invoices' : 'orders';
    $filename = $type . '_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

    if ($type === 'invoices') {
        return Excel::download(new InvoicesExport($request), $filename);
    } else {
        return Excel::download(new OrdersExport($request), $filename);
    }
}
}
<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;

class OrdersExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = Order::query()
            ->with(['user'])
            ->select('orders.*')
            ->leftJoin('users', 'orders.user_id', '=', 'users.id');

        if ($this->request->filled('search')) {
            $search = $this->request->input('search');
            $query->where('orders.unique_order_id', 'like', "%{$search}%");
        }

        if ($this->request->filled('start_date') && $this->request->filled('end_date')) {
            $query->whereBetween('orders.created_at', [
                $this->request->input('start_date'),
                $this->request->input('end_date') . ' 23:59:59'
            ]);
        }

        if ($this->request->filled('payment_status')) {
            $query->where('orders.payment_status', $this->request->input('payment_status'));
        }

        if ($this->request->filled('order_status')) {
            $query->where('orders.status', $this->request->input('order_status'));
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Order #',
            'Customer',
            'Date',
            'Total',
            'Payment Status',
            'Order Status'
        ];
    }

    public function map($order): array
    {
        return [
            $order->unique_order_id,
            $order->user->name ?? 'Guest',
            $order->created_at->format('d M Y'),
            number_format($order->total_amount, 2),
            $order->payment_status_text,
            $order->status_text,
        ];
    }
}
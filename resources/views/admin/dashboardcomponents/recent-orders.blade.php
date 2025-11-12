<!-- Recent Orders Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <div class="flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-800">Recent Orders</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-blue-500 font-medium">View All</a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @foreach ($recentOrders as $order)
                    <tr class="hover:bg-gray-50">
@php
    $shortId = 'N/A';

    if (!empty($order) && !empty($order->unique_order_id)) {
        $parts = explode('-', $order->unique_order_id);

        if (count($parts) >= 2) {
            $shortId = $parts[0] . '-' . substr($parts[1], 0, 6);
        }
    }
@endphp

<p>{{ $shortId }}</p>

<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
    {{ $shortId }}
</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->user->name ?? 'Unknown' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">₹{{ number_format($order->total_amount, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if ($order->statusText == 'Pending')
                                    bg-yellow-100 text-yellow-800
                                @elseif ($order->statusText == 'Shipped')
                                    bg-green-100 text-green-800
                                @elseif ($order->statusText == 'Delivered')
                                    bg-blue-100 text-blue-800
                                @elseif ($order->statusText == 'Canceled')
                                    bg-red-100 text-red-800
                                @else
                                    bg-gray-100 text-gray-800
                                @endif">
                                {{ $order->statusText }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
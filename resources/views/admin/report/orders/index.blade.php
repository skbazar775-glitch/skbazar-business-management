@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center border-b pb-4">
        <h1 class="text-3xl font-bold text-gray-900">Orders Report</h1>
        <div class="flex space-x-2">
            <button id="exportBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md shadow-md transition duration-200 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                Export Report
            </button>
            <a href="{{ route('admin.report.sales.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md shadow-md transition duration-200 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2h12v8H4V6zm2 2h8v2H6V8zm0 3h4v2H6v-2z" clip-rule="evenodd"/>
                </svg>
                View Sales Report
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-gray-600 text-sm font-semibold mb-2">Total Sales</h3>
            <p class="text-3xl font-bold text-gray-900">₹{{ $orderData['total_sales'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-gray-600 text-sm font-semibold mb-2">Average Sale</h3>
            <p class="text-3xl font-bold text-gray-900">₹{{ $orderData['average_sale'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-gray-600 text-sm font-semibold mb-2">Total Orders</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $orderData['total_orders'] }}</p>
        </div>
    </div>

    <!-- Status Totals Table -->
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Totals by Status</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order Count</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Sales</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($orderData['status_totals'] as $status => $data)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $data['status_text'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $data['order_count'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">₹{{ $data['total_sales'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100 mb-8">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search by order number</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 text-gray-900">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 text-gray-900">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 text-gray-900">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
                <select name="payment_status"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 text-gray-900">
                    <option value="">All</option>
                    <option value="0" {{ request('payment_status') === '0' ? 'selected' : '' }}>Unpaid</option>
                    <option value="1" {{ request('payment_status') === '1' ? 'selected' : '' }}>Paid</option>
                    <option value="2" {{ request('payment_status') === '2' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Order Status</label>
                <select name="order_status"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 text-gray-900">
                    <option value="">All</option>
                    <option value="0" {{ request('order_status') === '0' ? 'selected' : '' }}>Pending</option>
                    <option value="1" {{ request('order_status') === '1' ? 'selected' : '' }}>Confirmed</option>
                    <option value="2" {{ request('order_status') === '2' ? 'selected' : '' }}>Packed</option>
                    <option value="3" {{ request('order_status') === '3' ? 'selected' : '' }}>Shipped</option>
                    <option value="4" {{ request('order_status') === '4' ? 'selected' : '' }}>Delivered</option>
                    <option value="5" {{ request('order_status') === '5' ? 'selected' : '' }}>Canceled</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow-md transition duration-200 flex-1">
                    Filter
                </button>
                <a href="{{ route('admin.report.orders.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md shadow-md transition duration-200 flex-1 text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($orders as $order)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-medium">{{ $order->unique_order_id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $order->user->name ?? 'Guest' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $order->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($order->total_amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $order->payment_status_text }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $order->status == 4 ? 'bg-green-100 text-green-800' :
                                       ($order->status == 5 ? 'bg-red-100 text-red-800' :
                                       ($order->status == 0 ? 'bg-yellow-100 text-yellow-800' :
                                       'bg-blue-100 text-blue-800')) }}">
                                    {{ $order->status_text }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-center">
        {{ $orders->links() }}
    </div>
</div>

<script>
document.getElementById('exportBtn').addEventListener('click', function() {
    window.location.href = '{{ route('admin.report.orders.export') }}?' + new URLSearchParams({
        search: '{{ request('search') }}',
        start_date: '{{ request('start_date') }}',
        end_date: '{{ request('end_date') }}',
        payment_status: '{{ request('payment_status') }}',
        order_status: '{{ request('order_status') }}'
    });
});
</script>

<style>
@import 'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css';

/* Custom styles for pagination */
.pagination {
    display: flex;
    list-style: none;
    padding: 0;
}

.pagination li {
    margin: 0 4px;
}

.pagination a, .pagination span {
    display: inline-block;
    padding: 8px 12px;
    border-radius: 4px;
    border: 1px solid #e2e8f0;
    color: #4a5568;
    text-decoration: none;
    transition: all 0.2s;
}

.pagination a:hover {
    background-color: #edf2f7;
}

.pagination .active span {
    background-color: #4299e1;
    color: white;
    border-color: #4299e1;
}

.pagination .disabled span {
    color: #a0aec0;
    background-color: #f7fafc;
    border-color: #e2e8f0;
}
</style>
@endsection
@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center border-b pb-4">
        <h1 class="text-3xl font-bold text-gray-900">Sales Report</h1>
        <div class="flex space-x-2">
            <button id="exportBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md shadow-md transition duration-200 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                Export Report
            </button>
            <a href="{{ route('admin.report.orders.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md shadow-md transition duration-200 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9 2a1 1 0 00-.894.553L7.382 4H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-3.382l-.724-1.447A1 1 0 009 2zm-1 2h2l1.414 2.828A1 1 0 0012 7H4a1 1 0 00-.447.106L5 4h3zm8 12H4V6h12v10z"/>
                </svg>
                View Orders Report
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-gray-600 text-sm font-semibold mb-2">Total Sales</h3>
            <p class="text-3xl font-bold text-gray-900">₹{{ $salesData['total_sales'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-gray-600 text-sm font-semibold mb-2">Total GST</h3>
            <p class="text-3xl font-bold text-gray-900">₹{{ $salesData['total_gst'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-gray-600 text-sm font-semibold mb-2">Total Discount</h3>
            <p class="text-3xl font-bold text-gray-900">₹{{ $salesData['total_discount'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-gray-600 text-sm font-semibold mb-2">Average Sale</h3>
            <p class="text-3xl font-bold text-gray-900">₹{{ $salesData['average_sale'] }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100 mb-8">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search by invoice number</label>
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
                    <option value="0" {{ request('payment_status') === '0' ? 'selected' : '' }}>Mobile Banking</option>
                    <option value="1" {{ request('payment_status') === '1' ? 'selected' : '' }}>Online Payment</option>
                    <option value="2" {{ request('payment_status') === '2' ? 'selected' : '' }}>Cash Payment</option>
                    <option value="3" {{ request('payment_status') === '3' ? 'selected' : '' }}>Cash on Delivery</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow-md transition duration-200 flex-1">
                    Filter
                </button>
                <a href="{{ route('admin.report.sales.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md shadow-md transition duration-200 flex-1 text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">GST</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($invoices as $invoice)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-medium">{{ $invoice->invoice_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $invoice->customer_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $invoice->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($invoice->final_amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($invoice->total_gst, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $invoice->payment_mode_text }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $invoice->payment_terms == 0 ? 'bg-green-100 text-green-800' :
                                       ($invoice->payment_terms == 1 ? 'bg-yellow-100 text-yellow-800' :
                                       'bg-red-100 text-red-800') }}">
                                    {{ $invoice->payment_terms_text }}
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
        {{ $invoices->links() }}
    </div>
</div>

<script>
document.getElementById('exportBtn').addEventListener('click', function() {
    window.location.href = '{{ route('admin.report.sales.export') }}?' + new URLSearchParams({
        search: '{{ request('search') }}',
        start_date: '{{ request('start_date') }}',
        end_date: '{{ request('end_date') }}',
        payment_status: '{{ request('payment_status') }}'
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
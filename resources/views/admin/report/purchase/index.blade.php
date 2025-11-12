@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Warning Message -->
    @if (session('warning'))
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
            <p>{{ session('warning') }}</p>
        </div>
    @endif

    <!-- Header -->
    <div class="mb-8 flex justify-between items-center border-b pb-4">
        <h1 class="text-3xl font-bold text-gray-900">Purchase Report</h1>
        <div class="flex space-x-2">
            <button id="exportBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md shadow-md transition duration-200 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                Export Report
            </button>

        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-gray-600 text-sm font-semibold mb-2">Total Purchase</h3>
            <p class="text-3xl font-bold text-gray-900">₹{{ $purchaseData['total_purchase'] }}</p>
            @if ($purchaseData['total_purchase'] == '0.00' && $purchaseData['total_purchases'] > 0)
                <p class="text-sm text-red-600 mt-2">Total purchase is zero due to missing total price values.</p>
            @endif
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-gray-600 text-sm font-semibold mb-2">Total GST</h3>
            <p class="text-3xl font-bold text-gray-900">₹{{ $purchaseData['total_gst'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-gray-600 text-sm font-semibold mb-2">Total Transportation</h3>
            <p class="text-3xl font-bold text-gray-900">₹{{ $purchaseData['total_transportation'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-gray-600 text-sm font-semibold mb-2">Average Purchase</h3>
            <p class="text-3xl font-bold text-gray-900">₹{{ $purchaseData['average_purchase'] }}</p>
            @if ($purchaseData['average_purchase'] == '0.00' && $purchaseData['total_purchases'] > 0)
                <p class="text-sm text-red-600 mt-2">Average purchase is zero due to missing total price values.</p>
            @endif
        </div>
    </div>

    <!-- Chart -->
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100 mb-8">
        <h3 class="text-gray-600 text-sm font-semibold mb-4">Purchase Report Summary Chart</h3>
        <canvas id="purchaseChart" height="100"></canvas>
    </div>

    <!-- Filters -->
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100 mb-8">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search by Invoice/Supplier</label>
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
                <label class="block text-sm font-medium text-gray-700 mb-1">GST Percent</label>
                <select name="gst_percent"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 text-gray-900">
                    <option value="">All</option>
                    <option value="0" {{ request('gst_percent') === '0' ? 'selected' : '' }}>0%</option>
                    <option value="5" {{ request('gst_percent') === '5' ? 'selected' : '' }}>5%</option>
                    <option value="12" {{ request('gst_percent') === '12' ? 'selected' : '' }}>12%</option>
                    <option value="18" {{ request('gst_percent') === '18' ? 'selected' : '' }}>18%</option>
                    <option value="28" {{ request('gst_percent') === '28' ? 'selected' : '' }}>28%</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow-md transition duration-200 flex-1">
                    Filter
                </button>
                <a href="{{ route('admin.report.purchase.index') }}"
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">GST (%)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total GST</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transportation Cost</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($purchases as $purchase)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-medium">{{ $purchase->invoice_no }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $purchase->supplier->name ?? 'Unknown' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $purchase->product->name ?? 'Unknown' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $purchase->quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $purchase->invoice_date ? $purchase->invoice_date->format('d M Y') : 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($purchase->total_price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $purchase->gst_percent }}%</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($purchase->total_gst_value, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($purchase->transportation_costs_incl, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-gray-600">No purchases found for the selected filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-center">
        {{ $purchases->links() }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('purchaseChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Total Purchase', 'Total GST', 'Total Transportation', 'Average Purchase'],
            datasets: [{
                label: 'Purchase Report Summary (₹)',
                data: [
                    {{ $chartData['total_purchase'] }},
                    {{ $chartData['total_gst'] }},
                    {{ $chartData['total_transportation'] }},
                    {{ $chartData['average_purchase'] }}
                ],
                backgroundColor: [
                    'rgba(59, 130, 246, 0.6)',
                    'rgba(16, 185, 129, 0.6)',
                    'rgba(245, 158, 11, 0.6)',
                    'rgba(139, 92, 246, 0.6)'
                ],
                borderColor: [
                    'rgba(59, 130, 246, 1)',
                    'rgba(16, 185, 129, 1)',
                    'rgba(245, 158, 11, 1)',
                    'rgba(139, 92, 246, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Amount (₹)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Metrics'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Purchase Report Summary'
                }
            }
        }
    });
});

document.getElementById('exportBtn').addEventListener('click', function() {
    window.location.href = '{{ route('admin.report.purchase.export') }}?' + new URLSearchParams({
        search: '{{ request('search') }}',
        start_date: '{{ request('start_date') }}',
        end_date: '{{ request('end_date') }}',
        gst_percent: '{{ request('gst_percent') }}'
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
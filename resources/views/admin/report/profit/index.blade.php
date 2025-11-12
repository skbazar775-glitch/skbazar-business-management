@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Warning/Error Message -->
    @if (session('warning') || session('error'))
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
            <p>{{ session('warning') ?? session('error') }}</p>
        </div>
    @endif

    <!-- Header -->
    <div class="mb-8 flex justify-between items-center border-b pb-4">
        <h1 class="text-3xl font-bold text-gray-900">Profit Report</h1>
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
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-gray-600 text-sm font-semibold mb-2">Total Profit</h3>
            <p class="text-3xl font-bold text-gray-900">₹{{ $profitData['total_profit'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-gray-600 text-sm font-semibold mb-2">Total Orders</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $profitData['total_orders'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-gray-600 text-sm font-semibold mb-2">Average Profit</h3>
            <p class="text-3xl font-bold text-gray-900">₹{{ $profitData['average_profit'] }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100 mb-8">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
            {{-- <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Order Status</label>
                <select name="status"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 text-gray-900">
                    <option value="">All</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Confirmed</option>
                    <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Packed</option>
                    <option value="3" {{ request('status') === '3' ? 'selected' : '' }}>Shipped</option>
                    <option value="4" {{ request('status', '4') === '4' ? 'selected' : '' }}>Delivered</option>
                </select>
            </div> --}}
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow-md transition duration-200 flex-1">
                    Filter
                </button>
                <a href="{{ route('admin.report.profit.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md shadow-md transition duration-200 flex-1 text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Day-Wise Profit</h3>
            <canvas id="dayWiseChart"></canvas>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Month-Wise Profit</h3>
            <canvas id="monthWiseChart"></canvas>
        </div>
    </div>

    <!-- Tables -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Day-Wise Table -->
<!-- Day-Wise Table -->
<div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Day-Wise Profit</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" id="dayWiseTable">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profit (₹)</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($profitData['day_wise_profit'] as $date => $profit)
                    <tr class="{{ $loop->index >= 10 ? 'hidden day-wise-row' : '' }}">
                        <td class="px-4 py-4 whitespace-nowrap text-gray-900">{{ $date }}</td>
                        <td class="px-4 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($profit, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-4 py-4 text-center text-gray-600">No day-wise profit data available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if (count($profitData['day_wise_profit']) > 10)
            <div class="mt-4 text-center">
                <button id="viewMoreBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow-md transition duration-200">
                    View More
                </button>
            </div>
        @endif
    </div>
</div>

        <!-- Month-Wise Table -->
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Month-Wise Profit</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Month</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profit (₹)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($profitData['month_wise_profit'] as $month => $profit)
                            <tr>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-900">{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($profit, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-4 py-4 text-center text-gray-600">No month-wise profit data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Year-Wise Table -->
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Year-Wise Profit</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profit (₹)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($profitData['year_wise_profit'] as $year => $profit)
                            <tr>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-900">{{ $year }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($profit, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-4 py-4 text-center text-gray-600">No year-wise profit data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Details</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $order->unique_order_id ?? $order->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $order->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($order->total_amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                ₹{{ number_format(
                                    $order->orderItems->sum(function ($item) {
                                        $product = \App\Models\Product::find($item->product_id);
                                        return $product ? $item->quantity * $product->price_p : 0;
                                    }), 2)
                                }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                ₹{{ number_format(
                                    $order->total_amount - $order->orderItems->sum(function ($item) {
                                        $product = \App\Models\Product::find($item->product_id);
                                        return $product ? $item->quantity * $product->price_p : 0;
                                    }), 2)
                                }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $order->status_text }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-600">No orders found for the selected filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
            {{ $orders->links() }}
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Day-Wise Chart
    const dayWiseCtx = document.getElementById('dayWiseChart').getContext('2d');
    new Chart(dayWiseCtx, {
        type: 'line',
        data: {
            labels: @json(array_keys($profitData['day_wise_profit'])),
            datasets: [{
                label: 'Day-Wise Profit',
                data: @json(array_values($profitData['day_wise_profit'])),
                borderColor: '#4299e1',
                backgroundColor: 'rgba(66, 153, 225, 0.2)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'Day-Wise Profit Trend' }
            },
            scales: {
                y: { beginAtZero: true, title: { display: true, text: 'Profit (₹)' } },
                x: { title: { display: true, text: 'Date' } }
            }
        }
    });

    // Month-Wise Chart
    const monthWiseCtx = document.getElementById('monthWiseChart').getContext('2d');
    new Chart(monthWiseCtx, {
        type: 'bar',
        data: {
            labels: @json(array_map(function($month) {
                return \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y');
            }, array_keys($profitData['month_wise_profit']))),
            datasets: [{
                label: 'Month-Wise Profit',
                data: @json(array_values($profitData['month_wise_profit'])),
                backgroundColor: '#68d391',
                borderColor: '#38a169',
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'Month-Wise Profit' }
            },
            scales: {
                y: { beginAtZero: true, title: { display: true, text: 'Profit (₹)' } },
                x: { title: { display: true, text: 'Month' } }
            }
        }
    });

    // Export button
    document.getElementById('exportBtn').addEventListener('click', function() {
        window.location.href = '{{ route('admin.report.profit.export') }}?' + new URLSearchParams({
            start_date: '{{ request('start_date') }}',
            end_date: '{{ request('end_date') }}',
            status: '{{ request('status', '4') }}'
        });
    });

    // View More button functionality
    document.getElementById('viewMoreBtn')?.addEventListener('click', function() {
        const hiddenRows = document.querySelectorAll('.day-wise-row');
        const isHidden = hiddenRows[0]?.classList.contains('hidden');
        
        hiddenRows.forEach(row => {
            row.classList.toggle('hidden');
        });
        
        this.textContent = isHidden ? 'View Less' : 'View More';
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
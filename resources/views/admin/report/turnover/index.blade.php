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
        <h1 class="text-3xl font-bold text-gray-900">Turnover Report</h1>
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
            <h3 class="text-gray-600 text-sm font-semibold mb-2">Total  Turnover </h3>
            <p class="text-3xl font-bold text-gray-900">₹{{ $revenueData['total_revenue'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-gray-600 text-sm font-semibold mb-2">Order  Turnover </h3>
            <p class="text-3xl font-bold text-gray-900">₹{{ $revenueData['order_revenue'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-gray-600 text-sm font-semibold mb-2">Invoice  Turnover </h3>
            <p class="text-3xl font-bold text-gray-900">₹{{ $revenueData['invoice_revenue'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-gray-600 text-sm font-semibold mb-2">Average  Turnover </h3>
            <p class="text-3xl font-bold text-gray-900">₹{{ $revenueData['average_revenue'] }}</p>
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
            </div> --}}
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow-md transition duration-200 flex-1">
                    Filter
                </button>
                <a href="{{ route('admin.report.turnover.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md shadow-md transition duration-200 flex-1 text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Daily  Turnover </h3>
            <canvas id="dailyRevenueChart"></canvas>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly  Turnover </h3>
            <canvas id="monthlyRevenueChart"></canvas>
        </div>
    </div>

    <!-- Tables -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Daily Revenue -->
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Daily  Turnover </h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order  Turnover  (₹)</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice  Turnover  (₹)</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total  Turnover  (₹)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($revenueData['daily_revenue'] as $date => $revenue)
                            <tr>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-900">{{ $date }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($revenue['order_revenue'] ?? 0, 2) }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($revenue['invoice_revenue'] ?? 0, 2) }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($revenue['total_revenue'], 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-center text-gray-600">No daily  Turnover  data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly  Turnover </h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Month</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order  Turnover  (₹)</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice  Turnover  (₹)</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total  Turnover  (₹)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($revenueData['monthly_revenue'] as $month => $revenue)
                            <tr>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-900">{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($revenue['order_revenue'] ?? 0, 2) }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($revenue['invoice_revenue'] ?? 0, 2) }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($revenue['total_revenue'], 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-center text-gray-600">No monthly  Turnover  data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Yearly Revenue -->
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Yearly  Turnover </h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order  Turnover  (₹)</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice  Turnover  (₹)</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total  Turnover  (₹)</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($revenueData['yearly_revenue'] as $year => $revenue)
                        <tr>
                            <td class="px-4 py-4 whitespace-nowrap text-gray-900">{{ $year }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($revenue['order_revenue'] ?? 0, 2) }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($revenue['invoice_revenue'] ?? 0, 2) }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($revenue['total_revenue'], 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-4 text-center text-gray-600">No yearly  Turnover  data available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- GST-wise Breakdown -->
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">GST-wise Breakdown (Invoices)</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">GST Percent</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total GST (₹)</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CGST (₹)</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SGST (₹)</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Count</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($revenueData['gst_wise'] as $percent => $data)
                        <tr>
                            <td class="px-4 py-4 whitespace-nowrap text-gray-900">{{ match ($percent) { '0' => '0%', '1' => '12%', '2' => '18%', '3' => '28%', default => 'Unknown' } }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($data['total_gst'], 2) }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($data['cgst'], 2) }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($data['sgst'], 2) }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-gray-900">{{ $data['count'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Order Details Table -->
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Details</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount (₹)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $order->unique_order_id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $order->user ? $order->user->name : 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $order->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($order->total_amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $order->payment_status_text }}</td>
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
            {{ $orders->appends(['invoices_page' => request('invoices_page')])->links() }}
        </div>
    </div>

    <!-- Invoice Details Table -->
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Invoice Details</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Final Amount (₹)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total GST (₹)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Terms</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Mode</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($invoices as $invoice)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $invoice->invoice_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $invoice->customer_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $invoice->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($invoice->final_amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($invoice->total_gst, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $invoice->payment_terms_text }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $invoice->payment_mode_text }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-600">No invoices found for the selected filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
            {{ $invoices->appends(['orders_page' => request('orders_page')])->links() }}
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Daily Revenue Chart
    const dailyRevenueCtx = document.getElementById('dailyRevenueChart').getContext('2d');
    new Chart(dailyRevenueCtx, {
        type: 'line',
        data: {
            labels: @json(array_keys($revenueData['daily_revenue'])),
            datasets: [
                {
                    label: 'Total Revenue',
                    data: @json(array_map(fn($revenue) => $revenue['total_revenue'], $revenueData['daily_revenue'])),
                    borderColor: '#4299e1',
                    backgroundColor: 'rgba(66, 153, 225, 0.2)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Order Revenue',
                    data: @json(array_map(fn($revenue) => $revenue['order_revenue'] ?? 0, $revenueData['daily_revenue'])),
                    borderColor: '#68d391',
                    backgroundColor: 'rgba(104, 211, 145, 0.2)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Invoice Revenue',
                    data: @json(array_map(fn($revenue) => $revenue['invoice_revenue'] ?? 0, $revenueData['daily_revenue'])),
                    borderColor: '#e53e3e',
                    backgroundColor: 'rgba(229, 62, 62, 0.2)',
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'Daily Revenue Trend' }
            },
            scales: {
                y: { beginAtZero: true, title: { display: true, text: 'Amount (₹)' } },
                x: { title: { display: true, text: 'Date' } }
            }
        }
    });

    // Monthly Revenue Chart
    const monthlyRevenueCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
    new Chart(monthlyRevenueCtx, {
        type: 'bar',
        data: {
            labels: @json(array_map(function($month) {
                return \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y');
            }, array_keys($revenueData['monthly_revenue']))),
            datasets: [
                {
                    label: 'Total Revenue',
                    data: @json(array_map(fn($revenue) => $revenue['total_revenue'], $revenueData['monthly_revenue'])),
                    backgroundColor: '#4299e1',
                    borderColor: '#3182ce',
                    borderWidth: 1
                },
                {
                    label: 'Order Revenue',
                    data: @json(array_map(fn($revenue) => $revenue['order_revenue'] ?? 0, $revenueData['monthly_revenue'])),
                    backgroundColor: '#68d391',
                    borderColor: '#38a169',
                    borderWidth: 1
                },
                {
                    label: 'Invoice Revenue',
                    data: @json(array_map(fn($revenue) => $revenue['invoice_revenue'] ?? 0, $revenueData['monthly_revenue'])),
                    backgroundColor: '#e53e3e',
                    borderColor: '#c53030',
                    borderWidth: 1
                }
            ]
        },
        options: {
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'Monthly Revenue' }
            },
            scales: {
                y: { beginAtZero: true, title: { display: true, text: 'Amount (₹)' } },
                x: { title: { display: true, text: 'Month' } }
            }
        }
    });

    // Export button
    document.getElementById('exportBtn').addEventListener('click', function() {
        window.location.href = '{{ route('admin.report.turnover.export') }}?' + new URLSearchParams({
            start_date: '{{ request('start_date') }}',
            end_date: '{{ request('end_date') }}',
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
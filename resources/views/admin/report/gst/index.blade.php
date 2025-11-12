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
        <h1 class="text-3xl font-bold text-gray-900">GST Report</h1>
        <div class="flex space-x-2">
            <button id="exportBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md shadow-md transition duration-200 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                Export Report
            </button>
            <a href="{{ route('admin.report.expense.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md shadow-md transition duration-200 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2h12v8H4V6zm2 2h8v2H6V8zm0 3h4v2H6v-2z" clip-rule="evenodd"/>
                </svg>
                View Expense Report
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-gray-600 text-sm font-semibold mb-2">Total GST</h3>
            <p class="text-3xl font-bold text-gray-900">₹{{ $gstData['total_gst'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-gray-600 text-sm font-semibold mb-2">Total CGST</h3>
            <p class="text-3xl font-bold text-gray-900">₹{{ $gstData['total_cgst'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-gray-600 text-sm font-semibold mb-2">Total SGST</h3>
            <p class="text-3xl font-bold text-gray-900">₹{{ $gstData['total_sgst'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-gray-600 text-sm font-semibold mb-2">Average GST</h3>
            <p class="text-3xl font-bold text-gray-900">₹{{ $gstData['average_gst'] }}</p>
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
                <label class="block text-sm font-medium text-gray-700 mb-1">GST Percent</label>
                <select name="gst_percent"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 text-gray-900">
                    <option value="">All</option>
                    <option value="0" {{ request('gst_percent') === '0' ? 'selected' : '' }}>0%</option>
                    <option value="1" {{ request('gst_percent') === '1' ? 'selected' : '' }}>12%</option>
                    <option value="2" {{ request('gst_percent') === '2' ? 'selected' : '' }}>18%</option>
                    <option value="3" {{ request('gst_percent') === '3' ? 'selected' : '' }}>28%</option>
                </select>
            </div> --}}
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow-md transition duration-200 flex-1">
                    Filter
                </button>
                <a href="{{ route('admin.report.gst.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md shadow-md transition duration-200 flex-1 text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Monthly Chart (Full Width) -->
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly GST</h3>
        <canvas id="monthlyGstChart"></canvas>
    </div>

    <!-- Tables -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Daily GST -->
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Daily GST</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total GST (₹)</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CGST (₹)</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SGST (₹)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($gstData['daily_gst'] as $date => $gst)
                            <tr>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-900">{{ $date }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($gst['total_gst'], 2) }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($gst['cgst'], 2) }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($gst['sgst'], 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-center text-gray-600">No daily GST data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Monthly GST -->
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly GST</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Month</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total GST (₹)</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CGST (₹)</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SGST (₹)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($gstData['monthly_gst'] as $month => $gst)
                            <tr>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-900">{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($gst['total_gst'], 2) }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($gst['cgst'], 2) }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($gst['sgst'], 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-center text-gray-600">No monthly GST data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- GST-wise Breakdown -->
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">GST-wise Breakdown</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total GST (₹)</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CGST (₹)</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SGST (₹)</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Count</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($gstData['gst_wise'] as $percent => $data)
                        <tr>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Price (₹)</th>
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
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($invoice->total_price, 2) }}</td>
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
            {{ $invoices->links() }}
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Monthly GST Chart (Full Width)
    const monthlyGstCtx = document.getElementById('monthlyGstChart').getContext('2d');
    new Chart(monthlyGstCtx, {
        type: 'bar',
        data: {
            labels: @json(array_map(function($month) {
                return \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y');
            }, array_keys($gstData['monthly_gst']))),
            datasets: [
                {
                    label: 'Total GST',
                    data: @json(array_map(fn($gst) => $gst['total_gst'], $gstData['monthly_gst'])),
                    backgroundColor: '#4299e1',
                    borderColor: '#3182ce',
                    borderWidth: 1
                },
                {
                    label: 'CGST',
                    data: @json(array_map(fn($gst) => $gst['cgst'], $gstData['monthly_gst'])),
                    backgroundColor: '#68d391',
                    borderColor: '#38a169',
                    borderWidth: 1
                },
                {
                    label: 'SGST',
                    data: @json(array_map(fn($gst) => $gst['sgst'], $gstData['monthly_gst'])),
                    backgroundColor: '#e53e3e',
                    borderColor: '#c53030',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'Monthly GST' }
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    title: { display: true, text: 'Amount (₹)' } 
                },
                x: { 
                    title: { display: true, text: 'Month' } 
                }
            }
        }
    });

    // Export button
    document.getElementById('exportBtn').addEventListener('click', function() {
        window.location.href = '{{ route('admin.report.gst.export') }}?' + new URLSearchParams({
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

/* Chart container styling */
#monthlyGstChart {
    width: 100% !important;
    height: 400px !important;
}
</style>
@endsection
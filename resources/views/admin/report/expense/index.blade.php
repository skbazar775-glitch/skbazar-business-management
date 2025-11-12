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
        <h1 class="text-3xl font-bold text-gray-900">Expense Report</h1>
        <div class="flex space-x-2">
            <button id="exportBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md shadow-md transition duration-200 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                Export Report
            </button>
            <a href="{{ route('admin.report.profit.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md shadow-md transition duration-200 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2h12v8H4V6zm2 2h8v2H6V8zm0 3h4v2H6v-2z" clip-rule="evenodd"/>
                </svg>
                View Profit Report
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-gray-600 text-sm font-semibold mb-2">Total Normal Expenses</h3>
<p class="text-3xl font-bold text-yellow-500">₹{{ $expenseData['total_normal'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-gray-600 text-sm font-semibold mb-2">Total Loss Expenses</h3>
<p class="text-3xl font-bold text-red-500">₹{{ $expenseData['total_loss'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-gray-600 text-sm font-semibold mb-2">Average Expense</h3>
            <p class="text-3xl font-bold text-gray-900">₹{{ $expenseData['average_expense'] }}</p>
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
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 text-gray-900">
                    <option value="">All</option>
                    <option value="normal" {{ request('status') === 'normal' ? 'selected' : '' }}>Normal</option>
                    <option value="loss" {{ request('status') === 'loss' ? 'selected' : '' }}>Loss</option>
                </select>
            </div> --}}
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow-md transition duration-200 flex-1">
                    Filter
                </button>
                <a href="{{ route('admin.report.expense.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md shadow-md transition duration-200 flex-1 text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Daily Expenses (Normal vs Loss)</h3>
            <canvas id="dailyExpenseChart"></canvas>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Expenses (Normal vs Loss)</h3>
            <canvas id="monthlyExpenseChart"></canvas>
        </div>
    </div>

    <!-- Tables -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Daily Expenses -->
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Daily Expenses</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="dailyExpensesTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Normal (₹)</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loss (₹)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($expenseData['daily_expenses']['normal'] + $expenseData['daily_expenses']['loss'] as $date => $value)
                            <tr data-visible="{{ $loop->index < 10 ? 'true' : 'false' }}" class="{{ $loop->index >= 10 ? 'hidden' : '' }}">
                                <td class="px-4 py-4 whitespace-nowrap text-gray-900">{{ $date }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($expenseData['daily_expenses']['normal'][$date] ?? 0, 2) }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($expenseData['daily_expenses']['loss'][$date] ?? 0, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-center text-gray-600">No daily expense data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if (count($expenseData['daily_expenses']['normal'] + $expenseData['daily_expenses']['loss']) > 10)
                    <div class="mt-4 flex justify-center">
                        <button id="viewMoreDailyBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow-md transition duration-200">
                            View More
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Monthly Expenses -->
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Expenses</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Month</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Normal (₹)</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loss (₹)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($expenseData['monthly_expenses']['normal'] + $expenseData['monthly_expenses']['loss'] as $month => $value)
                            <tr>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-900">{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($expenseData['monthly_expenses']['normal'][$month] ?? 0, 2) }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($expenseData['monthly_expenses']['loss'][$month] ?? 0, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-center text-gray-600">No monthly expense data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Expense Details Table -->
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Expense Details</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($expenses as $expense)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $expense->title }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $expense->expense_date->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">₹{{ number_format($expense->amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ ucfirst($expense->status) }}</td>
                            <td class="px-6 py-4 text-gray-900">{{ $expense->notes ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-600">No expenses found for the selected filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
            {{ $expenses->links() }}
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Daily Expense Chart
    const dailyExpenseCtx = document.getElementById('dailyExpenseChart').getContext('2d');
    new Chart(dailyExpenseCtx, {
        type: 'line',
        data: {
            labels: @json(array_keys($expenseData['daily_expenses']['normal'] + $expenseData['daily_expenses']['loss'])),
            datasets: [
                {
                    label: 'Normal Expenses',
                    data: @json(array_map(fn($date) => $expenseData['daily_expenses']['normal'][$date] ?? 0, array_keys($expenseData['daily_expenses']['normal'] + $expenseData['daily_expenses']['loss']))),
                    borderColor: '#4299e1',
                    backgroundColor: 'rgba(66, 153, 225, 0.2)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Loss Expenses',
                    data: @json(array_map(fn($date) => $expenseData['daily_expenses']['loss'][$date] ?? 0, array_keys($expenseData['daily_expenses']['normal'] + $expenseData['daily_expenses']['loss']))),
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
                title: { display: true, text: 'Daily Expenses (Normal vs Loss)' }
            },
            scales: {
                y: { beginAtZero: true, title: { display: true, text: 'Amount (₹)' } },
                x: { title: { display: true, text: 'Date' } }
            }
        }
    });

    // Monthly Expense Chart
    const monthlyExpenseCtx = document.getElementById('monthlyExpenseChart').getContext('2d');
    new Chart(monthlyExpenseCtx, {
        type: 'bar',
        data: {
            labels: @json(array_map(function($month) {
                return \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y');
            }, array_keys($expenseData['monthly_expenses']['normal'] + $expenseData['monthly_expenses']['loss']))),
            datasets: [
                {
                    label: 'Normal Expenses',
                    data: @json(array_map(fn($month) => $expenseData['monthly_expenses']['normal'][$month] ?? 0, array_keys($expenseData['monthly_expenses']['normal'] + $expenseData['monthly_expenses']['loss']))),
                    backgroundColor: '#68d391',
                    borderColor: '#38a169',
                    borderWidth: 1
                },
                {
                    label: 'Loss Expenses',
                    data: @json(array_map(fn($month) => $expenseData['monthly_expenses']['loss'][$month] ?? 0, array_keys($expenseData['monthly_expenses']['normal'] + $expenseData['monthly_expenses']['loss']))),
                    backgroundColor: '#feb2b2',
                    borderColor: '#f56565',
                    borderWidth: 1
                }
            ]
        },
        options: {
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'Monthly Expenses (Normal vs Loss)' }
            },
            scales: {
                y: { beginAtZero: true, title: { display: true, text: 'Amount (₹)' } },
                x: { title: { display: true, text: 'Month' } }
            }
        }
    });

    // Export button
    document.getElementById('exportBtn').addEventListener('click', function() {
        window.location.href = '{{ route('admin.report.expense.export') }}?' + new URLSearchParams({
            start_date: '{{ request('start_date') }}',
            end_date: '{{ request('end_date') }}',
            status: '{{ request('status') }}'
        });
    });

    // View More button for Daily Expenses
    const viewMoreDailyBtn = document.getElementById('viewMoreDailyBtn');
    if (viewMoreDailyBtn) {
        viewMoreDailyBtn.addEventListener('click', function() {
            const rows = document.querySelectorAll('#dailyExpensesTable tbody tr[data-visible="false"]');
            const isHidden = rows[0]?.classList.contains('hidden');
            rows.forEach(row => {
                row.classList.toggle('hidden', !isHidden);
            });
            this.textContent = isHidden ? 'View Less' : 'View More';
        });
    }
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
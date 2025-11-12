<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExpenseExport;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

class ReportExpenceController extends Controller
{
    public function index(Request $request)
    {
        // Validate inputs
        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date|before_or_equal:end_date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|in:normal,loss',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Default date range: last 30 days
        $start_date = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $end_date = $request->input('end_date', now()->format('Y-m-d'));
        $status = $request->input('status', null); // Null means all statuses

        // Initialize expense data
        $dailyExpenses = ['normal' => [], 'loss' => []];
        $monthlyExpenses = ['normal' => [], 'loss' => []];

        // Base query
        $expenseQuery = Expense::query()
            ->whereBetween('expense_date', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);

        if ($status !== null) {
            $expenseQuery->where('status', $status);
        }

        $expenses = $expenseQuery->get();

        // Calculate aggregates
        $totalNormal = 0;
        $totalLoss = 0;
        $totalExpenses = 0;

        foreach ($expenses as $expense) {
            $amount = $expense->amount ?? 0;
            $statusKey = $expense->status === 'loss' ? 'loss' : 'normal';
            $totalExpenses++;

            if ($statusKey === 'normal') {
                $totalNormal += $amount;
            } else {
                $totalLoss += $amount;
            }

            // Daily expenses
            $dateKey = $expense->expense_date->format('Y-m-d');
            $dailyExpenses[$statusKey][$dateKey] = ($dailyExpenses[$statusKey][$dateKey] ?? 0) + $amount;

            // Monthly expenses
            $monthKey = $expense->expense_date->format('Y-m');
            $monthlyExpenses[$statusKey][$monthKey] = ($monthlyExpenses[$statusKey][$monthKey] ?? 0) + $amount;
        }

        // Log for debugging
        Log::debug('Expense Report Data', [
            'total_normal' => $totalNormal,
            'total_loss' => $totalLoss,
            'total_expenses' => $totalExpenses,
            'daily_expenses' => $dailyExpenses,
            'monthly_expenses' => $monthlyExpenses,
            'filters' => $request->all()
        ]);

        // Prepare data for view
        $expenseData = [
            'total_normal' => number_format($totalNormal, 2),
            'total_loss' => number_format($totalLoss, 2),
            'total_expenses' => $totalExpenses,
            'average_expense' => $totalExpenses > 0 ? number_format(($totalNormal + $totalLoss) / $totalExpenses, 2) : '0.00',
            'daily_expenses' => $dailyExpenses,
            'monthly_expenses' => $monthlyExpenses,
        ];

        // Check if no data found
if ($totalExpenses == 0) {
    $emptyExpenses = new LengthAwarePaginator([], 0, 10, $request->input('page', 1), [
        'path' => $request->url(),
        'query' => $request->query(),
    ]);

    return view('admin.report.expense.index', [
        'expenseData' => $expenseData,
        'expenses' => $emptyExpenses,
    ])->with('warning', 'No expenses found for the selected filters.');
}

        // Paginate expenses for table
        $expenses = $expenseQuery->orderBy('expense_date', 'desc')->paginate(10);

        return view('admin.report.expense.index', compact('expenseData', 'expenses'));
    }

    public function export(Request $request)
    {
        // Validate inputs
        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date|before_or_equal:end_date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|in:normal,loss',
        ]);

        if ($validator->fails()) {
            Log::error('Expense Export Validation Failed', ['errors' => $validator->errors()]);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $filename = 'expense_report_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
            return Excel::download(new ExpenseExport($request), $filename);
        } catch (\Exception $e) {
            Log::error('Expense Export Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'filters' => $request->all()
            ]);
            return redirect()->back()->with('error', 'Failed to export expense report. Please try again.');
        }
    }
}
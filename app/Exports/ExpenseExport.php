<?php

namespace App\Exports;

use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExpenseExport implements FromCollection, WithHeadings, WithStyles
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $start_date = $this->request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $end_date = $this->request->input('end_date', now()->format('Y-m-d'));
        $status = $this->request->input('status', null);

        // Initialize data arrays
        $dailyExpenses = ['normal' => [], 'loss' => []];
        $monthlyExpenses = ['normal' => [], 'loss' => []];
        $expenseDetails = [];

        // Query expenses
        $expenseQuery = Expense::query()
            ->whereBetween('expense_date', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);

        if ($status !== null) {
            $expenseQuery->where('status', $status);
        }

        $expenses = $expenseQuery->get();

        // Calculate aggregates
        foreach ($expenses as $expense) {
            $amount = $expense->amount ?? 0;
            $statusKey = $expense->status === 'loss' ? 'loss' : 'normal';

            // Daily expenses
            $dateKey = $expense->expense_date->format('Y-m-d');
            $dailyExpenses[$statusKey][$dateKey] = ($dailyExpenses[$statusKey][$dateKey] ?? 0) + $amount;

            // Monthly expenses
            $monthKey = $expense->expense_date->format('Y-m');
            $monthlyExpenses[$statusKey][$monthKey] = ($monthlyExpenses[$statusKey][$monthKey] ?? 0) + $amount;

            // Expense details
            $expenseDetails[] = [
                'title' => $expense->title,
                'date' => $expense->expense_date->format('d M Y'),
                'amount' => number_format($expense->amount, 2),
                'status' => ucfirst($expense->status),
                'notes' => $expense->notes ?? 'N/A',
            ];
        }

        // Log for debugging
        Log::debug('Expense Export Data', [
            'daily_expenses' => $dailyExpenses,
            'monthly_expenses' => $monthlyExpenses,
            'expense_count' => count($expenseDetails),
            'filters' => $this->request->all()
        ]);

        // Combine data for export
        $exportData = [];

        // Daily Expenses
        $exportData[] = ['Daily Expenses', '', ''];
        $exportData[] = ['Date', 'Normal (₹)', 'Loss (₹)'];
        foreach ($dailyExpenses['normal'] + $dailyExpenses['loss'] as $date => $value) {
            $exportData[] = [
                $date,
                number_format($dailyExpenses['normal'][$date] ?? 0, 2),
                number_format($dailyExpenses['loss'][$date] ?? 0, 2),
            ];
        }
        $exportData[] = ['', '', ''];

        // Monthly Expenses
        $exportData[] = ['Monthly Expenses', '', ''];
        $exportData[] = ['Month', 'Normal (₹)', 'Loss (₹)'];
        foreach ($monthlyExpenses['normal'] + $monthlyExpenses['loss'] as $month => $value) {
            $exportData[] = [
                \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y'),
                number_format($monthlyExpenses['normal'][$month] ?? 0, 2),
                number_format($monthlyExpenses['loss'][$month] ?? 0, 2),
            ];
        }
        $exportData[] = ['', '', ''];

        // Expense Details
        $exportData[] = ['Expense Details', '', '', '', ''];
        $exportData[] = ['Title', 'Date', 'Amount (₹)', 'Status', 'Notes'];
        foreach ($expenseDetails as $detail) {
            $exportData[] = [
                $detail['title'],
                $detail['date'],
                $detail['amount'],
                $detail['status'],
                $detail['notes'],
            ];
        }

        // If no data, return empty message
        if (empty($dailyExpenses['normal']) && empty($dailyExpenses['loss']) && empty($expenseDetails)) {
            $exportData[] = ['No data available for the selected filters.', '', '', '', ''];
        }

        return collect($exportData);
    }

    public function headings(): array
    {
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        $styles = [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FFCCCCCC']]],
            2 => ['font' => ['bold' => true]],
        ];

        // Dynamically find section headers
        $collection = $this->collection();
        $monthlyIndex = $collection->search(['Monthly Expenses', '', '']);
        if ($monthlyIndex !== false) {
            $styles[$monthlyIndex + 1] = ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FFCCCCCC']]];
            $styles[$monthlyIndex + 2] = ['font' => ['bold' => true]];
        }

        $detailsIndex = $collection->search(['Expense Details', '', '', '', '']);
        if ($detailsIndex !== false) {
            $styles[$detailsIndex + 1] = ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FFCCCCCC']]];
            $styles[$detailsIndex + 2] = ['font' => ['bold' => true]];
        }

        return $styles;
    }
}
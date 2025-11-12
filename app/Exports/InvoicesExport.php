<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;

class InvoicesExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = Invoice::query()
            ->with(['user'])
            ->select('invoices.*')
            ->leftJoin('users', 'invoices.user_id', '=', 'users.id');

        if ($this->request->filled('search')) {
            $search = $this->request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('invoices.customer_name', 'like', "%{$search}%")
                  ->orWhere('invoices.invoice_number', 'like', "%{$search}%");
            });
        }

        if ($this->request->filled('start_date') && $this->request->filled('end_date')) {
            $query->whereBetween('invoices.created_at', [
                $this->request->input('start_date'),
                $this->request->input('end_date') . ' 23:59:59'
            ]);
        }

        if ($this->request->filled('payment_status')) {
            $query->where('invoices.payment_mode', $this->request->input('payment_status'));
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Invoice #',
            'Customer',
            'Date',
            'Total',
            'GST',
            'Payment',
            'Status'
        ];
    }

    public function map($invoice): array
    {
        return [
            $invoice->invoice_number,
            $invoice->customer_name,
            $invoice->created_at->format('d M Y'),
            number_format($invoice->final_amount, 2),
            number_format($invoice->total_gst, 2),
            $invoice->payment_mode_text,
            $invoice->payment_terms_text,
        ];
    }
}
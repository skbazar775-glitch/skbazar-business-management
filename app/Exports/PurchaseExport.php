<?php

namespace App\Exports;

use App\Models\SupplierPurchase;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PurchaseExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = SupplierPurchase::query()
            ->with(['supplier', 'product']);

        if ($this->request->filled('search')) {
            $search = $this->request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($this->request->filled('start_date') && $this->request->filled('end_date')) {
            $query->whereBetween('invoice_date', [
                $this->request->input('start_date'),
                $this->request->input('end_date') . ' 23:59:59'
            ]);
        }

        if ($this->request->filled('gst_percent')) {
            $query->where('gst_percent', $this->request->input('gst_percent'));
        }

        // Debug query results
        $count = $query->count();
        Log::debug('Purchase Export Query', [
            'record_count' => $count,
            'filters' => $this->request->all()
        ]);

        return $query;
    }

    public function headings(): array
    {
        return [
            'Invoice #',
            'Supplier',
            'Product',
            'Quantity',
            'Invoice Date',
            'Total Price',
            'GST (%)',
            'Total GST',
            'Transportation Cost',
        ];
    }

    public function map($purchase): array
    {
        return [
            $purchase->invoice_no,
            $purchase->supplier->name ?? 'Unknown',
            $purchase->product->name ?? 'Unknown',
            $purchase->quantity,
            $purchase->invoice_date ? $purchase->invoice_date->format('d M Y') : 'N/A',
            number_format($purchase->total_price, 2),
            $purchase->gst_percent,
            number_format($purchase->total_gst_value, 2),
            number_format($purchase->transportation_costs_incl, 2),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FFCCCCCC']],
            ],
        ];
    }
}
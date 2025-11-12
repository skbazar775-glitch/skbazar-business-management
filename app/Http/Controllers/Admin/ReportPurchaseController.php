<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupplierPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PurchaseExport;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

class ReportPurchaseController extends Controller
{
    public function index(Request $request)
    {
        // Validate inputs
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string|max:255',
            'start_date' => 'nullable|date|before_or_equal:end_date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'gst_percent' => 'nullable|numeric|in:0,5,12,18,28',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Initialize query for purchases
        $purchaseQuery = SupplierPurchase::query()
            ->with(['supplier', 'product']);

        // Search by invoice number or supplier name
        if ($request->filled('search')) {
            $search = $request->input('search');
            $purchaseQuery->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Date range filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $purchaseQuery->whereBetween('invoice_date', [
                $request->input('start_date'),
                $request->input('end_date') . ' 23:59:59'
            ]);
        }

        // GST percent filter
        if ($request->filled('gst_percent')) {
            $purchaseQuery->where('gst_percent', $request->input('gst_percent'));
        }

        // Calculate aggregates separately
        $aggregatesQuery = SupplierPurchase::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $aggregatesQuery->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $aggregatesQuery->whereBetween('invoice_date', [
                $request->input('start_date'),
                $request->input('end_date') . ' 23:59:59'
            ]);
        }

        if ($request->filled('gst_percent')) {
            $aggregatesQuery->where('gst_percent', $request->input('gst_percent'));
        }

        // Use COALESCE to handle NULL values and use total_price
        $aggregates = $aggregatesQuery->selectRaw('
            COALESCE(SUM(total_price), 0) as total_purchase,
            COALESCE(SUM(total_gst_value), 0) as total_gst,
            COALESCE(SUM(transportation_costs_incl), 0) as total_transportation,
            COALESCE(AVG(total_price), 0) as average_purchase,
            COUNT(*) as total_records
        ')->first();

        // Log aggregates for debugging
        Log::debug('Purchase Report Aggregates', [
            'total_purchase' => $aggregates->total_purchase,
            'total_gst' => $aggregates->total_gst,
            'total_transportation' => $aggregates->total_transportation,
            'average_purchase' => $aggregates->average_purchase,
            'total_records' => $aggregates->total_records,
            'filters' => $request->all()
        ]);

        // Store unformatted values for chart
        $chartData = [
            'total_purchase' => $aggregates->total_purchase,
            'total_gst' => $aggregates->total_gst,
            'total_transportation' => $aggregates->total_transportation,
            'average_purchase' => $aggregates->average_purchase,
        ];

        // Check if no records found
        if ($aggregates->total_records == 0) {
            // Create an empty paginated response
            $purchases = new LengthAwarePaginator([], 0, 10, 1, [
                'path' => $request->url(),
                'query' => $request->query(),
            ]);

            return view('admin.report.purchase.index', [
                'purchases' => $purchases,
                'purchaseData' => [
                    'total_purchase' => '0.00',
                    'total_gst' => '0.00',
                    'total_transportation' => '0.00',
                    'average_purchase' => '0.00',
                    'total_purchases' => 0,
                ],
                'chartData' => $chartData
            ])->with('warning', 'No purchases found for the selected filters.');
        }

        // Check if total_purchase is zero due to missing total_price
        if ($aggregates->total_purchase == 0 && $aggregates->total_records > 0) {
            return view('admin.report.purchase.index', [
                'purchases' => $purchaseQuery->orderBy('invoice_date', 'desc')->paginate(10),
                'purchaseData' => [
                    'total_purchase' => '0.00',
                    'total_gst' => number_format($aggregates->total_gst, 2),
                    'total_transportation' => number_format($aggregates->total_transportation, 2),
                    'average_purchase' => '0.00',
                    'total_purchases' => $aggregates->total_records,
                ],
                'chartData' => $chartData
            ])->with('warning', 'Total purchase and average purchase are zero due to missing total price values.');
        }

        // Paginate results
        $purchases = $purchaseQuery->orderBy('invoice_date', 'desc')->paginate(10);

        // Format data for view
        $purchaseData = [
            'total_purchase' => number_format($aggregates->total_purchase, 2),
            'total_gst' => number_format($aggregates->total_gst, 2),
            'total_transportation' => number_format($aggregates->total_transportation, 2),
            'average_purchase' => number_format($aggregates->average_purchase, 2),
            'total_purchases' => $purchases->total(),
        ];

        return view('admin.report.purchase.index', compact('purchases', 'purchaseData', 'chartData'));
    }

    public function export(Request $request)
    {
        // Validate inputs
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string|max:255',
            'start_date' => 'nullable|date|before_or_equal:end_date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'gst_percent' => 'nullable|numeric|in:0,5,12,18,28',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $filename = 'purchases_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        return Excel::download(new PurchaseExport($request), $filename);
    }
}
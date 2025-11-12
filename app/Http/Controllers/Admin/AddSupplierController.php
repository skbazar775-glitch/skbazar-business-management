<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\SupplierCredit;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AddSupplierController extends Controller
{

    public function index()
    {
        $suppliers = Supplier::latest()->paginate(10);
        $supplierBalances = Supplier::with('credits')
            ->get()
            ->mapWithKeys(function ($supplier) {
                $totalReceived = $supplier->credits()->where('type', 'received')->sum('amount');
                $totalDue = $supplier->credits()->where('type', 'due')->sum('amount');
                return [$supplier->id => $totalReceived - $totalDue];
            });

        return view('admin.addsupplier.index', compact('suppliers', 'supplierBalances'));
    }

    public function create()
    {
        return view('admin.addsupplier.create');
    }

    public function store(Request $request)
    {
$request->validate([
    'name' => ['required', 'string', 'max:255'],
    'email' => ['nullable', 'string', 'email', 'max:255', 'unique:suppliers'],
    'contact_number' => ['nullable', 'string', 'max:20'],
    'location' => ['nullable', 'string', 'max:255'],
    'amount' => ['nullable', 'numeric', 'min:0'],
]);


        $supplier = Supplier::create([
            'name' => $request->name,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'location' => $request->location,
            'amount' => $request->amount ?? 0,
        ]);

        if ($request->filled('amount') && $request->amount > 0) {
            $supplier->credits()->create([
                'type' => 'received',
                'amount' => $request->amount,
                'date' => Carbon::now('Asia/Kolkata'),
                'note' => 'Initial balance',
            ]);
        }

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier created successfully.');
    }

    public function edit(Supplier $supplier)
    {
        return view('admin.addsupplier.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:suppliers,email,' . $supplier->id],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'location' => ['nullable', 'string', 'max:255'],
            'amount' => ['nullable', 'numeric', 'min:0'],
        ]);

        $supplier->update([
            'name' => $request->name,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'location' => $request->location,
            'amount' => $request->amount ?? 0,
        ]);

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->credits()->delete();
        $supplier->delete();
        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier deleted successfully.');
    }

    public function showAccount(Supplier $supplier)
    {
        $supplier->load('credits');
        $totals = $supplier->credits()
            ->selectRaw("SUM(CASE WHEN type = 'due' THEN amount ELSE 0 END) as total_due")
            ->selectRaw("SUM(CASE WHEN type = 'received' THEN amount ELSE 0 END) as total_received")
            ->first();
        $totalDue = $totals->total_due;
        $totalReceived = $totals->total_received;
        $balanceAdvance = $totalReceived - $totalDue;

        $suppliers = Supplier::latest()->paginate(10);
        $supplierBalances = Supplier::with('credits')
            ->get()
            ->mapWithKeys(function ($sup) {
                $totalReceived = $sup->credits()->where('type', 'received')->sum('amount');
                $totalDue = $sup->credits()->where('type', 'due')->sum('amount');
                return [$sup->id => $totalReceived - $totalDue];
            });

        return view('admin.addsupplier.supplieraccount', compact('supplier', 'totalDue', 'totalReceived', 'balanceAdvance', 'suppliers', 'supplierBalances'));
    }

    public function search(Request $request)
    {
        if (!$request->expectsJson()) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        $query = $request->query('q');
        $suppliers = Supplier::where('name', 'ilike', "%{$query}%")
            ->orWhere('email', 'ilike', "%{$query}%")
            ->latest()
            ->paginate(10);

        return response()->json(['suppliers' => $suppliers->toArray()]);
    }

    public function storeCredit(Request $request, Supplier $supplier)
    {
        $request->validate([
            'transaction_date' => [
                'nullable',
                'date',
                'before_or_equal:' . Carbon::now('Asia/Kolkata')->format('Y-m-d H:i'),
                'after_or_equal:' . Carbon::now('Asia/Kolkata')->subYears(5)->format('Y-m-d'),
            ],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'type' => ['required', 'in:received,due'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $transactionDate = $request->filled('transaction_date')
            ? Carbon::createFromFormat('Y-m-d\TH:i', $request->transaction_date, 'Asia/Kolkata')
            : Carbon::now('Asia/Kolkata');

        $supplier->credits()->create([
            'type' => $request->type,
            'amount' => $request->amount,
            'note' => $request->note,
            'date' => $transactionDate,
        ]);

        return redirect()->route('admin.suppliers.account', $supplier)
            ->with('success', 'Transaction recorded successfully.');
    }

    public function updateCredit(Request $request, Supplier $supplier, SupplierCredit $credit)
    {
        $request->validate([
            'transaction_date' => [
                'nullable',
                'date',
                'before_or_equal:' . Carbon::now('Asia/Kolkata')->format('Y-m-d H:i'),
                'after_or_equal:' . Carbon::now('Asia/Kolkata')->subYears(5)->format('Y-m-d'),
            ],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'type' => ['required', 'in:received,due'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $transactionDate = $request->filled('transaction_date')
            ? Carbon::createFromFormat('Y-m-d\TH:i', $request->transaction_date, 'Asia/Kolkata')
            : Carbon::now('Asia/Kolkata');

        $credit->update([
            'type' => $request->type,
            'amount' => $request->amount,
            'note' => $request->note,
            'date' => $transactionDate,
        ]);

        return redirect()->route('admin.suppliers.account', $supplier)
            ->with('success', 'Transaction updated successfully.');
    }

    public function deleteCredit(Request $request, Supplier $supplier, SupplierCredit $credit)
    {
        $credit->delete();
        return redirect()->route('admin.suppliers.account', $supplier)
            ->with('success', 'Transaction deleted successfully.');
    }
}
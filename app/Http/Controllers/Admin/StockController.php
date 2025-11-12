<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\Product;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Stock::query()->with('product');
        
        if ($search = $request->query('q')) {
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $stocks = $query->latest()->paginate(10);
        
        return view('admin.inventory.stock.index', compact('stocks'));
    }

    public function show($id)
    {
        $stock = Stock::with('product')->findOrFail($id);
        
        return view('admin.inventory.stock.show', compact('stock'));
    }

    public function edit($id)
    {
        $stock = Stock::findOrFail($id);
        $units = ['g', 'kg', 'lit', 'ml', 'piece', 'inch', 'm', 'dozen', 'packet', 'box', 'unit'];
        
        return view('admin.inventory.stock.edit', compact('stock', 'units'));
    }

public function update(Request $request, $id)
{
    $stock = Stock::findOrFail($id);

    $validated = $request->validate([
        'stock_quantity' => 'required|numeric|min:0',
        'stock_quantity_unit' => 'required|in:g,kg,lit,ml,piece,inch,m,dozen,packet,box,unit',
    ]);

    $stock->update([
        'stock_quantity' => $validated['stock_quantity'],
        'stock_quantity_unit' => $validated['stock_quantity_unit'],
        'updated_by' => auth()->id(),
    ]);

    return redirect()->route('admin.stock.index')->with('success', 'Stock updated successfully');
}


    public function destroy($id)
    {
        $stock = Stock::findOrFail($id);
        $stock->delete();

        return redirect()->back()->with('success', 'Stock deleted successfully');
    }
}
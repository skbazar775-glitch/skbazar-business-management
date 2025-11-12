<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\SupplierPurchase;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\Paginator;

class BuySupplierProductsController extends Controller
{
    /**
 * Display the supplier purchase history.
 *
 * @param Request $request
 * @return \Illuminate\View\View
 */
public function history(Request $request)
{
    $purchases = SupplierPurchase::with(['supplier', 'product'])->paginate(10); // Paginate with 10 records per page
    return view('admin.buysupplierproducts.purchase_history', compact('purchases'));
}
public function boot()
{
    // Paginator::useBootstrapFive(); // Remove or comment out
}

    /**
     * Display the list of booked services and form for supplier purchase.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $suppliers = Supplier::all();
        $products = Product::all(); // Show all products
        $purchases = SupplierPurchase::with(['supplier', 'product'])->get();

        return view('admin.buysupplierproducts.index', compact('suppliers', 'products', 'purchases'));
    }

    /**
     * Store a new supplier purchase and update stock quantity.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.purchase_price_incl_gst' => 'nullable|numeric|min:0',
            'products.*.purchase_price_excl_gst' => 'nullable|numeric|min:0',
            'products.*.gst_percent' => 'required|numeric|min:0',
            'invoice_no' => 'nullable|string|max:255',
            'invoice_date' => 'nullable|date',
            'transportation_costs_incl' => 'nullable|numeric|min:0',
            'transportation_costs_excl' => 'nullable|numeric|min:0',
            'total_payment' => 'nullable|numeric|min:0',
        ]);

        try {
            $totalInvoiceValue = 0;
            $totalInvoiceValueInclTransportation = 0;

            foreach ($request->products as $productData) {
                $purchasePriceInclGst = $productData['purchase_price_incl_gst'] ?? null;
                $purchasePriceExclGst = $productData['purchase_price_excl_gst'] ?? null;
                $gstPercent = $productData['gst_percent'];
                $quantity = $productData['quantity'];
                $productId = $productData['product_id'];

                // Calculate GST values
                if ($purchasePriceInclGst !== null) {
                    // Calculate excluding GST from including GST price
                    $gstValuePerQty = $purchasePriceInclGst - ($purchasePriceInclGst / (1 + $gstPercent / 100));
                    $purchasePriceExclGst = $purchasePriceInclGst - $gstValuePerQty;
                } elseif ($purchasePriceExclGst !== null) {
                    // Calculate including GST from excluding GST price
                    $gstValuePerQty = $purchasePriceExclGst * ($gstPercent / 100);
                    $purchasePriceInclGst = $purchasePriceExclGst + $gstValuePerQty;
                } else {
                    throw new \Exception('Either purchase_price_incl_gst or purchase_price_excl_gst must be provided.');
                }

                $totalGstValue = $gstValuePerQty * $quantity;
                $totalPrice = $purchasePriceInclGst * $quantity;
                $totalPriceWithoutGst = $purchasePriceExclGst * $quantity;

                $totalInvoiceValue += $totalPrice;

                // Save the supplier purchase
                SupplierPurchase::create([
                    'supplier_id' => $request->supplier_id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'purchase_price_incl_gst' => $purchasePriceInclGst,
                    'purchase_price_excl_gst' => $purchasePriceExclGst,
                    'gst_percent' => $gstPercent,
                    'gst_value_per_qty' => $gstValuePerQty,
                    'total_gst_value' => $totalGstValue,
                    'total_price' => $totalPrice,
                    'total_price_without_gst' => $totalPriceWithoutGst,
                    'invoice_no' => $request->invoice_no,
                    'invoice_date' => $request->invoice_date,
                    'total_invoice_value' => $totalPrice, // Per product entry
                    'transportation_costs_incl' => $request->transportation_costs_incl,
                    'transportation_costs_excl' => $request->transportation_costs_excl,
                    'total_payment' => $request->total_payment,
                    'after_payment_total_value' => $request->total_payment ? $totalPrice - $request->total_payment : $totalPrice,
                ]);

                // Update stock quantity
                $stock = Stock::firstOrCreate(
                    ['product_id' => $productId],
                    [
                        'stock_quantity' => 0,
                        'stock_quantity_unit' => 'unit', // Default unit, adjust as needed
                        'updated_by' => auth()->id(),
                    ]
                );

                $stock->increment('stock_quantity', $quantity);
                $stock->updated_by = auth()->id();
                $stock->save();
            }

            $totalInvoiceValueInclTransportation = $totalInvoiceValue + ($request->transportation_costs_incl ?? 0);

            return redirect()->route('admin.buysupplierproducts.index')
                ->with('success', 'Supplier purchase recorded successfully, and stock updated.');
        } catch (\Exception $e) {
            Log::error('Error saving supplier purchase or updating stock: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to record purchase or update stock: ' . $e->getMessage());
        }
    }
}
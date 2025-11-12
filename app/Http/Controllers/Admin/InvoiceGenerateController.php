<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\HsnCode;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceGenerateController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('address')->get();
        $products = Product::all();
        $hsnCodes = HsnCode::all();

        Log::info('Products fetched for invoice form', ['count' => $products->count(), 'products' => $products->pluck('name', 'id')]);

        return view('admin.invoice.index', compact('users', 'products', 'hsnCodes'));
    }

    
    public function withoutindex(Request $request)
    {
        $users = User::with('address')->get();
        $products = Product::all();
        $hsnCodes = HsnCode::all();

        Log::info('Products fetched for invoice form', ['count' => $products->count(), 'products' => $products->pluck('name', 'id')]);

        return view('admin.invoice.withoutgst', compact('users', 'products', 'hsnCodes'));
    }


    public function list(Request $request)
    {
        $invoices = Invoice::with('user', 'address')->latest()->paginate(10);
        return view('admin.invoice.list', compact('invoices'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:users,id',
            'customer_name' => 'required|string|max:255',
            'customer_address' => 'required|string',
            'customer_mobile' => 'required|string|max:20',
            'customer_gst_no' => 'nullable|string|max:50',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.hsn_code' => 'required|exists:hsn_codes,code',
            'items.*.rate_with_gst' => 'required|numeric|min:0',
            'items.*.gst_percent' => 'required|in:0,1,2,3',
            'discount' => 'nullable|numeric|min:0',
            'advance_payment' => 'nullable|numeric|min:0',
            'payment_terms' => 'required|in:0,1,2',
            'payment_mode' => 'required|in:0,1,2,3',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $addressId = null;
        if ($request->user_id) {
            $user = User::with('address')->find($request->user_id);
            if (!$user) {
                return redirect()->back()->withErrors(['user_id' => 'Selected user not found.'])->withInput();
            }
            $addressId = $user->address ? $user->address->id : null;
        } else {
            $address = Address::create([
                'user_id' => null,
                'name' => $request->customer_name,
                'phone' => $request->customer_mobile,
                'area' => $request->customer_address,
                'city' => '',
                'district' => '',
                'pin_code' => '',
                'landmark' => '',
                'gst_no' => $request->customer_gst_no ?? 'NA',
            ]);
            $addressId = $address->id;
        }

        $totalPrice = 0;
        $totalGst = 0;

        foreach ($request->items as $item) {
            $gstRate = match ($item['gst_percent']) {
                '0' => 0,
                '1' => 12,
                '2' => 18,
                '3' => 28,
                default => 0,
            };

            $rateWithoutGst = $item['rate_with_gst'] / (1 + ($gstRate / 100));
            $priceWithoutGst = $rateWithoutGst * $item['quantity'];
            $gstValue = ($item['rate_with_gst'] * $item['quantity']) - $priceWithoutGst;

            $totalPrice += $item['rate_with_gst'] * $item['quantity'];
            $totalGst += $gstValue;
        }

        $sgst = $totalGst / 2;
        $cgst = $totalGst / 2;
        $finalAmount = $totalPrice - ($request->discount ?? 0) - ($request->advance_payment ?? 0);

        $totalInWords = $this->numberToWords($finalAmount);

        $invoice = Invoice::create([
            'user_id' => $request->user_id,
            'address_id' => $addressId,
            'customer_name' => $request->customer_name,
            'customer_address' => $request->customer_address,
            'customer_mobile' => $request->customer_mobile,
            'customer_gst_no' => $request->customer_gst_no ?? 'NA',
            'invoice_number' => 'INV-' . time(),
            'total_price' => $totalPrice,
            'total_gst' => $totalGst,
            'sgst' => $sgst,
            'cgst' => $cgst,
            'discount' => $request->discount ?? 0,
            'advance_payment' => $request->advance_payment ?? 0,
            'final_amount' => $finalAmount,
            'total_in_words' => $totalInWords,
            'payment_terms' => $request->payment_terms,
            'payment_mode' => $request->payment_mode,
        ]);

        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            if (!$product) {
                return redirect()->back()->withErrors(['items' => 'Product ID ' . $item['product_id'] . ' not found.'])->withInput();
            }

            $gstRate = match ($item['gst_percent']) {
                '0' => 0,
                '1' => 12,
                '2' => 18,
                '3' => 28,
                default => 0,
            };

            $rateWithoutGst = $item['rate_with_gst'] / (1 + ($gstRate / 100));
            $priceWithoutGst = $rateWithoutGst * $item['quantity'];
            $gstValue = ($item['rate_with_gst'] * $item['quantity']) - $priceWithoutGst;

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_name' => $product->name,
                'serial_number' => $product->id,
                'quantity' => $item['quantity'],
                'hsn_code' => $item['hsn_code'],
                'rate_with_gst' => $item['rate_with_gst'],
                'gst_percent' => $item['gst_percent'],
                'rate_without_gst' => $rateWithoutGst,
                'price_without_gst' => $priceWithoutGst,
                'gst_value' => $gstValue,
                'total_price' => $item['rate_with_gst'] * $item['quantity'],
            ]);
        }

        return redirect()->route('admin.invoice.list')->with('success', 'Invoice created successfully! Invoice ID: ' . $invoice->id);
    }


// app/Http/Controllers/InvoiceController.php
public function storeWithoutGst(Request $request)
{
    $validator = Validator::make($request->all(), [
        'user_id' => 'nullable|exists:users,id',
        'customer_name' => 'required|string|max:255',
        'customer_address' => 'required|string',
        'customer_mobile' => 'required|string|max:20',
        'customer_gst_no' => 'nullable|string|max:50',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.price' => 'required|numeric|min:0',
        'discount' => 'nullable|numeric|min:0',
        'advance_payment' => 'nullable|numeric|min:0',
        'payment_terms' => 'required|in:0,1,2',
        'payment_mode' => 'required|in:0,1,2,3',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $addressId = null;
    if ($request->user_id) {
        $user = User::with('address')->find($request->user_id);
        if (!$user) {
            return redirect()->back()->withErrors(['user_id' => 'Selected user not found.'])->withInput();
        }
        $addressId = $user->address ? $user->address->id : null;
    } else {
        $address = Address::create([
            'user_id' => null,
            'name' => $request->customer_name,
            'phone' => $request->customer_mobile,
            'area' => $request->customer_address,
            'city' => '',
            'district' => '',
            'pin_code' => '',
            'landmark' => '',
            'gst_no' => $request->customer_gst_no ?? 'NA',
        ]);
        $addressId = $address->id;
    }

    $totalPrice = 0;

    foreach ($request->items as $item) {
        $totalPrice += $item['price'] * $item['quantity'];
    }

    $finalAmount = $totalPrice - ($request->discount ?? 0) - ($request->advance_payment ?? 0);

    $totalInWords = $this->numberToWords($finalAmount);

    $invoice = Invoice::create([
        'user_id' => $request->user_id,
        'address_id' => $addressId,
        'customer_name' => $request->customer_name,
        'customer_address' => $request->customer_address,
        'customer_mobile' => $request->customer_mobile,
        'customer_gst_no' => $request->customer_gst_no ?? 'NA',
        'invoice_number' => 'INV-' . time(),
        'total_price' => $totalPrice,
        'total_gst' => 0,
        'sgst' => 0,
        'cgst' => 0,
        'discount' => $request->discount ?? 0,
        'advance_payment' => $request->advance_payment ?? 0,
        'final_amount' => $finalAmount,
        'total_in_words' => $totalInWords,
        'payment_terms' => $request->payment_terms,
        'payment_mode' => $request->payment_mode,
        'is_without_gst' => true, // Add this flag to identify non-GST invoices
    ]);

    foreach ($request->items as $item) {
        $product = Product::find($item['product_id']);
        if (!$product) {
            return redirect()->back()->withErrors(['items' => 'Product ID ' . $item['product_id'] . ' not found.'])->withInput();
        }

        InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'product_name' => $product->name,
            'serial_number' => $product->id,
            'quantity' => $item['quantity'],
            'hsn_code' => 'N/A',
            'rate_with_gst' => $item['price'],
            'gst_percent' => 0,
            'rate_without_gst' => $item['price'],
            'price_without_gst' => $item['price'] * $item['quantity'],
            'gst_value' => 0,
            'total_price' => $item['price'] * $item['quantity'],
            'is_without_gst' => true, // Add this flag to identify non-GST items
        ]);
    }

    return redirect()->route('admin.invoice.list')->with('success', 'Invoice (without GST) created successfully! Invoice ID: ' . $invoice->id);
}


    public function edit($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        $users = User::with('address')->get();
        $products = Product::all();
        $hsnCodes = HsnCode::all();

        return view('admin.invoice.edit', compact('invoice', 'users', 'products', 'hsnCodes'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:users,id',
            'customer_name' => 'required|string|max:255',
            'customer_address' => 'required|string',
            'customer_mobile' => 'required|string|max:20',
            'customer_gst_no' => 'nullable|string|max:50',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.hsn_code' => 'required|exists:hsn_codes,code',
            'items.*.rate_with_gst' => 'required|numeric|min:0',
            'items.*.gst_percent' => 'required|in:0,1,2,3',
            'discount' => 'nullable|numeric|min:0',
            'advance_payment' => 'nullable|numeric|min:0',
            'payment_terms' => 'required|in:0,1,2',
            'payment_mode' => 'required|in:0,1,2,3',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $invoice = Invoice::findOrFail($id);

        $addressId = $invoice->address_id;
        if ($request->user_id) {
            $user = User::with('address')->find($request->user_id);
            if (!$user) {
                return redirect()->back()->withErrors(['user_id' => 'Selected user not found.'])->withInput();
            }
            $addressId = $user->address ? $user->address->id : null;
        } else {
            if ($addressId) {
                Address::where('id', $addressId)->update([
                    'name' => $request->customer_name,
                    'phone' => $request->customer_mobile,
                    'area' => $request->customer_address,
                    'gst_no' => $request->customer_gst_no ?? 'NA',
                ]);
            } else {
                $address = Address::create([
                    'user_id' => null,
                    'name' => $request->customer_name,
                    'phone' => $request->customer_mobile,
                    'area' => $request->customer_address,
                    'city' => '',
                    'district' => '',
                    'pin_code' => '',
                    'landmark' => '',
                    'gst_no' => $request->customer_gst_no ?? 'NA',
                ]);
                $addressId = $address->id;
            }
        }

        $totalPrice = 0;
        $totalGst = 0;

        foreach ($request->items as $item) {
            $gstRate = match ($item['gst_percent']) {
                '0' => 0,
                '1' => 12,
                '2' => 18,
                '3' => 28,
                default => 0,
            };

            $rateWithoutGst = $item['rate_with_gst'] / (1 + ($gstRate / 100));
            $priceWithoutGst = $rateWithoutGst * $item['quantity'];
            $gstValue = ($item['rate_with_gst'] * $item['quantity']) - $priceWithoutGst;

            $totalPrice += $item['rate_with_gst'] * $item['quantity'];
            $totalGst += $gstValue;
        }

        $sgst = $totalGst / 2;
        $cgst = $totalGst / 2;
        $finalAmount = $totalPrice - ($request->discount ?? 0) - ($request->advance_payment ?? 0);

        $totalInWords = $this->numberToWords($finalAmount);

        $invoice->update([
            'user_id' => $request->user_id,
            'address_id' => $addressId,
            'customer_name' => $request->customer_name,
            'customer_address' => $request->customer_address,
            'customer_mobile' => $request->customer_mobile,
            'customer_gst_no' => $request->customer_gst_no ?? 'NA',
            'total_price' => $totalPrice,
            'total_gst' => $totalGst,
            'sgst' => $sgst,
            'cgst' => $cgst,
            'discount' => $request->discount ?? 0,
            'advance_payment' => $request->advance_payment ?? 0,
            'final_amount' => $finalAmount,
            'total_in_words' => $totalInWords,
            'payment_terms' => $request->payment_terms,
            'payment_mode' => $request->payment_mode,
        ]);

        InvoiceItem::where('invoice_id', $invoice->id)->delete();

        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            if (!$product) {
                return redirect()->back()->withErrors(['items' => 'Product ID ' . $item['product_id'] . ' not found.'])->withInput();
            }

            $gstRate = match ($item['gst_percent']) {
                '0' => 0,
                '1' => 12,
                '2' => 18,
                '3' => 28,
                default => 0,
            };

            $rateWithoutGst = $item['rate_with_gst'] / (1 + ($gstRate / 100));
            $priceWithoutGst = $rateWithoutGst * $item['quantity'];
            $gstValue = ($item['rate_with_gst'] * $item['quantity']) - $priceWithoutGst;

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_name' => $product->name,
                'serial_number' => $product->id,
                'quantity' => $item['quantity'],
                'hsn_code' => $item['hsn_code'],
                'rate_with_gst' => $item['rate_with_gst'],
                'gst_percent' => $item['gst_percent'],
                'rate_without_gst' => $rateWithoutGst,
                'price_without_gst' => $priceWithoutGst,
                'gst_value' => $gstValue,
                'total_price' => $item['rate_with_gst'] * $item['quantity'],
            ]);
        }

        return redirect()->route('admin.invoice.list')->with('success', 'Invoice updated successfully! Invoice ID: ' . $invoice->id);
    }

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        InvoiceItem::where('invoice_id', $invoice->id)->delete();
        $invoice->delete();

        return redirect()->route('admin.invoice.list')->with('success', 'Invoice deleted successfully!');
    }

    public function downloadPdf($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        $pdf = Pdf::loadView('admin.invoice.pdf', compact('invoice'));
        return $pdf->download('invoice_' . $invoice->invoice_number . '.pdf');
    }

public function preview($id)
{
    $invoice = Invoice::with(['items', 'user', 'address'])->findOrFail($id);

    // Direct model object pass kar
    $pdf = \Pdf::loadView('admin.invoice.pdf', [
        'invoice' => $invoice
    ]);

    return $pdf->stream('invoice_' . $invoice->invoice_number . '.pdf');
}


    public function getUserDetails($id)
    {
        $user = User::with('address')->find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json([
            'name' => $user->name,
            'address' => $user->address ? $user->address->full_address : 'No address available',
            'phone' => $user->address ? $user->address->phone : 'No phone available',
            'gst' => $user->address ? ($user->address->gst_no ?? 'NA') : 'NA',
        ]);
    }

    private function numberToWords($number)
    {
        $ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
        $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
        $teens = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];

        $number = round($number);
        if ($number == 0) {
            return 'Zero';
        }

        if ($number < 10) {
            return $ones[$number];
        }

        if ($number < 20) {
            return $teens[$number - 10];
        }

        if ($number < 100) {
            $tensPart = floor($number / 10);
            $onesPart = $number % 10;
            return $tens[$tensPart] . ($onesPart ? ' ' . $ones[$onesPart] : '');
        }

        return number_format($number) . ' Rupees';
    }
}
?>
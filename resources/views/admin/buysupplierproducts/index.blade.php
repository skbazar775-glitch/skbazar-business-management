@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Supplier Purchase Management</h1>

    <!-- GST Calculation Instructions -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-8 rounded-r-lg relative">
        <button type="button" class="absolute top-3 right-3 text-blue-500 hover:text-blue-700 focus:outline-none" aria-label="Close instructions">
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-gray-900">GST Calculation Instructions</h3>
                <div class="mt-2 text-sm text-gray-900">
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Select a <strong class="text-gray-900">GST %</strong> from the dropdown (5, 10, 12, 18, or 28) and enter either <strong class="text-gray-900">Price Excluding GST</strong> or <strong class="text-gray-900">Price Including GST</strong>.</li>
                        <li>If <strong class="text-gray-900">Price Excl. GST</strong> is entered, <strong class="text-gray-900">Price Incl. GST</strong> will be calculated as: <code class="bg-blue-100 px-1 rounded text-gray-900">Price Incl. GST = Price Excl. GST + (Price Excl. GST × GST % / 100)</code>.</li>
                        <li>If <strong class="text-gray-900">Price Incl. GST</strong> is entered, <strong class="text-gray-900">Price Excl. GST</strong> will be calculated as: <code class="bg-blue-100 px-1 rounded text-gray-900">Price Excl. GST = Price Incl. GST / (1 + GST % / 100)</code>.</li>
                        <li>Changing the <strong class="text-gray-900">GST %</strong> will automatically update the other price field if one is provided.</li>
                        <li><strong class="text-gray-900">GST Value per Quantity</strong> = Price Incl. GST - Price Excl. GST.</li>
                        <li><strong class="text-gray-900">Total GST Value</strong> = GST Value per Quantity × Quantity.</li>
                        <li><strong class="text-gray-900">Total Price</strong> = Price Incl. GST × Quantity (contributes to Total Invoice Value).</li>
                        <li><strong class="text-gray-900">Total Price Without GST</strong> = Price Excl. GST × Quantity.</li>
                        <li><strong class="text-gray-900">Total Invoice Value</strong> = Sum of Total Price for all products.</li>
                        <li><strong class="text-gray-900">Total Invoice Value Incl. Transportation</strong> = Total Invoice Value + Transportation Costs (Incl. GST).</li>
                        <li><strong class="text-gray-900">After Payment Total Value</strong> = Total Price - Total Payment (if provided).</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Form for Supplier Purchase -->
    <form action="{{ route('admin.buysupplierproducts.store') }}" method="POST">
        @csrf
        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-8">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Add New Purchase</h2>
            </div>
            <div class="p-6">
                <!-- Supplier Selection -->
                <div class="mb-6">
                    <label for="supplier_id" class="block text-sm font-medium text-gray-900 mb-1">Select Supplier</label>
                    <select name="supplier_id" id="supplier_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md bg-white text-gray-900" required>
                        <option value="">Select a supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" class="text-gray-900">{{ $supplier->name }} ({{ $supplier->email }}) - {{ $supplier->location }}</option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Product Selection -->
                <div id="products-container">
                    <div class="product-row mb-6 p-4 border border-gray-200 rounded-lg">
                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12 sm:col-span-4">
                                <label for="product_id" class="block text-sm font-medium text-gray-900 mb-1">Select Product</label>
                                <select name="products[0][product_id]" class="product-select mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md bg-white text-gray-900" required>
                                    <option value="">Select a product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}"
                                                data-price-e="{{ $product->price_e }}"
                                                data-price-s="{{ $product->price_s }}"
                                                class="text-gray-900">
                                            {{ $product->name }} ({{ $product->getStatusTextAttribute() }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('products.0.product_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-span-6 sm:col-span-2">
                                <label for="quantity" class="block text-sm font-medium text-gray-900 mb-1">Quantity</label>
                                <input type="number" name="products[0][quantity]" class="quantity mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900" min="1" required>
                                @error('products.0.quantity')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-span-6 sm:col-span-2">
                                <label for="purchase_price_excl_gst" class="block text-sm font-medium text-gray-900 mb-1">Price Excl. GST</label>
                                <input type="number" name="products[0][purchase_price_excl_gst]" class="purchase_price_excl_gst mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900" step="0.01">
                                @error('products.0.purchase_price_excl_gst')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-span-6 sm:col-span-2">
                                <label for="purchase_price_incl_gst" class="block text-sm font-medium text-gray-900 mb-1">Price Incl. GST</label>
                                <input type="number" name="products[0][purchase_price_incl_gst]" class="purchase_price_incl_gst mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900" step="0.01">
                                @error('products.0.purchase_price_incl_gst')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-span-6 sm:col-span-1">
                                <label for="gst_percent" class="block text-sm font-medium text-gray-900 mb-1">GST %</label>
                                <select name="products[0][gst_percent]" class="gst_percent mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md bg-white text-gray-900" required>
                                    <option value="">Select GST %</option>
                                    <option value="5">5%</option>
                                    <option value="10">10%</option>
                                    <option value="12">12%</option>
                                    <option value="18">18%</option>
                                    <option value="28">28%</option>
                                </select>
                                @error('products.0.gst_percent')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-span-6 sm:col-span-1 flex items-end">
                                <button type="button" class="remove-product w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" id="add-product" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Add Another Product
                </button>

                <!-- Other Fields -->
                <div class="mt-8 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-2">
                        <label for="invoice_no" class="block text-sm font-medium text-gray-900">Invoice No</label>
                        <input type="text" name="invoice_no" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900">
                        @error('invoice_no')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="invoice_date" class="block text-sm font-medium text-gray-900">Invoice Date</label>
                        <input type="date" name="invoice_date" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900">
                        @error('invoice_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="transportation_costs_incl" class="block text-sm font-medium text-gray-900">Transportation Costs (Incl. GST)</label>
                        <input type="number" name="transportation_costs_incl" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900" step="0.01">
                        @error('transportation_costs_incl')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="transportation_costs_excl" class="block text-sm font-medium text-gray-900">Transportation Costs (Excl. GST)</label>
                        <input type="number" name="transportation_costs_excl" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900" step="0.01">
                        @error('transportation_costs_excl')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="total_payment" class="block text-sm font-medium text-gray-900">Total Payment</label>
                        <input type="number" name="total_payment" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900" step="0.01">
                        @error('total_payment')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8">
                    <button type="submit" class="w-full inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-lg font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Save Purchase
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Display Existing Purchases -->
    {{-- <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Purchase History</h2>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Supplier</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Product</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Qty</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Price Excl.</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Price Incl.</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">GST %</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">GST/Unit</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Total GST</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Total</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Total w/o GST</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Invoice No</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Invoice Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Trans. Cost</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Invoice Value</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Payment</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">After Payment</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Created At</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($purchases as $purchase)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $purchase->supplier->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $purchase->product->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $purchase->quantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($purchase->purchase_price_excl_gst, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($purchase->purchase_price_incl_gst, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $purchase->gst_percent }}%</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($purchase->gst_value_per_qty, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($purchase->total_gst_value, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($purchase->total_price, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($purchase->total_price_without_gst, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $purchase->invoice_no ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $purchase->invoice_date ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $purchase->transportation_costs_incl ? number_format($purchase->transportation_costs_incl, 2) : '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($purchase->total_invoice_value, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $purchase->total_payment ? number_format($purchase->total_payment, 2) : '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($purchase->after_payment_total_value, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $purchase->created_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div> --}}
</div>

<!-- JavaScript for Dynamic Product Rows and GST Calculations -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    let productIndex = 1;

    // Close instructions button
    document.querySelector('.bg-blue-50 button').addEventListener('click', function() {
        this.closest('.bg-blue-50').style.display = 'none';
    });

    // Add new product row
    document.getElementById('add-product').addEventListener('click', function () {
        const container = document.getElementById('products-container');
        const newRow = document.createElement('div');
        newRow.classList.add('product-row', 'mb-6', 'p-4', 'border', 'border-gray-200', 'rounded-lg');
        newRow.innerHTML = `
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 sm:col-span-4">
                    <label for="product_id" class="block text-sm font-medium text-gray-900 mb-1">Select Product</label>
                    <select name="products[${productIndex}][product_id]" class="product-select mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md bg-white text-gray-900" required>
                        <option value="">Select a product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}"
                                    data-price-e="{{ $product->price_e }}"
                                    data-price-s="{{ $product->price_s }}"
                                    class="text-gray-900">
                                {{ $product->name }} ({{ $product->getStatusTextAttribute() }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-6 sm:col-span-2">
                    <label for="quantity" class="block text-sm font-medium text-gray-900 mb-1">Quantity</label>
                    <input type="number" name="products[${productIndex}][quantity]" class="quantity mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900" min="1" required>
                </div>
                <div class="col-span-6 sm:col-span-2">
                    <label for="purchase_price_excl_gst" class="block text-sm font-medium text-gray-900 mb-1">Price Excl. GST</label>
                    <input type="number" name="products[${productIndex}][purchase_price_excl_gst]" class="purchase_price_excl_gst mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900" step="0.01">
                </div>
                <div class="col-span-6 sm:col-span-2">
                    <label for="purchase_price_incl_gst" class="block text-sm font-medium text-gray-900 mb-1">Price Incl. GST</label>
                    <input type="number" name="products[${productIndex}][purchase_price_incl_gst]" class="purchase_price_incl_gst mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900" step="0.01">
                </div>
                <div class="col-span-6 sm:col-span-1">
                    <label for="gst_percent" class="block text-sm font-medium text-gray-900 mb-1">GST %</label>
                    <select name="products[${productIndex}][gst_percent]" class="gst_percent mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md bg-white text-gray-900" required>
                        <option value="">Select GST %</option>
                        <option value="5">5%</option>
                        <option value="10">10%</option>
                        <option value="12">12%</option>
                        <option value="18">18%</option>
                        <option value="28">28%</option>
                    </select>
                </div>
                <div class="col-span-6 sm:col-span-1 flex items-end">
                    <button type="button" class="remove-product w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Remove
                    </button>
                </div>
            </div>
        `;
        container.appendChild(newRow);
        productIndex++;
    });

    // Remove product row
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-product')) {
            const productRows = document.querySelectorAll('.product-row');
            if (productRows.length > 1) {
                e.target.closest('.product-row').remove();
            } else {
                alert('At least one product is required.');
            }
        }
    });

    // GST Calculations
    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('gst_percent') || 
            e.target.classList.contains('purchase_price_excl_gst') || 
            e.target.classList.contains('purchase_price_incl_gst')) {
            const row = e.target.closest('.product-row');
            const exclGst = row.querySelector('.purchase_price_excl_gst');
            const inclGst = row.querySelector('.purchase_price_incl_gst');
            const gstPercent = row.querySelector('.gst_percent');

            if (gstPercent.value && (exclGst.value || inclGst.value)) {
                const gstRate = parseFloat(gstPercent.value);
                if (exclGst.value && !inclGst.value) {
                    // Calculate incl. GST from excl. GST
                    const exclValue = parseFloat(exclGst.value);
                    const gstValue = exclValue * (gstRate / 100);
                    inclGst.value = (exclValue + gstValue).toFixed(2);
                } else if (inclGst.value && !exclGst.value) {
                    // Calculate excl. GST from incl. GST
                    const inclValue = parseFloat(inclGst.value);
                    const exclValue = inclValue / (1 + gstRate / 100);
                    exclGst.value = exclValue.toFixed(2);
                } else if (exclGst.value && inclGst.value) {
                    // If both are provided, prioritize excl. GST and recalculate incl. GST
                    const exclValue = parseFloat(exclGst.value);
                    const gstValue = exclValue * (gstRate / 100);
                    inclGst.value = (exclValue + gstValue).toFixed(2);
                }
            }
        }
    });

    // Trigger GST calculation on input for price fields
    document.addEventListener('input', function (e) {
        if (e.target.classList.contains('purchase_price_excl_gst') || 
            e.target.classList.contains('purchase_price_incl_gst')) {
            const row = e.target.closest('.product-row');
            const exclGst = row.querySelector('.purchase_price_excl_gst');
            const inclGst = row.querySelector('.purchase_price_incl_gst');
            const gstPercent = row.querySelector('.gst_percent');

            if (gstPercent.value && (exclGst.value || inclGst.value)) {
                const gstRate = parseFloat(gstPercent.value);
                if (e.target.classList.contains('purchase_price_excl_gst') && exclGst.value) {
                    // Calculate incl. GST from excl. GST
                    const exclValue = parseFloat(exclGst.value);
                    const gstValue = exclValue * (gstRate / 100);
                    inclGst.value = (exclValue + gstValue).toFixed(2);
                } else if (e.target.classList.contains('purchase_price_incl_gst') && inclGst.value) {
                    // Calculate excl. GST from incl. GST
                    const inclValue = parseFloat(inclGst.value);
                    const exclValue = inclValue / (1 + gstRate / 100);
                    exclGst.value = exclValue.toFixed(2);
                }
            }
        }
    });
});
</script>
@endsection
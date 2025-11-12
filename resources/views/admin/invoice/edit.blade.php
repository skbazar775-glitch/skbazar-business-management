
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Invoice #{{ $invoice->invoice_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@2.51.6/dist/full.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            color: #000000;
        }
        .form-section {
            background-color: #f8fafc;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .form-section-title {
            font-weight: 600;
            color: #000000;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e2e8f0;
        }
        .item-row {
            transition: all 0.2s ease;
        }
        .item-row:hover {
            background-color: #f1f5f9;
        }
        .total-card {
            background-color: #ffffff;
            border-radius: 0.5rem;
            padding: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #3b82f6;
        }
        select, .select, .select select, .select option {
            color: #000000 !important;
        }
        input, textarea {
            color: #000000 !important;
        }
        label, .label, .label-text {
            color: #000000 !important;
        }
        .alert, .alert-success, .alert-error {
            color: #000000 !important;
        }
    </style>
</head>
<body>
@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-4 max-w-6xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-black">Edit Invoice #{{ $invoice->invoice_number }}</h1>
        <a href="{{ route('admin.invoice.list') }}" class="btn btn-ghost text-black">Back to List</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success shadow-lg mb-6 text-black">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-error shadow-lg mb-6 text-black">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <h3 class="font-bold">Validation Errors!</h3>
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form id="invoiceForm" class="space-y-6" action="{{ route('admin.invoice.update', $invoice->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-section">
            <h2 class="form-section-title">Customer Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="block text-sm font-medium text-black mb-1">Manual Entry</label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="manual_entry" name="manual_entry" class="toggle toggle-primary" {{ !$invoice->user_id ? 'checked' : '' }}>
                        </label>
                    </div>
                    
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-black mb-1">Select Customer</label>
                        <select id="user_id" name="user_id" class="select select-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-black">
                            <option value="">Create New Customer</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}"
                                    data-name="{{ $user->name }}"
                                    data-address="{{ $user->address ? $user->address->full_address : 'No address available' }}"
                                    data-phone="{{ $user->address ? $user->address->phone : 'No phone available' }}"
                                    data-gst="{{ $user->address ? ($user->address->gst_no ?? 'NA') : 'NA' }}"
                                    {{ $invoice->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label for="customer_name" class="block text-sm font-medium text-black mb-1">Customer Name</label>
                        <input type="text" id="customer_name" name="customer_name" class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 text-black" value="{{ old('customer_name', $invoice->customer_name) }}" required>
                    </div>
                    <div>
                        <label for="customer_mobile" class="block text-sm font-medium text-black mb-1">Customer Mobile</label>
                        <input type="text" id="customer_mobile" name="customer_mobile" class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 text-black" value="{{ old('customer_mobile', $invoice->customer_mobile) }}" required>
                    </div>
                </div>
                
                <div>
                    <label for="customer_address" class="block text-sm font-medium text-black mb-1">Customer Address</label>
                    <textarea id="customer_address" name="customer_address" rows="3" class="textarea textarea-bordered w-full focus:ring-2 focus:ring-blue-500 text-black" required>{{ old('customer_address', $invoice->customer_address) }}</textarea>
                </div>
                
                <div>
                    <label for="customer_gst_no" class="block text-sm font-medium text-black mb-1">Customer GST No (Optional)</label>
                    <input type="text" id="customer_gst_no" name="customer_gst_no" class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 text-black" value="{{ old('customer_gst_no', $invoice->customer_gst_no) }}">
                </div>
            </div>

            <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-100 text-black">
                <h3 class="text-lg font-semibold text-black mb-2">Customer Preview</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-black">Name</p>
                        <p class="font-medium" id="display_name">{{ $invoice->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-black">Mobile</p>
                        <p class="font-medium" id="display_phone">{{ $invoice->customer_mobile }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-black">Address</p>
                        <p class="font-medium" id="display_address">{{ $invoice->customer_address }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-black">GST No</p>
                        <p class="font-medium" id="display_gst">{{ $invoice->customer_gst_no }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="flex justify-between items-center mb-4">
                <h2 class="form-section-title">Invoice Items</h2>
                <button type="button" id="addItem" class="btn btn-primary btn-sm text-black">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Item
                </button>
            </div>
            
            <div id="itemsContainer" class="space-y-3">
                @foreach($invoice->items as $index => $item)
                    <div class="item-row p-3 rounded-lg border border-gray-200 grid grid-cols-12 gap-3 items-center text-black">
                        <div class="col-span-12 md:col-span-4">
                            <label class="block text-xs font-medium text-black mb-1">Product</label>
                            <select name="items[{{ $index }}][product_id]" class="select select-bordered select-sm w-full product-select focus:ring-2 focus:ring-blue-500 text-black" required>
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ $item->serial_number == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-6 md:col-span-1">
                            <label class="block text-xs font-medium text-black mb-1">Quantity</label>
                            <input type="number" name="items[{{ $index }}][quantity]" class="input input-bordered input-sm w-full quantity focus:ring-2 focus:ring-blue-500 text-black" min="1" value="{{ $item->quantity }}" required>
                        </div>
                        <div class="col-span-6 md:col-span-2">
                            <label class="block text-xs font-medium text-black mb-1">HSN Code</label>
                            <select name="items[{{ $index }}][hsn_code]" class="select select-bordered select-sm w-full hsn-code focus:ring-2 focus:ring-blue-500 text-black" required>
                                <option value="">Select HSN Code</option>
                                @foreach($hsnCodes as $hsn)
                                    <option value="{{ $hsn->code }}" {{ $item->hsn_code == $hsn->code ? 'selected' : '' }}>{{ $hsn->code }} ({{ $hsn->gst_rate }}%)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-6 md:col-span-2">
                            <label class="block text-xs font-medium text-black mb-1">Rate (Inc. GST)</label>
                            <input type="number" name="items[{{ $index }}][rate_with_gst]" class="input input-bordered input-sm w-full rate-with-gst focus:ring-2 focus:ring-blue-500 text-black" step="0.01" value="{{ $item->rate_with_gst }}" required>
                        </div>
                        <div class="col-span-6 md:col-span-2">
                            <label class="block text-xs font-medium text-black mb-1">GST %</label>
                            <select name="items[{{ $index }}][gst_percent]" class="select select-bordered select-sm w-full gst-percent focus:ring-2 focus:ring-blue-500 text-black" required>
                                <option value="0" {{ $item->gst_percent == 0 ? 'selected' : '' }}>0%</option>
                                <option value="1" {{ $item->gst_percent == 1 ? 'selected' : '' }}>12%</option>
                                <option value="2" {{ $item->gst_percent == 2 ? 'selected' : '' }}>18%</option>
                                <option value="3" {{ $item->gst_percent == 3 ? 'selected' : '' }}>28%</option>
                            </select>
                        </div>
                        <div class="col-span-12 md:col-span-1 flex justify-end">
                            <button type="button" class="btn btn-error btn-sm btn-circle remove-item text-black">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="form-section">
            <h2 class="form-section-title">Invoice Summary</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="total-card">
                        <label class="block text-xs font-medium text-black mb-1">Total GST</label>
                        <input type="text" id="total_gst" class="input input-ghost w-full text-lg font-bold text-black" value="₹{{ number_format($invoice->total_gst, 2) }}" readonly>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="total-card">
                            <label class="block text-xs font-medium text-black mb-1">SGST</label>
                            <input type="text" id="sgst" class="input input-ghost w-full text-black" value="₹{{ number_format($invoice->sgst, 2) }}" readonly>
                        </div>
                        <div class="total-card">
                            <label class="block text-xs font-medium text-black mb-1">CGST</label>
                            <input type="text" id="cgst" class="input input-ghost w-full text-black" value="₹{{ number_format($invoice->cgst, 2) }}" readonly>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="total-card">
                        <label class="block text-xs font-medium text-black mb-1">Total Price</label>
                        <input type="text" id="total_price" class="input input-ghost w-full text-lg font-bold text-black" value="₹{{ number_format($invoice->total_price, 2) }}" readonly>
                    </div>
                    
                    <div class="total-card">
                        <label class="block text-xs font-medium text-black mb-1">Discount</label>
                        <input type="number" name="discount" id="discount" class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 text-black" step="0.01" value="{{ old('discount', $invoice->discount) }}">
                    </div>
                    
                    <div class="total-card">
                        <label class="block text-xs font-medium text-black mb-1">Total After Discount</label>
                        <input type="text" id="total_after_discount" class="input input-ghost w-full font-bold text-black" value="₹{{ number_format($invoice->total_price - $invoice->discount, 2) }}" readonly>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="total-card">
                        <label class="block text-xs font-medium text-black mb-1">Advance Payment</label>
                        <input type="number" name="advance_payment" id="advance_payment" class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 text-black" step="0.01" value="{{ old('advance_payment', $invoice->advance_payment) }}">
                    </div>
                    
                    <div class="total-card bg-blue-50 border-blue-200">
                        <label class="block text-xs font-medium text-black mb-1">Final Amount</label>
                        <input type="text" id="final_amount" class="input input-ghost w-full text-xl font-bold text-black" value="₹{{ number_format($invoice->final_amount, 2) }}" readonly>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="total-card">
                        <label class="block text-xs font-medium text-black mb-1">Total in Words</label>
                        <input type="text" id="total_in_words" class="input input-ghost w-full italic text-black" value="{{ $invoice->total_in_words }}" readonly>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-black mb-1">Payment Terms</label>
                            <select name="payment_terms" class="select select-bordered w-full focus:ring-2 focus:ring-blue-500 text-black" required>
                                <option value="0" {{ $invoice->payment_terms == 0 ? 'selected' : '' }}>Fully Advance</option>
                                <option value="1" {{ $invoice->payment_terms == 1 ? 'selected' : '' }}>Half Advance</option>
                                <option value="2" {{ $invoice->payment_terms == 2 ? 'selected' : '' }}>Due</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-black mb-1">Payment Mode</label>
                            <select name="payment_mode" class="select select-bordered w-full focus:ring-2 focus:ring-blue-500 text-black" required>
                                <option value="0" {{ $invoice->payment_mode == 0 ? 'selected' : '' }}>Mobile Banking</option>
                                <option value="1" {{ $invoice->payment_mode == 1 ? 'selected' : '' }}>Online Payment</option>
                                <option value="2" {{ $invoice->payment_mode == 2 ? 'selected' : '' }}>Cash Payment</option>
                                <option value="3" {{ $invoice->payment_mode == 3 ? 'selected' : '' }}>Cash on Delivery</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.invoice.list') }}" class="btn btn-ghost text-black">Cancel</a>
            <button type="submit" class="btn btn-primary text-black">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Update Invoice
            </button>
        </div>
    </form>
</div>

<script>
let itemCount = {{ count($invoice->items) }};

function calculateTotals() {
    let totalPrice = 0;
    let totalGst = 0;

    $('.item-row').each(function() {
        const quantity = parseFloat($(this).find('.quantity').val()) || 0;
        const rateWithGst = parseFloat($(this).find('.rate-with-gst').val()) || 0;
        const gstPercent = parseFloat($(this).find('.gst-percent').val());
        const gstRate = {0: 0, 1: 12, 2: 18, 3: 28}[gstPercent] || 0;

        const rateWithoutGst = rateWithGst / (1 + (gstRate / 100));
        const priceWithoutGst = rateWithoutGst * quantity;
        const gstValue = (rateWithGst * quantity) - priceWithoutGst;

        totalPrice += rateWithGst * quantity;
        totalGst += gstValue;
    });

    const discount = parseFloat($('#discount').val()) || 0;
    const advancePayment = parseFloat($('#advance_payment').val()) || 0;

    $('#total_gst').val('₹' + totalGst.toFixed(2));
    $('#sgst').val('₹' + (totalGst / 2).toFixed(2));
    $('#cgst').val('₹' + (totalGst / 2).toFixed(2));
    $('#total_price').val('₹' + totalPrice.toFixed(2));
    $('#total_after_discount').val('₹' + (totalPrice - discount).toFixed(2));
    $('#final_amount').val('₹' + (totalPrice - discount - advancePayment).toFixed(2));

    const numberToWords = (num) => {
        const single = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
        const double = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
        const tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
        const formatTenth = (digit, prev) => {
            return 0 == digit ? '' : ' ' + (1 == digit ? double[prev] : tens[digit]);
        };
        const formatOther = (digit, next, denom) => {
            return (0 != digit && 1 != next ? ' ' + single[digit] : '') + (0 != next || digit > 0 ? ' ' + denom : '');
        };
        
        let str = '';
        let rupees = Math.floor(num);
        let paise = Math.round((num - rupees) * 100);
        
        if (rupees > 0) {
            str += single[rupees] || rupees + ' Rupees';
        }
        if (paise > 0) {
            str += (str ? ' and ' : '') + (single[paise] || paise + ' Paise');
        }
        return str || 'Zero Rupees';
    };
    
    $('#total_in_words').val(numberToWords(totalPrice - discount - advancePayment));
}

$(document).ready(function() {
    if (typeof jQuery === 'undefined') {
        console.error('jQuery is not loaded');
    } else {
        console.log('jQuery loaded successfully');
    }

    $('#manual_entry').change(function() {
        const isManual = $(this).is(':checked');
        $('#user_id').prop('disabled', isManual);
        $('#customer_name, #customer_address, #customer_mobile, #customer_gst_no')
            .prop('readonly', !isManual)
            .val(isManual ? '' : $('#user_id').find('option:selected').data('name') || '');
        
        if (!isManual && $('#user_id').val()) {
            const selectedOption = $('#user_id').find('option:selected');
            $('#customer_name').val(selectedOption.data('name')).prop('readonly', true);
            $('#customer_address').val(selectedOption.data('address')).prop('readonly', true);
            $('#customer_mobile').val(selectedOption.data('phone')).prop('readonly', true);
            $('#customer_gst_no').val(selectedOption.data('gst')).prop('readonly', true);
            $('#display_name').text(selectedOption.data('name'));
            $('#display_address').text(selectedOption.data('address'));
            $('#display_phone').text(selectedOption.data('phone'));
            $('#display_gst').text(selectedOption.data('gst'));
        } else {
            $('#display_name, #display_address, #display_phone, #display_gst').text('-');
        }
    });

    $('#user_id').change(function() {
        if (!$('#manual_entry').is(':checked')) {
            const selectedOption = $(this).find('option:selected');
            if (selectedOption.val()) {
                $('#customer_name').val(selectedOption.data('name')).prop('readonly', true);
                $('#customer_address').val(selectedOption.data('address')).prop('readonly', true);
                $('#customer_mobile').val(selectedOption.data('phone')).prop('readonly', true);
                $('#customer_gst_no').val(selectedOption.data('gst')).prop('readonly', true);
                $('#display_name').text(selectedOption.data('name'));
                $('#display_address').text(selectedOption.data('address'));
                $('#display_phone').text(selectedOption.data('phone'));
                $('#display_gst').text(selectedOption.data('gst'));
            } else {
                $('#customer_name, #customer_address, #customer_mobile, #customer_gst_no')
                    .val('')
                    .prop('readonly', true);
                $('#display_name, #display_address, #display_phone, #display_gst').text('-');
            }
        }
    });

    $('#addItem').click(function() {
        console.log('Add Item button clicked');
        const firstRow = $('.item-row:first');
        if (!firstRow.length) {
            console.error('No item row found to clone');
            alert('Error: No item row available to clone.');
            return;
        }
        const newRow = firstRow.clone(true);
        newRow.find('input, select').each(function() {
            const name = $(this).attr('name').replace(/\[\d+\]/, `[${itemCount}]`);
            $(this).attr('name', name);
            if ($(this).is('input')) {
                $(this).val('');
            } else if ($(this).is('select')) {
                $(this).prop('selectedIndex', 0);
            }
        });
        itemCount++;
        $('#itemsContainer').append(newRow);
        console.log('New row added, itemCount:', itemCount);
        calculateTotals();
    });

    $(document).on('click', '.remove-item', function() {
        if ($('.item-row').length > 1) {
            $(this).closest('.item-row').remove();
            console.log('Item row removed, remaining:', $('.item-row').length);
            calculateTotals();
        } else {
            $(this).closest('.item-row').find('input').val('');
            $(this).closest('.item-row').find('select').prop('selectedIndex', 0);
            console.log('Last row cleared');
            calculateTotals();
        }
    });

    $(document).on('change keyup', '.quantity, .rate-with-gst, .gst-percent, #discount, #advance_payment', function() {
        console.log('Input changed, recalculating totals');
        calculateTotals();
    });

    console.log('Initializing form');
    calculateTotals();
    $('#user_id').trigger('change');
});
</script>
@endsection
</body>
</html>

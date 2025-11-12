@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-4 max-w-6xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-black">Generate New Invoice</h1>
        <div class="badge badge-lg badge-primary text-black">Draft</div>
    </div>

    <!-- Display Success or Error Messages -->
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

    <!-- Invoice Form -->
    <form id="invoiceForm" class="space-y-6" action="{{ route('admin.invoice.store.without.gst') }}" method="POST">
        @csrf
        
        <!-- Customer Information Section -->
        <div class="form-section">
            <h2 class="form-section-title">Customer Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="block text-sm font-medium text-black mb-1">Manual Entry</label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="manual_entry" name="manual_entry" class="toggle toggle-primary">
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
                                    data-gst="{{ $user->address ? ($user->address->gst_no ?? 'NA') : 'NA' }}">
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label for="customer_name" class="block text-sm font-medium text-black mb-1">Customer Name</label>
                        <input type="text" id="customer_name" name="customer_name" class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 text-black" required>
                    </div>
                    <div>
                        <label for="customer_mobile" class="block text-sm font-medium text-black mb-1">Customer Mobile</label>
                        <input type="text" id="customer_mobile" name="customer_mobile" class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 text-black" required>
                    </div>
                </div>
                
                <div>
                    <label for="customer_address" class="block text-sm font-medium text-black mb-1">Customer Address</label>
                    <textarea id="customer_address" name="customer_address" rows="3" class="textarea textarea-bordered w-full focus:ring-2 focus:ring-blue-500 text-black" required></textarea>
                </div>
                
                <div>
                    <label for="customer_gst_no" class="block text-sm font-medium text-black mb-1">Customer GST No (Optional)</label>
                    <input type="text" id="customer_gst_no" name="customer_gst_no" class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 text-black">
                </div>
            </div>

            <!-- Selected Customer Details Display -->
            <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-100 text-black">
                <h3 class="text-lg font-semibold text-black mb-2">Customer Preview</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-black">Name</p>
                        <p class="font-medium" id="display_name">-</p>
                    </div>
                    <div>
                        <p class="text-sm text-black">Mobile</p>
                        <p class="font-medium" id="display_phone">-</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-black">Address</p>
                        <p class="font-medium" id="display_address">-</p>
                    </div>
                    <div>
                        <p class="text-sm text-black">GST No</p>
                        <p class="font-medium" id="display_gst">-</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Items Section -->
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
                <div class="item-row p-3 rounded-lg border border-gray-200 grid grid-cols-12 gap-3 items-center text-black">
                    <div class="col-span-12 md:col-span-5">
                        <label class="block text-xs font-medium text-black mb-1">Product</label>
                        <select name="items[0][product_id]" class="select select-bordered select-sm w-full product-select focus:ring-2 focus:ring-blue-500 text-black" required>
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <!-- Added data-price_s for auto-fill -->
                                <option value="{{ $product->id }}" data-price_s="{{ $product->price_s ?? 0 }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-6 md:col-span-2">
                        <label class="block text-xs font-medium text-black mb-1">Quantity</label>
                        <input type="number" name="items[0][quantity]" class="input input-bordered input-sm w-full quantity focus:ring-2 focus:ring-blue-500 text-black" min="1" required>
                    </div>
                    <div class="col-span-6 md:col-span-3">
                        <label class="block text-xs font-medium text-black mb-1">Price</label>
                        <input type="number" name="items[0][price]" class="input input-bordered input-sm w-full price focus:ring-2 focus:ring-blue-500 text-black" step="0.01" required>
                    </div>
                    <div class="col-span-12 md:col-span-2 flex justify-end">
                        <button type="button" class="btn btn-error btn-sm btn-circle remove-item text-black">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Totals Section -->
        <div class="form-section">
            <h2 class="form-section-title">Invoice Summary</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="total-card">
                        <label class="block text-xs font-medium text-black mb-1">Subtotal</label>
                        <input type="text" id="subtotal" class="input input-ghost w-full text-lg font-bold text-black" readonly>
                    </div>
                    
                    <div class="total-card">
                        <label class="block text-xs font-medium text-black mb-1">Discount</label>
                        <input type="number" name="discount" id="discount" class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 text-black" step="0.01">
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="total-card">
                        <label class="block text-xs font-medium text-black mb-1">Total After Discount</label>
                        <input type="text" id="total_after_discount" class="input input-ghost w-full font-bold text-black" readonly>
                    </div>
                    
                    <div class="total-card">
                        <label class="block text-xs font-medium text-black mb-1">Advance Payment</label>
                        <input type="number" name="advance_payment" id="advance_payment" class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 text-black" step="0.01">
                    </div>
                    
                    <div class="total-card bg-blue-50 border-blue-200">
                        <label class="block text-xs font-medium text-black mb-1">Final Amount</label>
                        <input type="text" id="final_amount" class="input input-ghost w-full text-xl font-bold text-black" readonly>
                    </div>
                </div>
                
                <div class="space-y-4 md:col-span-2">
                    <div class="total-card">
                        <label class="block text-xs font-medium text-black mb-1">Total in Words</label>
                        <input type="text" id="total_in_words" class="input input-ghost w-full italic text-black" readonly>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-black mb-1">Payment Terms</label>
                            <select name="payment_terms" class="select select-bordered w-full focus:ring-2 focus:ring-blue-500 text-black" required>
                                <option value="0">Fully Advance</option>
                                <option value="1">Half Advance</option>
                                <option value="2">Due</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-black mb-1">Payment Mode</label>
                            <select name="payment_mode" class="select select-bordered w-full focus:ring-2 focus:ring-blue-500 text-black" required>
                                <option value="0">Mobile Banking</option>
                                <option value="1">Online Payment</option>
                                <option value="2">Cash Payment</option>
                                <option value="3">Cash on Delivery</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <button type="button" class="btn btn-ghost text-black">Cancel</button>
            <button type="submit" class="btn btn-primary text-black">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Generate Invoice
            </button>
        </div>
    </form>
</div>

<script>
let itemCount = 1; // Initialize item counter

function calculateTotals() { // Calculate totals function
    let subtotal = 0; // Initialize subtotal

    // Calculate subtotal
    $('.item-row').each(function() { // Loop through item rows
        const quantity = parseFloat($(this).find('.quantity').val()) || 0; // Get quantity
        const price = parseFloat($(this).find('.price').val()) || 0; // Get price

        subtotal += price * quantity; // Add to subtotal
    });

    // Apply discount
    const discount = parseFloat($('#discount').val()) || 0; // Get discount
    const afterDiscount = subtotal - discount; // Calculate after discount

    // Apply advance payment
    const advancePayment = parseFloat($('#advance_payment').val()) || 0; // Get advance
    const finalAmount = afterDiscount - advancePayment; // Calculate final

    // Update UI fields
    $('#subtotal').val('₹' + subtotal.toFixed(2)); // Set subtotal
    $('#total_after_discount').val('₹' + afterDiscount.toFixed(2)); // Set after discount
    $('#final_amount').val('₹' + finalAmount.toFixed(2)); // Set final amount

    // Number to words
    const numberToWords = (num) => { // Convert number to words
        const single = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
        const double = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
        const tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

        let str = ''; // Initialize string
        let rupees = Math.floor(num); // Get rupees
        let paise = Math.round((num - rupees) * 100); // Get paise

        if (rupees > 0) { // If rupees exist
            str += single[rupees] || rupees + ' Rupees'; // Add rupees
        }
        if (paise > 0) { // If paise exist
            str += (str ? ' and ' : '') + (single[paise] || paise + ' Paise'); // Add paise
        }
        return str || 'Zero Rupees'; // Return result
    };

    $('#total_in_words').val(numberToWords(finalAmount)); // Set total in words
}

$(document).ready(function() { // Document ready
    if (typeof jQuery === 'undefined') { // Check jQuery
        console.error('jQuery is not loaded'); // Log error
    } else {
        console.log('jQuery loaded successfully'); // Log success
    }

    // Manual Entry Checkbox
    $('#manual_entry').change(function() { // Manual entry toggle
        const isManual = $(this).is(':checked'); // Check state
        $('#user_id').prop('disabled', isManual); // Disable select
        $('#customer_name, #customer_address, #customer_mobile, #customer_gst_no')
            .prop('readonly', !isManual) // Toggle readonly
            .val(isManual ? '' : $('#user_id').find('option:selected').data('name') || ''); // Set or clear
        
        if (!isManual && $('#user_id').val()) { // If not manual and user selected
            const selectedOption = $('#user_id').find('option:selected'); // Get option
            $('#customer_name').val(selectedOption.data('name')).prop('readonly', true); // Set name
            $('#customer_address').val(selectedOption.data('address')).prop('readonly', true); // Set address
            $('#customer_mobile').val(selectedOption.data('phone')).prop('readonly', true); // Set phone
            $('#customer_gst_no').val(selectedOption.data('gst')).prop('readonly', true); // Set GST
            $('#display_name').text(selectedOption.data('name')); // Display name
            $('#display_address').text(selectedOption.data('address')); // Display address
            $('#display_phone').text(selectedOption.data('phone')); // Display phone
            $('#display_gst').text(selectedOption.data('gst')); // Display GST
        } else {
            $('#display_name, #display_address, #display_phone, #display_gst').text('-'); // Reset display
        }
    });

    // Customer selection
    $('#user_id').change(function() { // User select change
        if (!$('#manual_entry').is(':checked')) { // If not manual
            const selectedOption = $(this).find('option:selected'); // Get option
            if (selectedOption.val()) { // If value exists
                $('#customer_name').val(selectedOption.data('name')).prop('readonly', true); // Set name
                $('#customer_address').val(selectedOption.data('address')).prop('readonly', true); // Set address
                $('#customer_mobile').val(selectedOption.data('phone')).prop('readonly', true); // Set phone
                $('#customer_gst_no').val(selectedOption.data('gst')).prop('readonly', true); // Set GST
                $('#display_name').text(selectedOption.data('name')); // Display name
                $('#display_address').text(selectedOption.data('address')); // Display address
                $('#display_phone').text(selectedOption.data('phone')); // Display phone
                $('#display_gst').text(selectedOption.data('gst')); // Display GST
            } else {
                $('#customer_name, #customer_address, #customer_mobile, #customer_gst_no')
                    .val('') // Clear inputs
                    .prop('readonly', true); // Set readonly
                $('#display_name, #display_address, #display_phone, #display_gst').text('-'); // Reset display
            }
        }
    });

    // Add item row
    $('#addItem').click(function() { // Add item click
        console.log('Add Item button clicked'); // Log click
        const firstRow = $('.item-row:first'); // Get first row
        if (!firstRow.length) { // Check if row exists
            console.error('No item row found to clone'); // Log error
            alert('Error: No item row available to clone.'); // Alert user
            return; // Exit
        }
        const newRow = firstRow.clone(true); // Clone with events
        newRow.find('input, select').each(function() { // Loop inputs/selects
            const name = $(this).attr('name').replace('[0]', `[${itemCount}]`); // Update name
            $(this).attr('name', name); // Set name
            if ($(this).is('input')) { // If input
                $(this).val(''); // Clear value
            } else if ($(this).is('select')) { // If select
                $(this).prop('selectedIndex', 0); // Reset to first option
            }
        });
        itemCount++; // Increment counter
        $('#itemsContainer').append(newRow); // Append new row
        console.log('New row added, itemCount:', itemCount); // Log addition
        calculateTotals(); // Recalculate totals
    });

    // Remove item
    $(document).on('click', '.remove-item', function() { // Remove item click
        if ($('.item-row').length > 1) { // If more than one row
            $(this).closest('.item-row').remove(); // Remove row
            console.log('Item row removed, remaining:', $('.item-row').length); // Log remaining
            calculateTotals(); // Recalculate totals
        } else {
            $(this).closest('.item-row').find('input').val(''); // Clear inputs
            $(this).closest('.item-row').find('select').prop('selectedIndex', 0); // Reset selects
            console.log('Last row cleared'); // Log clear
            calculateTotals(); // Recalculate totals
        }
    });

    // Calculate totals on input change
    $(document).on('change keyup', '.quantity, .price, #discount, #advance_payment', function() { // Input change
        console.log('Input changed, recalculating totals'); // Log change
        calculateTotals(); // Recalculate totals
    });

    // Product selection auto-fills price
    $(document).on('change', '.product-select', function() { // Product select change
        console.log('Product selected'); // Log selection
        const selectedOption = $(this).find('option:selected'); // Get selected option
        const priceS = parseFloat(selectedOption.data('price_s')) || 0; // Parse price_s
        const priceInput = $(this).closest('.item-row').find('.price'); // Find price input
        priceInput.val(priceS.toFixed(2)); // Set price with 2 decimals
        priceInput.focus(); // Focus for editing
        console.log('Price set to:', priceS.toFixed(2)); // Log price
        calculateTotals(); // Recalculate totals
    });

    // Initialize form
    console.log('Initializing form'); // Log init
    calculateTotals(); // Initial totals
    $('#user_id').trigger('change'); // Trigger customer change
});
</script>
    <style>
        body {
            font-family: 'Inter', sans-serif; /* Font */
            color: #000000; /* Text color */
        }
        .form-section {
            background-color: #f8fafc; /* Background */
            border-radius: 0.5rem; /* Rounded corners */
            padding: 1.5rem; /* Padding */
            margin-bottom: 1.5rem; /* Margin */
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); /* Shadow */
        }
        .form-section-title {
            font-weight: 600; /* Bold title */
            color: #000000; /* Title color */
            margin-bottom: 1rem; /* Margin */
            padding-bottom: 0.5rem; /* Padding */
            border-bottom: 1px solid #e2e8f0; /* Border */
        }
        .item-row {
            transition: all 0.2s ease; /* Smooth transition */
        }
        .item-row:hover {
            background-color: #f1f5f9; /* Hover background */
        }
        .total-card {
            background-color: #ffffff; /* Card background */
            border-radius: 0.5rem; /* Rounded corners */
            padding: 1rem; /* Padding */
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); /* Shadow */
            border-left: 4px solid #3b82f6; /* Blue border */
        }
        select, .select, .select select, .select option {
            color: #000000 !important; /* Force black text */
        }
        input, textarea {
            color: #000000 !important; /* Force black text */
        }
        label, .label, .label-text {
            color: #000000 !important; /* Force black text */
        }
        .alert, .alert-success, .alert-error {
            color: #000000 !important; /* Force black text */
        }
    </style>
@endsection
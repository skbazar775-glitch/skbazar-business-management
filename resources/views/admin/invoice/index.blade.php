@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-4 max-w-6xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-black">Generate New Invoice, Yo!</h1>
        <div class="badge badge-lg badge-primary text-black">Draft Mode</div>
    </div>

    <!-- Success/Error Messages -->
    @if (session('success'))
        <div class="alert alert-success shadow-lg mb-6 text-black">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-error shadow-lg mb-6 text-black">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <h3 class="font-bold">Validation Errors, Bro!</h3>
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
    <form id="invoiceForm" class="space-y-6" action="{{ route('admin.invoice.store') }}" method="POST">
        @csrf
        
        <!-- Customer Info Section -->
        <div class="form-section">
            <h2 class="form-section-title">Customer Info, Let's Go!</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="block text-sm font-medium text-black mb-1">Manual Entry, Y/N?</label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="manual_entry" name="manual_entry" class="toggle toggle-primary">
                        </label>
                    </div>
                    
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-black mb-1">Pick a Customer</label>
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

            <!-- Customer Preview -->
            <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-100 text-black">
                <h3 class="text-lg font-semibold text-black mb-2">Customer Preview, Check It!</h3>
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
                <h2 class="form-section-title">Invoice Items, Add 'Em Up!</h2>
                <button type="button" id="addItem" class="btn btn-primary btn-sm text-black">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Item
                </button>
            </div>
            
            <div id="itemsContainer" class="space-y-3">
                <div class="item-row p-3 rounded-lg border border-gray-200 grid grid-cols-12 gap-3 items-center text-black">
                    <div class="col-span-12 md:col-span-4">
                        <label class="block text-xs font-medium text-black mb-1">Product</label>
                        <select name="items[0][product_id]" class="select select-bordered select-sm w-full product-select focus:ring-2 focus:ring-blue-500 text-black" required>
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-price_s="{{ $product->price_s ?? 0 }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-6 md:col-span-1">
                        <label class="block text-xs font-medium text-black mb-1">Quantity</label>
                        <input type="number" name="items[0][quantity]" class="input input-bordered input-sm w-full quantity focus:ring-2 focus:ring-blue-500 text-black" min="1" required>
                    </div>
                    <div class="col-span-6 md:col-span-2">
                        <label class="block text-xs font-medium text-black mb-1">HSN Code</label>
                        <select name="items[0][hsn_code]" class="select select-bordered select-sm w-full hsn-code focus:ring-2 focus:ring-blue-500 text-black" required>
                            <option value="">Select HSN Code</option>
                            @foreach($hsnCodes as $hsn)
                                <option value="{{ $hsn->code }}">{{ $hsn->code }} ({{ $hsn->gst_rate }}%)</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-6 md:col-span-2">
                        <label class="block text-xs font-medium text-black mb-1">Rate (Inc. GST)</label>
                        <input type="number" name="items[0][rate_with_gst]" class="input input-bordered input-sm w-full rate-with-gst focus:ring-2 focus:ring-blue-500 text-black" step="0.01" readonly>
                    </div>
                    <div class="col-span-6 md:col-span-2">
                        <label class="block text-xs font-medium text-black mb-1">GST %</label>
                        <select name="items[0][gst_percent]" class="select select-bordered select-sm w-full gst-percent focus:ring-2 focus:ring-blue-500 text-black" required>
                            <option value="0">0%</option>
                            <option value="1">12%</option>
                            <option value="2">18%</option>
                            <option value="3">28%</option>
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
            </div>
        </div>

        <!-- Totals Section -->
        <div class="form-section">
            <h2 class="form-section-title">Invoice Summary, Let's Wrap It!</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="total-card">
                        <label class="block text-xs font-medium text-black mb-1">Total GST</label>
                        <input type="text" id="total_gst" class="input input-ghost w-full text-lg font-bold text-black" readonly>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="total-card">
                            <label class="block text-xs font-medium text-black mb-1">SGST</label>
                            <input type="text" id="sgst" class="input input-ghost w-full text-black" readonly>
                        </div>
                        <div class="total-card">
                            <label class="block text-xs font-medium text-black mb-1">CGST</label>
                            <input type="text" id="cgst" class="input input-ghost w-full text-black" readonly>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="total-card">
                        <label class="block text-xs font-medium text-black mb-1">Total Price</label>
                        <input type="text" id="total_price" class="input input-ghost w-full text-lg font-bold text-black" readonly>
                    </div>
                    
                    <div class="total-card">
                        <label class="block text-xs font-medium text-black mb-1">Discount</label>
                        <input type="number" name="discount" id="discount" class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 text-black" step="0.01">
                    </div>
                    
                    <div class="total-card">
                        <label class="block text-xs font-medium text-black mb-1">Total After Discount</label>
                        <input type="text" id="total_after_discount" class="input input-ghost w-full font-bold text-black" readonly>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="total-card">
                        <label class="block text-xs font-medium text-black mb-1">Advance Payment</label>
                        <input type="number" name="advance_payment" id="advance_payment" class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 text-black" step="0.01">
                    </div>
                    
                    <div class="total-card bg-blue-50 border-blue-200">
                        <label class="block text-xs font-medium text-black mb-1">Final Amount</label>
                        <input type="text" id="final_amount" class="input input-ghost w-full text-xl font-bold text-black" readonly>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="total-card">
                        <label class="block text-xs font-medium text-black mb-1">Total in Words</label>
                        <input type="text" id="total_in_words" class="input input-ghost w-full italic text-black" readonly>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
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
            <!--<button type="button" class="btn btn-ghost text-black">Cancel, Nah!</button>-->
            <!--<button type="button" id="previewInvoice" class="btn btn-secondary text-black">-->
            <!--    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">-->
            <!--        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M19.5 12c0-1.5-.5-2.9-1.4-4.1-.9-1.2-2.2-2.1-3.6-2.6-1.4-.5-2.9-.6-4.4-.3-1.5.3-2.9 1.1-4 2.3-1.1 1.2-1.8 2.7-2.1 4.3-.3 1.6-.1 3.2.5 4.7.6 1.5 1.7 2.8 3 3.7 1.3.9 2.8 1.4 4.4 1.4 1.6 0 3.1-.5 4.4-1.4 1.3-.9 2.4-2.2 3-3.7.6-1.5.8-3.1.5-4.7z" />-->
            <!--    </svg>-->
            <!--    Preview Invoice-->
            <!--</button>-->
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
// Item counter init
let itemCount = 1;

// Calculate totals function
function calculateTotals() { // Totals calc
    let subTotal = 0; // Subtotal init

    // Step 1: Calculate subtotal (without GST breakup)
    $('.item-row').each(function() { // Loop each item row
        const quantity = parseFloat($(this).find('.quantity').val()) || 0; // Get quantity
        const rateWithGst = parseFloat($(this).find('.rate-with-gst').val()) || 0; // Get rate

        subTotal += rateWithGst * quantity; // Add to subtotal
    });

    // Step 2: Apply discount before GST
    const discount = parseFloat($('#discount').val()) || 0; // Get discount
    const afterDiscount = subTotal - discount; // Calc after discount

    // Step 3: Calculate GST only on discounted amount
    let totalGst = 0; // GST init
    let finalPrice = 0; // Final price init

    $('.item-row').each(function() { // Loop each row again
        const quantity = parseFloat($(this).find('.quantity').val()) || 0; // Quantity
        const rateWithGst = parseFloat($(this).find('.rate-with-gst').val()) || 0; // Rate
        const gstPercent = parseFloat($(this).find('.gst-percent').val()); // GST percent value
        const gstRate = {0: 0, 1: 12, 2: 18, 3: 28}[gstPercent] || 0; // Map to actual %

        // Proportionate value after discount
        const proportion = (rateWithGst * quantity) / (subTotal || 1); // Proportion calc
        const discountedItemTotal = afterDiscount * proportion; // Discounted total for item

        // Separate GST from discounted item price
        const rateWithoutGst = discountedItemTotal / (1 + (gstRate / 100)); // Without GST
        const gstValue = discountedItemTotal - rateWithoutGst; // GST value

        totalGst += gstValue; // Add to total GST
        finalPrice += discountedItemTotal; // Add to final
    });

    const advancePayment = parseFloat($('#advance_payment').val()) || 0; // Advance

    // Step 4: Update UI fields
    $('#total_gst').val('₹' + totalGst.toFixed(2)); // Set total GST
    $('#sgst').val('₹' + (totalGst / 2).toFixed(2)); // SGST half
    $('#cgst').val('₹' + (totalGst / 2).toFixed(2)); // CGST half
    $('#total_price').val('₹' + subTotal.toFixed(2)); // Total price
    $('#total_after_discount').val('₹' + afterDiscount.toFixed(2)); // After discount
    $('#final_amount').val('₹' + (finalPrice - advancePayment).toFixed(2)); // Final

    // Number to words (simple, for small nums)
    const numberToWords = (num) => { // Words function
        const single = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine']; // Singles
        const double = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen']; // Doubles
        const tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety']; // Tens

        let str = ''; // String init
        let rupees = Math.floor(num); // Rupees
        let paise = Math.round((num - rupees) * 100); // Paise

        if (rupees > 0) { // If rupees
            str += single[rupees] || rupees + ' Rupees'; // Add
        }
        if (paise > 0) { // If paise
            str += (str ? ' and ' : '') + (single[paise] || paise + ' Paise'); // Add
        }
        return str || 'Zero Rupees'; // Return
    };

    $('#total_in_words').val(numberToWords(finalPrice - advancePayment)); // Set words
}

$(document).ready(function() { // Doc ready
    if (typeof jQuery === 'undefined') { // Check jQuery
        console.error('jQuery nahi hai, bro!'); // Error log
    } else {
        console.log('jQuery loaded, sab set hai!'); // Success log
    }

    // Manual Entry Checkbox
    $('#manual_entry').change(function() { // Manual toggle change
        const isManual = $(this).is(':checked'); // Check state
        $('#user_id').prop('disabled', isManual); // Disable select if manual
        $('#customer_name, #customer_address, #customer_mobile, #customer_gst_no')
            .prop('readonly', !isManual) // Readonly toggle
            .val(isManual ? '' : $('#user_id').find('option:selected').data('name') || ''); // Clear or set
        
        if (!isManual && $('#user_id').val()) { // If not manual and selected
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
            if (selectedOption.val()) { // If value
                $('#customer_name').val(selectedOption.data('name')).prop('readonly', true); // Set name
                $('#customer_address').val(selectedOption.data('address')).prop('readonly', true); // Set address
                $('#customer_mobile').val(selectedOption.data('phone')).prop('readonly', true); // Set phone
                $('#customer_gst_no').val(selectedOption.data('gst')).prop('readonly', true); // Set GST
                $('#display_name').text(selectedOption.data('name')); // Display
                $('#display_address').text(selectedOption.data('address')); // Display
                $('#display_phone').text(selectedOption.data('phone')); // Display
                $('#display_gst').text(selectedOption.data('gst')); // Display
            } else {
                $('#customer_name, #customer_address, #customer_mobile, #customer_gst_no')
                    .val('') // Clear
                    .prop('readonly', true); // Readonly
                $('#display_name, #display_address, #display_phone, #display_gst').text('-'); // Reset
            }
        }
    });

    // Add item row
    $('#addItem').click(function() { // Add button click
        console.log('Add Item button dabaya, bro!'); // Log click
        const firstRow = $('.item-row:first'); // Get first row
        if (!firstRow.length) { // If no row
            console.error('Koi item row nahi mila!'); // Error
            alert('Error: No item row to clone, bhai!'); // Alert
            return; // Exit
        }
        const newRow = firstRow.clone(true); // Clone with events
        newRow.find('input, select').each(function() { // Loop inputs/selects
            const name = $(this).attr('name').replace('[0]', `[${itemCount}]`); // Update name
            $(this).attr('name', name); // Set name
            if ($(this).is('input')) { // If input
                $(this).val(''); // Clear
            } else if ($(this).is('select')) { // If select
                $(this).prop('selectedIndex', 0); // Reset
            }
        });
        newRow.find('.rate-with-gst').prop('readonly', true); // Make rate readonly
        itemCount++; // Increment count
        $('#itemsContainer').append(newRow); // Append row
        console.log('Naya row add hua, itemCount:', itemCount); // Log
        calculateTotals(); // Recalc
    });

    // Remove item
    $(document).on('click', '.remove-item', function() { // Remove click
        if ($('.item-row').length > 1) { // If more than 1
            $(this).closest('.item-row').remove(); // Remove
            console.log('Item row hata diya, bacha:', $('.item-row').length); // Log
            calculateTotals(); // Recalc
        } else {
            $(this).closest('.item-row').find('input').val(''); // Clear inputs
            $(this).closest('.item-row').find('select').prop('selectedIndex', 0); // Reset selects
            console.log('Last row clear kiya!'); // Log
            calculateTotals(); // Recalc
        }
    });

    // Update rate with GST on product or GST change
    $(document).on('change', '.product-select, .gst-percent', function() { // Product or GST change
        const row = $(this).closest('.item-row'); // Get current row
        const productSelect = row.find('.product-select'); // Product select
        const gstSelect = row.find('.gst-percent'); // GST select
        const rateInput = row.find('.rate-with-gst'); // Rate input

        const basePrice = parseFloat(productSelect.find('option:selected').data('price_s')) || 0; // Base price
        const gstPercent = parseFloat(gstSelect.val()); // GST value
        const gstRate = {0: 0, 1: 12, 2: 18, 3: 28}[gstPercent] || 0; // Map GST %

        const rateWithGst = basePrice * (1 + gstRate / 100); // Calculate rate with GST
        rateInput.val(rateWithGst.toFixed(2)).prop('readonly', true); // Set readonly and value
        rateInput.css('transition', 'all 0.3s ease'); // Smooth transition
        calculateTotals(); // Recalculate totals
    });

    // Calculate totals on input change
    $(document).on('change keyup', '.quantity, .gst-percent, #discount, #advance_payment', function() { // Input changes
        console.log('Input change hua, totals recalculate kar rahe!'); // Log
        calculateTotals(); // Recalc
    });

    // Preview button click handler
    $(document).on('click', '#previewInvoice', function() { // Preview button click
        console.log('Preview button dabaya, bhai!'); // Log click
        
        // Collect form data
        const customerName = $('#customer_name').val() || 'N/A'; // Customer name
        const customerAddress = $('#customer_address').val() || 'N/A'; // Address
        const customerMobile = $('#customer_mobile').val() || 'N/A'; // Mobile
        const customerGst = $('#customer_gst_no').val() || 'N/A'; // GST
        const discount = parseFloat($('#discount').val()) || 0; // Discount
        const advancePayment = parseFloat($('#advance_payment').val()) || 0; // Advance
        const finalAmount = parseFloat($('#final_amount').val().replace('₹', '')) || 0; // Final amount
        const paymentTerms = $('select[name="payment_terms"] option:selected').text(); // Payment terms
        const paymentMode = $('select[name="payment_mode"] option:selected').text(); // Payment mode
        const invoiceDate = new Date().toLocaleDateString('en-GB').split('/').join('-'); // Today’s date

        // Collect items
        let itemsHtml = ''; // Items HTML init
        let itemIndex = 1; // Serial number
        $('.item-row').each(function() { // Loop each item
            const productName = $(this).find('.product-select option:selected').text() || 'N/A'; // Product
            const quantity = parseFloat($(this).find('.quantity').val()) || 0; // Quantity
            const hsnCode = $(this).find('.hsn-code option:selected').val() || 'N/A'; // HSN
            const gstPercent = parseFloat($(this).find('.gst-percent').val()); // GST %
            const gstRate = {0: 0, 1: 12, 2: 18, 3: 28}[gstPercent] || 0; // Map GST
            const rateWithGst = parseFloat($(this).find('.rate-with-gst').val()) || 0; // Rate with GST
            const rateWithoutGst = rateWithGst / (1 + gstRate / 100); // Rate without GST
            const gstValue = rateWithGst - rateWithoutGst; // GST value
            const totalPrice = rateWithGst * quantity; // Total price

            itemsHtml += `
                <tr>
                    <td>${itemIndex++}</td>
                    <td class="text-left">${productName}</td>
                    <td>${quantity.toFixed(2)}</td>
                    <td>${hsnCode}</td>
                    <td>${(gstRate / 2).toFixed(2)}</td>
                    <td>${(gstRate / 2).toFixed(2)}</td>
                    <td>₹${gstValue.toFixed(2)}</td>
                    <td>₹${rateWithoutGst.toFixed(2)}</td>
                    <td>₹${rateWithGst.toFixed(2)}</td>
                    <td>₹${totalPrice.toFixed(2)}</td>
                </tr>`;
        });

        // Modal HTML
        const modalHtml = `
            <div id="previewModal" class="modal modal-open">
                <div class="modal-box max-w-4xl">
                    <h2 class="text-2xl font-bold mb-4">Invoice Preview, Yo!</h2>
                    <div class="preview-content p-4 bg-white border border-gray-200 rounded-lg">
                        <table class="header-table" style="width: 100%; margin-bottom: 10px;">
                            <tr>
                                <td style="width: 50%;">
                                    <div class="text-logo">
                                        <div class="text-sk">SK</div>
                                        <div class="text-bazar">BAZAR</div>
                                    </div>
                                    <p>
                                        <strong class="bold-max">Skbazar</strong><br>
                                        <span class="bold-normal">SkBazar India Ka Apna Solar Bazar</span><br>
                                        <span class="bold-normal">Address:</span> Barua,Power House, Beldanga,Murshidabad, WB,Pin-742189<br>
                                        <span class="bold-normal">Phone:</span> 8597804890<br>
                                        <span class="bold-normal">GSTIN:</span> 19GZTPS2361Q1Z1
                                    </p>
                                </td>
                                <td style="width: 50%;">
                                    <div class="heading bold-max">Tax Invoice</div>
                                    <p>
                                        <span class="bold-strong">Invoice No:</span> INV-2025-001<br>
                                        <span class="bold-strong">Date:</span> ${invoiceDate}<br>
                                        <span class="bold-strong">Payment Term:</span> ${paymentTerms}<br>
                                        <span class="bold-strong">Payment Mode:</span> ${paymentMode}
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <table class="no-border" style="margin-bottom: 10px;">
                            <tr>
                                <td style="width: 50%;">
                                    <strong class="bold-strong">Buyer:</strong><br>
                                    <span class="bold-normal">Name:</span> ${customerName}<br>
                                    <span class="bold-normal">Address:</span> ${customerAddress}<br>
                                    <span class="bold-normal">GSTIN:</span> ${customerGst}<br>
                                    <span class="bold-normal">Phone:</span> ${customerMobile}
                                </td>
                                <td style="width: 50%;">
                                    <strong class="bold-strong">Company Payment Details:</strong><br>
                                    <span class="bold-normal">Bank:</span> BHANDHAN BANK<br>
                                    <span class="bold-normal">Name:</span> SK BAZAR<br>
                                    <span class="bold-normal">A/C:</span> 20100031361107<br>
                                    <span class="bold-normal">IFSC:</span> BDBL0001107<br>
                                    <span class="bold-normal">Branch:</span> BELDANGA
                                </td>
                            </tr>
                        </table>
                        <table class="bordered">
                            <thead>
                                <tr>
                                    <th class="bold-strong">S/N</th>
                                    <th class="bold-strong">Product Name</th>
                                    <th class="bold-strong">Quantity</th>
                                    <th class="bold-strong">HSN</th>
                                    <th class="bold-strong">CGST %</th>
                                    <th class="bold-strong">SGST %</th>
                                    <th class="bold-strong">GST Value</th>
                                    <th class="bold-strong">Rate</th>
                                    <th class="bold-strong">Price</th>
                                    <th class="bold-strong">Total Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${itemsHtml || '<tr><td colspan="10">No items added, bhai!</td></tr>'}
                                <tr>
                                    <th colspan="9" style="text-align: right;" class="bold-strong">Final Amount:</th>
                                    <th class="bold-strong">₹${finalAmount.toFixed(2)}</th>
                                </tr>
                            </tbody>
                        </table>
                        <table class="no-border" style="margin-top: 10px;">
                            <tr>
                                <td style="width: 50%;">
                                    <table>
                                        <tr>
                                            <td class="bold-normal">Discount:</td>
                                            <td>₹${discount.toFixed(2)}</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-normal">Advance Received:</td>
                                            <td>₹${advancePayment.toFixed(2)}</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-strong">Total:</td>
                                            <td class="bold-strong">₹${finalAmount.toFixed(2)}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <div class="note-box">
                            <strong class="bold-strong">Notes</strong>
                            <ol>
                                <li class="bold-normal">All Products Under Manufacturer Warranty</li>
                                <li class="bold-normal">Any Problem Inform Us Time Take Upto 30 Days</li>
                                <li class="bold-normal">Warranty Void on Physical Damage, Breakage</li>
                                <li class="bold-normal">Warranty Seal & Serial No. with Invoice Must Present</li>
                                <li class="bold-normal">Goods Sold Will Not Be Taken Back</li>
                            </ol>
                        </div>
                        <div class="signature">
                            <strong class="bold-normal">For SkBazar Solar System</strong><br><br><br>
                            ___________________________<br>
                            <strong class="bold-strong">Authorized Signature</strong>
                        </div>
                    </div>
                    <div class="modal-action">
                        <button class="btn btn-primary" onclick="$('#previewModal').removeClass('modal-open')">Close, Bhai!</button>
                    </div>
                </div>
            </div>`;
        
        // Inject modal into body
        $('body').append(modalHtml); // Add modal
    });

    $('#invoiceForm').submit(function(e) { // Form submit
        console.log('Form submit ho gaya, bhai!'); // Log
    });

    console.log('Form init ho raha hai, sab set!'); // Init log
    calculateTotals(); // Initial calc
    $('#user_id').trigger('change'); // Trigger customer change
});
</script>

<style>
    body {
        font-family: 'Inter', sans-serif; /* Font set kiya */
        color: #000000; /* Text color black */
    }
    .form-section {
        background-color: #f8fafc; /* Light bg for section */
        border-radius: 0.5rem; /* Rounded corners */
        padding: 1.5rem; /* Padding add kiya */
        margin-bottom: 1.5rem; /* Margin for spacing */
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    }
    .form-section-title {
        font-weight: 600; /* Bold title */
        color: #000000; /* Black text */
        margin-bottom: 1rem; /* Margin below */
        padding-bottom: 0.5rem; /* Padding below */
        border-bottom: 1px solid #e2e8f0; /* Border bottom */
    }
    .item-row {
        transition: all 0.2s ease; /* Smooth transition for rows */
    }
    .item-row:hover {
        background-color: #f1f5f9; /* Hover bg light gray */
    }
    .total-card {
        background-color: #ffffff; /* White bg for cards */
        border-radius: 0.5rem; /* Rounded corners */
        padding: 1rem; /* Padding inside */
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        border-left: 4px solid #3b82f6; /* Blue left border */
    }
    select, .select, .select select, .select option {
        color: #000000 !important; /* Black text for selects */
    }
    input, textarea {
        color: #000000 !important; /* Black text for inputs */
    }
    label, .label, .label-text {
        color: #000000 !important; /* Black text for labels */
    }
    .alert, .alert-success, .alert-error {
        color: #000000 !important; /* Black text for alerts */
    }
    /* Modal and preview styles */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Fixed position */
        top: 0; left: 0; width: 100%; height: 100%; /* Full screen */
        background: rgba(0,0,0,0.5); /* Dim background */
        align-items: center; justify-content: center; /* Center content */
    }
    .modal-open {
        display: flex; /* Show when open */
    }
    .modal-box {
        background: white; /* White bg */
        border-radius: 0.5rem; /* Rounded corners */
        padding: 1.5rem; /* Padding */
        max-height: 90vh; /* Max height */
        overflow-y: auto; /* Scroll if needed */
    }
    .preview-content table {
        border-collapse: collapse; /* Collapse borders */
        width: 100%; /* Full width */
    }
    .preview-content .bordered th, .preview-content .bordered td {
        border: 1px solid black; /* Black borders */
        padding: 8px; /* Padding */
        text-align: center; /* Center text */
    }
    .preview-content .no-border td {
        border: none; /* No borders */
        padding: 5px; /* Padding */
    }
    .preview-content .text-left {
        text-align: left; /* Left align */
    }
    .preview-content .header-table td {
        vertical-align: top; /* Top align */
        padding: 5px; /* Padding */
    }
    .preview-content .note-box {
        border: 1px solid black; /* Border */
        padding: 10px; /* Padding */
        margin-top: 15px; /* Margin */
    }
    .preview-content .signature {
        float: right; /* Right align */
        margin-top: 40px; /* Margin */
        text-align: right; /* Right text */
    }
    .preview-content .heading {
        font-size: 18px; /* Font size */
        font-weight: bold; /* Bold */
        text-align: right; /* Right align */
    }
    .preview-content .bold-normal {
        font-weight: 600; /* Normal bold */
    }
    .preview-content .bold-strong {
        font-weight: 700; /* Strong bold */
    }
    .preview-content .bold-max {
        font-weight: 900; /* Max bold */
    }
    .preview-content .text-logo {
        line-height: 1; /* Line height */
        margin-bottom: 10px; /* Margin */
    }
    .preview-content .text-sk {
        font-size: 32px; /* SK size */
        font-weight: 900; /* Max bold */
        color: #e91e63; /* Pinkish red */
        letter-spacing: 4px; /* Spacing */
    }
    .preview-content .text-bazar {
        font-size: 28px; /* Bazar size */
        font-weight: 700; /* Strong bold */
        color: #3f51b5; /* Indigo */
        letter-spacing: 6px; /* Spacing */
        margin-top: 4px; /* Margin */
    }
</style>
@endsection
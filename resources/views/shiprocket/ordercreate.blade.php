<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Order - Shiprocket</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Custom styles for enhanced UI */
        input, select, textarea {
            min-height: 44px;
            padding: 10px 14px !important;
            font-size: 16px !important;
            transition: all 0.2s ease-in-out;
        }
        input:focus, select:focus, textarea:focus {
            transform: scale(1.01);
            box-shadow: 0 0 8px rgba(79, 70, 229, 0.2);
        }
        .form-section {
            background-color: #f9fafb;
            border-radius: 0.75rem;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        .form-section h2 {
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 1rem;
            margin-bottom: 1.75rem;
            color: #1e293b;
            font-size: 1.5rem;
            font-weight: 600;
        }
        .order-item {
            background-color: white;
            padding: 1.5rem;
            border-radius: 0.75rem;
            margin-bottom: 1.25rem;
            border: 1px solid #e5e7eb;
            transition: transform 0.2s ease-in-out;
        }
        .order-item:hover {
            transform: translateY(-2px);
        }
        button {
            transition: all 0.2s ease-in-out;
        }
        button:hover {
            transform: translateY(-1px);
        }
        .remove-item:hover {
            transform: scale(1.05);
        }
        .error {
            border-color: #ef4444 !important;
        }
        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
    </style>
</head>

@extends('layouts.shiprocket')

@section('content')
<body class="bg-gray-100 font-sans">
            <!-- Display Success Message and Response -->
        {{-- @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
                <div class="mt-2">
                    <h3 class="text-lg font-semibold">API Response</h3>
                    <pre class="bg-gray-900 text-white p-4 rounded-md overflow-x-auto">
                        {{ json_encode(session('response'), JSON_PRETTY_PRINT) }}
                    </pre>
                </div>
            </div>
        @endif

        <!-- Display Error Message and API Response -->
        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p>{{ session('error') }}</p>
                <div class="mt-2">
                    <h3 class="text-lg font-semibold">API Response</h3>
                    <pre class="bg-gray-900 text-white p-4 rounded-md overflow-x-auto">
                        {{ json_encode(session('api_response'), JSON_PRETTY_PRINT) }}
                    </pre>
                    <p><strong>Status Code:</strong> {{ session('status_code') }}</p>
                </div>
            </div>
        @endif

        <!-- Display Sent Payload -->
        @if (session('sent_payload'))
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
                <h3 class="text-lg font-semibold">Sent Payload</h3>
                <pre class="bg-gray-900 text-white p-4 rounded-md overflow-x-auto">
                    {{ json_encode(session('sent_payload'), JSON_PRETTY_PRINT) }}
                </pre>
            </div>
        @endif --}}
    <div class="container mx-auto px-4 py-10 max-w-7xl">
        <div class="flex items-center justify-between mb-10">
            <h1 class="text-4xl font-extrabold text-gray-900">Create New Order</h1>
            <div class="bg-indigo-100 text-indigo-800 px-5 py-2.5 rounded-full text-sm font-semibold">
                Shiprocket Integration
            </div>
        </div>

        <div class="bg-white shadow-2xl rounded-2xl overflow-hidden">
            <form action="{{ route('shiprocket.order.store') }}" method="POST" class="space-y-10 p-8" id="order-form">
                @csrf

                <!-- Order Details -->
                <div class="form-section">
                    <h2 class="text-xl font-semibold">Order Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <label for="order_id" class="block text-sm font-medium text-gray-700 mb-1.5">Order ID <span class="text-red-500">*</span></label>
                            <input type="text" name="order_id" id="order_id" value="{{ old('order_id') }}" placeholder="Enter unique order ID (e.g., 224-447)" 
                                   class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('order_id') error @enderror">
                            @error('order_id')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="order_date" class="block text-sm font-medium text-gray-700 mb-1.5">Order Date <span class="text-red-500">*</span></label>
                            <input type="text" name="order_date" id="order_date" value="{{ old('order_date') }}" placeholder="YYYY-MM-DD HH:MM (e.g., 2025-07-17 14:30)" 
                                   class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('order_date') error @enderror">
                            @error('order_date')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="pickup_location" class="block text-sm font-medium text-gray-700 mb-1.5">Pickup Location <span class="text-red-500">*</span></label>
                            <input type="text" name="pickup_location" id="pickup_location" value="{{ old('pickup_location') }}" placeholder="Enter your saved pickup location" 
                                   class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('pickup_location') error @enderror">
                            <p class="mt-1.5 text-xs text-gray-500">Type your saved address from Shiprocket</p>
                            @error('pickup_location')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1.5">Payment Method <span class="text-red-500">*</span></label>
                            <select name="payment_method" id="payment_method" 
                                    class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('payment_method') error @enderror">
                                <option value="" disabled {{ old('payment_method') ? '' : 'selected' }}>Select payment method</option>
                                <option value="Prepaid" {{ old('payment_method') == 'Prepaid' ? 'selected' : '' }}>Prepaid</option>
                                <option value="COD" {{ old('payment_method') == 'COD' ? 'selected' : '' }}>COD (Cash on Delivery)</option>
                            </select>
                            @error('payment_method')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="comment" class="block text-sm font-medium text-gray-700 mb-1.5">Order Comments</label>
                            <input type="text" name="comment" id="comment" value="{{ old('comment') }}" placeholder="Any special instructions or notes (e.g., Reseller: M/s Goku)" 
                                   class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('comment') error @enderror">
                            @error('comment')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Billing Details -->
                <div class="form-section">
                    <h2 class="text-xl font-semibold">Billing Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <label for="billing_customer_name" class="block text-sm font-medium text-gray-700 mb-1.5">First Name <span class="text-red-500">*</span></label>
                            <input type="text" name="billing_customer_name" id="billing_customer_name" value="{{ old('billing_customer_name') }}" placeholder="Customer's first name (e.g., Naruto)" 
                                   class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('billing_customer_name') error @enderror">
                            @error('billing_customer_name')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="billing_last_name" class="block text-sm font-medium text-gray-700 mb-1.5">Last Name</label>
                            <input type="text" name="billing_last_name" id="billing_last_name" value="{{ old('billing_last_name') }}" placeholder="Customer's last name (e.g., Uzumaki)" 
                                   class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('billing_last_name') error @enderror">
                            @error('billing_last_name')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="billing_email" class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="billing_email" id="billing_email" value="{{ old('billing_email') }}" placeholder="Customer's email (e.g., naruto@uzumaki.com)" 
                                   class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('billing_email') error @enderror">
                            @error('billing_email')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="billing_phone" class="block text-sm font-medium text-gray-700 mb-1.5">Phone <span class="text-red-500">*</span></label>
                            <input type="text" name="billing_phone" id="billing_phone" value="{{ old('billing_phone') }}" placeholder="10-digit mobile number (e.g., 9876543210)" 
                                   class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('billing_phone') error @enderror">
                            @error('billing_phone')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="billing_address" class="block text-sm font-medium text-gray-700 mb-1.5">Address <span class="text-red-500">*</span></label>
                            <input type="text" name="billing_address" id="billing_address" value="{{ old('billing_address') }}" placeholder="Complete address (e.g., House 221B, Leaf Village)" 
                                   class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('billing_address') error @enderror">
                            @error('billing_address')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="billing_address_2" class="block text-sm font-medium text-gray-700 mb-1.5">Address Line 2</label>
                            <input type="text" name="billing_address_2" id="billing_address_2" value="{{ old('billing_address_2') }}" placeholder="Landmark or additional info (e.g., Near Hokage House)" 
                                   class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('billing_address_2') error @enderror">
                            @error('billing_address_2')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="billing_city" class="block text-sm font-medium text-gray-700 mb-1.5">City <span class="text-red-500">*</span></label>
                            <input type="text" name="billing_city" id="billing_city" value="{{ old('billing_city') }}" placeholder="City name (e.g., New Delhi)" 
                                   class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('billing_city') error @enderror">
                            @error('billing_city')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="billing_pincode" class="block text-sm font-medium text-gray-700 mb-1.5">Pincode <span class="text-red-500">*</span></label>
                            <input type="text" name="billing_pincode" id="billing_pincode" value="{{ old('billing_pincode') }}" placeholder="6-digit postal code (e.g., 110002)" 
                                   class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('billing_pincode') error @enderror">
                            @error('billing_pincode')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="billing_state" class="block text-sm font-medium text-gray-700 mb-1.5">State <span class="text-red-500">*</span></label>
                            <input type="text" name="billing_state" id="billing_state" value="{{ old('billing_state') }}" placeholder="State name (e.g., Delhi)" 
                                   class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('billing_state') error @enderror">
                            @error('billing_state')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="billing_country" class="block text-sm font-medium text-gray-700 mb-1.5">Country <span class="text-red-500">*</span></label>
                            <input type="text" name="billing_country" id="billing_country" value="{{ old('billing_country') }}" placeholder="Country name (e.g., India)" 
                                   class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('billing_country') error @enderror">
                            @error('billing_country')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Shipping Details -->
                <div class="form-section">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold">Shipping Information</h2>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="shipping_is_billing" id="shipping_is_billing" value="1" {{ old('shipping_is_billing', 1) ? 'checked' : '' }} 
                                   class="form-checkbox h-6 w-6 text-indigo-600 rounded focus:ring-indigo-600">
                            <span class="ml-3 text-sm font-medium text-gray-700">Same as billing address</span>
                        </label>
                    </div>
                    
                    <div id="shipping_fields" class="{{ old('shipping_is_billing', 1) ? 'hidden' : '' }} mt-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <label for="shipping_customer_name" class="block text-sm font-medium text-gray-700 mb-1.5">First Name <span class="text-red-500">*</span></label>
                                <input type="text" name="shipping_customer_name" id="shipping_customer_name" value="{{ old('shipping_customer_name') }}" placeholder="Recipient's first name (e.g., Naruto)" 
                                       class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('shipping_customer_name') error @enderror">
                                @error('shipping_customer_name')
                                    <p class="error-message">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="shipping_last_name" class="block text-sm font-medium text-gray-700 mb-1.5">Last Name</label>
                                <input type="text" name="shipping_last_name" id="shipping_last_name" value="{{ old('shipping_last_name') }}" placeholder="Recipient's last name (e.g., Uzumaki)" 
                                       class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('shipping_last_name') error @enderror">
                                @error('shipping_last_name')
                                    <p class="error-message">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="shipping_email" class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                                <input type="email" name="shipping_email" id="shipping_email" value="{{ old('shipping_email') }}" placeholder="Recipient's email (e.g., naruto@uzumaki.com)" 
                                       class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('shipping_email') error @enderror">
                                @error('shipping_email')
                                    <p class="error-message">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="shipping_phone" class="block text-sm font-medium text-gray-700 mb-1.5">Phone <span class="text-red-500">*</span></label>
                                <input type="text" name="shipping_phone" id="shipping_phone" value="{{ old('shipping_phone') }}" placeholder="10-digit mobile number (e.g., 9876543210)" 
                                       class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('shipping_phone') error @enderror">
                                @error('shipping_phone')
                                    <p class="error-message">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-1.5">Address <span class="text-red-500">*</span></label>
                                <input type="text" name="shipping_address" id="shipping_address" value="{{ old('shipping_address') }}" placeholder="Complete shipping address (e.g., House 221B, Leaf Village)" 
                                       class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('shipping_address') error @enderror">
                                @error('shipping_address')
                                    <p class="error-message">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="shipping_city" class="block text-sm font-medium text-gray-700 mb-1.5">City <span class="text-red-500">*</span></label>
                                <input type="text" name="shipping_city" id="shipping_city" value="{{ old('shipping_city') }}" placeholder="City name (e.g., New Delhi)" 
                                       class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('shipping_city') error @enderror">
                                @error('shipping_city')
                                    <p class="error-message">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="shipping_pincode" class="block text-sm font-medium text-gray-700 mb-1.5">Pincode <span class="text-red-500">*</span></label>
                                <input type="text" name="shipping_pincode" id="shipping_pincode" value="{{ old('shipping_pincode') }}" placeholder="6-digit postal code (e.g., 110002)" 
                                       class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('shipping_pincode') error @enderror">
                                @error('shipping_pincode')
                                    <p class="error-message">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="shipping_state" class="block text-sm font-medium text-gray-700 mb-1.5">State <span class="text-red-500">*</span></label>
                                <input type="text" name="shipping_state" id="shipping_state" value="{{ old('shipping_state') }}" placeholder="State name (e.g., Delhi)" 
                                       class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('shipping_state') error @enderror">
                                @error('shipping_state')
                                    <p class="error-message">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="shipping_country" class="block text-sm font-medium text-gray-700 mb-1.5">Country <span class="text-red-500">*</span></label>
                                <input type="text" name="shipping_country" id="shipping_country" value="{{ old('shipping_country') }}" placeholder="Country name (e.g., India)" 
                                       class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('shipping_country') error @enderror">
                                @error('shipping_country')
                                    <p class="error-message">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="form-section">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold">Order Items</h2>
                        <button type="button" id="add_item" class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg hover:bg-indigo-700 flex items-center font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Add Item
                        </button>
                    </div>
                    
                    <div id="order_items" class="mt-6 space-y-4">
                        @php $itemCount = old('order_items') ? count(old('order_items')) : 1; @endphp
                        @for ($i = 0; $i < $itemCount; $i++)
                            <div class="order-item">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <div>
                                        <label for="order_items[{{ $i }}][name]" class="block text-sm font-medium text-gray-700 mb-1.5">Product Name <span class="text-red-500">*</span></label>
                                        <input type="text" name="order_items[{{ $i }}][name]" value="{{ old('order_items.' . $i . '.name') }}" placeholder="Enter product name (e.g., Kunai)" 
                                               class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('order_items.' . $i . '.name') error @enderror">
                                        @error('order_items.' . $i . '.name')
                                            <p class="error-message">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="order_items[{{ $i }}][sku]" class="block text-sm font-medium text-gray-700 mb-1.5">SKU <span class="text-red-500">*</span></label>
                                        <input type="text" name="order_items[{{ $i }}][sku]" value="{{ old('order_items.' . $i . '.sku') }}" placeholder="Product SKU (e.g., chakra123)" 
                                               class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('order_items.' . $i . '.sku') error @enderror">
                                        @error('order_items.' . $i . '.sku')
                                            <p class="error-message">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="order_items[{{ $i }}][units]" class="block text-sm font-medium text-gray-700 mb-1.5">Quantity <span class="text-red-500">*</span></label>
                                        <input type="number" name="order_items[{{ $i }}][units]" value="{{ old('order_items.' . $i . '.units') }}" placeholder="Number of units (e.g., 10)" 
                                               class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('order_items.' . $i . '.units') error @enderror">
                                        @error('order_items.' . $i . '.units')
                                            <p class="error-message">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="order_items[{{ $i }}][selling_price]" class="block text-sm font-medium text-gray-700 mb-1.5">Price <span class="text-red-500">*</span></label>
                                        <input type="number" step="0.01" name="order_items[{{ $i }}][selling_price]" value="{{ old('order_items.' . $i . '.selling_price') }}" placeholder="Selling price per unit (e.g., 900.00)" 
                                               class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('order_items.' . $i . '.selling_price') error @enderror">
                                        @error('order_items.' . $i . '.selling_price')
                                            <p class="error-message">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="order_items[{{ $i }}][hsn]" class="block text-sm font-medium text-gray-700 mb-1.5">HSN Code</label>
                                        <input type="text" name="order_items[{{ $i }}][hsn]" value="{{ old('order_items.' . $i . '.hsn') }}" placeholder="HSN code (e.g., 441122)" 
                                               class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('order_items.' . $i . '.hsn') error @enderror">
                                        @error('order_items.' . $i . '.hsn')
                                            <p class="error-message">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="order_items[{{ $i }}][discount]" class="block text-sm font-medium text-gray-700 mb-1.5">Discount</label>
                                        <input type="text" name="order_items[{{ $i }}][discount]" value="{{ old('order_items.' . $i . '.discount') }}" placeholder="Discount amount (e.g., 0.00)" 
                                               class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('order_items.' . $i . '.discount') error @enderror">
                                        @error('order_items.' . $i . '.discount')
                                            <p class="error-message">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="order_items[{{ $i }}][tax]" class="block text-sm font-medium text-gray-700 mb-1.5">Tax</label>
                                        <input type="text" name="order_items[{{ $i }}][tax]" value="{{ old('order_items.' . $i . '.tax') }}" placeholder="Tax amount (e.g., 0.00)" 
                                               class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('order_items.' . $i . '.tax') error @enderror">
                                        @error('order_items.' . $i . '.tax')
                                            <p class="error-message">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    @if ($i > 0)
                                        <div class="flex items-end">
                                            <button type="button" class="remove-item text-red-600 hover:text-red-800 text-sm font-medium flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                                Remove Item
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- Package Details -->
                <div class="form-section">
                    <h2 class="text-xl font-semibold">Package Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div>
                            <label for="length" class="block text-sm font-medium text-gray-700 mb-1.5">Length (cm) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" name="length" id="length" value="{{ old('length') }}" placeholder="Package length in cm (e.g., 10.5)" 
                                   class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('length') error @enderror">
                            @error('length')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="breadth" class="block text-sm font-medium text-gray-700 mb-1.5">Breadth (cm) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" name="breadth" id="breadth" value="{{ old('breadth') }}" placeholder="Package width in cm (e.g., 15.2)" 
                                   class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('breadth') error @enderror">
                            @error('breadth')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="height" class="block text-sm font-medium text-gray-700 mb-1.5">Height (cm) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" name="height" id="height" value="{{ old('height') }}" placeholder="Package height in cm (e.g., 20.0)" 
                                   class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('height') error @enderror">
                            @error('height')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="weight" class="block text-sm font-medium text-gray-700 mb-1.5">Weight (kg) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" name="weight" id="weight" value="{{ old('weight') }}" placeholder="Package weight in kg (e.g., 0.5)" 
                                   class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('weight') error @enderror">
                            @error('weight')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Order Charges -->
                <div class="form-section">
                    <h2 class="text-xl font-semibold">Order Charges</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <label for="shipping_charges" class="block text-sm font-medium text-gray-700 mb-1.5">Shipping Charges</label>
                            <input type="number" step="0.01" name="shipping_charges" id="shipping_charges" value="{{ old('shipping_charges') }}" placeholder="Shipping cost (e.g., 50.00)" 
                                   class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('shipping_charges') error @enderror">
                            @error('shipping_charges')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="sub_total" class="block text-sm font-medium text-gray-700 mb-1.5">Sub Total <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" name="sub_total" id="sub_total" value="{{ old('sub_total') }}" placeholder="Order subtotal before discounts (e.g., 9000.00)" 
                                   class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('sub_total') error @enderror">
                            @error('sub_total')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="total_discount" class="block text-sm font-medium text-gray-700 mb-1.5">Total Discount</label>
                            <input type="number" step="0.01" name="total_discount" id="total_discount" value="{{ old('total_discount') }}" placeholder="Total discount amount (e.g., 0.00)" 
                                   class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('total_discount') error @enderror">
                            @error('total_discount')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="giftwrap_charges" class="block text-sm font-medium text-gray-700 mb-1.5">Giftwrap Charges</label>
                            <input type="number" step="0.01" name="giftwrap_charges" id="giftwrap_charges" value="{{ old('giftwrap_charges') }}" placeholder="Gift wrapping cost (e.g., 0.00)" 
                                   class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('giftwrap_charges') error @enderror">
                            @error('giftwrap_charges')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="transaction_charges" class="block text-sm font-medium text-gray-700 mb-1.5">Transaction Charges</label>
                            <input type="number" step="0.01" name="transaction_charges" id="transaction_charges" value="{{ old('transaction_charges') }}" placeholder="Payment processing fees (e.g., 0.00)" 
                                   class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 @error('transaction_charges') error @enderror">
                            @error('transaction_charges')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 mt-10">
                    <button type="reset" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-gray-50 hover:bg-gray-100 font-medium">
                        Reset Form
                    </button>
                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 flex items-center font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        Create Order
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // SweetAlert2 for Success with Redirect
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Order Created Successfully',
                html: `
                    <div class="text-left">
                        <p class="mb-2"><strong class="text-gray-700">Order ID:</strong> <span class="font-medium">{{ session('response.order_id', 'N/A') }}</span></p>
                        <p class="mb-2"><strong class="text-gray-700">Shipment ID:</strong> <span class="font-medium">{{ session('response.shipment_id', 'N/A') }}</span></p>
                        <p class="mb-2"><strong class="text-gray-700">Status:</strong> <span class="font-medium">{{ session('response.status', 'N/A') }}</span></p>
                        <p class="mt-4 text-sm text-gray-500">The order has been successfully created in Shiprocket.</p>
                    </div>
                `,
                confirmButtonText: 'Continue',
                confirmButtonColor: '#4f46e5',
                customClass: {
                    popup: 'rounded-xl',
                    confirmButton: 'px-4 py-2 rounded-lg'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'https://skbazar.in/shiprocket/orders';
                }
            });
        @endif



        // SweetAlert2 for Error
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error Creating Order',
                html: `
                    <div class="text-left">
                        <p class="mb-4 text-red-600 font-medium">{{ session('error') }}</p>
                        
                        @if (session('api_response.message'))
                            <p class="mb-2"><strong class="text-gray-700">API Message:</strong> <span class="font-medium">{{ session('api_response.message') }}</span></p>
                        @endif
                        
                        @if (session('api_response.errors'))
                            <p class="mb-1"><strong class="text-gray-700">Errors:</strong></p>
                            <ul class="list-disc pl-5 mb-3 text-sm">
                                @foreach (session('api_response.errors', []) as $field => $messages)
                                    @foreach ($messages as $message)
                                        <li class="mb-1"><span class="font-medium">{{ $field }}:</span> {{ $message }}</li>
                                    @endforeach
                                @endforeach
                            </ul>
                        @endif
                        
                        <p class="mt-2"><strong class="text-gray-700">Status Code:</strong> <span class="font-medium">{{ session('status_code', 'N/A') }}</span></p>
                        <p class="mt-4 text-sm text-gray-500">Please correct the errors and try again.</p>
                    </div>
                `,
                confirmButtonText: 'Understand',
                confirmButtonColor: '#4f46e5',
                customClass: {
                    popup: 'rounded-xl',
                    confirmButton: 'px-4 py-2 rounded-lg'
                }
            });
        @endif

        // Toggle shipping fields with animation
        document.getElementById('shipping_is_billing').addEventListener('change', function() {
            const shippingFields = document.getElementById('shipping_fields');
            if (this.checked) {
                shippingFields.classList.add('hidden');
            } else {
                shippingFields.classList.remove('hidden');
            }
        });

        // Client-side form validation
        document.getElementById('order-form').addEventListener('submit', function(e) {
            const shippingIsBilling = document.getElementById('shipping_is_billing').checked;
            const requiredFields = [
                { id: 'order_id', label: 'Order ID' },
                { id: 'order_date', label: 'Order Date' },
                { id: 'pickup_location', label: 'Pickup Location' },
                { id: 'payment_method', label: 'Payment Method' },
                { id: 'billing_customer_name', label: 'Billing First Name' },
                { id: 'billing_email', label: 'Billing Email' },
                { id: 'billing_phone', label: 'Billing Phone' },
                { id: 'billing_address', label: 'Billing Address' },
                { id: 'billing_city', label: 'Billing City' },
                { id: 'billing_pincode', label: 'Billing Pincode' },
                { id: 'billing_state', label: 'Billing State' },
                { id: 'billing_country', label: 'Billing Country' },
                { id: 'sub_total', label: 'Sub Total' },
                { id: 'length', label: 'Package Length' },
                { id: 'breadth', label: 'Package Breadth' },
                { id: 'height', label: 'Package Height' },
                { id: 'weight', label: 'Package Weight' }
            ];

            // Validate shipping fields if shipping_is_billing is unchecked
            const shippingFields = [
                { id: 'shipping_customer_name', label: 'Shipping First Name' },
                { id: 'shipping_email', label: 'Shipping Email' },
                { id: 'shipping_phone', label: 'Shipping Phone' },
                { id: 'shipping_address', label: 'Shipping Address' },
                { id: 'shipping_city', label: 'Shipping City' },
                { id: 'shipping_pincode', label: 'Shipping Pincode' },
                { id: 'shipping_state', label: 'Shipping State' },
                { id: 'shipping_country', label: 'Shipping Country' }
            ];

            // Validate required fields
            for (const field of requiredFields) {
                const input = document.getElementById(field.id);
                if (!input.value.trim()) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Missing Required Field',
                        text: `Please fill in the ${field.label} field.`,
                        confirmButtonText: 'Okay',
                        confirmButtonColor: '#4f46e5',
                    });
                    return;
                }
            }

            // Validate shipping fields if not same as billing
            if (!shippingIsBilling) {
                for (const field of shippingFields) {
                    const input = document.getElementById(field.id);
                    if (!input.value.trim()) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Missing Shipping Information',
                            text: `Please fill in the ${field.label} field.`,
                            confirmButtonText: 'Okay',
                            confirmButtonColor: '#4f46e5',
                        });
                        return;
                    }
                }
            }

            // Validate order items
            const orderItems = document.querySelectorAll('.order-item');
            orderItems.forEach((item, index) => {
                const itemFields = [
                    { name: `order_items[${index}][name]`, label: 'Product Name' },
                    { name: `order_items[${index}][sku]`, label: 'SKU' },
                    { name: `order_items[${index}][units]`, label: 'Quantity' },
                    { name: `order_items[${index}][selling_price]`, label: 'Price' }
                ];
                for (const field of itemFields) {
                    const input = item.querySelector(`input[name="${field.name}"]`);
                    if (!input.value.trim()) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Missing Order Item Field',
                            text: `Please fill in the ${field.label} for item ${index + 1}.`,
                            confirmButtonText: 'Okay',
                            confirmButtonColor: '#4f46e5',
                        });
                        return;
                    }
                }
            });
        });

        // Add new order item with improved UI
        let itemCount = {{ $itemCount }};
        document.getElementById('add_item').addEventListener('click', function() {
            const container = document.getElementById('order_items');
            const newItem = document.createElement('div');
            newItem.className = 'order-item';
            newItem.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="order_items[${itemCount}][name]" class="block text-sm font-medium text-gray-700 mb-1.5">Product Name <span class="text-red-500">*</span></label>
                        <input type="text" name="order_items[${itemCount}][name]" placeholder="Enter product name (e.g., Kunai)" 
                               class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600">
                    </div>
                    <div>
                        <label for="order_items[${itemCount}][sku]" class="block text-sm font-medium text-gray-700 mb-1.5">SKU <span class="text-red-500">*</span></label>
                        <input type="text" name="order_items[${itemCount}][sku]" placeholder="Product SKU (e.g., chakra123)" 
                               class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600">
                    </div>
                    <div>
                        <label for="order_items[${itemCount}][units]" class="block text-sm font-medium text-gray-700 mb-1.5">Quantity <span class="text-red-500">*</span></label>
                        <input type="number" name="order_items[${itemCount}][units]" placeholder="Number of units (e.g., 10)" 
                               class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600">
                    </div>
                    <div>
                        <label for="order_items[${itemCount}][selling_price]" class="block text-sm font-medium text-gray-700 mb-1.5">Price <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="order_items[${itemCount}][selling_price]" placeholder="Selling price per unit (e.g., 900.00)" 
                               class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600">
                    </div>
                    <div>
                        <label for="order_items[${itemCount}][hsn]" class="block text-sm font-medium text-gray-700 mb-1.5">HSN Code</label>
                        <input type="text" name="order_items[${itemCount}][hsn]" placeholder="HSN code (e.g., 441122)" 
                               class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600">
                    </div>
                    <div>
                        <label for="order_items[${itemCount}][discount]" class="block text-sm font-medium text-gray-700 mb-1.5">Discount</label>
                        <input type="text" name="order_items[${itemCount}][discount]" placeholder="Discount amount (e.g., 0.00)" 
                               class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600">
                    </div>
                    <div>
                        <label for="order_items[${itemCount}][tax]" class="block text-sm font-medium text-gray-700 mb-1.5">Tax</label>
                        <input type="text" name="order_items[${itemCount}][tax]" placeholder="Tax amount (e.g., 0.00)" 
                               class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600">
                    </div>
                    <div class="flex items-end">
                        <button type="button" class="remove-item text-red-600 hover:text-red-800 text-sm font-medium flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            Remove Item
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(newItem);
            
            // Add event listener to the remove button
            newItem.querySelector('.remove-item').addEventListener('click', function() {
                container.removeChild(newItem);
            });
            
            itemCount++;
        });
    </script>
    @endsection

</body>
</html>
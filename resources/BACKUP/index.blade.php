
@extends('layouts.shiprocket')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">ShipRocket Orders</h1>
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
    </div>

    <!-- Search Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="w-full md:w-1/3">
            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search Orders</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <input type="text" id="search" placeholder="Search by order ID, customer, phone..." 
                       class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Products</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shipping</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $order['id'] }}</div>
                            <div class="text-sm text-gray-500">{{ $order['channel_order_id'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $order['customer_name'] }}</div>
                            <div class="text-sm text-gray-500">{{ $order['customer_email'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $order['customer_phone'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $order['customer_city'] }}, {{ $order['customer_state'] }}
                            </div>
                            <div class="text-sm text-gray-500">{{ $order['customer_pincode'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColor = [
                                    'new' => 'bg-blue-100 text-blue-800',
                                    'processing' => 'bg-yellow-100 text-yellow-800',
                                    'shipped' => 'bg-purple-100 text-purple-800',
                                    'delivered' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                    'pickup scheduled' => 'bg-indigo-100 text-indigo-800',
                                    'ready to ship' => 'bg-orange-100 text-orange-800',
                                    'pickup completed' => 'bg-teal-100 text-teal-800',
                                ][strtolower($order['status'])] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                                {{ $order['status'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">₹{{ number_format($order['total'], 2) }}</div>
                            <div class="text-sm text-gray-500">{{ $order['payment_method'] }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                @foreach ($order['products'] as $product)
                                    <div class="mb-1">
                                        <span class="font-medium">{{ $product['name'] }}</span>
                                        <span class="text-gray-500 text-xs block">SKU: {{ $product['channel_sku'] }}</span>
                                        <span class="text-sm text-gray-500">Qty: {{ $product['quantity'] }} × ₹{{ $product['price'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @foreach ($order['shipments'] as $shipment)
                                <div class="mb-2">
                                    <div class="text-sm font-medium">{{ $shipment['courier'] }}</div>
                                    <div class="text-xs text-gray-500">AWB: {{ $shipment['awb'] ?? 'N/A' }}</div>
                                    @if($shipment['etd'])
                                        <div class="text-xs text-gray-500">ETD: {{ $shipment['etd'] }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ date('d M Y', strtotime($order['created_at'])) }}</div>
                            <div class="text-xs text-gray-500">{{ date('H:i', strtotime($order['created_at'])) }}</div>
                        </td>
<td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
    <!-- View Details: Always visible -->
    <button class="text-blue-600 hover:text-blue-900 mr-3" title="View Details">
        <i class="fas fa-eye"></i>
    </button>

    @php
        $status = strtolower($order['status']);
        $shipmentId = $order['shipments'][0]['id'] ?? '';
    @endphp

    <!-- Track Order: Hide if status is new, canceled, cancellation requested, or picked up -->
    @if(!in_array($status, ['new', 'canceled', 'cancellation requested', 'picked up']))
        <button class="text-green-600 hover:text-green-900 track-order {{ empty($shipmentId) ? 'opacity-50 cursor-not-allowed' : '' }} mr-3"
                title="Track Order"
                data-shipment-id="{{ $shipmentId }}"
                @if(empty($shipmentId)) disabled @endif>
            <i class="fas fa-truck"></i>
        </button>
    @endif

    <!-- Generate Pickup: Only when status is 'ready to ship' -->
    @if($status === 'ready to ship')
        <button class="text-indigo-600 hover:text-indigo-900 generate-pickup {{ empty($shipmentId) ? 'opacity-50 cursor-not-allowed' : '' }} mr-3"
                title="Generate Pickup"
                data-shipment-id="{{ $shipmentId }}"
                @if(empty($shipmentId)) disabled @endif>
            <i class="fas fa-dolly"></i>
        </button>
    @endif

    <!-- Ship Now: Hide if status is canceled, cancellation requested, picked up, pickup scheduled, or pickup completed -->
    @if(!in_array($status, ['canceled', 'cancellation requested', 'picked up', 'pickup scheduled', 'pickup completed']))
        <button class="text-purple-600 hover:text-purple-900 ship-now {{ empty($shipmentId) ? 'opacity-50 cursor-not-allowed' : '' }} mr-3"
                title="Ship Now"
                data-order-id="{{ $order['id'] }}"
                data-shipment-id="{{ $shipmentId }}"
                @if(empty($shipmentId)) disabled @endif>
            <i class="fas fa-shipping-fast"></i>
        </button>
    @endif

    <!-- Manifest & Label: Only when status is pickup scheduled or pickup completed -->
    @if(in_array($status, ['pickup scheduled', 'pickup completed']))
        <button class="text-teal-600 hover:text-teal-900 generate-manifest {{ empty($shipmentId) ? 'opacity-50 cursor-not-allowed' : '' }} mr-3"
                title="Download Manifest"
                data-shipment-id="{{ $shipmentId }}"
                @if(empty($shipmentId)) disabled @endif>
            <i class="fas fa-file-download"></i>
        </button>

        <button class="text-pink-600 hover:text-pink-900 generate-label {{ empty($shipmentId) ? 'opacity-50 cursor-not-allowed' : '' }}"
                title="Download Label"
                data-shipment-id="{{ $shipmentId }}"
                @if(empty($shipmentId)) disabled @endif>
            <i class="fas fa-tag"></i>
        </button>
    @endif
</td>



                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-4 text-center text-sm text-gray-500">
                            No orders found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Order Details Modal -->
<div class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true" id="orderModal">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Order Details
                        </h3>
                        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="font-medium text-gray-900">Customer Information</h4>
                                <p id="modal-customer-name" class="text-sm text-gray-500"></p>
                                <p id="modal-customer-email" class="text-sm text-gray-500"></p>
                                <p id="modal-customer-phone" class="text-sm text-gray-500"></p>
                                <p id="modal-customer-address" class="text-sm text-gray-500 mt-2"></p>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">Order Information</h4>
                                <p id="modal-order-id" class="text-sm text-gray-500"></p>
                                <p id="modal-order-date" class="text-sm text-gray-500"></p>
                                <p id="modal-order-status" class="text-sm text-gray-500"></p>
                                <p id="modal-order-total" class="text-sm text-gray-500"></p>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">Products</h4>
                                <div id="modal-products" class="text-sm text-gray-500"></div>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">Shipping</h4>
                                <div id="modal-shipping" class="text-sm text-gray-500"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" id="closeModal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Serviceability Modal -->
<div class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="serviceability-modal-title" role="dialog" aria-modal="true" id="serviceabilityModal">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="serviceability-modal-title">
                            Courier Serviceability
                        </h3>
                        <div class="mt-2">
                            <div id="pickup-content" class="text-sm text-gray-500 mb-4">
                                <p>Pickup not yet requested.</p>
                            </div>
                            <div id="serviceability-content" class="text-sm text-gray-500">
                                <p>Loading serviceability information...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" id="closeServiceabilityModal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
    // Search functionality
    document.getElementById('search').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // Order Details Modal functionality
    document.querySelectorAll('[title="View Details"]').forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            document.getElementById('modal-customer-name').textContent = row.querySelector('td:nth-child(2) div:first-child').textContent;
            document.getElementById('modal-customer-email').textContent = row.querySelector('td:nth-child(2) div:last-child').textContent;
            document.getElementById('modal-customer-phone').textContent = row.querySelector('td:nth-child(3) div').textContent;
            document.getElementById('modal-customer-address').textContent = row.querySelector('td:nth-child(4) div:first-child').textContent + ', ' + row.querySelector('td:nth-child(4) div:last-child').textContent;
            
            document.getElementById('modal-order-id').textContent = 'Order ID: ' + row.querySelector('td:first-child div:first-child').textContent;
            document.getElementById('modal-order-date').textContent = 'Date: ' + row.querySelector('td:nth-child(9) div:first-child').textContent;
            document.getElementById('modal-order-status').textContent = 'Status: ' + row.querySelector('td:nth-child(5) span').textContent;
            document.getElementById('modal-order-total').textContent = 'Total: ' + row.querySelector('td:nth-child(6) div:first-child').textContent;
            
            document.getElementById('modal-products').innerHTML = row.querySelector('td:nth-child(7)').innerHTML;
            document.getElementById('modal-shipping').innerHTML = row.querySelector('td:nth-child(8)').innerHTML;
            
            document.getElementById('orderModal').classList.remove('hidden');
        });
    });
    
    document.getElementById('closeModal').addEventListener('click', function() {
        document.getElementById('orderModal').classList.add('hidden');
    });

    // Serviceability Modal functionality for Ship Now
    document.querySelectorAll('.ship-now').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            const shipmentId = this.getAttribute('data-shipment-id');
            const pickupContent = document.getElementById('pickup-content');
            const serviceabilityContent = document.getElementById('serviceability-content');
            const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
            
            if (!csrfTokenElement) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'CSRF token is missing. Please contact support or ensure the CSRF meta tag is included in the page.',
                });
                return;
            }

            if (!shipmentId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Shipment ID is missing. Please ensure the order has a valid shipment.',
                });
                return;
            }

            const csrfToken = csrfTokenElement.getAttribute('content');

            // Fetch serviceability details
            fetch(`/shiprocket/orders/serviceability/${orderId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    serviceabilityContent.innerHTML = `<p class="text-red-500">${data.error}</p>`;
                } else {
                    let html = '<h4 class="font-medium text-gray-900">Available Couriers</h4>';
                    if (data.data && data.data.available_courier_companies) {
                        data.data.available_courier_companies.forEach(courier => {
                            html += `
                                <div class="border-b py-2">
                                    <p><strong>Courier:</strong> ${courier.courier_name}</p>
                                    <p><strong>Estimated Delivery:</strong> ${courier.etd}</p>
                                    <p><strong>Rate:</strong> ₹${courier.rate}</p>
                                    <p><strong>COD Available:</strong> ${courier.cod === '1' ? 'Yes' : 'No'}</p>
                                    <p><strong>Rating:</strong> ${courier.rating}</p>
                                    <button class="mt-2 inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 assign-awb" 
                                            data-courier-id="${courier.courier_company_id}" 
                                            data-shipment-id="${shipmentId}" 
                                            data-status="${courier.status || ''}">
                                        Ship Now
                                    </button>
                                </div>
                            `;
                        });
                    } else {
                        html += '<p>No courier services available.</p>';
                    }
                    serviceabilityContent.innerHTML = html;
                    
                    // Add event listeners for AWB assignment buttons
                    document.querySelectorAll('.assign-awb').forEach(awbButton => {
                        awbButton.addEventListener('click', function() {
                            const courierId = this.getAttribute('data-courier-id');
                            const shipmentId = this.getAttribute('data-shipment-id');
                            const status = this.getAttribute('data-status');
                            
                            // Step 1: Assign AWB
                            fetch('/orders/assign-awb', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': csrfToken
                                },
                                body: JSON.stringify({
                                    shipment_id: shipmentId,
                                    courier_id: courierId,
                                    status: status
                                })
                            })
                            .then(response => response.json())
                            .then(awbData => {
                                if (awbData.error) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: awbData.error,
                                    });
                                    return;
                                }

                                // Show AWB assignment success in SweetAlert2
                                Swal.fire({
                                    icon: 'success',
                                    title: 'AWB Assigned',
                                    html: `
                                        <p><strong>Shipment ID:</strong> ${awbData.shipment_id || 'N/A'}</p>
                                        <p><strong>Courier ID:</strong> ${awbData.courier_id || 'N/A'}</p>
                                        <p><strong>Status:</strong> ${awbData.status || 'N/A'}</p>
                                    `
                                });

                                // Step 2: Show pickup form
                                pickupContent.innerHTML = `
                                    <h4 class="font-medium text-gray-900 mb-2">Schedule Pickup</h4>
    
                                    <form id="pickup-form" class="space-y-4">
                                        <div>
                                            <label for="pickup-shipment-id" class="block text-sm font-medium text-gray-700">Shipment ID</label>
                                            <input type="text" id="pickup-shipment-id" name="shipment_id" value="${awbData.shipment_id || shipmentId}" readonly class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm bg-gray-100">
                                        </div>
                                        <div>
                                            <label for="pickup-status" class="block text-sm font-medium text-gray-700">Status</label>
                                            <select id="pickup-status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                                <option value="">None</option>
                                                <option value="retry">Retry</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="pickup-date" class="block text-sm font-medium text-gray-700">Pickup Date</label>
                                            <input type="date" id="pickup-date" name="pickup_date" min="${new Date().toISOString().split('T')[0]}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        </div>
                                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Schedule Pickup
                                        </button>
                                    </form>
                                `;

                                // Handle pickup form submission
                                document.getElementById('pickup-form').addEventListener('submit', function(e) {
                                    e.preventDefault();
                                    const formData = new FormData(this);
                                    const pickupData = {
                                        shipment_id: [formData.get('shipment_id')],
                                        status: formData.get('status') || undefined,
                                        pickup_date: formData.get('pickup_date') ? [formData.get('pickup_date')] : undefined
                                    };

                                    fetch('/shiprocket/orders/generate-pickup', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'Accept': 'application/json',
                                            'X-Requested-With': 'XMLHttpRequest',
                                            'X-CSRF-TOKEN': csrfToken
                                        },
                                        body: JSON.stringify(pickupData)
                                    })
                                    .then(response => response.json())
                                    .then(pickupResponse => {
                                        if (pickupResponse.error) {
                                            pickupContent.innerHTML = `<p class="text-red-500">Pickup Error: ${pickupResponse.error}</p>`;
                                        } else {
                                            let pickupHtml = '<h4 class="font-medium text-gray-900">Pickup Details</h4>';
                                            pickupHtml += `
                                                <p><strong>Status:</strong> ${pickupResponse.status || 'N/A'}</p>
                                                <p><strong>Pickup Scheduled Date:</strong> ${pickupResponse.pickup_scheduled_date || 'N/A'}</p>
                                                <p><strong>Pickup Token:</strong> ${pickupResponse.pickup_token_number || 'N/A'}</p>
                                            `;
                                            pickupContent.innerHTML = pickupHtml;
                                        }
                                    })
                                    .catch(error => {
                                        pickupContent.innerHTML = '<p class="text-red-500">Error generating pickup.</p>';
                                    });
                                });
                            })
                            .catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Failed to assign AWB. Please try again.',
                                });
                            });
                        });
                    });
                }
                document.getElementById('serviceabilityModal').classList.remove('hidden');
            })
            .catch(error => {
                serviceabilityContent.innerHTML = '<p class="text-red-500">Error fetching serviceability details.</p>';
                document.getElementById('serviceabilityModal').classList.remove('hidden');
            });
        });
    });

    // Generate Pickup Button functionality
    document.querySelectorAll('.generate-pickup').forEach(button => {
        button.addEventListener('click', function() {
            const shipmentId = this.getAttribute('data-shipment-id');
            const pickupContent = document.getElementById('pickup-content');
            const serviceabilityContent = document.getElementById('serviceability-content');
            const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
            
            if (!csrfTokenElement) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'CSRF token is missing. Please contact support or ensure the CSRF meta tag is included in the page.',
                });
                return;
            }

            if (!shipmentId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Shipment ID is missing. Please ensure the order has a valid shipment.',
                });
                return;
            }

            const csrfToken = csrfTokenElement.getAttribute('content');

            // Hide serviceability content and show pickup form
            serviceabilityContent.innerHTML = '';
            pickupContent.innerHTML = `
                <h4 class="font-medium text-gray-900 mb-2">Schedule Pickup</h4>

                <form id="pickup-form" class="space-y-4">
                    <div>
                        <label for="pickup-shipment-id" class="block text-sm font-medium text-gray-700">Shipment ID</label>
                        <input type="text" id="pickup-shipment-id" name="shipment_id" value="${shipmentId}" readonly class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm bg-gray-100">
                    </div>
                    <div>
                        <label for="pickup-status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select id="pickup-status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">None</option>
                            <option value="retry">Retry</option>
                        </select>
                    </div>
                    <div>
                        <label for="pickup-date" class="block text-sm font-medium text-gray-700">Pickup Date</label>
                        <input type="date" id="pickup-date" name="pickup_date" min="${new Date().toISOString().split('T')[0]}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Schedule Pickup
                    </button>
                </form>
            `;

            // Handle pickup form submission
            document.getElementById('pickup-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const pickupData = {
                    shipment_id: [formData.get('shipment_id')],
                    status: formData.get('status') || undefined,
                    pickup_date: formData.get('pickup_date') ? [formData.get('pickup_date')] : undefined
                };

                fetch('/shiprocket/orders/generate-pickup', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(pickupData)
                })
                .then(response => response.json())
                .then(pickupResponse => {
                    if (pickupResponse.error) {
                        pickupContent.innerHTML = `<p class="text-red-500">Pickup Error: ${pickupResponse.error}</p>`;
                    } else {
                        let pickupHtml = '<h4 class="font-medium text-gray-900">Pickup Details</h4>';
                        pickupHtml += `
                            <p><strong>Status:</strong> ${pickupResponse.status || 'N/A'}</p>
                            <p><strong>Pickup Scheduled Date:</strong> ${pickupResponse.pickup_scheduled_date || 'N/A'}</p>
                            <p><strong>Pickup Token:</strong> ${pickupResponse.pickup_token_number || 'N/A'}</p>
                        `;
                        pickupContent.innerHTML = pickupHtml;
                    }
                })
                .catch(error => {
                    pickupContent.innerHTML = '<p class="text-red-500">Error generating pickup.</p>';
                });
            });

            document.getElementById('serviceabilityModal').classList.remove('hidden');
        });
    });

    // Generate Manifest Button functionality
    document.querySelectorAll('.generate-manifest').forEach(button => {
        button.addEventListener('click', function() {
            const shipmentId = this.getAttribute('data-shipment-id');
            const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
            
            if (!csrfTokenElement) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'CSRF token is missing. Please contact support or ensure the CSRF meta tag is included in the page.',
                });
                return;
            }

            if (!shipmentId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Shipment ID is missing. Please ensure the order has a valid shipment.',
                });
                return;
            }

            const csrfToken = csrfTokenElement.getAttribute('content');

            fetch('/shiprocket/orders/generate-manifest', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    shipment_id: [shipmentId]
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.error,
                    });
                } else if (data.manifest_url) {
                    window.open(data.manifest_url, '_blank');
                    Swal.fire({
                        icon: 'success',
                        title: 'Manifest Generated',
                        text: 'Manifest has been generated successfully and is downloading.',
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No manifest URL provided in the response.',
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to generate manifest. Please try again.',
                });
            });
        });
    });

    // Generate Label Button functionality
    document.querySelectorAll('.generate-label').forEach(button => {
        button.addEventListener('click', function() {
            const shipmentId = this.getAttribute('data-shipment-id');
            const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
            
            if (!csrfTokenElement) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'CSRF token is missing. Please contact support or ensure the CSRF meta tag is included in the page.',
                });
                return;
            }

            if (!shipmentId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Shipment ID is missing. Please ensure the order has a valid shipment.',
                });
                return;
            }

            const csrfToken = csrfTokenElement.getAttribute('content');

            fetch('/shiprocket/orders/generate-label', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    shipment_id: [shipmentId]
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.error,
                    });
                } else if (data.label_url) {
                    window.open(data.label_url, '_blank');
                    Swal.fire({
                        icon: 'success',
                        title: 'Label Generated',
                        text: 'Label has been generated successfully and is downloading.',
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No label URL provided in the response.',
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to generate label. Please try again.',
                });
            });
        });
    });

    // Track Order Button functionality
    document.querySelectorAll('.track-order').forEach(button => {
        button.addEventListener('click', function() {
            const shipmentId = this.getAttribute('data-shipment-id');
            const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
            
            if (!csrfTokenElement) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'CSRF token is missing. Please contact support or ensure the CSRF meta tag is included in the page.',
                });
                return;
            }

            if (!shipmentId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Shipment ID is missing. Please ensure the order has a valid shipment.',
                });
                return;
            }

            const csrfToken = csrfTokenElement.getAttribute('content');

            fetch(`/orders/track/${shipmentId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.error,
                    });
                } else if (data.track_url) {
                    window.open(data.track_url, '_blank');
                    Swal.fire({
                        icon: 'success',
                        title: 'Tracking Initiated',
                        text: 'You are being redirected to the tracking page.',
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No tracking URL provided in the response.',
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch tracking details. Please try again.',
                });
            });
        });
    });

    document.getElementById('closeServiceabilityModal').addEventListener('click', function() {
        document.getElementById('serviceabilityModal').classList.add('hidden');
    });
</script>
@endsection

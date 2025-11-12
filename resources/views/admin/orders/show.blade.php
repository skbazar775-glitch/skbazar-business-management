@extends('layouts.admin')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<div class="container-fluid px-4 py-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800" data-aos="fade-right">Order Details</h1>
        <a href="{{ route('admin.orders.index') }}" class="mt-4 sm:mt-0 btn bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 hover:shadow-lg transition-all" data-aos="fade-left">
            <i class="fas fa-arrow-left mr-2"></i> Back to Orders
        </a>
    </div>

    <!-- Order Information Card -->
    <div class="card bg-white shadow-lg rounded-xl mb-6 overflow-hidden" data-aos="fade-up">
        <div class="card-header bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-4 px-6">
            <h6 class="text-lg font-bold">Order Information - {{ $order->unique_order_id }}</h6>
        </div>
        <div class="card-body p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="border-l-4 border-blue-500 pl-4" data-aos="fade-right" data-aos-delay="100">
                    <h6 class="text-blue-600 font-semibold mb-3">Customer Details</h6>
                    <p class="mb-2"><strong>Name:</strong> {{ $order->user->name ?? 'N/A' }}</p>
                    <p class="mb-2"><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
                    <p class="mb-0"><strong>Order ID:</strong> {{ $order->unique_order_id }}</p>
                </div>
                <div class="border-l-4 border-teal-500 pl-4" data-aos="fade-left" data-aos-delay="200">
                    <h6 class="text-teal-600 font-semibold mb-3">Order Summary</h6>
                    <p class="mb-2"><strong>Total Amount:</strong> <span class="text-green-600 font-bold">${{ number_format($order->total_amount, 2) }}</span></p>
                    <p class="mb-2"><strong>Payment Method:</strong> {{ $order->payment_method ?? 'N/A' }}</p>
                    <p class="mb-2"><strong>Payment Status:</strong> 
                        <span class="badge px-3 py-1 rounded-full {{ $order->payment_status == 1 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $order->payment_status_text }}
                        </span>
                    </p>
                    <p class="mb-0"><strong>Order Status:</strong> 
                        <span class="badge px-3 py-1 rounded-full {{ $order->status == 4 ? 'bg-green-100 text-green-800' : ($order->status == 5 ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
                            {{ $order->status_text }}
                        </span>
                    </p>
                </div>
            </div>
            <div class="mt-6">
                <div class="timeline" data-aos="fade-up" data-aos-delay="300">
                    <h6 class="text-gray-600 font-semibold mb-3">Timeline</h6>
                    <div class="timeline-item relative pl-8 pb-4">
                        <p class="mb-0"><strong>Created At:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                    <!-- Add more timeline items dynamically if needed -->
                </div>
            </div>
        </div>
    </div>

    <!-- Order Items Card -->
    <div class="card bg-white shadow-lg rounded-xl mb-6 overflow-hidden" data-aos="fade-up" data-aos-delay="400">
        <div class="card-header bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-4 px-6">
            <h6 class="text-lg font-bold">Order Items</h6>
        </div>
        <div class="card-body p-6">
            <div class="overflow-x-auto">
                <table class="w-full table-auto bg-white shadow-md rounded-lg">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="px-4 py-3 text-left">Image</th>
                            <th class="px-4 py-3 text-left">Product Name</th>
                            <th class="px-4 py-3 text-left">Category</th>
                            <th class="px-4 py-3 text-left">Quantity</th>
                            <th class="px-4 py-3 text-left">Unit Price</th>
                            <th class="px-4 py-3 text-left">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->orderItems as $item)
                            <tr class="hover:bg-blue-50 hover:translate-x-1 transition-all" data-aos="fade-up" data-aos-delay="{{ 100 * $loop->index }}">
                                <td class="px-4 py-3">
                                    @if ($item->product->image)
                                        <img src="{{ asset('uploaded/products/' . $item->product->image) }}" 
                                             alt="{{ $item->product->name }}" 
                                             class="w-16 h-16 object-cover rounded-lg border-2 border-gray-200">
                                    @else
                                        <div class="w-16 h-16 bg-gray-100 flex items-center justify-center rounded-lg">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-semibold">{{ $item->product->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3">
                                    <span class="badge px-3 py-1 bg-teal-100 text-teal-800 rounded-full">
                                        {{ $item->product->category->title ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="badge px-3 py-1 bg-blue-100 text-blue-800 rounded-full">
                                        {{ $item->quantity }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-green-600">${{ number_format($item->product->price_e ?? 0, 2) }}</td>
                                <td class="px-4 py-3 text-green-600 font-bold">${{ number_format(($item->product->price_e ?? 0) * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-100">
                            <td colspan="5" class="px-4 py-3 text-right font-bold">Grand Total:</td>
                            <td class="px-4 py-3 text-green-600 font-bold">${{ number_format($order->total_amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex gap-4 mb-6" data-aos="fade-up" data-aos-delay="500">
        <a href="{{ route('admin.orders.index') }}" class="btn bg-gradient-to-r from-gray-600 to-gray-800 text-white px-4 py-2 rounded-lg hover:shadow-lg hover:scale-105 transition-all">
            <i class="fas fa-arrow-left mr-2"></i> Back to Orders
        </a>

    </div>
</div>

<style>
    .timeline {
        position: relative;
        padding-left: 2rem;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 0.5rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, #3b82f6, #1e40af);
    }
    .timeline-item {
        position: relative;
        margin-bottom: 1rem;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -1.5rem;
        top: 0.5rem;
        width: 10px;
        height: 10px;
        background: #3b82f6;
        border-radius: 50%;
        border: 2px solid #fff;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({ duration: 800, easing: 'ease-in-out', once: true });
    });
</script>
@endsection
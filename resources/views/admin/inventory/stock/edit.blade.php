@extends('layouts.admin')
@section('content')
<!-- Wrapping content in a responsive container -->
<div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-8">
    <!-- Glassmorphism card for form -->
    <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-lg p-6 max-w-lg mx-auto">
        <!-- Form title with black text -->
        <h1 class="text-2xl sm:text-3xl font-bold text-black mb-6">Edit Stock</h1>

        <!-- Error alert with black text -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 text-black rounded-lg shadow-sm animate-fade-in">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <!-- Close button with black text -->
                <button type="button" class="float-right text-black hover:text-gray-700">✕</button>
            </div>
        @endif

        <!-- Success alert with black text -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 text-black rounded-lg shadow-sm animate-fade-in">
                {{ session('success') }}
                <!-- Close button with black text -->
                <button type="button" class="float-right text-black hover:text-gray-700">✕</button>
            </div>
        @endif

        <!-- Form with Tailwind styling -->
        <form action="{{ route('admin.stock.update', $stock->id) }}" method="POST">
            @csrf
            @method('PUT')
            <!-- Stock quantity input -->
            <div class="mb-4">
                <label for="stock_quantity" class="block text-black text-sm sm:text-base font-semibold mb-2">Stock Quantity</label>
                <input type="number" step="0.01" class="w-full p-3 rounded-lg bg-gradient-to-r from-gray-100 to-gray-200 border-none focus:ring-2 focus:ring-blue-500 transition duration-300 text-black text-sm sm:text-base" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $stock->stock_quantity) }}" required>
            </div>
            <!-- Unit select dropdown -->
            <div class="mb-4">
                <label for="stock_quantity_unit" class="block text-black text-sm sm:text-base font-semibold mb-2">Unit</label>
                <select class="w-full p-3 rounded-lg bg-gradient-to-r from-gray-100 to-gray-200 border-none focus:ring-2 focus:ring-blue-500 transition duration-300 text-black text-sm sm:text-base" id="stock_quantity_unit" name="stock_quantity_unit" required>
                    @foreach ($units as $unit)
                        <option value="{{ $unit }}" {{ $stock->stock_quantity_unit == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Form buttons -->
            <div class="flex space-x-4">
                <!-- Submit button with gradient and black text -->
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-blue-400 to-blue-600 text-black rounded-lg hover:scale-105 transition-transform text-sm sm:text-base">Update Stock</button>
                <!-- Back button with gradient and black text -->
                <a href="{{ route('admin.stock.index') }}" class="px-4 py-2 bg-gradient-to-r from-gray-500 to-gray-700 text-black rounded-lg hover:scale-105 transition-transform text-sm sm:text-base">Back</a>
            </div>
        </form>
    </div>
</div>

<!-- Tailwind CSS CDN for styling -->
<script src="https://cdn.tailwindcss.com"></script>
<!-- Animation for fade-in -->
<style>
    @keyframes fade-in {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .animate-fade-in {
        animation: fade-in 0.5s ease-in;
    }
</style>
@endsection
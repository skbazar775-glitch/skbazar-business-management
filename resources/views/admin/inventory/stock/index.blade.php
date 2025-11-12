@extends('layouts.admin')
@section('content')
<!-- Wrapping the content in a container with padding and centering -->
<div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-8">
    <!-- Card with glassmorphism effect -->
    <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-lg p-6">
        <!-- Header with flexbox for alignment -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl sm:text-3xl font-bold text-black">Manage Stocks</h2>
            <!-- Refresh button with gradient and black text -->
            <a href="{{ route('admin.stock.index') }}" class="px-4 py-2 bg-gradient-to-r from-gray-500 to-gray-700 text-black rounded-lg hover:scale-105 transition-transform">Refresh</a>
        </div>

        <!-- Success alert with black text -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 text-black rounded-lg shadow-sm animate-fade-in">
                {{ session('success') }}
                <!-- Close button with black text -->
                <button type="button" class="float-right text-black hover:text-gray-700">✕</button>
            </div>
        @endif

        <!-- Search bar with gradient and animation -->
        <form method="GET" action="{{ route('admin.stock.index') }}" class="mb-6">
            <div class="relative w-full max-w-lg mx-auto">
                <!-- Input with gradient and black text -->
                <input type="text" name="q" class="w-full p-3 pl-10 rounded-lg bg-gradient-to-r from-gray-100 to-gray-200 border-none focus:ring-2 focus:ring-blue-500 transition duration-300 text-black text-sm sm:text-base" placeholder="Search by product name..." value="{{ request('q') }}">
                <!-- Search button with black text -->
                <button class="absolute right-0 top-0 h-full px-4 bg-blue-500 text-black rounded-r-lg hover:bg-blue-600 transition duration-300">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>

        <!-- Responsive table wrapper -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <!-- Table header with black text -->
                <thead class="bg-gray-200 text-black">
                    <tr>
                        <th class="p-3 text-sm sm:text-base font-semibold">ID</th>
                        <th class="p-3 text-sm sm:text-base font-semibold">Product Name</th>
                        <th class="p-3 text-sm sm:text-base font-semibold">Product Image</th>
                        <th class="p-3 text-sm sm:text-base font-semibold">Stock Quantity</th>
                        <th class="p-3 text-sm sm:text-base font-semibold">Unit</th>
                        <th class="p-3 text-sm sm:text-base font-semibold">Actions</th>
                    </tr>
                </thead>
                <!-- Table body with black text -->
                <tbody>
                    @forelse ($stocks as $stock)
                        <tr class="odd:bg-gray-50 even:bg-white hover:bg-gray-100 transition-colors">
                            <td class="p-3 text-sm sm:text-base text-black">{{ $stock->id }}</td>
                            <td class="p-3 text-sm sm:text-base text-black">{{ $stock->product->name ?? 'N/A' }}</td>
                            <td class="p-3">
                                @if ($stock->product && $stock->product->image)
                                    <!-- Responsive image -->
                                    <img src="{{ asset('uploaded/products/' . $stock->product->image) }}" alt="{{ $stock->product->name }}" class="w-12 h-12 sm:w-16 sm:h-16 object-cover rounded">
                                @else
                                    <span class="text-black text-sm sm:text-base">No Image</span>
                                @endif
                            </td>
                            <td class="p-3 text-sm sm:text-base text-black">{{ number_format($stock->stock_quantity, 2) }}</td>
                            <td class="p-3 text-sm sm:text-base text-black">{{ $stock->stock_quantity_unit }}</td>
                            <td class="p-3">
                                <!-- Action buttons with black text -->
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.stock.show', $stock->id) }}" class="px-3 py-1 bg-gradient-to-r from-blue-400 to-blue-600 text-black rounded-lg hover:scale-105 transition-transform text-sm sm:text-base">View</a>
                                    <a href="{{ route('admin.stock.edit', $stock->id) }}" class="px-3 py-1 bg-gradient-to-r from-green-400 to-green-600 text-black rounded-lg hover:scale-105 transition-transform text-sm sm:text-base">Update</a>
                                    <form action="{{ route('admin.stock.destroy', $stock->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-gradient-to-r from-red-400 to-red-600 text-black rounded-lg hover:scale-105 transition-transform text-sm sm:text-base" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-3 text-center text-black text-sm sm:text-base">No stocks found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination with black text -->
        <div class="flex justify-center mt-6">
            {{ $stocks->appends(request()->query())->links('pagination::tailwind') }}
        </div>
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
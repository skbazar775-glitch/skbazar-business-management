@extends('layouts.admin')
@section('content')
<!-- Responsive container with padding -->
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Glassmorphism card -->
    <div class="bg-white/80 backdrop-blur-md shadow-lg rounded-2xl overflow-hidden">
        <!-- Header with gradient -->
        <div class="bg-gradient-to-r from-blue-400 to-indigo-600 px-6 py-4">
            <h1 class="text-2xl sm:text-3xl font-bold text-black">Supplier Purchase History</h1>
            <p class="text-black text-sm sm:text-base">View and manage all supplier purchase records</p>
        </div>

        <!-- Messages -->
        @if (session('success'))
            <!-- Success alert with black text and fade-in -->
            <div class="mx-6 mt-4 p-4 bg-green-100 text-black rounded-lg shadow-sm animate-fade-in">
                {{ session('success') }}
                <button type="button" class="float-right text-black hover:text-gray-700">✕</button>
            </div>
        @endif
        @if (session('error'))
            <!-- Error alert with black text and fade-in -->
            <div class="mx-6 mt-4 p-4 bg-red-100 text-black rounded-lg shadow-sm animate-fade-in">
                {{ session('error') }}
                <button type="button" class="float-right text-black hover:text-gray-700">✕</button>
            </div>
        @endif

        <!-- Table Card -->
        <div class="p-6">
            <!-- Search bar -->
            <div class="mb-4">
                <input type="text" id="searchInput" class="w-full max-w-lg p-3 rounded-lg bg-gradient-to-r from-gray-100 to-gray-200 border-none focus:ring-2 focus:ring-blue-500 transition duration-300 text-black text-sm sm:text-base" placeholder="Search by supplier or product...">
            </div>
            <!-- Responsive table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="purchaseHistoryTable">
                    <!-- Table header with sortable columns -->
                    <thead class="bg-gray-200 text-black">
                        <tr>
                            <th class="p-3 text-sm sm:text-base font-semibold cursor-pointer" data-sort="id">ID</th>
                            <th class="p-3 text-sm sm:text-base font-semibold cursor-pointer" data-sort="supplier">Supplier</th>
                            <th class="p-3 text-sm sm:text-base font-semibold cursor-pointer" data-sort="product">Product</th>
                            <th class="p-3 text-sm sm:text-base font-semibold cursor-pointer" data-sort="quantity">Quantity</th>
                            <th class="p-3 text-sm sm:text-base font-semibold cursor-pointer" data-sort="price">Price (Incl. GST)</th>
                            <th class="p-3 text-sm sm:text-base font-semibold cursor-pointer" data-sort="gst">GST %</th>
                            <th class="p-3 text-sm sm:text-base font-semibold cursor-pointer" data-sort="total">Total Price</th>
                            <th class="p-3 text-sm sm:text-base font-semibold cursor-pointer" data-sort="invoice">Invoice No</th>
                            <th class="p-3 text-sm sm:text-base font-semibold cursor-pointer" data-sort="date">Invoice Date</th>
                            <th class="p-3 text-sm sm:text-base font-semibold cursor-pointer" data-sort="transport">Transport Cost</th>
                            <th class="p-3 text-sm sm:text-base font-semibold cursor-pointer" data-sort="payment">Total Payment</th>
                            <th class="p-3 text-sm sm:text-base font-semibold cursor-pointer" data-sort="created">Purchased At</th>
                        </tr>
                    </thead>
                    <!-- Table body with zebra stripes -->
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($purchases as $purchase)
                            <tr class="odd:bg-gray-50 even:bg-white hover:bg-gray-100 transition-colors">
                                <td class="p-3 text-sm sm:text-base text-black">{{ $loop->iteration }}</td>
                                <td class="p-3 text-sm sm:text-base text-black">{{ $purchase->supplier->name ?? 'N/A' }}</td>
                                <td class="p-3 text-sm sm:text-base text-black">{{ $purchase->product->name ?? 'N/A' }}</td>
                                <td class="p-3 text-sm sm:text-base text-black">{{ $purchase->quantity }}</td>
                                <td class="p-3 text-sm sm:text-base text-black">{{ number_format($purchase->purchase_price_incl_gst, 2) }}</td>
                                <td class="p-3 text-sm sm:text-base text-black">{{ $purchase->gst_percent }}%</td>
                                <td class="p-3 text-sm sm:text-base text-black">{{ number_format($purchase->total_price, 2) }}</td>
                                <td class="p-3 text-sm sm:text-base text-black">{{ $purchase->invoice_no ?? 'N/A' }}</td>
                                <td class="p-3 text-sm sm:text-base text-black">{{ $purchase->invoice_date ? \Carbon\Carbon::parse($purchase->invoice_date)->format('d-m-Y') : 'N/A' }}</td>
                                <td class="p-3 text-sm sm:text-base text-black">{{ number_format($purchase->transportation_costs_incl ?? 0, 2) }}</td>
                                <td class="p-3 text-sm sm:text-base text-black">{{ number_format($purchase->total_payment ?? 0, 2) }}</td>
                                <td class="p-3 text-sm sm:text-base text-black">{{ \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination with Tailwind -->
            @if ($purchases->hasPages())
                <nav class="mt-6 flex items-center justify-between" aria-label="Pagination">
                    <div class="hidden sm:block">
                        <p class="text-sm text-black">
                            Showing
                            <span class="font-medium">{{ $purchases->firstItem() }}</span>
                            to
                            <span class="font-medium">{{ $purchases->lastItem() }}</span>
                            of
                            <span class="font-medium">{{ $purchases->total() }}</span>
                            results
                        </p>
                    </div>
                    <div class="flex space-x-2">
                        <!-- Previous Button -->
                        @if ($purchases->onFirstPage())
                            <span class="px-4 py-2 text-sm font-medium text-black bg-gray-200 rounded-full cursor-not-allowed">Previous</span>
                        @else
                            <a href="{{ $purchases->previousPageUrl() }}" class="px-4 py-2 text-sm font-medium text-black bg-gradient-to-r from-blue-400 to-blue-600 rounded-full hover:scale-105 transition-transform">Previous</a>
                        @endif
                        <!-- Page Numbers -->
                        @foreach ($purchases->getUrlRange(1, $purchases->lastPage()) as $page => $url)
                            @if ($page == $purchases->currentPage())
                                <span class="px-4 py-2 text-sm font-medium text-black bg-blue-400 rounded-full">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-4 py-2 text-sm font-medium text-black bg-gray-200 hover:bg-blue-400 rounded-full transition-colors">{{ $page }}</a>
                            @endif
                        @endforeach
                        <!-- Next Button -->
                        @if ($purchases->hasMorePages())
                            <a href="{{ $purchases->nextPageUrl() }}" class="px-4 py-2 text-sm font-medium text-black bg-gradient-to-r from-blue-400 to-blue-600 rounded-full hover:scale-105 transition-transform">Next</a>
                        @else
                            <span class="px-4 py-2 text-sm font-medium text-black bg-gray-200 rounded-full cursor-not-allowed">Next</span>
                        @endif
                    </div>
                </nav>
            @endif
        </div>

        <!-- Back Button -->
        <div class="px-6 py-4">
            <a href="{{ route('admin.buysupplierproducts.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-400 to-blue-600 text-black font-semibold rounded-lg hover:scale-105 transition-transform text-sm sm:text-base">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Purchase Form
            </a>
        </div>
    </div>
</div>
@endsection

@section('styles')
<!-- Tailwind CSS CDN -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<!-- Custom styles for animations -->
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

@section('scripts')
<!-- Vanilla JS for search and sort -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const table = document.getElementById('purchaseHistoryTable');
        const rows = table.querySelectorAll('tbody tr');
        const headers = table.querySelectorAll('th');
        const searchInput = document.getElementById('searchInput');
        
        // Search functionality
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            rows.forEach(row => {
                const supplier = row.cells[1].textContent.toLowerCase();
                const product = row.cells[2].textContent.toLowerCase();
                row.style.display = (supplier.includes(searchTerm) || product.includes(searchTerm)) ? '' : 'none';
            });
        });

        // Sort functionality
        headers.forEach(header => {
            header.addEventListener('click', function() {
                const sortKey = this.dataset.sort;
                const isNumeric = ['quantity', 'price', 'gst', 'total', 'transport', 'payment'].includes(sortKey);
                const isDate = ['date', 'created'].includes(sortKey);
                const order = this.dataset.order === 'asc' ? 'desc' : 'asc';
                this.dataset.order = order;

                const sortedRows = Array.from(rows).sort((a, b) => {
                    let aValue = a.cells[getColumnIndex(sortKey)].textContent;
                    let bValue = b.cells[getColumnIndex(sortKey)].textContent;

                    if (isNumeric) {
                        aValue = parseFloat(aValue.replace('%', '')) || 0;
                        bValue = parseFloat(bValue.replace('%', '')) || 0;
                    } else if (isDate) {
                        aValue = new Date(aValue.split('-').reverse().join('-')).getTime();
                        bValue = new Date(bValue.split('-').reverse().join('-')).getTime();
                    }

                    if (order === 'asc') {
                        return aValue > bValue ? 1 : -1;
                    }
                    return aValue < bValue ? 1 : -1;
                });

                table.querySelector('tbody').innerHTML = '';
                sortedRows.forEach(row => table.querySelector('tbody').appendChild(row));
            });
        });

        function getColumnIndex(key) {
            const keys = ['id', 'supplier', 'product', 'quantity', 'price', 'gst', 'total', 'invoice', 'date', 'transport', 'payment', 'created'];
            return keys.indexOf(key);
        }
    });
</script>
@endsection
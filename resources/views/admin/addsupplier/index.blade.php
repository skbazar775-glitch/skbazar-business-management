@extends('layouts.admin')

@section('content')
<!-- Responsive container with padding -->
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Glassmorphism card -->
    <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-lg overflow-hidden">
        <!-- Header with flexbox -->
        <div class="flex justify-between items-center p-5 border-b border-gray-100">
            <h2 class="text-2xl sm:text-3xl font-bold text-black">Suppliers</h2>
            <!-- Add new supplier button -->
            <a href="{{ route('admin.suppliers.create') }}"
               class="px-4 py-2 bg-gradient-to-r from-blue-400 to-blue-600 text-black rounded-lg hover:scale-105 transition-transform text-sm sm:text-base">
                Add New Supplier
            </a>
        </div>
        <!-- Search bar -->
        <div class="p-5 border-b border-gray-100">
            <div class="relative">
                <input type="text" id="supplierSearch" placeholder="Search by name or email..."
                       class="w-full max-w-lg p-3 pl-10 rounded-lg bg-gradient-to-r from-gray-100 to-gray-200 border-none focus:ring-2 focus:ring-blue-500 transition duration-300 text-black text-sm sm:text-base"
                       autocomplete="off">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
        <!-- Responsive table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="supplierTable">
                <thead class="bg-gray-200 text-black">
                    <tr>
                        <th class="p-3 text-sm sm:text-base font-semibold cursor-pointer" data-sort="name">Name</th>
                        <th class="p-3 text-sm sm:text-base font-semibold cursor-pointer" data-sort="email">Email</th>
                        <th class="p-3 text-sm sm:text-base font-semibold cursor-pointer" data-sort="contact">Contact</th>
                        <th class="p-3 text-sm sm:text-base font-semibold cursor-pointer" data-sort="location">Location</th>
                        <th class="p-3 text-sm sm:text-base font-semibold cursor-pointer" data-sort="balance">Balance</th>
                        <th class="p-3 text-sm sm:text-base font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="supplierList">
                    @foreach ($suppliers as $supplier)
                        <tr class="supplier-item odd:bg-gray-50 even:bg-white hover:bg-gray-100 transition-colors"
                            data-name="{{ strtolower($supplier->name) }}"
                            data-email="{{ strtolower($supplier->email ?? '') }}"
                            data-contact="{{ strtolower($supplier->contact_number ?? '') }}"
                            data-location="{{ strtolower($supplier->location ?? '') }}"
                            data-balance="{{ $supplierBalances[$supplier->id] }}">
                            <td class="p-3 text-sm sm:text-base text-black">{{ $supplier->name }}</td>
                            <td class="p-3 text-sm sm:text-base text-black">{{ $supplier->email ?? 'N/A' }}</td>
                            <td class="p-3 text-sm sm:text-base text-black">{{ $supplier->contact_number ?? 'N/A' }}</td>
                            <td class="p-3 text-sm sm:text-base text-black">{{ $supplier->location ?? 'N/A' }}</td>
                            <td class="p-3 text-sm sm:text-base text-black">
                                <span class="inline-block px-2 py-1 rounded-full {{ $supplierBalances[$supplier->id] >= 0 ? 'bg-green-200' : 'bg-red-200' }} text-black">
                                    ₹{{ number_format(abs($supplierBalances[$supplier->id]), 2) }} {{ $supplierBalances[$supplier->id] >= 0 ? 'Advance' : 'Due' }}
                                </span>
                            </td>
                            <td class="p-3 text-sm sm:text-base">
                                <div class="flex space-x-2">
                                    <!-- View button -->
                                    <a href="{{ route('admin.suppliers.account', $supplier) }}"
                                       class="px-3 py-1 bg-gradient-to-r from-blue-400 to-blue-600 text-black rounded-lg hover:scale-105 transition-transform">View</a>
                                    <!-- Edit button -->
                                    <a href="{{ route('admin.suppliers.edit', $supplier) }}"
                                       class="px-3 py-1 bg-gradient-to-r from-green-400 to-green-600 text-black rounded-lg hover:scale-105 transition-transform">Edit</a>
                                    <!-- Delete button -->
                                    <form action="{{ route('admin.suppliers.destroy', $supplier) }}"
                                          method="POST"
                                          class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete {{ $supplier->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1 bg-gradient-to-r from-red-400 to-red-600 text-black rounded-lg hover:scale-105 transition-transform">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="p-4 flex justify-center">
            {{ $suppliers->links('pagination::tailwind') }}
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
        const searchInput = document.getElementById('supplierSearch');
        const table = document.getElementById('supplierTable');
        const rows = table.querySelectorAll('.supplier-item');
        const headers = table.querySelectorAll('th[data-sort]');

        // Search functionality
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            rows.forEach(row => {
                const name = row.dataset.name;
                const email = row.dataset.email;
                row.style.display = (name.includes(searchTerm) || email.includes(searchTerm)) ? '' : 'none';
            });
        });

        // Sort functionality
        headers.forEach(header => {
            header.addEventListener('click', function() {
                const sortKey = this.dataset.sort;
                const isNumeric = ['balance'].includes(sortKey);
                const order = this.dataset.order === 'asc' ? 'desc' : 'asc';
                this.dataset.order = order;

                const sortedRows = Array.from(rows).sort((a, b) => {
                    let aValue = a.dataset[sortKey];
                    let bValue = b.dataset[sortKey];

                    if (isNumeric) {
                        aValue = parseFloat(aValue) || 0;
                        bValue = parseFloat(bValue) || 0;
                    } else {
                        aValue = aValue.toLowerCase();
                        bValue = bValue.toLowerCase();
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
    });
</script>
@endsection
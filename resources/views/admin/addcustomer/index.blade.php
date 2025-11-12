@extends('layouts.admin')

@section('content')
<!-- Responsive container with padding -->
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Glassmorphism card -->
    <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-lg p-6">
        <!-- Header with flexbox -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl sm:text-3xl font-bold text-black">Manage Customers</h1>
            <!-- Add new customer button -->
            <a href="{{ route('admin.customers.create') }}"
               class="px-4 py-2 bg-gradient-to-r from-blue-400 to-blue-600 text-black rounded-lg hover:scale-105 transition-transform text-sm sm:text-base">
                + Add New Customer
            </a>
        </div>

        <!-- Success alert -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 text-black rounded-lg shadow-sm animate-fade-in">
                {{ session('success') }}
                <button type="button" class="float-right text-black hover:text-gray-700">✕</button>
            </div>
        @endif

        @if ($customers->isEmpty())
            <!-- Empty state -->
            <div class="text-center py-8 text-black">
                <p class="text-lg sm:text-xl">No customers found.</p>
            </div>
        @else
            <!-- Search bar -->
            <div class="mb-4">
                <input type="text" id="searchInput" class="w-full max-w-lg p-3 rounded-lg bg-gradient-to-r from-gray-100 to-gray-200 border-none focus:ring-2 focus:ring-blue-500 transition duration-300 text-black text-sm sm:text-base" placeholder="Search by name or email...">
            </div>
            <!-- Responsive table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="customerTable">
                    <thead class="bg-gray-200 text-black">
                        <tr>
                            <th class="p-3 text-sm sm:text-base font-semibold">Name</th>
                            <th class="p-3 text-sm sm:text-base font-semibold">Email</th>
                            <th class="p-3 text-sm sm:text-base font-semibold">Phone</th>
                            <th class="p-3 text-sm sm:text-base font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($customers as $customer)
                            <tr class="odd:bg-gray-50 even:bg-white hover:bg-gray-100 transition-colors">
                                <td class="p-3 text-sm sm:text-base text-black">{{ $customer->name }}</td>
                                <td class="p-3 text-sm sm:text-base text-black">{{ $customer->email }}</td>
                                <td class="p-3 text-sm sm:text-base text-black">{{ $customer->customerAddress->phone ?? 'N/A' }}</td>

                                <td class="p-3 text-sm sm:text-base">
                                    <div class="flex space-x-2">
                                        <!-- Edit button -->
                                        <a href="{{ route('admin.customers.edit', $customer) }}"
                                           class="px-3 py-1 bg-gradient-to-r from-blue-400 to-blue-600 text-black rounded-lg hover:scale-105 transition-transform"
                                           aria-label="Edit customer {{ $customer->name }}">Edit</a>
                                        <!-- Delete button -->
                                        <form action="{{ route('admin.customers.destroy', $customer) }}"
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete {{ $customer->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-1 bg-gradient-to-r from-red-400 to-red-600 text-black rounded-lg hover:scale-105 transition-transform"
                                                    aria-label="Delete customer {{ $customer->name }}">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="mt-6 flex justify-center">
                {{ $customers->links('pagination::tailwind') }}
            </div>
        @endif
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
<!-- Vanilla JS for search -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const table = document.getElementById('customerTable');
        const rows = table.querySelectorAll('tbody tr');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            rows.forEach(row => {
                const name = row.cells[0].textContent.toLowerCase();
                const email = row.cells[1].textContent.toLowerCase();
                row.style.display = (name.includes(searchTerm) || email.includes(searchTerm)) ? '' : 'none';
            });
        });
    });
</script>
@endsection
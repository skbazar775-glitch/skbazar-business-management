@extends('layouts.admin')

@section('content')
<!-- Responsive container with max width -->
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 max-w-6xl">
    <!-- Glassmorphism card -->
    <div class="bg-white/80 backdrop-blur-md shadow-lg rounded-2xl overflow-hidden">
        <!-- Header with flexbox -->
        <div class="flex justify-between items-center p-6">
            <h1 class="text-2xl sm:text-3xl font-bold text-black">Invoice List</h1>
            <!-- Create new invoice button -->
            <a href="{{ route('admin.invoice.index') }}" class="px-4 py-2 bg-gradient-to-r from-blue-400 to-blue-600 text-black rounded-lg hover:scale-105 transition-transform text-sm sm:text-base">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create New Invoice
            </a>
        </div>

        <!-- Success alert -->
        @if (session('success'))
            <div class="mx-6 mb-6 p-4 bg-green-100 text-black rounded-lg shadow-sm animate-fade-in">
                {{ session('success') }}
                <button type="button" class="float-right text-black hover:text-gray-700">✕</button>
            </div>
        @endif

        <!-- Table card -->
        <div class="p-6">
            <!-- Search bar -->
            <div class="mb-4">
                <input type="text" id="searchInput" class="w-full max-w-lg p-3 rounded-lg bg-gradient-to-r from-gray-100 to-gray-200 border-none focus:ring-2 focus:ring-blue-500 transition duration-300 text-black text-sm sm:text-base" placeholder="Search by invoice number or customer...">
            </div>
            <!-- Responsive table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="invoiceTable">
                    <thead class="bg-gray-200 text-black">
                        <tr>
                            <th class="p-3 text-sm sm:text-base font-semibold">Invoice No</th>
                            <th class="p-3 text-sm sm:text-base font-semibold">Customer</th>
                            <th class="p-3 text-sm sm:text-base font-semibold">Date</th>
                            <th class="p-3 text-sm sm:text-base font-semibold">Total Amount</th>
                            <th class="p-3 text-sm sm:text-base font-semibold">Status</th>
                            <th class="p-3 text-sm sm:text-base font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($invoices as $invoice)
                            <tr class="odd:bg-gray-50 even:bg-white hover:bg-gray-100 transition-colors">
                                <td class="p-3 text-sm sm:text-base text-black">{{ $invoice->invoice_number }}</td>
                                <td class="p-3 text-sm sm:text-base text-black">{{ $invoice->customer_name }}</td>
                                <td class="p-3 text-sm sm:text-base text-black">{{ $invoice->created_at->format('d/m/Y') }}</td>
                                <td class="p-3 text-sm sm:text-base text-black">₹{{ number_format($invoice->final_amount, 2) }}</td>
                                <td class="p-3 text-sm sm:text-base text-black">
                                    <span class="inline-block px-2 py-1 rounded-full {{ $invoice->payment_terms == 2 ? 'bg-yellow-200' : 'bg-green-200' }} text-black">
                                        {{ ['Fully Advance', 'Half Advance', 'Due'][$invoice->payment_terms] }}
                                    </span>
                                </td>
                                <td class="p-3 text-sm sm:text-base">
                                    <div class="flex space-x-2">
                                        <!-- Download PDF button -->
                                        <a href="{{ route('admin.invoice.pdf', $invoice->id) }}" class="px-3 py-1 bg-gradient-to-r from-blue-400 to-blue-600 text-black rounded-lg hover:scale-105 transition-transform" title="Download PDF">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </a>
                                        <!-- Print button -->
                                        <button onclick="printInvoice({{ $invoice->id }})" class="px-3 py-1 bg-gradient-to-r from-gray-400 to-gray-600 text-black rounded-lg hover:scale-105 transition-transform" title="Print">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H7a2 2 0 00-2 2v4h12z" />
                                            </svg>
                                        </button>
                                        <!-- Edit button -->
                                        <a href="{{ route('admin.invoice.edit', $invoice->id) }}" class="px-3 py-1 bg-gradient-to-r from-green-400 to-green-600 text-black rounded-lg hover:scale-105 transition-transform" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15.828H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <!-- Delete button -->
                                        <form action="{{ route('admin.invoice.destroy', $invoice->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this invoice?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 bg-gradient-to-r from-red-400 to-red-600 text-black rounded-lg hover:scale-105 transition-transform" title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
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
                {{ $invoices->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>

<!-- Print invoice script -->
<script>
function printInvoice(id) {
    const url = '{{ route("admin.invoice.preview", ":id") }}'.replace(':id', id);
    const win = window.open(url, '_blank');
    win.addEventListener('load', function() {
        win.print();
    });
}
</script>
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
        const table = document.getElementById('invoiceTable');
        const rows = table.querySelectorAll('tbody tr');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            rows.forEach(row => {
                const invoiceNo = row.cells[0].textContent.toLowerCase();
                const customer = row.cells[1].textContent.toLowerCase();
                row.style.display = (invoiceNo.includes(searchTerm) || customer.includes(searchTerm)) ? '' : 'none';
            });
        });
    });
</script>
@endsection
@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col lg:flex-row gap-6 h-[calc(100vh-7.5rem)]">
            <!-- Left Sidebar: Customers List -->
            <div class="w-full lg:w-1/3 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-800">Customers</h2>
                    <div class="relative mt-4">
                        <input type="text" id="customerSearch" placeholder="Search customers..." 
                               class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm text-gray-700 placeholder-gray-400"
                               onkeyup="filterCustomers()" autocomplete="off">
                        <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
                <div class="overflow-y-auto h-[calc(100%-7rem)]" id="customerList">
                    @foreach ($customers as $user)
                        @php
                            $totalReceived = $user->skCredits->where('type', 'received')->sum('amount');
                            $totalDue = $user->skCredits->where('type', 'due')->sum('amount');
                            $balance = $totalReceived - $totalDue;
                        @endphp
                        <a href="{{ route('admin.customers.account', $user) }}"
                           class="flex items-center p-4 hover:bg-gray-50 transition-colors duration-150 border-b border-gray-50 customer-item {{ $customer->id === $user->id ? 'bg-blue-50 border-l-4 border-l-blue-500' : '' }}"
                           data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email ?? '') }}">
                            <div class="relative">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" alt="{{ $user->name }}" class="w-10 h-10 rounded-full mr-3">
                                @if($balance < 0)
                                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full">!</span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                                <div class="flex items-center justify-between">
                                    <p class="text-xs text-gray-500">Last active {{ $user->updated_at->setTimezone('Asia/Kolkata')->diffForHumans() }}</p>
                                    <p class="text-xs font-medium {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        ₹{{ number_format(abs($balance), 2) }} {{ $balance >= 0 ? 'Advance' : 'Due' }}
                                    </p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @endforeach
                </div>
                <div class="p-4">
                    {{ $customers->links() }}
                </div>
            </div>

            <!-- Right Section: Customer Account Details -->
            <div class="w-full lg:w-2/3 flex flex-col bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Customer Header -->
                <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="flex items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($customer->name) }}&background=random" alt="{{ $customer->name }}" class="w-12 h-12 rounded-full">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">{{ $customer->name }}</h2>
                            <div class="flex items-center gap-2">
                                <span class="text-xs px-2 py-1 rounded-full {{ $balanceAdvance >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $balanceAdvance >= 0 ? 'Advance' : 'Due' }}: ₹{{ number_format(abs($balanceAdvance), 2) }}
                                </span>
                                <span class="text-xs text-gray-500">Last activity: {{ $customer->updated_at->setTimezone('Asia/Kolkata')->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.customers.edit', $customer) }}" class="flex items-center gap-1 px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Profile
                        </a>
                        <button class="flex items-center gap-1 px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                            </svg>
                            More
                        </button>
                    </div>
                </div>

                <!-- Transaction History -->
                <div class="flex-1 p-5 overflow-y-auto bg-gray-50 space-y-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Transaction History</h3>
                        <div class="flex items-center gap-2">
                            <button class="px-3 py-1.5 text-xs font-medium bg-white border border-gray-200 rounded-lg hover:bg-gray-50">Filter</button>
                            <button class="px-3 py-1.5 text-xs font-medium bg-white border border-gray-200 rounded-lg hover:bg-gray-50">Export</button>
                        </div>
                    </div>

                    @forelse ($customer->skCredits->sortByDesc('date') as $credit)
                        <div class="flex items-start gap-3 p-4 bg-white rounded-lg border border-gray-100 shadow-xs">
                            <div class="p-2 rounded-full {{ $credit->type === 'received' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                @if($credit->type === 'received')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                    </svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                    </svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $credit->note ?? 'No description' }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            Transaction: {{ $credit->date ? $credit->date->setTimezone('Asia/Kolkata')->format('d M Y, h:i A') : 'Date not set' }}
                                        </p>
                                        {{-- <p class="text-xs text-gray-500 mt-1">
                                            Created: {{ $credit->created_at ? $credit->created_at->setTimezone('Asia/Kolkata')->format('d M Y, h:i A') : 'Not set' }}
                                        </p> --}}
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-medium {{ $credit->type === 'received' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $credit->type === 'received' ? '+' : '-' }}₹{{ number_format($credit->amount, 2) }}
                                        </p>
                                        <button onclick="openEditModal('{{ $credit->id }}', '{{ $credit->type }}', '{{ $credit->amount }}', '{{ $credit->note ?? '' }}', '{{ $credit->date ? $credit->date->setTimezone('Asia/Kolkata')->format('Y-m-d\TH:i') : '' }}')"
                                                class="text-sm text-blue-600 hover:text-blue-800">
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.customers.deleteCredit', [$customer, $credit]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this transaction?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:text-red-800">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No transactions</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new transaction.</p>
                        </div>
                    @endforelse
                </div>
<!-- Transaction Form -->
<div class="p-5 border-t border-gray-100 bg-white">
    <style>
        input[type="text"],
        input[type="number"],
        input[type="datetime-local"] {
            color: #000 !important;   /* Force black text */
        }

        input::placeholder {
            color: #9ca3af; /* Tailwind placeholder-gray-400 */
        }
    </style>

    <form action="{{ route('admin.customers.storeCredit', $customer) }}" method="POST" class="space-y-4">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="transaction_date" class="block text-sm font-medium text-gray-700 mb-1">Transaction Date & Time</label>
                <input type="datetime-local" name="transaction_date" id="transaction_date"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm 
                           focus:outline-none focus:ring-blue-500 focus:border-blue-500 
                           text-sm text-black placeholder-gray-400"
                    value="{{ old('transaction_date', Carbon\Carbon::now('Asia/Kolkata')->format('Y-m-d\TH:i')) }}"
                    max="{{ Carbon\Carbon::now('Asia/Kolkata')->format('Y-m-d\TH:i') }}">
                @error('transaction_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="note" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <input type="text" name="note" id="note" placeholder="Payment for order #1234" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm 
                              focus:outline-none focus:ring-blue-500 focus:border-blue-500 
                              text-sm text-black placeholder-gray-400"
                       value="{{ old('note') }}">
                @error('note')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount (₹)</label>
                <input type="number" name="amount" id="amount" step="0.01" placeholder="0.00" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm 
                              focus:outline-none focus:ring-blue-500 focus:border-blue-500 
                              text-sm text-black placeholder-gray-400"
                       value="{{ old('amount') }}">
                @error('amount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="flex-1">
                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Transaction Type</label>
                <div class="flex rounded-md shadow-sm">
                    <button
                        type="button"
                        data-type="received"
                        onclick="setTransactionType('received')"
                        class="flex-1 py-2 px-3 text-sm font-medium rounded-l-md border {{ old('type', 'received') === 'received' ? 'bg-green-100 border-green-300 text-green-700' : 'bg-white border-gray-300 text-gray-700' }}">
                        Received
                    </button>
                    <button
                        type="button"
                        data-type="due"
                        onclick="setTransactionType('due')"
                        class="flex-1 py-2 px-3 text-sm font-medium rounded-r-md border {{ old('type') === 'due' ? 'bg-red-100 border-red-300 text-red-700' : 'bg-white border-gray-300 text-gray-700' }}">
                        Sent
                    </button>
                    <input type="hidden" name="type" id="transactionType" value="{{ old('type', 'received') }}">
                </div>
                @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="self-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Record cssdss Transaction
                </button>
            </div>
        </div>
    </form>
</div>

            </div>
        </div>
    </div>
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold text-black mb-4">Edit Transaction</h3>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="credit_id" id="editCreditId">
            <div class="space-y-4">
                <div>
                    <label for="edit_transaction_date" class="block text-sm font-medium text-black mb-1">Transaction Date & Time</label>
                    <input type="datetime-local" name="transaction_date" id="edit_transaction_date"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm text-black"
                           max="{{ Carbon\Carbon::now('Asia/Kolkata')->format('Y-m-d\TH:i') }}">
                    @error('transaction_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="edit_note" class="block text-sm font-medium text-black mb-1">Description</label>
                    <input type="text" name="note" id="edit_note" placeholder="Payment for order #1234"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm text-black">
                    @error('note')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="edit_amount" class="block text-sm font-medium text-black mb-1">Amount (₹)</label>
                    <input type="number" name="amount" id="edit_amount" step="0.01" placeholder="0.00" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm text-black">
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="edit_type" class="block text-sm font-medium text-black mb-1">Transaction Type</label>
                    <div class="flex rounded-md shadow-sm">
                        <button type="button" data-type="received" onclick="setEditTransactionType('received')"
                                class="flex-1 py-2 px-3 text-sm font-medium rounded-l-md border bg-white border-gray-300 text-black">
                            Received
                        </button>
                        <button type="button" data-type="due" onclick="setEditTransactionType('due')"
                                class="flex-1 py-2 px-3 text-sm font-medium rounded-r-md border bg-white border-gray-300 text-black">
                            Sent
                        </button>
                        <input type="hidden" name="type" id="edit_transactionType" value="received">
                    </div>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-200 rounded-md text-sm font-medium text-black hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 rounded-md text-sm font-medium text-white hover:bg-blue-700">Update Transaction</button>
                </div>
            </div>
        </form>
    </div>
</div>

    <script>
        function setTransactionType(type) {
            document.getElementById('transactionType').value = type;
            const buttons = document.querySelectorAll('[data-type]');
            buttons.forEach(button => {
                if (button.dataset.type === type) {
                    button.classList.add(type === 'received' ? 'bg-green-100' : 'bg-red-100', type === 'received' ? 'border-green-300' : 'border-red-300', type === 'received' ? 'text-green-700' : 'text-red-700');
                    button.classList.remove('bg-white', 'border-gray-300', 'text-gray-700');
                } else {
                    button.classList.add('bg-white', 'border-gray-300', 'text-gray-700');
                    button.classList.remove('bg-green-100', 'border-green-300', 'text-green-700', 'bg-red-100', 'border-red-300', 'text-red-700');
                }
            });
        }

        function setEditTransactionType(type) {
            document.getElementById('edit_transactionType').value = type;
            const buttons = document.querySelectorAll('#editForm [data-type]');
            buttons.forEach(button => {
                if (button.dataset.type === type) {
                    button.classList.add(type === 'received' ? 'bg-green-100' : 'bg-red-100', type === 'received' ? 'border-green-300' : 'border-red-300', type === 'received' ? 'text-green-700' : 'text-red-700');
                    button.classList.remove('bg-white', 'border-gray-300', 'text-gray-700');
                } else {
                    button.classList.add('bg-white', 'border-gray-300', 'text-gray-700');
                    button.classList.remove('bg-green-100', 'border-green-300', 'text-green-700', 'bg-red-100', 'border-red-300', 'text-red-700');
                }
            });
        }

        function openEditModal(creditId, type, amount, note, date) {
            const form = document.getElementById('editForm');
            form.action = '{{ route('admin.customers.updateCredit', [$customer, ":creditId"]) }}'.replace(':creditId', creditId);
            document.getElementById('editCreditId').value = creditId;
            document.getElementById('edit_transactionType').value = type;
            document.getElementById('edit_amount').value = amount;
            document.getElementById('edit_note').value = note;
            document.getElementById('edit_transaction_date').value = date;
            setEditTransactionType(type);
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editForm').reset();
            setEditTransactionType('received');
        }

        // Client-side search functionality
        function filterCustomers() {
            const searchInput = document.getElementById('customerSearch').value.toLowerCase();
            const customerItems = document.querySelectorAll('.customer-item');

            customerItems.forEach(item => {
                const name = item.dataset.name;
                const email = item.dataset.email;
                if (name.includes(searchInput) || email.includes(searchInput)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Server-side search with AJAX (commented out to avoid conflicts)
        /*
        let debounceTimeout;
        document.getElementById('customerSearch').addEventListener('input', function() {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(() => {
                const query = this.value;
                fetch(`{{ route('admin.customers.search') }}?q=${encodeURIComponent(query)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const customerList = document.getElementById('customerList');
                    customerList.innerHTML = '';
                    data.customers.forEach(user => {
                        const balance = user.skCredits.reduce((acc, credit) => {
                            return credit.type === 'received' ? acc + credit.amount : acc - credit.amount;
                        }, 0);
                        const isActive = {{ $customer->id }} === user.id ? 'bg-blue-50 border-l-4 border-l-blue-500' : '';
                        const item = `
                            <a href="{{ route('admin.customers.account', ':userId') }}".replace(':userId', user.id)
                               class="flex items-center p-4 hover:bg-gray-50 transition-colors duration-150 border-b border-gray-50 customer-item ${isActive}"
                               data-name="${user.name.toLowerCase()}" data-email="${user.email ? user.email.toLowerCase() : ''}">
                                <div class="relative">
                                    <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=random" alt="${user.name}" class="w-10 h-10 rounded-full mr-3">
                                    ${balance < 0 ? '<span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full">!</span>' : ''}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">${user.name}</p>
                                    <div class="flex items-center justify-between">
                                        <p class="text-xs text-gray-500">Last active ${new Date(user.updated_at).toLocaleString('en-IN', { timeZone: 'Asia/Kolkata' })}</p>
                                        <p class="text-xs font-medium ${balance >= 0 ? 'text-green-600' : 'text-red-600'}">
                                            ₹${Math.abs(balance).toFixed(2)} ${balance >= 0 ? 'Advance' : 'Due'}
                                        </p>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        `;
                        customerList.innerHTML += item;
                    });
                })
                .catch(error => console.error('Error fetching customers:', error));
            }, 300);
        });
        */
    </script>
@endsection
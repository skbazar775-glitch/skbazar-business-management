<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Total Sales Card -->
    <div class="bg-gradient-to-br from-blue-50 to-white p-4 rounded-xl shadow-sm border border-blue-100 hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 group flex flex-col">
        <div class="flex justify-between items-start flex-grow">
            <div>
                <p class="text-[0.65rem] font-semibold text-blue-600 uppercase tracking-wider">Total Sales</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">₹{{ number_format($totalSales, 2) }}</h3>
                <div class="flex space-x-3 mt-3">
                    <div>
                        <p class="text-[0.65rem] text-blue-500">Orders</p>
                        <p class="text-[0.75rem] font-medium text-blue-700">₹{{ number_format($orderSales, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-[0.65rem] text-blue-500">Invoices</p>
                        <p class="text-[0.75rem] font-medium text-blue-700">₹{{ number_format($invoiceSales, 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="p-2 rounded-lg bg-white shadow-sm group-hover:shadow-md transition-shadow">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <div class="mt-3 pt-3 border-t border-blue-50 flex justify-between items-center">
            <p class="text-[0.65rem] text-blue-400">Updated just now</p>
            <button class="text-[0.65rem] font-medium text-blue-600 hover:text-blue-800 transition-colors flex items-center">
                Details <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Pending Orders Card -->
    <div class="bg-gradient-to-br from-orange-50 to-white p-4 rounded-xl shadow-sm border border-orange-100 hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 group flex flex-col">
        <div class="flex justify-between items-start flex-grow">
            <div>
                <p class="text-[0.65rem] font-semibold text-orange-600 uppercase tracking-wider">Pending Orders</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $pendingOrders }}</h3>
                <div class="flex space-x-3 mt-3">
                    <div>
                        <p class="text-[0.65rem] text-orange-500">Pending</p>
                        <p class="text-[0.75rem] font-medium text-orange-700">{{ $pendingOrders }}</p>
                    </div>
                    <div>
                        <p class="text-[0.65rem] text-orange-500">Confirmed</p>
                        <p class="text-[0.75rem] font-medium text-orange-700">{{ $confirmedOrders }}</p>
                    </div>
                </div>
            </div>
            <div class="p-2 rounded-lg bg-white shadow-sm group-hover:shadow-md transition-shadow">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
        </div>
        <div class="mt-3 pt-3 border-t border-orange-50">
            <a href="{{ route('admin.orders.index') }}" class="w-full text-[0.65rem] font-medium bg-green-600 text-white px-3 py-1.5 rounded-md hover:bg-green-700 transition-colors flex items-center justify-center group-hover:shadow-sm">
                <span>View all orders</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>
    </div>

    <!-- Service Bookings Card -->
    <div class="bg-gradient-to-br from-green-50 to-white p-4 rounded-xl shadow-sm border border-green-100 hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 group flex flex-col">
        <div class="flex justify-between items-start flex-grow">
            <div>
                <p class="text-[0.65rem] font-semibold text-green-600 uppercase tracking-wider">Service Bookings</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $totalServices }}</h3>
                <div class="flex space-x-3 mt-3">
                    <div>
                        <p class="text-[0.65rem] text-green-500">Pending</p>
                        <p class="text-[0.75rem] font-medium text-green-700">{{ $pendingServices }}</p>
                    </div>
                    <div>
                        <p class="text-[0.65rem] text-green-500">Confirmed</p>
                        <p class="text-[0.75rem] font-medium text-green-700">{{ $confirmedServices }}</p>
                    </div>
                </div>
            </div>
            <div class="p-2 rounded-lg bg-white shadow-sm group-hover:shadow-md transition-shadow">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
        </div>
        <div class="mt-3 pt-3 border-t border-green-50">
            <a href="{{ route('admin.bookedservices.index') }}" class="w-full text-[0.65rem] font-medium bg-green-600 text-white px-3 py-1.5 rounded-md hover:bg-green-700 transition-colors flex items-center justify-center group-hover:shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                All Booked Services
            </a>
        </div>
    </div>

    <!-- Low Stock Items Card -->
    <div class="bg-gradient-to-br from-red-50 to-white p-4 rounded-xl shadow-sm border border-red-100 hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 group flex flex-col">
        <div class="flex justify-between items-start flex-grow">
            <div>
                <p class="text-[0.65rem] font-semibold text-red-600 uppercase tracking-wider">Low Stock Items</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $lowStockItems->count() }}</h3>
                <div class="mt-2 space-y-1.5 max-h-16 overflow-y-auto pr-2">
                    @foreach ($lowStockItems as $item)
                        <div class="flex justify-between items-center text-[0.65rem]">
                            <span class="text-gray-700 truncate mr-2">{{ $item->name }}</span>
                            <span class="font-medium text-red-600 whitespace-nowrap">{{ $item->stock_quantity }} units</span>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="p-2 rounded-lg bg-white shadow-sm group-hover:shadow-md transition-shadow animate-pulse">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
        </div>
        <div class="mt-3 pt-3 border-t border-red-50">
            <a href="{{ route('admin.buysupplierproducts.index') }}" class="w-full text-[0.65rem] font-medium bg-red-600 text-white px-3 py-1.5 rounded-md hover:bg-red-700 transition-colors flex items-center justify-center group-hover:shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                Reorder Now
            </a>
        </div>
    </div>
</div>
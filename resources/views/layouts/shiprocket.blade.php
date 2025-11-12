<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shiprocket Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-SOMEHASHHERE" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">
    <!-- Main Container -->
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64 bg-white border-r border-gray-200">
                <!-- Logo -->
                <div class="h-16 flex items-center px-4 border-b border-gray-200">
                    <img src="https://www.shiprocket.in/wp-content/themes/shiprocket/assets/images/logo.svg" alt="Shiprocket" class="h-8">
                </div>
                
                <!-- Navigation -->
                <nav class="flex-1 px-2 py-4 space-y-1">
                    <a href="{{ route('shiprocket.dashboard') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ request()->is('dashboard') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('shiprocket.orders.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ request()->routeIs('shiprocket.orders.index') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fas fa-box mr-3"></i>
                        Orders
                    </a>
                    <a href="{{ route('shiprocket.ordercreate') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ request()->routeIs('shiprocket.order-create.index') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fas fa-plus-circle mr-3"></i>
                        Order Create
                    </a>
                    <a href="{{ route('shiprocket.shiped') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ request()->routeIs('shiprocket.schedule.index') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fas fa-calendar-alt mr-3"></i>
                        Pickup & Manifest
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ request()->routeIs('shiprocket.label-download.index') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fas fa-download mr-3"></i>
                        Label Download
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ request()->routeIs('shiprocket.track.index') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fas fa-map-marker-alt mr-3"></i>
                        Track
                    </a>
                </nav>
                
                <!-- User Profile -->
                <div class="p-4 border-t border-gray-200">
                    <div class="flex items-center">
                        <img class="w-10 h-10 rounded-full" src="https://randomuser.me/api/portraits/men/32.jpg" alt="User">
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-700">John Doe</p>
                            <p class="text-xs font-medium text-gray-500">john@example.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-4">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
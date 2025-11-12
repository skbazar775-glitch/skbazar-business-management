<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Staff Dashboard for SkBazar - Manage inventory, orders, and attendance">
    <meta name="keywords" content="SkBazar, staff dashboard, inventory, orders, attendance">
    <meta name="author" content="SkBazar">
    <title>{{ $title ?? 'Staff Dashboard' }} - SkBazar</title>
    <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    /* Tailwind CSS is used for responsive design */
@tailwind base;
@tailwind components;
@tailwind utilities;

/* Additional custom styles */
.table-auto th, .table-auto td {
    @apply px-4 py-2;
}

@media (max-width: 640px) {
    .table-auto {
        @apply text-xs;
    }
    
    .table-auto th, .table-auto td {
        @apply px-2 py-1;
    }
}
    .glassmorphism {
        background: rgba(255, 245, 230, 0.2); /* Cream tint for warmth */
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 127, 127, 0.3); /* Coral border */
        box-shadow: 0 8px 32px rgba(0, 128, 128, 0.2); /* Teal shadow */
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .glassmorphism:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0, 128, 128, 0.3);
    }

    .sidebar-scroll {
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: rgba(255, 99, 71, 0.5) transparent; /* Tomato red scrollbar */
    }

    .sidebar-scroll::-webkit-scrollbar {
        width: 8px;
    }
    .sidebar-scroll::-webkit-scrollbar-track {
        background: transparent;
    }
    .sidebar-scroll::-webkit-scrollbar-thumb {
        background: rgba(255, 99, 71, 0.5);
        border-radius: 4px;
        transition: background 0.3s ease;
    }
    .sidebar-scroll::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 99, 71, 0.8);
    }

    .main-scroll {
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: rgba(0, 128, 128, 0.4) transparent; /* Teal scrollbar */
    }

    .main-scroll::-webkit-scrollbar {
        width: 8px;
    }
    .main-scroll::-webkit-scrollbar-track {
        background: transparent;
    }
    .main-scroll::-webkit-scrollbar-thumb {
        background: rgba(0, 128, 128, 0.4);
        border-radius: 4px;
        transition: background 0.3s ease;
    }
    .main-scroll::-webkit-scrollbar-thumb:hover {
        background: rgba(0, 128, 128, 0.7);
    }

    .submenu {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s ease-in-out, opacity 0.4s ease-in-out;
        opacity: 0;
    }

    .submenu.active {
        max-height: 300px; /* Adjusted for smoother animation */
        opacity: 1;
    }

    .menu-item {
        transition: background 0.3s ease, transform 0.2s ease;
    }
    .menu-item:hover {
        transform: translateX(5px);
    }

    .submenu-item {
        transition: background 0.3s ease, transform 0.2s ease;
    }
    .submenu-item:hover {
        transform: translateX(3px);
    }
</style>
</head>
<body class="bg-gradient-to-br from-teal-900 to-coral-900 font-sans min-h-screen text-black">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 glassmorphism flex-shrink-0 hidden md:block sidebar-scroll h-screen sticky top-0">
            <div class="p-4">
                <h1 class="text-2xl font-bold text-black">SkBazar Staff</h1>
            </div>
            <nav class="mt-4 pb-4">
                <ul>
                    <!-- Dashboard -->
                    <li class="mb-2">
                        <a href="{{ route('staff.dashboard') }}" class="menu-item block px-4 py-2 hover:bg-coral-200 hover:bg-opacity-30 rounded-lg mx-2 text-black">Dashboard</a>
                    </li>
                      <li class="mb-2">
                        <a href="{{ route('staff.assignedservice.index') }}" class="menu-item block px-4 py-2 hover:bg-coral-200 hover:bg-opacity-30 rounded-lg mx-2 text-black">Assigned Services </a>
                    </li>
                     <li class="mb-2">
                        <a href="{{ route('staff.attendance.index') }}" class="menu-item block px-4 py-2 hover:bg-coral-200 hover:bg-opacity-30 rounded-lg mx-2 text-black">Mark Attendance</a>
                    </li>                                     <!-- Inventory -->
                    <li class="mb-2">
                        <button class="submenu-toggle block w-full text-left px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-coral-200 hover:bg-opacity-30 rounded-lg mx-2 flex justify-between items-center" aria-expanded="false" aria-controls="submenu-inventory">
                            Inventory
                            <svg class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <ul class="submenu pl-4" id="submenu-inventory">
                            <li><a href="#" class="submenu-item block px-4 py-2 hover:bg-coral-100 hover:bg-opacity-20 rounded-lg text-black">Stock Tracking</a></li>
                            <li><a href="#" class="submenu-item block px-4 py-2 hover:bg-coral-100 hover:bg-opacity-20 rounded-lg text-black">View Products</a></li>
                        </ul>
                    </li>
                    <!-- Orders -->
                    <li class="mb-2">
                        <button class="submenu-toggle block w-full text-left px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-coral-200 hover:bg-opacity-30 rounded-lg mx-2 flex justify-between items-center" aria-expanded="false" aria-controls="submenu-orders">
                            Orders
                            <svg class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <ul class="submenu pl-4" id="submenu-orders">
                            <li><a href="#" class="submenu-item block px-4 py-2 hover:bg-coral-100 hover:bg-opacity-20 rounded-lg text-black">Order List</a></li>
                            <li><a href="#" class="submenu-item block px-4 py-2 hover:bg-coral-100 hover:bg-opacity-20 rounded-lg text-black">Booked Services</a></li>
                        </ul>
                    </li>
                    <!-- Attendance -->
                    <li class="mb-2">
                        <button class="submenu-toggle block w-full text-left px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-coral-200 hover:bg-opacity-30 rounded-lg mx-2 flex justify-between items-center" aria-expanded="false" aria-controls  ="submenu-attendance">
                            Attendance
                            <svg class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <ul class="submenu pl-4" id="submenu-attendance">
                            <li><a href="#" class="submenu-item block px-4 py-2 hover:bg-coral-100 hover:bg-opacity-20 rounded-lg text-black">Mark Attendance</a></li>
                            <li><a href="#" class="submenu-item block px-4 py-2 hover:bg-coral-100 hover:bg-opacity-20 rounded-lg text-black">Attendance Summary</a></li>
                        </ul>
                    </li>
                    <!-- Logout -->
                    <li class="mb-2">
                        <form method="POST" action="#" id="logout-form">
                            @csrf
                            <button type="submit" class="menu-item block w-full text-left px-4 py-2 hover:bg-red-600 hover:bg-opacity-50 rounded-lg mx-2 text-black" onclick="confirmLogout(event)">Logout</button>
                        </form>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Mobile Menu Toggle -->
        <div class="md:hidden glassmorphism text-black p-4 sticky top-0 z-40">
            <button id="menu-toggle" class="text-black focus:outline-none" aria-label="Open mobile menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </div>

        <!-- Mobile Sidebar -->
        <div id="mobile-menu" class="hidden fixed inset-0 glassmorphism text-black z-50 md:hidden sidebar-scroll">
            <div class="p-4 flex justify-between items-center sticky top-0 glassmorphism">
                <h1 class="text-2xl font-bold text-black">SkBazar Staff</h1>
                <button id="close-menu" class="text-black focus:outline-none" aria-label="Close mobile menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <nav class="mt-4 pb-4">
                <ul>
                    <!-- Dashboard -->
                    <li class="mb-2">
                        <a href="#" class="menu-item block px-4 py-2 hover:bg-coral-200 hover:bg-opacity-30 rounded-lg mx-2 text-black">Dashboard</a>
                    </li>
                    <!-- Inventory -->
                    <li class="mb-2">
                        <button class="submenu-toggle block w-full text-left px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-coral-200 hover:bg-opacity-30 rounded-lg mx-2 flex justify-between items-center" aria-expanded="false" aria-controls="mobile-submenu-inventory">
                            Inventory
                            <svg class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <ul class="submenu pl-4" id="mobile-submenu-inventory">
                            <li><a href="#" class="submenu-item block px-4 py-2 hover:bg-coral-100 hover:bg-opacity-20 rounded-lg text-black">Stock Tracking</a></li>
                            <li><a href="#" class="submenu-item block px-4 py-2 hover:bg-coral-100 hover:bg-opacity-20 rounded-lg text-black">View Products</a></li>
                        </ul>
                    </li>
                    <!-- Orders -->
                    <li class="mb-2">
                        <button class="submenu-toggle block w-full text-left px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-coral-200 hover:bg-opacity-30 rounded-lg mx-2 flex justify-between items-center" aria-expanded="false" aria-controls="mobile-submenu-orders">
                            Orders
                            <svg class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <ul class="submenu pl-4" id="mobile-submenu-orders">
                            <li><a href="#" class="submenu-item block px-4 py-2 hover:bg-coral-100 hover:bg-opacity-20 rounded-lg text-black">Order List</a></li>
                            <li><a href="#" class="submenu-item block px-4 py-2 hover:bg-coral-100 hover:bg-opacity-20 rounded-lg text-black">Booked Services</a></li>
                        </ul>
                    </li>
                    <!-- Attendance -->
                    <li class="mb-2">
                        <button class="submenu-toggle block w-full text-left px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-coral-200 hover:bg-opacity-30 rounded-lg mx-2 flex justify-between items-center" aria-expanded="false" aria-controls="mobile-submenu-attendance">
                            Attendance
                            <svg class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <ul class="submenu pl-4" id="mobile-submenu-attendance">
                            <li><a href="#" class="submenu-item block px-4 py-2 hover:bg-coral-100 hover:bg-opacity-20 rounded-lg text-black">Mark Attendance</a></li>
                            <li><a href="#" class="submenu-item block px-4 py-2 hover:bg-coral-100 hover:bg-opacity-20 rounded-lg text-black">Attendance Summary</a></li>
                        </ul>
                    </li>
                    <!-- Logout -->
                    <li class="mb-2">
                        <form method="POST" action="#" id="mobile-logout-form">
                            @csrf
                            <button type="submit" class="menu-item block w-full text-left px-4 py-2 hover:bg-red-600 hover:bg-opacity-50 rounded-lg mx-2 text-black" onclick="confirmLogout(event)">Logout</button>
                        </form>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <main class="flex-1 p-6 main-scroll">
            <div class="glassmorphism p-6 rounded-xl">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <!-- JavaScript for Mobile Menu and Submenu Toggle -->
    <script>
        const menuToggle = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        const closeMenu = document.getElementById('close-menu');
        const submenuToggles = document.querySelectorAll('.submenu-toggle');

        // Mobile menu toggle
        menuToggle.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            mobileMenu.style.transition = 'opacity 0.3s ease-in-out';
            mobileMenu.style.opacity = mobileMenu.classList.contains('hidden') ? '0' : '1';
        });

        closeMenu.addEventListener('click', () => {
            mobileMenu.style.opacity = '0';
            setTimeout(() => mobileMenu.classList.add('hidden'), 300);
        });

        // Submenu toggle
        submenuToggles.forEach(toggle => {
            toggle.addEventListener('click', () => {
                const submenu = toggle.nextElementSibling;
                const arrow = toggle.querySelector('svg');
                const isActive = submenu.classList.toggle('active');
                arrow.classList.toggle('rotate-180');
                toggle.setAttribute('aria-expanded', isActive ? 'true' : 'false');

                // Close other open submenus
                submenuToggles.forEach(otherToggle => {
                    if (otherToggle !== toggle) {
                        const otherSubmenu = otherToggle.nextElementSibling;
                        const otherArrow = otherToggle.querySelector('svg');
                        otherSubmenu.classList.remove('active');
                        otherArrow.classList.remove('rotate-180');
                        otherToggle.setAttribute('aria-expanded', 'false');
                    }
                });
            });

            // Keyboard navigation for submenus
            toggle.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    toggle.click();
                }
            });
        });

        // SweetAlert2 for logout confirmation
        function confirmLogout(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to log out?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, log out',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#FF6347',
                cancelButtonColor: '#008080'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = event.target.closest('form');
                    form.submit();
                }
            });
        }
    </script>
    @yield('scripts')
</body>
</html>
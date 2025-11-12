<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admin Dashboard for SkBazar - Manage e-commerce, inventory, payroll, and more">
    <meta name="keywords" content="SkBazar, admin dashboard, e-commerce, inventory, payroll, service booking">
    <meta name="author" content="SkBazar">
    <title>{{ $title ?? 'Admin Dashboard' }} - SkBazar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" href="{{ asset('logo/fevicon2.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@2.51.6/dist/full.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #001a33 0%, #002b66 100%);
            font-family: 'Inter', sans-serif;
        }

        .glassmorphism {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            border-radius: 12px;
        }

        .sidebar-scroll {
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
        }

        .sidebar-scroll::-webkit-scrollbar {
            width: 8px;
        }
        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 4px;
        }

        .main-scroll {
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
        }

        .main-scroll::-webkit-scrollbar {
            width: 8px;
        }
        .main-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .main-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }

        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease-in-out, opacity 0.3s ease-in-out;
            opacity: 0;
        }

        .submenu.active {
            max-height: 600px;
            opacity: 1;
        }

        .nav-item {
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .nav-item:hover {
            background-color: rgba(255, 255, 255, 0.15);
            transform: translateX(5px);
        }

        .submenu a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(3px);
        }

        .btn-logout:hover {
            background-color: rgba(239, 68, 68, 0.9);
            transform: scale(1.05);
        }
    </style>
</head>
<body class="min-h-screen text-white">
    <div class="flex min-h-screen">
        <!-- Sidebar Include -->
    @include('layouts.sidebar')

        <!-- Main Content -->
        <main class="flex-1 p-8 main-scroll">
            <div class="glassmorphism p-8 rounded-2xl">
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

    <!-- JavaScript for Mobile Menu and Submenu Toggle -->
    <script>
        const menuToggle = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        const closeMenu = document.getElementById('close-menu');
        const submenuToggles = document.querySelectorAll('.submenu-toggle');

        menuToggle.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        closeMenu.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
        });

        submenuToggles.forEach(toggle => {
            toggle.addEventListener('click', () => {
                const submenu = toggle.nextElementSibling;
                const arrow = toggle.querySelector('svg');
                
                submenu.classList.toggle('active');
                arrow.classList.toggle('rotate-180');
                
                submenuToggles.forEach(otherToggle => {
                    if (otherToggle !== toggle) {
                        const otherSubmenu = otherToggle.nextElementSibling;
                        const otherArrow = otherToggle.querySelector('svg');
                        otherSubmenu.classList.remove('active');
                        otherArrow.classList.remove('rotate-180');
                    }
                });
            });
        });
    </script>
    @yield('scripts')
</body>
</html>

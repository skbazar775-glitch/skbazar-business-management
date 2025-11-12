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
        <!-- Sidebar -->
        <aside class="w-72 glassmorphism text-white flex-shrink-0 hidden md:block sidebar-scroll h-screen sticky top-0">
            <div class="flex flex-col items-center py-6">
                <img src="{{ asset('logo/logo.png') }}" alt="SkBazar Logo" class="h-[70px] w-auto mb-3">
                <h1 class="text-2xl font-bold text-white">SkBazar Admin</h1>
            </div>

            <nav class="mt-4 pb-4 px-2">
                <ul>
                    <li class="mb-2 nav-item rounded-lg">
                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 text-lg font-medium flex items-center">
                            <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
                        </a>
                    </li>
                    <li class="mb-2 nav-item">
                        <button class="submenu-toggle block w-full text-left px-4 py-3 text-lg font-medium flex items-center justify-between">
                            <span><i class="fas fa-warehouse mr-3"></i> Inventory</span>
                            <i class="fas fa-chevron-down w-4 h-4 transform transition-transform"></i>
                        </button>
                        <ul class="submenu pl-6">
    <li><a href="{{ route('admin.products.create') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-plus-circle mr-2"></i> Add Product</a></li>
    <li><a href="{{ route('admin.products.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-box-open mr-2"></i> All Product List</a></li>
    <li><a href="{{ route('admin.buysupplierproducts.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-truck-loading mr-2"></i> Purchase Entry</a></li>
    <li><a href="{{ route('admin.buysupplierproducts.history') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-history mr-2"></i> Purchase History</a></li>
    <li><a href="{{ route('admin.category.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-tags mr-2"></i> Add Category</a></li>
    <li><a href="{{ route('admin.stock.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-boxes mr-2"></i> Stock Update</a></li>
</ul>

                    </li>
                    <li class="mb-2 nav-item">
                        <button class="submenu-toggle block w-full text-left px-4 py-3 text-lg font-medium flex items-center justify-between">
                            <span><i class="fas fa-file-invoice-dollar mr-3"></i> Billing</span>
                            <i class="fas fa-chevron-down w-4 h-4 transform transition-transform"></i>
                        </button>
                        <ul class="submenu pl-6">
                            <li>
                                <a href="{{ route('admin.invoice.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg flex items-center justify-between">
                                    <span><i class="fas fa-file-invoice mr-2"></i> Generate Invoices</span>
                                    <span class="ml-2 text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full badge">GST</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.invoice.withoutgst') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg flex items-center justify-between">
                                    <span><i class="fas fa-file-invoice mr-2"></i> Generate Invoices</span>
                                    <span class="ml-2 text-xs bg-red-100 text-red-800 px-2 py-0.5 rounded-full badge">No GST</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.invoice.list') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg">
                                    <i class="fas fa-list-alt mr-2"></i> All Invoices
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.invoice.hsn.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg">
                                    <i class="fas fa-barcode mr-2"></i> Add HSN Code
                                </a>
                            </li>
                        </ul>
                    </li>
               
                    <li class="mb-2 nav-item">
                        <button class="submenu-toggle block w-full text-left px-4 py-3 text-lg font-medium flex items-center justify-between">
                            <span><i class="fas fa-user-shield mr-3"></i> Customer</span>
                            <i class="fas fa-chevron-down w-4 h-4 transform transition-transform"></i>
                        </button>
                        <ul class="submenu pl-6">
                           
                            <li><a href="{{ route('admin.customers.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-user mr-2"></i> All Customer</a></li>
                            @php
                            $customer = 13;
                            @endphp
                            <li><a href="{{ route('admin.customers.account', $customer) }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-user-check mr-2"></i> Customer Account</a></li>
                             <li><a href="{{ route('admin.customers.create') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-tachometer-alt mr-3"></i> Add Customer</a></li>
                        </ul>
                    </li>
                    
                         <li class="mb-2 nav-item">
                        <button class="submenu-toggle block w-full text-left px-4 py-3 text-lg font-medium flex items-center justify-between">
                            <span><i class="fas fa-user-shield mr-3"></i> Supplier</span>
                            <i class="fas fa-chevron-down w-4 h-4 transform transition-transform"></i>
                        </button>
                        <ul class="submenu pl-6">
                            
                            <li><a href="{{ route('admin.suppliers.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-truck mr-2"></i> All Supplier</a></li>
                            @php
                            $supplier = 3;
                            @endphp
                            <li><a href="{{ route('admin.suppliers.account', $supplier) }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-user-cog mr-2"></i> Supplier Account</a></li>
                            <li><a href="{{ route('admin.suppliers.create') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-tachometer-alt mr-3"></i> Add Supplier</a></li>
                        </ul>
                    </li>
                    
                      <li class="mb-2 nav-item">
                        <button class="submenu-toggle block w-full text-left px-4 py-3 text-lg font-medium flex items-center justify-between">
                            <span><i class="fas fa-money-check-alt mr-3"></i> Expenses</span>
                            <i class="fas fa-chevron-down w-4 h-4 transform transition-transform"></i>
                        </button>
                        <ul class="submenu pl-6">
                            <li><a href="{{ route('admin.expenses.create') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-list mr-2"></i> Add Expense</a></li>
                            <li><a href="{{ route('admin.expenses.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-list mr-2"></i> Expense List</a></li>
                        </ul>
                    </li>
                    
                      <li class="mb-2 nav-item">
                        <button class="submenu-toggle block w-full text-left px-4 py-3 text-lg font-medium flex items-center justify-between">
                            <span><i class="fas fa-shopping-bag mr-3"></i> E-Commerce</span>
                            <i class="fas fa-chevron-down w-4 h-4 transform transition-transform"></i>
                        </button>
                        <ul class="submenu pl-6">
                            <li><a href="{{ route('admin.orders.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-box mr-2"></i> Order Management</a></li>
                            <li><a href="{{ route('admin.category.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-tags mr-2"></i> Category</a></li>
                            <li><a href="{{ route('admin.products.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-box-open mr-2"></i> Product</a></li>
                        </ul>
                    </li>
                  
                    
                    <li class="mb-2 nav-item">
                        <button class="submenu-toggle block w-full text-left px-4 py-3 text-lg font-medium flex items-center justify-between">
                            <span><i class="fas fa-concierge-bell mr-3"></i> Service Booking</span>
                            <i class="fas fa-chevron-down w-4 h-4 transform transition-transform"></i>
                        </button>
                        <ul class="submenu pl-6">
                            <li><a href="{{ route('admin.servicelist.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-list-ul mr-2"></i> Services List</a></li>
                            <li><a href="{{ route('admin.bookedservices.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-book mr-2"></i> Booked Services</a></li>
                        </ul>
                    </li>
                    
                              <li><a href="{{url('core-hr')}}" class="" aria-expanded="false">
                <div class="menu-icon">
                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M15.8381 12.7317C16.4566 12.7317 16.9757 13.2422 16.8811 13.853C16.3263 17.4463 13.2502 20.1143 9.54009 20.1143C5.43536 20.1143 2.10834 16.7873 2.10834 12.6835C2.10834 9.30245 4.67693 6.15297 7.56878 5.44087C8.19018 5.28745 8.82702 5.72455 8.82702 6.36429C8.82702 10.6987 8.97272 11.8199 9.79579 12.4297C10.6189 13.0396 11.5867 12.7317 15.8381 12.7317Z" stroke="#888888" stroke-linecap="round" stroke-linejoin="round"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M19.8848 9.1223C19.934 6.33756 16.5134 1.84879 12.345 1.92599C12.0208 1.93178 11.7612 2.20195 11.7468 2.5252C11.6416 4.81493 11.7834 7.78204 11.8626 9.12713C11.8867 9.5459 12.2157 9.87493 12.6335 9.89906C14.0162 9.97818 17.0914 10.0862 19.3483 9.74467C19.6552 9.69835 19.88 9.43204 19.8848 9.1223Z" stroke="#888888" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <span class="nav-text">HR Core Maneges</span>
                </a>
            </li>
                  
                   <li class="mb-2 nav-item">
                        <button class="submenu-toggle block w-full text-left px-4 py-3 text-lg font-medium flex items-center justify-between">
                            <span><i class="fas fa-money-bill-wave mr-3"></i> Payroll</span>
                            <i class="fas fa-chevron-down w-4 h-4 transform transition-transform"></i>
                        </button>
                        <ul class="submenu pl-6">
                            <li><a href="{{ route('admin.advancesalary.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-hand-holding-usd mr-2"></i> Advance/Loan</a></li>
                        </ul>
                    </li>
                  
                    <li class="mb-2 nav-item">
                        <button class="submenu-toggle block w-full text-left px-4 py-3 text-lg font-medium flex items-center justify-between">
                            <span><i class="fas fa-clock mr-3"></i> Attendance</span>
                            <i class="fas fa-chevron-down w-4 h-4 transform transition-transform"></i>
                        </button>
                        <ul class="submenu pl-6">
                            <li><a href="{{ route('admin.attendance.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-user-check mr-2"></i> Mark Attendance</a></li>
                            <li><a href="{{ route('admin.viewattendance.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-calendar-check mr-2"></i> Attendance Records</a></li>
                        </ul>
                    </li>
                    
                    
                    <li class="mb-2 nav-item">
                        <button class="submenu-toggle block w-full text-left px-4 py-3 text-lg font-medium flex items-center justify-between">
                            <span><i class="fas fa-users mr-3"></i> User Management</span>
                            <i class="fas fa-chevron-down w-4 h-4 transform transition-transform"></i>
                        </button>
                        <ul class="submenu pl-6">
                            <li><a href="{{ route('admin.managers.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-user-tie mr-2"></i> Manager</a></li>
                            <li><a href="{{ route('admin.accountants.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-calculator mr-2"></i> Accountant</a></li>
                            <li><a href="{{ route('admin.technicians.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-tools mr-2"></i> Technician</a></li>
                            <li><a href="{{ route('admin.staff.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-user-friends mr-2"></i> Staff</a></li>
                        </ul>
                    </li>
                    <li class="mb-2 nav-item">
                        <button class="submenu-toggle block w-full text-left px-4 py-3 text-lg font-medium flex items-center justify-between">
                            <span><i class="fas fa-chart-bar mr-3"></i> Reports</span>
                            <i class="fas fa-chevron-down w-4 h-4 transform transition-transform"></i>
                        </button>
                        <ul class="submenu pl-6">
                            <li><a href="{{ route('admin.report.sales.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-file-invoice mr-2"></i> Sales Report</a></li>
                            <li><a href="{{ route('admin.report.purchase.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-shopping-cart mr-2"></i> Purchase Report</a></li>
                            <li><a href="{{ route('admin.report.profit.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-balance-scale mr-2"></i> Profit</a></li>
                            <li><a href="{{ route('admin.report.expense.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-money-bill mr-2"></i> Expense & Loss</a></li>
                            <li><a href="{{ route('admin.report.gst.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-file-alt mr-2"></i> GST Summary</a></li>
                            <li><a href="{{ route('admin.report.turnover.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-chart-line mr-2"></i> Turnover</a></li>
                        </ul>
                    </li>
                   
                    <li class="mb-2 nav-item">
                        <button class="submenu-toggle block w-full text-left px-4 py-3 text-lg font-medium flex items-center justify-between">
                            <span><i class="fas fa-paint-roller mr-3"></i> Setting</span>
                            <i class="fas fa-chevron-down w-4 h-4 transform transition-transform"></i>
                        </button>
                        <ul class="submenu pl-6">
                            <li><a href="{{ route('admin.hero.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-image mr-2"></i> Hero Edit</a></li>
                            <li><a href="{{ route('admin.projects.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-briefcase mr-2"></i> Project Edit</a></li>
                            <li><a href="{{ route('admin.team.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-users mr-2"></i> Team Edit</a></li>
                            <li><a href="{{ route('admin.testimonials.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-comment-dots mr-2"></i> Manage Testimonials</a></li>
                            <li><a href="{{ route('admin.contact-requests.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-inbox mr-2"></i> Manage Contact Requests</a></li>
                            <li><a href="{{ route('admin.contact-info.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-phone-alt mr-2"></i> Manage Contact Info</a></li>
                            <li><a href="{{ route('admin.sections.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-toggle-on mr-2"></i> Toggle E-Commerce</a></li>
                        </ul>
                    </li>
                    <li class="mb-2 nav-item">
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="btn-logout block w-full text-left px-4 py-3 text-lg font-medium flex items-center rounded-lg text-red-400 hover:text-white">
                                <i class="fas fa-sign-out-alt mr-3"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Mobile Menu Toggle -->
        <div class="md:hidden glassmorphism text-white p-4 sticky top-0 z-40">
            <button id="menu-toggle" class="text-white focus:outline-none">
                <i class="fas fa-bars w-6 h-6"></i>
            </button>
        </div>

        <!-- Mobile Sidebar -->
        <div id="mobile-menu" class="hidden fixed inset-0 glassmorphism text-white z-50 md:hidden sidebar-scroll">
            <div class="p-4 flex justify-between items-center sticky top-0 glassmorphism">
                <h1 class="text-2xl font-bold">SkBazar Admin</h1>
                <button id="close-menu" class="text-white focus:outline-none">
                    <i class="fas fa-times w-6 h-6"></i>
                </button>
            </div>
            <nav class="mt-4 pb-4 px-2">
                <ul>
                    <li class="mb-2 nav-item rounded-lg">
                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 text-lg font-medium flex items-center">
                            <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
                        </a>
                    </li>
                    <li class="mb-2 nav-item">
                        <button class="submenu-toggle block w-full text-left px-4 py-3 text-lg font-medium flex items-center justify-between">
                            <span><i class="fas fa-warehouse mr-3"></i> Inventory</span>
                            <i class="fas fa-chevron-down w-4 h-4 transform transition-transform"></i>
                        </button>
                        <ul class="submenu pl-6">
                            <li><a href="{{ route('admin.products.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-box-open mr-2"></i> Add Product</a></li>
                            <li><a href="{{ route('admin.buysupplierproducts.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-truck-loading mr-2"></i> Product Purchase</a></li>
                            <li><a href="{{ route('admin.buysupplierproducts.history') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-history mr-2"></i> Purchase History</a></li>
                            <li><a href="{{ route('admin.category.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-tags mr-2"></i> Add Category</a></li>
                            <li><a href="{{ route('admin.stock.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-boxes mr-2"></i> Stock Update</a></li>
                        </ul>
                    </li>
                    <li class="mb-2 nav-item">
                        <button class="submenu-toggle block w-full text-left px-4 py-3 text-lg font-medium flex items-center justify-between">
                            <span><i class="fas fa-file-invoice-dollar mr-3"></i> Billing</span>
                            <i class="fas fa-chevron-down w-4 h-4 transform transition-transform"></i>
                        </button>
                        <ul class="submenu pl-6">
                            <li>
                                <a href="{{ route('admin.invoice.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg flex items-center justify-between">
                                    <span><i class="fas fa-file-invoice mr-2"></i> Generate Invoices</span>
                                    <span class="ml-2 text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full badge">GST</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.invoice.withoutgst') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg flex items-center justify-between">
                                    <span><i class="fas fa-file-invoice mr-2"></i> Generate Invoices</span>
                                    <span class="ml-2 text-xs bg-red-100 text-red-800 px-2 py-0.5 rounded-full badge">No GST</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.invoice.list') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg">
                                    <i class="fas fa-list-alt mr-2"></i> All Invoices
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.invoice.hsn.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg">
                                    <i class="fas fa-barcode mr-2"></i> Add HSN Code
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="mb-2 nav-item">
                        <button class="submenu-toggle block w-full text-left px-4 py-3 text-lg font-medium flex items-center justify-between">
                            <span><i class="fas fa-user-shield mr-3"></i> Supplier</span>
                            <i class="fas fa-chevron-down w-4 h-4 transform transition-transform"></i>
                        </button>
                        <ul class="submenu pl-6">
                            <li><a href="{{ route('admin.suppliers.create') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-tachometer-alt mr-3"></i> Add Supplier</a></li>
                            <li><a href="{{ route('admin.suppliers.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-truck mr-2"></i> All Supplier</a></li>
                            @php
                            $supplier = 3;
                            @endphp
                            <li><a href="{{ route('admin.suppliers.account', $supplier) }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-user-cog mr-2"></i> Supplier Account</a></li>
                        </ul>
                    </li>
                    <li class="mb-2 nav-item">
                        <button class="submenu-toggle block w-full text-left px-4 py-3 text-lg font-medium flex items-center justify-between">
                            <span><i class="fas fa-user-shield mr-3"></i> Customer</span>
                            <i class="fas fa-chevron-down w-4 h-4 transform transition-transform"></i>
                        </button>
                        <ul class="submenu pl-6">
                            <li><a href="{{ route('admin.customers.create') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-tachometer-alt mr-3"></i> Add Customer</a></li>
                            <li><a href="{{ route('admin.customers.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-user mr-2"></i> All Customer</a></li>
                            @php
                            $customer = 13;
                            @endphp
                            <li><a href="{{ route('admin.customers.account', $customer) }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-user-check mr-2"></i> Customer Account</a></li>
                        </ul>
                    </li>
                    <li class="mb-2 nav-item">
                        <button class="submenu-toggle block w-full text-left px-4 py-3 text-lg font-medium flex items-center justify-between">
                            <span><i class="fas fa-concierge-bell mr-3"></i> Service Booking</span>
                            <i class="fas fa-chevron-down w-4 h-4 transform transition-transform"></i>
                        </button>
                        <ul class="submenu pl-6">
                            <li><a href="{{ route('admin.servicelist.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-list-ul mr-2"></i> Services List</a></li>
                            <li><a href="{{ route('admin.bookedservices.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-book mr-2"></i> Booked Services</a></li>
                        </ul>
                    </li>
                    <li class="mb-2 nav-item">
                        <button class="submenu-toggle block w-full text-left px-4 py-3 text-lg font-medium flex items-center justify-between">
                            <span><i class="fas fa-shopping-bag mr-3"></i> E-Commerce</span>
                            <i class="fas fa-chevron-down w-4 h-4 transform transition-transform"></i>
                        </button>
                        <ul class="submenu pl-6">
                            <li><a href="{{ route('admin.orders.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-box mr-2"></i> Order Management</a></li>
                            <li><a href="{{ route('admin.category.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-tags mr-2"></i> Category</a></li>
                            <li><a href="{{ route('admin.products.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-box-open mr-2"></i> Product</a></li>
                        </ul>
                    </li>
                    <li class="mb-2 nav-item">
                        <button class="submenu-toggle block w-full text-left px-4 py-3 text-lg font-medium flex items-center justify-between">
                            <span><i class="fas fa-money-check-alt mr-3"></i> Expenses</span>
                            <i class="fas fa-chevron-down w-4 h-4 transform transition-transform"></i>
                        </button>
                        <ul class="submenu pl-6">
                            <li><a href="{{ route('admin.expenses.create') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-list mr-2"></i> Add Expense</a></li>
                            <li><a href="{{ route('admin.expenses.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-list mr-2"></i> Expense List</a></li>
                        </ul>
                    </li>
                    <li class="mb-2 nav-item">
                        <button class="submenu-toggle block w-full text-left px-4 py-3 text-lg font-medium flex items-center justify-between">
                            <span><i class="fas fa-clock mr-3"></i> Attendance</span>
                            <i class="fas fa-chevron-down w-4 h-4 transform transition-transform"></i>
                        </button>
                        <ul class="submenu pl-6">
                            <li><a href="{{ route('admin.attendance.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-user-check mr-2"></i> Mark Attendance</a></li>
                            <li><a href="{{ route('admin.viewattendance.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-calendar-check mr-2"></i> Attendance Records</a></li>
                        </ul>
                    </li>
                    <li class="mb-2 nav-item">
                        <button class="submenu-toggle block w-full text-left px-4 py-3 text-lg font-medium flex items-center justify-between">
                            <span><i class="fas fa-users mr-3"></i> User Management</span>
                            <i class="fas fa-chevron-down w-4 h-4 transform transition-transform"></i>
                        </button>
                        <ul class="submenu pl-6">
                            <li><a href="{{ route('admin.managers.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-user-tie mr-2"></i> Manager</a></li>
                            <li><a href="{{ route('admin.accountants.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-calculator mr-2"></i> Accountant</a></li>
                            <li><a href="{{ route('admin.technicians.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-tools mr-2"></i> Technician</a></li>
                            <li><a href="{{ route('admin.staff.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-user-friends mr-2"></i> Staff</a></li>
                        </ul>
                    </li>
                    <li class="mb-2 nav-item">
                        <button class="submenu-toggle block w-full text-left px-4 py-3 text-lg font-medium flex items-center justify-between">
                            <span><i class="fas fa-chart-bar mr-3"></i> Reports</span>
                            <i class="fas fa-chevron-down w-4 h-4 transform transition-transform"></i>
                        </button>
                        <ul class="submenu pl-6">
                            <li><a href="{{ route('admin.report.sales.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-file-invoice mr-2"></i> Sales Report</a></li>
                            <li><a href="{{ route('admin.report.purchase.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-shopping-cart mr-2"></i> Purchase Report</a></li>
                            <li><a href="{{ route('admin.report.profit.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-balance-scale mr-2"></i> Profit</a></li>
                            <li><a href="{{ route('admin.report.expense.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-money-bill mr-2"></i> Expense & Loss</a></li>
                            <li><a href="{{ route('admin.report.gst.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-file-alt mr-2"></i> GST Summary</a></li>
                            <li><a href="{{ route('admin.report.turnover.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-chart-line mr-2"></i> Turnover</a></li>
                        </ul>
                    </li>
                    <li class="mb-2 nav-item">
                        <button class="submenu-toggle block w-full text-left px-4 py-3 text-lg font-medium flex items-center justify-between">
                            <span><i class="fas fa-money-bill-wave mr-3"></i> Payroll</span>
                            <i class="fas fa-chevron-down w-4 h-4 transform transition-transform"></i>
                        </button>
                        <ul class="submenu pl-6">
                            <li><a href="{{ route('admin.advancesalary.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-hand-holding-usd mr-2"></i> Advance/Loan</a></li>
                        </ul>
                    </li>
                    <li class="mb-2 nav-item">
                        <button class="submenu-toggle block w-full text-left px-4 py-3 text-lg font-medium flex items-center justify-between">
                            <span><i class="fas fa-paint-roller mr-3"></i> Setting</span>
                            <i class="fas fa-chevron-down w-4 h-4 transform transition-transform"></i>
                        </button>
                        <ul class="submenu pl-6">
                            <li><a href="{{ route('admin.hero.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-image mr-2"></i> Hero Edit</a></li>
                            <li><a href="{{ route('admin.projects.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-briefcase mr-2"></i> Project Edit</a></li>
                            <li><a href="{{ route('admin.team.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-users mr-2"></i> Team Edit</a></li>
                            <li><a href="{{ route('admin.testimonials.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-comment-dots mr-2"></i> Manage Testimonials</a></li>
                            <li><a href="{{ route('admin.contact-requests.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-inbox mr-2"></i> Manage Contact Requests</a></li>
                            <li><a href="{{ route('admin.contact-info.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-phone-alt mr-2"></i> Manage Contact Info</a></li>
                            <li><a href="{{ route('admin.sections.index') }}" class="block px-4 py-2 hover:bg-opacity-10 rounded-lg"><i class="fas fa-toggle-on mr-2"></i> Toggle E-Commerce</a></li>
                        </ul>
                    </li>
                    <li class="mb-2 nav-item">
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="btn-logout block w-full text-left px-4 py-3 text-lg font-medium flex items-center rounded-lg text-red-400 hover:text-white">
                                <i class="fas fa-sign-out-alt mr-3"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
        </div>

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
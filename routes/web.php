<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\AccountantController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminManagerController;
use App\Http\Controllers\Admin\AdminAccountantController;
use App\Http\Controllers\Admin\AdminTechnicianController;
use App\Http\Controllers\Admin\AdminStaffController;
use App\Http\Controllers\Admin\AddCustomerController;
use App\Http\Controllers\Admin\AddSupplierController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\ServiceListController;
use App\Http\Controllers\Admin\BookedServicesController;
use App\Http\Controllers\Admin\BuySupplierProductsController;
use App\Http\Controllers\Admin\ViewAttendanceController;
use App\Http\Controllers\Admin\AdvanceSallerySystemController;
use App\Http\Controllers\Admin\InvoiceGenerateController;
use App\Http\Controllers\Admin\HsnCodeController;


use App\Http\Controllers\Admin\ReportSalesController;
use App\Http\Controllers\Admin\ReportTurnoverController;
use App\Http\Controllers\Admin\ReportExpenceController;
use App\Http\Controllers\Admin\ReportGstController;
use App\Http\Controllers\Admin\ReportProfitController;
use App\Http\Controllers\Admin\ReportPurchaseController;
use App\Http\Controllers\Admin\LandingThemeController;

use App\Http\Controllers\Admin\HeroSectionController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\SolarSolutionController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\AboutUsController;
use App\Http\Controllers\Admin\ContactRequestController;
use App\Http\Controllers\Admin\ContactInfoController;


use App\Http\Controllers\Api\ApiCreateOrder;


use App\Http\Controllers\Staff\AssignedServiceController;
use App\Http\Controllers\Staff\AttendanceController;


use App\Http\Controllers\ShipRocket\LoginController;
use App\Http\Controllers\ShipRocket\OrdersController;
use App\Http\Controllers\ShipRocket\PickupandManifestController;
use App\Http\Controllers\ShipRocket\OrderCreateController;
use Illuminate\Support\Facades\Artisan;



Route::get('/optimize-clear', function () {
    Artisan::call('optimize:clear');

    return "✅ Optimized and all caches cleared!";
});


Route::get('/shiprocket/login', [LoginController::class, 'showLoginForm'])->name('shiprocket.login.form');
Route::post('/shiprocket/login', [LoginController::class, 'login'])->name('shiprocket.login');
Route::get('/shiprocket/shiped', [PickupandManifestController::class, 'index'])->name('shiprocket.shiped');
Route::get('/shiprocket/ordercreate', [OrderCreateController::class, 'index'])->name('shiprocket.ordercreate');
Route::get('/shiprocket/order/create', [App\Http\Controllers\ShipRocket\OrderCreateController::class, 'index'])->name('shiprocket.order.create');
Route::post('/shiprocket/order/store', [App\Http\Controllers\ShipRocket\OrderCreateController::class, 'store'])->name('shiprocket.order.store');
Route::get('/shiprocket/logs', [App\Http\Controllers\ShipRocket\OrderCreateController::class, 'logs'])->name('shiprocket.logs');
// After login red
// 
// 
// irect



Route::match(['get', 'post'], '/check-order-status', [ApiCreateOrder::class, 'checkOrderStatus'])->name('1234.check-order-status');




Route::prefix('shiprocket')->name('shiprocket.')->group(function () {
    Route::get('/orders', [OrdersController::class, 'index'])->name('orders.index');
    Route::get('/orders/serviceability/{orderId}', [OrdersController::class, 'checkServiceability'])->name('orders.serviceability');
    Route::post('/orders/generate-pickup', [OrdersController::class, 'generatePickup'])->name('orders.generate-pickup');
});

Route::post('/orders/assign-awb', [OrdersController::class, 'assignAwb'])->name('orders.assign-awb');


Route::post('/shiprocket/orders/generate-manifest', [OrdersController::class, 'generateManifest'])->name('orders.generate-manifest');
Route::post('/shiprocket/orders/generate-label', [OrdersController::class, 'generateLabel'])->name('orders.generate-label');
// routes/web.php

Route::get('/orders/track/{shipmentId}', [OrdersController::class, 'trackShipment'])->name('orders.track');


Route::get('/shiprocket/dashboard', function () {
    return view('shiprocket.dashboard');
})->name('shiprocket.dashboard');
Route::get('/shiprocket/logout', [LoginController::class, 'logout'])->name('shiprocket.logout');


Route::prefix('shiprocket')->group(function () {
    Route::get('/orders', [OrdersController::class, 'index'])->name('shiprocket.orders.index');
    Route::get('/login', function () {
        // Placeholder for login form route
        return view('shiprocket.login');
    })->name('shiprocket.login.form');
});

Route::prefix('admin')->group(function () {
    Route::get('/sections', [SectionController::class, 'index'])->name('admin.sections.index');
    Route::patch('/sections/{section}', [SectionController::class, 'update'])->name('admin.sections.update');
});



Route::get('/', [LandingThemeController::class, 'index'])->name('home');

// Admin routes for managing hero sections
Route::get('/', [LandingThemeController::class, 'index'])->name('home');

Route::prefix('admin/sections')->name('admin.sections.')->group(function () {
    Route::get('/', [LandingThemeController::class, 'adminIndex'])->name('index');
    Route::get('/create', [LandingThemeController::class, 'create'])->name('create');
    Route::post('/', [LandingThemeController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [LandingThemeController::class, 'edit'])->name('edit');
    Route::put('/{id}', [LandingThemeController::class, 'update'])->name('update');
    Route::delete('/{id}', [LandingThemeController::class, 'destroy'])->name('destroy');
});

Route::prefix('admin/hero')->name('admin.hero.')->group(function () {
    Route::get('/', [HeroSectionController::class, 'index'])->name('index');
    Route::get('/create', [HeroSectionController::class, 'create'])->name('create');
    Route::post('/', [HeroSectionController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [HeroSectionController::class, 'edit'])->name('edit');
    Route::put('/{id}', [HeroSectionController::class, 'update'])->name('update');
    Route::delete('/{id}', [HeroSectionController::class, 'destroy'])->name('destroy');
});

Route::prefix('admin/projects')->name('admin.projects.')->group(function () {
    Route::get('/', [ProjectController::class, 'index'])->name('index');
    Route::get('/create', [ProjectController::class, 'create'])->name('create');
    Route::post('/', [ProjectController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [ProjectController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ProjectController::class, 'update'])->name('update');
    Route::delete('/{id}', [ProjectController::class, 'destroy'])->name('destroy');
});

Route::prefix('admin/team')->name('admin.team.')->group(function () {
    Route::get('/', [TeamMemberController::class, 'index'])->name('index');
    Route::get('/create', [TeamMemberController::class, 'create'])->name('create');
    Route::post('/', [TeamMemberController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [TeamMemberController::class, 'edit'])->name('edit');
    Route::put('/{id}', [TeamMemberController::class, 'update'])->name('update');
    Route::delete('/{id}', [TeamMemberController::class, 'destroy'])->name('destroy');
});


Route::prefix('admin/solutions')->name('admin.solutions.')->group(function () {
    Route::get('/', [SolarSolutionController::class, 'index'])->name('index');
    Route::get('/create', [SolarSolutionController::class, 'create'])->name('create');
    Route::post('/', [SolarSolutionController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [SolarSolutionController::class, 'edit'])->name('edit');
    Route::put('/{id}', [SolarSolutionController::class, 'update'])->name('update');
    Route::delete('/{id}', [SolarSolutionController::class, 'destroy'])->name('destroy');
});
Route::prefix('admin/testimonials')->name('admin.testimonials.')->group(function () {
    Route::get('/', [TestimonialController::class, 'index'])->name('index');
    Route::get('/create', [TestimonialController::class, 'create'])->name('create');
    Route::post('/', [TestimonialController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [TestimonialController::class, 'edit'])->name('edit');
    Route::put('/{id}', [TestimonialController::class, 'update'])->name('update');
    Route::delete('/{id}', [TestimonialController::class, 'destroy'])->name('destroy');
});


Route::prefix('admin/about-us')->name('admin.about-us.')->group(function () {
    Route::get('/', [AboutUsController::class, 'index'])->name('index');
    Route::get('/create', [AboutUsController::class, 'create'])->name('create');
    Route::post('/', [AboutUsController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [AboutUsController::class, 'edit'])->name('edit');
    Route::put('/{id}', [AboutUsController::class, 'update'])->name('update');
    Route::delete('/{id}', [AboutUsController::class, 'destroy'])->name('destroy');
});




Route::prefix('admin/contact-requests')->name('admin.contact-requests.')->group(function () {
    Route::get('/', [ContactRequestController::class, 'index'])->name('index');
    Route::post('/', [ContactRequestController::class, 'store'])->name('store');
    Route::delete('/{id}', [ContactRequestController::class, 'destroy'])->name('destroy');
});

Route::prefix('admin/contact-info')->name('admin.contact-info.')->group(function () {
    Route::get('/', [ContactInfoController::class, 'index'])->name('index');
    Route::get('/create', [ContactInfoController::class, 'create'])->name('create');
    Route::post('/', [ContactInfoController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [ContactInfoController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ContactInfoController::class, 'update'])->name('update');
    Route::delete('/{id}', [ContactInfoController::class, 'destroy'])->name('destroy');
});



Route::get('/shop', function () {
    return Inertia::render('Shop');
});

Route::get('/search', function () {
    return Inertia::render('Search');
});

Route::get('/myaccount', function () {
    return Inertia::render('MyAccount');
})->middleware(['auth', 'verified'])->name('myaccount');

Route::get('/myaccount', [ProfileController::class, 'edit'])->name('myaccount');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login')->middleware('guest:admin');
    Route::post('/login', [AdminController::class, 'login'])->name('login');
    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
        Route::resource('customers', \App\Http\Controllers\Admin\AddCustomerController::class);

        // Admin routes for managing managers
        Route::get('/managers', [AdminManagerController::class, 'index'])->name('managers.index');
        Route::get('/managers/create', [AdminManagerController::class, 'create'])->name('managers.create');
        Route::post('/managers', [AdminManagerController::class, 'store'])->name('managers.store');
        Route::get('/managers/{manager}', [AdminManagerController::class, 'show'])->name('managers.show');
        Route::get('/managers/{manager}/edit', [AdminManagerController::class, 'edit'])->name('managers.edit');
        Route::put('/managers/{manager}', [AdminManagerController::class, 'update'])->name('managers.update');
        Route::delete('/managers/{manager}', [AdminManagerController::class, 'destroy'])->name('managers.destroy');

        // Accountant CRUD routes
        Route::get('/accountants', [AdminAccountantController::class, 'index'])->name('accountants.index');
        Route::get('/accountants/create', [AdminAccountantController::class, 'create'])->name('accountants.create');
        Route::post('/accountants', [AdminAccountantController::class, 'store'])->name('accountants.store');
        Route::get('/accountants/{accountant}', [AdminAccountantController::class, 'show'])->name('accountants.show');
        Route::get('/accountants/{accountant}/edit', [AdminAccountantController::class, 'edit'])->name('accountants.edit');
        Route::put('/accountants/{accountant}', [AdminAccountantController::class, 'update'])->name('accountants.update');
        Route::delete('/accountants/{accountant}', [AdminAccountantController::class, 'destroy'])->name('accountants.destroy');

        // Technician CRUD routes
        Route::get('/technicians', [AdminTechnicianController::class, 'index'])->name('technicians.index');
        Route::get('/technicians/create', [AdminTechnicianController::class, 'create'])->name('technicians.create');
        Route::post('/technicians', [AdminTechnicianController::class, 'store'])->name('technicians.store');
        Route::get('/technicians/{technician}', [AdminTechnicianController::class, 'show'])->name('technicians.show');
        Route::get('/technicians/{technician}/edit', [AdminTechnicianController::class, 'edit'])->name('technicians.edit');
        Route::put('/technicians/{technician}', [AdminTechnicianController::class, 'update'])->name('technicians.update');
        Route::delete('/technicians/{technician}', [AdminTechnicianController::class, 'destroy'])->name('technicians.destroy');

        // Staff CRUD routes
        Route::get('/staff', [AdminStaffController::class, 'index'])->name('staff.index');
        Route::get('/staff/create', [AdminStaffController::class, 'create'])->name('staff.create');
        Route::post('/staff', [AdminStaffController::class, 'store'])->name('staff.store');
        Route::get('/staff/{staff}', [AdminStaffController::class, 'show'])->name('staff.show');
        Route::get('/staff/{staff}/edit', [AdminStaffController::class, 'edit'])->name('staff.edit');
        Route::put('/staff/{staff}', [AdminStaffController::class, 'update'])->name('staff.update');
        Route::delete('/staff/{staff}', [AdminStaffController::class, 'destroy'])->name('staff.destroy');

        // Customer routes
        Route::prefix('customers')->name('customers.')->group(function () {
            Route::get('/', [AddCustomerController::class, 'index'])->name('index');
            Route::get('/create', [AddCustomerController::class, 'create'])->name('create');
            Route::post('/', [AddCustomerController::class, 'store'])->name('store');
            Route::get('/{customer}/edit', [AddCustomerController::class, 'edit'])->name('edit');
            Route::put('/{customer}', [AddCustomerController::class, 'update'])->name('update');
            Route::delete('/{customer}', [AddCustomerController::class, 'destroy'])->name('destroy');
            Route::get('/{customer}/account', [AddCustomerController::class, 'showAccount'])->name('account');
            Route::post('/{customer}/store-credit', [AddCustomerController::class, 'storeCredit'])->name('storeCredit');
            Route::put('/{customer}/credits/{credit}', [AddCustomerController::class, 'updateCredit'])->name('updateCredit');
            Route::delete('/{customer}/credits/{credit}', [AddCustomerController::class, 'deleteCredit'])->name('deleteCredit');
            Route::get('/search', [AddCustomerController::class, 'search'])->name('search');
        });


                Route::prefix('suppliers')->name('suppliers.')->group(function () {
                Route::get('/', [AddSupplierController::class, 'index'])->name('index');
                Route::get('/create', [AddSupplierController::class, 'create'])->name('create');
                Route::post('/', [AddSupplierController::class, 'store'])->name('store');
                Route::get('/{supplier}/edit', [AddSupplierController::class, 'edit'])->name('edit');
                Route::put('/{supplier}', [AddSupplierController::class, 'update'])->name('update');
                Route::delete('/{supplier}', [AddSupplierController::class, 'destroy'])->name('destroy');
                Route::get('/{supplier}/account', [AddSupplierController::class, 'showAccount'])->name('account');
                Route::post('/{supplier}/store-credit', [AddSupplierController::class, 'storeCredit'])->name('storeCredit');
                Route::put('/{supplier}/credits/{credit}', [AddSupplierController::class, 'updateCredit'])->name('updateCredit');
                Route::delete('/{supplier}/credits/{credit}', [AddSupplierController::class, 'deleteCredit'])->name('deleteCredit');
                Route::get('/search', [AddSupplierController::class, 'search'])->name('search');
            });

         
    });
});


                // 🔐 Admin Category Routes (Requires 'admin' middleware)
                Route::prefix('admin/inventory/category')
                    ->name('admin.category.')
                    ->middleware('admin')
                    ->group(function () {
                        Route::get('/', [CategoryController::class, 'index'])->name('index');
                        Route::get('/create', [CategoryController::class, 'create'])->name('create');
                        Route::post('/', [CategoryController::class, 'store'])->name('store');
                        Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('edit');
                        Route::put('/{id}', [CategoryController::class, 'update'])->name('update');
                        Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('destroy');
                    });

Route::prefix('admin/inventory/products')
->name('admin.products.')
->middleware('admin')
->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/create', [ProductController::class, 'create'])->name('create');
    Route::post('/', [ProductController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ProductController::class, 'update'])->name('update');
    Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/toggle-status', [ProductController::class, 'toggleStatus'])->name('toggleStatus');

});

Route::prefix('admin/inventory/stock')
->middleware('admin')
->group(function () {
    Route::get('/', [StockController::class, 'index'])->name('admin.stock.index');
    Route::get('/{id}', [StockController::class, 'show'])->name('admin.stock.show');
    Route::get('/{id}/edit', [StockController::class, 'edit'])->name('admin.stock.edit');
    Route::put('/{id}', [StockController::class, 'update'])->name('admin.stock.update');
    Route::delete('/{id}', [StockController::class, 'destroy'])->name('admin.stock.destroy');
});

Route::prefix('admin/expenses')->name('admin.expenses.')
->middleware('admin')
->group(function () {
    Route::get('/', [ExpenseController::class, 'index'])->name('index');
    Route::get('/create', [ExpenseController::class, 'create'])->name('create');
    Route::post('/', [ExpenseController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [ExpenseController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ExpenseController::class, 'update'])->name('update');
    Route::delete('/{id}', [ExpenseController::class, 'destroy'])->name('destroy');
});


Route::prefix('admin/orders')->name('admin.orders.')
    ->middleware('admin')
    ->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminOrderController::class, 'show'])->name('show');
        Route::patch('/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('updateStatus');
    });
    
Route::prefix('admin/servicelist')->name('admin.servicelist.')
    ->middleware('admin')
    ->group(function () {
        Route::get('/', [ServiceListController::class, 'index'])->name('index');
        Route::get('/create', [ServiceListController::class, 'create'])->name('create');
        Route::post('/', [ServiceListController::class, 'store'])->name('store');
        Route::get('/{service}/edit', [ServiceListController::class, 'edit'])->name('edit');
        Route::put('/{service}', [ServiceListController::class, 'update'])->name('update');
        Route::delete('/{service}', [ServiceListController::class, 'destroy'])->name('destroy');
    });

Route::prefix('admin/buysupplierproducts')->name('admin.buysupplierproducts.')
    ->middleware('admin')
    ->group(function () {
        Route::get('/', [BuySupplierProductsController::class, 'index'])->name('index');
        Route::post('/', [BuySupplierProductsController::class, 'store'])->name('store');
        Route::get('/history', [BuySupplierProductsController::class, 'history'])->name('history'); // New route
    });

Route::prefix('admin/bookedservices')->name('admin.bookedservices.')
    ->middleware('admin')
    ->group(function () {
        Route::get('/', [BookedServicesController::class, 'index'])->name('index');
        Route::put('/bookings/{booking}/status', [BookedServicesController::class, 'updateStatus'])->name('admin.bookings.status');
            Route::put('bookings/{booking}/assign-staff', [BookedServicesController::class, 'assignStaff'])->name('assign-staff');
    });



Route::prefix('admin/advancesalary')->name('admin.advancesalary.')
    ->middleware('admin')
    ->group(function () {
        Route::get('/', [AdvanceSallerySystemController::class, 'index'])->name('index');
        Route::get('/create', [AdvanceSallerySystemController::class, 'create'])->name('create');
        Route::post('/', [AdvanceSallerySystemController::class, 'store'])->name('store');
        Route::get('/{id}', [AdvanceSallerySystemController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AdvanceSallerySystemController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdvanceSallerySystemController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdvanceSallerySystemController::class, 'destroy'])->name('destroy');
        Route::post('/{advance_salary_id}/repayment', [AdvanceSallerySystemController::class, 'addRepayment'])->name('repayment');
    });

// routes/web.php (add this route)
Route::post('/admin/invoice/store-without-gst', [InvoiceGenerateController::class, 'storeWithoutGst'])->name('admin.invoice.store.without.gst');


Route::prefix('admin/viewattendance')->name('admin.viewattendance.')
    ->middleware('admin')
    ->group(function () {
        Route::get('/', [ViewAttendanceController::class, 'index'])->name('index');
        Route::get('/{id}', [ViewAttendanceController::class, 'show'])->name('show');
        Route::post('/', [ViewAttendanceController::class, 'store'])->name('store');
        Route::get('/calculate-salary/{staffId}', [ViewAttendanceController::class, 'calculateSalary'])->name('calculate_salary');
        Route::post('/{staffId}/store-bonuses', [ViewAttendanceController::class, 'storeBonuses'])->name('store_bonuses');
        Route::get('/{staffId}/pay-slip', [ViewAttendanceController::class, 'paySlip'])->name('pay_slip');
    });

Route::prefix('admin/invoice')->name('admin.invoice.')
    ->middleware('admin')
    ->group(function () {
        Route::get('/', [InvoiceGenerateController::class, 'index'])->name('index');
        Route::post('/store', [InvoiceGenerateController::class, 'store'])->name('store');
        Route::post('/number-to-words', [InvoiceGenerateController::class, 'numberToWords'])->name('number-to-words');
                Route::get('/', [InvoiceGenerateController::class, 'index'])->name('index');
        Route::post('/store', [InvoiceGenerateController::class, 'store'])->name('store');
        Route::get('/list', [InvoiceGenerateController::class, 'list'])->name('list');
        Route::get('/{id}/edit', [InvoiceGenerateController::class, 'edit'])->name('edit');
        Route::put('/{id}', [InvoiceGenerateController::class, 'update'])->name('update');


        Route::get('/store-without-gst', [InvoiceGenerateController::class, 'withoutindex'])->name('withoutgst');
        Route::delete('/{id}', [InvoiceGenerateController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/pdf', [InvoiceGenerateController::class, 'downloadPdf'])->name('pdf');
        Route::get('/{id}/preview', [InvoiceGenerateController::class, 'preview'])->name('preview');
        Route::get('/users/{id}', [InvoiceGenerateController::class, 'getUserDetails'])->name('users.details');
 
        // HSN Code Routes
        Route::prefix('hsn')->name('hsn.')->group(function () {
            Route::get('/', [HsnCodeController::class, 'index'])->name('index');
            Route::get('/create', [HsnCodeController::class, 'create'])->name('create');
            Route::post('/store', [HsnCodeController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [HsnCodeController::class, 'edit'])->name('edit');
            Route::put('/{id}/update', [HsnCodeController::class, 'update'])->name('update');
        });
    });


// Grouping under 'admin/report' for cleaner structure
Route::prefix('admin/report')->name('admin.report.')->group(function () {
    Route::get('/sales', [ReportSalesController::class, 'index'])->name('sales.index');
    Route::get('/sales/export', [ReportSalesController::class, 'export'])->name('sales.export');
    Route::get('/orders', [ReportSalesController::class, 'orders'])->name('orders.index');
    Route::get('/orders/export', [ReportSalesController::class, 'export'])->name('orders.export');
    Route::get('/turnover', [ReportTurnoverController::class, 'index'])->name('turnover.index');
    Route::get('/turnover/export', [ReportTurnoverController::class, 'export'])->name('turnover.export');
    Route::get('/expense', [ReportExpenceController::class, 'index'])->name('expense.index');
    Route::get('/expense/export', [ReportExpenceController::class, 'export'])->name('expense.export');
    Route::get('/gst', [ReportGstController::class, 'index'])->name('gst.index');
    Route::get('/gst/export', [ReportGstController::class, 'export'])->name('gst.export');
    Route::get('/profit', [ReportProfitController::class, 'index'])->name('profit.index');
    Route::get('/profit/export', [ReportProfitController::class, 'export'])->name('profit.export');
    Route::get('/purchase', [ReportPurchaseController::class, 'index'])->name('purchase.index');
    Route::get('/export', [ReportPurchaseController::class, 'export'])->name('purchase.export');
});


Route::prefix('manager')->name('manager.')->group(function () {
    Route::get('/login', [ManagerController::class, 'showLoginForm'])->name('login')->middleware('guest:manager');
    Route::post('/login', [ManagerController::class, 'login']);
    Route::middleware('manager')->group(function () {
        Route::get('/dashboard', [ManagerController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [ManagerController::class, 'logout'])->name('logout');
    });
});

Route::prefix('accountant')->name('accountant.')->group(function () {
    Route::get('/login', [AccountantController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AccountantController::class, 'login']);
    Route::middleware('accountant')->group(function () {
        Route::get('/dashboard', [AccountantController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [AccountantController::class, 'logout'])->name('logout');
    });
});

Route::prefix('technician')->name('technician.')->group(function () {
    Route::get('/login', [TechnicianController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [TechnicianController::class, 'login']);
    Route::middleware('technician')->group(function () {
        Route::get('/dashboard', [TechnicianController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [TechnicianController::class, 'logout'])->name('logout');
    });
});

Route::prefix('staff')->name('staff.')->group(function () {
    Route::get('/login', [StaffController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [StaffController::class, 'login']);
    Route::middleware('staff')->group(function () {
        Route::get('/dashboard', [StaffController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [StaffController::class, 'logout'])->name('logout');
    });
});


// Staff Routes
Route::prefix('staff')->name('staff.')->group(function () {
    Route::middleware('staff')->group(function () {
        Route::get('/assignedservice', [AssignedServiceController::class, 'index'])->name('assignedservice.index');
        Route::put('/assignedservice/{booking}/status', [AssignedServiceController::class, 'updateStatus'])->name('assignedservice.status');
        Route::post('/logout', [StaffController::class, 'logout'])->name('logout');
    });
});


Route::prefix('staff/attendance')->name('staff.attendance.')->group(function () {
    Route::middleware('staff')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('index');
        Route::post('/store', [AttendanceController::class, 'store'])->name('store');
        Route::get('/history', [AttendanceController::class, 'history'])->name('history');
    });
});

Route::prefix('admin/attendance')->name('admin.attendance.')->middleware('admin')->group(function () {
    Route::get('/', [AttendanceController::class, 'adminIndex'])->name('index');
    Route::get('/mark/{staffId}', [AttendanceController::class, 'adminMark'])->name('mark');
    Route::post('/store/{staffId}', [AttendanceController::class, 'adminStore'])->name('store');
});
require __DIR__.'/auth.php';
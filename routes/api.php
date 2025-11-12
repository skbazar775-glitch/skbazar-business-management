<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiProductController;
use App\Http\Controllers\Api\ApiAddAddress;
use App\Http\Controllers\Api\ApiOrderController;
use App\Http\Controllers\Api\ApiSearchController;
use App\Http\Controllers\Api\ApiServiceBookController;
use App\Http\Controllers\Api\ApiCreateOrder;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/test-auth', function (Request $request) {
    return response()->json([
        'message' => 'You are authenticated!',
        'user' => $request->user()
    ]);
});


Route::prefix('services')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [ApiServiceBookController::class, 'index'])->name('api.services.index');
    Route::post('/book', [ApiServiceBookController::class, 'book'])->name('api.services.book');
    Route::get('/bookings', [ApiServiceBookController::class, 'bookings'])->name('api.services.bookings');
});


Route::prefix('products')->group(function () {
    Route::get('/', [ApiProductController::class, 'index'])->name('api.products.index');
    Route::get('/{id}', [ApiProductController::class, 'show'])->name('api.products.show');
});
Route::get('/categories', [ApiProductController::class, 'categories']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/addresses', [ApiAddAddress::class, 'index'])->name('api.addresses.index');
    Route::post('/add-address', [ApiAddAddress::class, 'store'])->name('api.addresses.store');
    Route::put('/update-address/{id}', [ApiAddAddress::class, 'update'])->name('api.addresses.update');
    Route::delete('/delete-address/{id}', [ApiAddAddress::class, 'destroy'])->name('api.addresses.destroy');
    
});

Route::middleware('auth:sanctum')->group(function () {
    // Fetch all orders for the authenticated user
    Route::get('/orders', [ApiOrderController::class, 'index'])->name('api.orders.index');
    
    // Fetch details for a specific order
    Route::get('/orders/{id}', [ApiOrderController::class, 'show'])->name('api.orders.show');
    
    // Create a new order
    Route::post('/orders', [ApiOrderController::class, 'store'])->name('api.orders.store');

});
    Route::get('/search', [ApiSearchController::class, 'search']);

    
Route::post('/create-order', [ApiCreateOrder::class, 'create']);


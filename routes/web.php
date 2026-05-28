<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SupplierController;

Route::get('/', function () {
    return redirect()->route('products.index');
});

Route::resource('products', ProductController::class);
Route::resource('orders', OrderController::class);
Route::resource('categories', CategoryController::class);
Route::resource('order-items', OrderItemController::class);
Route::resource('customers', CustomerController::class);
Route::resource('carts', CartController::class);
Route::resource('payments', PaymentController::class);
Route::resource('vouchers', VoucherController::class);
Route::resource('reviews', ReviewController::class);
Route::resource('suppliers', SupplierController::class);

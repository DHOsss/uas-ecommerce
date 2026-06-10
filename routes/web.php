<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('customer.dashboard');
    }
    return redirect()->route('login');
});

// Routes yang bisa diakses keduanya (admin & customer) — hanya baca produk
Route::middleware(['auth'])->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show')->where('product', '[0-9]+');
});

// Cek voucher via AJAX
Route::get('/voucher/check', function () {
    $voucher = \App\Models\Voucher::where('code', request('code'))
        ->where('is_active', true)
        ->where('expired_at', '>=', now()->toDateString())
        ->first();

    if (!$voucher) {
        return response()->json(['valid' => false, 'message' => 'Kode voucher tidak valid atau sudah expired.']);
    }

    return response()->json([
        'valid'        => true,
        'discount'     => $voucher->discount,
        'min_purchase' => $voucher->min_purchase,
        'message'      => "Voucher valid! Diskon {$voucher->discount}%",
    ]);
})->middleware('auth')->name('voucher.check');

// Admin only — semua manajemen data
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', function () {
        $stats = [
            'revenue'          => \App\Models\Payment::where('status', 'paid')->sum('amount'),
            'orders_total'     => \App\Models\Order::count(),
            'orders_pending'   => \App\Models\Order::where('status', 'pending')->count(),
            'customers_total'  => \App\Models\Customer::count(),
            'payments_pending' => \App\Models\Payment::where('status', 'pending')->count(),
            'products_total'   => \App\Models\Product::count(),
            'reviews_total'    => \App\Models\Review::count(),
        ];
        $recentOrders   = \App\Models\Order::latest()->take(6)->get();
        $recentPayments = \App\Models\Payment::with('order')->latest()->take(6)->get();
        return view('admin.dashboard', compact('stats', 'recentOrders', 'recentPayments'));
    })->name('admin.dashboard');

    Route::resource('products', ProductController::class)->except(['index', 'show']);
    Route::resource('categories', CategoryController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('order-items', OrderItemController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('carts', CartController::class);
    Route::resource('payments', PaymentController::class);
    Route::patch('payments/{payment}/status', [PaymentController::class, 'updateStatus'])->name('payments.updateStatus');
    Route::resource('vouchers', VoucherController::class);
    Route::resource('reviews', ReviewController::class);
    Route::resource('suppliers', SupplierController::class);
});

// Customer only
Route::middleware(['auth', 'customer'])->group(function () {
    Route::get('/dashboard', fn() => view('customer.dashboard'))->name('customer.dashboard');
    Route::get('/my-orders', [OrderController::class, 'index'])->name('customer.orders');
    Route::get('/my-orders/create', [OrderController::class, 'create'])->name('customer.orders.create');
    Route::post('/my-orders', [OrderController::class, 'store'])->name('customer.orders.store');
    Route::get('/my-orders/{order}', [OrderController::class, 'show'])->name('customer.orders.show');
    Route::get('/my-cart', [CartController::class, 'customerIndex'])->name('customer.carts');
    Route::get('/my-cart/create', [CartController::class, 'customerCreate'])->name('customer.carts.create');
    Route::post('/my-cart', [CartController::class, 'customerStore'])->name('customer.carts.store');
    Route::post('/buy-now', [OrderController::class, 'buyNow'])->name('customer.buynow');
    Route::post('/my-cart/quick-add', [CartController::class, 'quickAdd'])->name('customer.cart.quickadd');
    Route::post('/my-cart/checkout', [CartController::class, 'checkout'])->name('customer.cart.checkout');
    Route::delete('/my-cart/{cart}', [CartController::class, 'customerDestroy'])->name('customer.carts.destroy');
    Route::get('/my-reviews', [ReviewController::class, 'customerIndex'])->name('customer.reviews');
    Route::get('/my-reviews/create', [ReviewController::class, 'customerCreate'])->name('customer.reviews.create');
    Route::post('/my-reviews', [ReviewController::class, 'customerStore'])->name('customer.reviews.store');

    Route::get('/my-payments', [PaymentController::class, 'customerIndex'])->name('customer.payments');
    Route::get('/my-payments/create', [PaymentController::class, 'customerCreate'])->name('customer.payments.create');
    Route::post('/my-payments', [PaymentController::class, 'customerStore'])->name('customer.payments.store');
    Route::get('/my-payments/{payment}', [PaymentController::class, 'customerShow'])->name('customer.payments.show');
});

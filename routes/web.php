<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PaymentController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/chat/history', [ChatController::class, 'history'])->name('chat.history');
Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/collection', [ShopController::class, 'collection'])->name('shop.collection');
Route::get('/shop/sale', [ShopController::class, 'sale'])->name('shop.sale');
Route::get('/shop/shirts', [ShopController::class, 'shirts'])->name('shop.shirts');
Route::get('/shop/pants', [ShopController::class, 'pants'])->name('shop.pants');
Route::get('/shop/accessories', [ShopController::class, 'accessories'])->name('shop.accessories');
Route::get('/shop/featured', [ShopController::class, 'featured'])->name('shop.featured');
Route::get('/shop/category/{category}', [ShopController::class, 'category'])->name('shop.category');
Route::get('/shop/product/{product}', [ShopController::class, 'show'])->name('shop.show');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot.password');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::get('/reset-password/{id}', [AuthController::class, 'showResetPassword'])->name('reset.password');
Route::post('/reset-password/{id}', [AuthController::class, 'resetPassword']);

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('home');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');

    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::put('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{itemId}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{itemId}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/select-items', [CheckoutController::class, 'selectItems'])->name('checkout.select');
    Route::post('/checkout', [CheckoutController::class, 'placeOrder'])->name('checkout.place');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{productId}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // Payment routes
    Route::get('/payment/vietqr/{order}', [PaymentController::class, 'vietqr'])->name('payment.vietqr');
    Route::get('/payment/vnpay/{order}', [PaymentController::class, 'vnpayPayment'])->name('payment.vnpay');
    Route::get('/payment/vnpay/callback', [PaymentController::class, 'vnpayCallback'])->name('payment.vnpay.callback');
    Route::get('/payment/result', [PaymentController::class, 'paymentResult'])->name('payment.result');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('products', ProductController::class)->except(['show']);
    Route::post('/products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::delete('/products/{id}/force-delete', [ProductController::class, 'forceDelete'])->name('products.force-delete');

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');

    Route::resource('coupons', CouponController::class)->except(['show']);

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::post('/customers/{user}/toggle', [CustomerController::class, 'toggleStatus'])->name('customers.toggle');
    Route::delete('/customers/{user}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::post('/permissions/roles', [PermissionController::class, 'storeRole'])->name('permissions.role.store');
    Route::post('/permissions/roles/{role}', [PermissionController::class, 'assignPermissions'])->name('permissions.role.assign');
    Route::post('/permissions/users/{user}', [PermissionController::class, 'assignRole'])->name('permissions.user.assign');
});

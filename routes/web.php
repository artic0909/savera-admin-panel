<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

use App\Http\Controllers\FrontendController;

Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/ajax/products', [FrontendController::class, 'ajaxProducts'])->name('ajax.products');
Route::get('/category/{slug}', [FrontendController::class, 'category'])->name('category.show');
Route::get('/product/{id}', [FrontendController::class, 'productDetails'])->name('product.show');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

use App\Http\Controllers\Frontend\CustomerAuthController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Frontend\CheckoutController;

Route::middleware('guest:customer')->group(function () {
    Route::get('register', [CustomerAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [CustomerAuthController::class, 'register']);
    Route::get('login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [CustomerAuthController::class, 'login']);
});

Route::middleware('auth:customer')->group(function () {
    // Authentication
    Route::post('logout', [CustomerAuthController::class, 'logout'])->name('logout');
    Route::get('profile', [CustomerAuthController::class, 'profile'])->name('profile');
    Route::put('profile', [CustomerAuthController::class, 'updateProfile'])->name('profile.update');

    // Cart Routes
    Route::get('cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('cart/count', [CartController::class, 'count'])->name('cart.count');

    // Wishlist Routes
    Route::get('wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('wishlist/remove/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::post('wishlist/move-to-cart/{id}', [WishlistController::class, 'moveToCart'])->name('wishlist.moveToCart');
    Route::get('wishlist/count', [WishlistController::class, 'count'])->name('wishlist.count');

    // Checkout & Orders Routes
    Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');
    Route::get('order-success/{orderNumber}', [CheckoutController::class, 'success'])->name('order.success');
    Route::get('my-orders', [CheckoutController::class, 'myOrders'])->name('orders.index');
    Route::get('order/{orderNumber}', [CheckoutController::class, 'orderDetails'])->name('order.details');
});

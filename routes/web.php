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

Route::middleware('guest:customer')->group(function () {
    Route::get('register', [CustomerAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [CustomerAuthController::class, 'register']);
    Route::get('login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [CustomerAuthController::class, 'login']);
});

Route::middleware('auth:customer')->group(function () {
    Route::post('logout', [CustomerAuthController::class, 'logout'])->name('logout');
    Route::get('profile', [CustomerAuthController::class, 'profile'])->name('profile');
    Route::put('profile', [CustomerAuthController::class, 'updateProfile'])->name('profile.update');
});

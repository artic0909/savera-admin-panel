<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PincodeController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;


Route::get('/admin/login', [AdminController::class, 'adminLoginView'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'adminLogin'])->name('admin.login.verify');


Route::middleware(['auth:admin'])->group(function () {


    Route::get('/admin/dashboard', [AdminController::class, 'adminDashboardView'])->name('admin.dashboard');
    Route::get('/admin/logout', [AdminController::class, 'adminLogout'])->name('admin.logout');


    // Categories
    Route::get('/admin/categories', [AdminController::class, 'adminCategoriesView'])->name('admin.categories.index');
    Route::post('/admin/categories/store', [AdminController::class, 'categoryStore'])->name('admin.categories.store');
    Route::get('/admin/categories/edit/{id}', [AdminController::class, 'categoryEdit'])->name('admin.categories.edit');
    Route::post('/admin/categories/update/{id}', [AdminController::class, 'categoryUpdate'])->name('admin.categories.update');
    Route::post('/admin/categories/delete/{id}', [AdminController::class, 'categoryDelete'])->name('admin.categories.delete');


    // Materials
    Route::get('/admin/materials', [AdminController::class, 'adminMaterialsView'])->name('admin.materials.index');
    Route::post('/admin/materials/store', [AdminController::class, 'materialStore'])->name('admin.materials.store');
    Route::post('/admin/materials/update/{id}', [AdminController::class, 'materialUpdate'])->name('admin.materials.update');
    Route::post('/admin/materials/delete/{id}', [AdminController::class, 'materialDelete'])->name('admin.materials.delete');


    // Products
    // Route::get('/admin/products/add', [AdminController::class, 'addProductView'])->name('admin.products.add');
    // Route::get('/admin/products/edit', [AdminController::class, 'editProductView'])->name('admin.products.edit');
    // Route::get('/admin/products/index', [AdminController::class, 'indexProductView'])->name('admin.products.index');

    Route::resource('admin/products', ProductController::class)->names('admin.products');

    // Pincodes
    Route::post('admin/pincodes/import', [PincodeController::class, 'import'])->name('admin.pincodes.import');
    Route::resource('admin/pincodes', PincodeController::class)->names('admin.pincodes');

    // Coupons
    Route::resource('admin/coupons', CouponController::class)->names('admin.coupons');


    // Orders
    Route::get('/admin/orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/admin/orders/{id}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::put('/admin/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
    Route::put('/admin/orders/{id}/payment-status', [OrderController::class, 'updatePaymentStatus'])->name('admin.orders.updatePaymentStatus');
    Route::delete('/admin/orders/{id}', [OrderController::class, 'destroy'])->name('admin.orders.destroy');


    // Why Choose
    Route::get('/admin/whychoose', [AdminController::class, 'adminWhyChooseView'])->name('admin.whychoose.index');
    Route::post('/admin/whychoose/store', [AdminController::class, 'whyChooseStore'])->name('admin.whychoose.store');
    Route::post('/admin/whychoose/update/{id}', [AdminController::class, 'whyChooseUpdate'])->name('admin.whychoose.update');
    Route::post('/admin/whychoose/delete/{id}', [AdminController::class, 'whyChooseDelete'])->name('admin.whychoose.delete');


    // Profile ==================================================================================================================================>
    Route::get('/admin/profile', [AdminController::class, 'adminProfileView'])->name('admin.profile');
    Route::post('/admin/profile/update', [AdminController::class, 'adminUpdateProfile'])->name('admin.profile.update');
});

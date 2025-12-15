<?php

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


    // Products
    Route::get('/admin/products/add', [AdminController::class, 'addProductView'])->name('admin.products.add');

    Route::get('/admin/products/edit', [AdminController::class, 'editProductView'])->name('admin.products.edit');

    Route::get('/admin/products/index', [AdminController::class, 'indexProductView'])->name('admin.products.index');




    // Profile ==================================================================================================================================>
    Route::get('/admin/profile', [AdminController::class, 'adminProfileView'])->name('admin.profile');
    Route::post('/admin/profile/update', [AdminController::class, 'adminUpdateProfile'])->name('admin.profile.update');

});

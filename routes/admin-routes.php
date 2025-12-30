<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PincodeController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CustomerController;
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


    // Sizes
    Route::get('/admin/sizes', [AdminController::class, 'adminSizesView'])->name('admin.sizes.index');
    Route::post('/admin/sizes/store', [AdminController::class, 'sizeStore'])->name('admin.sizes.store');
    Route::post('/admin/sizes/update/{id}', [AdminController::class, 'sizeUpdate'])->name('admin.sizes.update');
    Route::post('/admin/sizes/delete/{id}', [AdminController::class, 'sizeDelete'])->name('admin.sizes.delete');


    // Colors
    Route::get('/admin/colors', [AdminController::class, 'adminColorsView'])->name('admin.colors.index');
    Route::post('/admin/colors/store', [AdminController::class, 'colorStore'])->name('admin.colors.store');
    Route::post('/admin/colors/update/{id}', [AdminController::class, 'colorUpdate'])->name('admin.colors.update');
    Route::post('/admin/colors/delete/{id}', [AdminController::class, 'colorDelete'])->name('admin.colors.delete');


    // Products
    // Route::get('/admin/products/add', [AdminController::class, 'addProductView'])->name('admin.products.add');
    // Route::get('/admin/products/edit', [AdminController::class, 'editProductView'])->name('admin.products.edit');
    // Route::get('/admin/products/index', [AdminController::class, 'indexProductView'])->name('admin.products.index');

    // Products
    Route::get('/admin/products/check-sku', [ProductController::class, 'checkSKU'])->name('admin.products.checkSKU');
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


    // Payment Settings
    Route::get('/admin/payment-settings', [App\Http\Controllers\Admin\PaymentSettingController::class, 'index'])->name('admin.payment-settings.index');
    Route::post('/admin/payment-settings', [App\Http\Controllers\Admin\PaymentSettingController::class, 'update'])->name('admin.payment-settings.update');


    // SEO Settings
    Route::get('/admin/seo', [AdminController::class, 'seoIndex'])->name('admin.seo.index');
    Route::post('/admin/seo/store', [AdminController::class, 'seoStore'])->name('admin.seo.store');
    Route::post('/admin/seo/update/{id}', [AdminController::class, 'seoUpdate'])->name('admin.seo.update');
    Route::post('/admin/seo/delete/{id}', [AdminController::class, 'seoDestroy'])->name('admin.seo.delete');

    // Why Choose
    Route::get('/admin/whychoose', [AdminController::class, 'adminWhyChooseView'])->name('admin.whychoose.index');
    Route::post('/admin/whychoose/store', [AdminController::class, 'whyChooseStore'])->name('admin.whychoose.store');
    Route::post('/admin/whychoose/update/{id}', [AdminController::class, 'whyChooseUpdate'])->name('admin.whychoose.update');
    Route::post('/admin/whychoose/delete/{id}', [AdminController::class, 'whyChooseDelete'])->name('admin.whychoose.delete');


    // Inventory & Stock Management
    Route::get('/admin/inventory', [App\Http\Controllers\Admin\InventoryController::class, 'index'])->name('admin.inventory.index');
    Route::post('/admin/inventory/update-stock', [App\Http\Controllers\Admin\InventoryController::class, 'updateStock'])->name('admin.inventory.updateStock');
    Route::post('/admin/inventory/update-mrp', [App\Http\Controllers\Admin\InventoryController::class, 'updateMRP'])->name('admin.inventory.updateMRP');
    Route::get('/admin/stock-notifications', [AdminController::class, 'stockNotifications'])->name('admin.stock-notifications.index');
    Route::post('/admin/stock-notifications/{id}/status', [AdminController::class, 'updateNotificationStatus'])->name('admin.stock-notifications.updateStatus');

    // Reports & Exports
    Route::get('/admin/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('admin.reports.index');
    Route::get('/admin/reports/export', [App\Http\Controllers\Admin\ReportController::class, 'export'])->name('admin.reports.export');

    // Story Videos
    Route::resource('admin/story-videos', \App\Http\Controllers\Admin\StoryVideoController::class)->names('admin.story-videos');

    // Home Page Settings
    Route::get('/admin/home-settings', [\App\Http\Controllers\Admin\HomePageSettingController::class, 'index'])->name('admin.home-settings.index');
    Route::post('/admin/home-settings/update/{section}', [\App\Http\Controllers\Admin\HomePageSettingController::class, 'updateSection'])->name('admin.home-settings.update-section');

    // Customers
    Route::get('/admin/customers', [CustomerController::class, 'index'])->name('admin.customers.index');
    Route::delete('/admin/customers/{id}', [CustomerController::class, 'destroy'])->name('admin.customers.destroy');

    // Profile ==================================================================================================================================>
    Route::get('/admin/profile', [AdminController::class, 'adminProfileView'])->name('admin.profile');
    Route::post('/admin/profile/update', [AdminController::class, 'adminUpdateProfile'])->name('admin.profile.update');
});

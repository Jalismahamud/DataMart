<?php

use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SiteSettingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/settings', [SiteSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SiteSettingController::class, 'store'])->name('settings.store');

    Route::resource('products', ProductController::class);
    Route::resource('reviews', ReviewController::class);
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show']);
});

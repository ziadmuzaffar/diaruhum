<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Categories Routes
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
    Route::patch('categories/{category}/toggle-status', [App\Http\Controllers\Admin\CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
    Route::post('categories/update-order', [App\Http\Controllers\Admin\CategoryController::class, 'updateOrder'])->name('categories.update-order');
    Route::get('categories/by-type', [App\Http\Controllers\Admin\CategoryController::class, 'getByType'])->name('categories.by-type');
});

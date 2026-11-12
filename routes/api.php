<?php

use App\Http\Controllers\Api\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Categories API Routes
Route::prefix('categories')->name('categories.')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::get('/tree', [CategoryController::class, 'tree'])->name('tree');
    Route::get('/by-type', [CategoryController::class, 'byType'])->name('by-type');
    Route::get('/statistics', [CategoryController::class, 'statistics'])->name('statistics');
    Route::get('/search', [CategoryController::class, 'search'])->name('search');
    Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
});

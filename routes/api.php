<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\DatabaseToolController;

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('products')->name('api.products.')->group(function () {
        Route::get('/', [ApiController::class, 'products'])->name('index');
        Route::get('/{database}/{id}', [ApiController::class, 'productShow'])->name('show');
        Route::post('/', [ApiController::class, 'productStore'])->name('store');
        Route::put('/{database}/{id}', [ApiController::class, 'productUpdate'])->name('update');
        Route::delete('/{database}/{id}', [ApiController::class, 'productDestroy'])->name('destroy');
    });

    Route::prefix('blogs')->name('api.blogs.')->group(function () {
        Route::get('/', [ApiController::class, 'blogs'])->name('index');
        Route::get('/{id}', [ApiController::class, 'blogShow'])->name('show');
        Route::post('/', [ApiController::class, 'blogStore'])->name('store');
        Route::put('/{id}', [ApiController::class, 'blogUpdate'])->name('update');
        Route::delete('/{id}', [ApiController::class, 'blogDestroy'])->name('destroy');
    });

    Route::prefix('categories')->name('api.categories.')->group(function () {
        Route::get('/', [ApiController::class, 'categories'])->name('index');
        Route::post('/', [ApiController::class, 'categoryStore'])->name('store');
    });

    Route::prefix('database-tools')->name('api.database-tools.')->group(function () {
        Route::post('/backup', [DatabaseToolController::class, 'backup'])->name('backup');
        Route::post('/restore', [DatabaseToolController::class, 'restore'])->name('restore');
        Route::post('/migrate', [DatabaseToolController::class, 'runMigration'])->name('migrate');
        Route::post('/seed', [DatabaseToolController::class, 'runSeeder'])->name('seed');
        Route::post('/query', [DatabaseToolController::class, 'executeQuery'])->name('query');
        Route::get('/compare', [DatabaseToolController::class, 'compareDatabases'])->name('compare');
    });
});

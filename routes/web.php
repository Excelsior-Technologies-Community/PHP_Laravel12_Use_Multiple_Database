<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DatabaseController;

/*
|--------------------------------------------------------------------------
| Multi-Database Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [
    DatabaseController::class,
    'dashboard'
])->name('dashboard');


/*
|--------------------------------------------------------------------------
| Products
|--------------------------------------------------------------------------
*/

Route::get('/products', [
    ProductController::class,
    'index'
])->name('products.index');


/*
|--------------------------------------------------------------------------
| Synchronization
|--------------------------------------------------------------------------
*/

Route::post('/products/{id}/sync', [
    DatabaseController::class,
    'syncProduct'
])->name('products.sync');

Route::post('/products/sync-all', [
    DatabaseController::class,
    'syncAll'
])->name('products.sync-all');


/*
|--------------------------------------------------------------------------
| Database Health Monitor
|--------------------------------------------------------------------------
*/

Route::get('/database-health', [
    DatabaseController::class,
    'health'
])->name('database.health');


/*
|--------------------------------------------------------------------------
| Original Testing Routes
|--------------------------------------------------------------------------
*/

Route::get('/get-mysql-products', function () {
    $products = DB::connection('mysql')
        ->table('products')
        ->get();

    return response()->json($products);
});


Route::get('/get-mysql-second-products', function () {
    $products = DB::connection('mysql_second')
        ->table('products')
        ->get();

    return response()->json($products);
});
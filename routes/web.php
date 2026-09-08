<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DatabaseController;

/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

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
| Create Product
|--------------------------------------------------------------------------
*/

Route::get('/products/create', [
    ProductController::class,
    'create'
])->name('products.create');


Route::post('/products', [
    ProductController::class,
    'store'
])->name('products.store');


/*
|--------------------------------------------------------------------------
| Edit Product
|--------------------------------------------------------------------------
*/

Route::get('/products/{database}/{id}/edit', [
    ProductController::class,
    'edit'
])->whereIn('database', [
    'mysql',
    'mysql_second'
])->name('products.edit');


Route::put('/products/{database}/{id}', [
    ProductController::class,
    'update'
])->whereIn('database', [
    'mysql',
    'mysql_second'
])->name('products.update');


/*
|--------------------------------------------------------------------------
| Delete Product
|--------------------------------------------------------------------------
*/

Route::delete('/products/{database}/{id}', [
    ProductController::class,
    'destroy'
])->whereIn('database', [
    'mysql',
    'mysql_second'
])->name('products.destroy');


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
| Database Health
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


Route::post('/products/{id}/sync-to-primary', [ProductController::class, 'syncToPrimary'])
    ->name('products.sync-to-primary');
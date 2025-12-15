<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Get Products from Default Database
|--------------------------------------------------------------------------
*/
Route::get('/get-mysql-products', function () {
    $products = DB::table('products')->get();
    dd($products);
});

/*
|--------------------------------------------------------------------------
| Get Products from Second Database
|--------------------------------------------------------------------------
*/
Route::get('/get-mysql-second-products', function () {
    $products = DB::connection('mysql_second')
                  ->table('products')
                  ->get();

    dd($products);
});

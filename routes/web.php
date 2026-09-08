<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductTagController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\BlogTagController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\DatabaseToolController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ActivityLogController;

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
| Product Bulk Operations
|--------------------------------------------------------------------------
*/

Route::post('/products/import', [ProductController::class, 'importProducts'])
    ->name('products.import');

Route::get('/products/export', [ProductController::class, 'exportProducts'])
    ->name('products.export');

Route::post('/products/bulk-delete', [ProductController::class, 'bulkDelete'])
    ->name('products.bulk-delete');

Route::post('/products/bulk-sync', [ProductController::class, 'bulkSync'])
    ->name('products.bulk-sync');

Route::get('/products/trash', [ProductController::class, 'trash'])
    ->name('products.trash');

Route::post('/products/{id}/restore', [ProductController::class, 'restore'])
    ->name('products.restore');


/*
|--------------------------------------------------------------------------
| Product Categories
|--------------------------------------------------------------------------
*/

Route::resource('product-categories', ProductCategoryController::class);


/*
|--------------------------------------------------------------------------
| Product Tags
|--------------------------------------------------------------------------
*/

Route::resource('product-tags', ProductTagController::class);


/*
|--------------------------------------------------------------------------
| Product Variants (Nested under Products)
|--------------------------------------------------------------------------
*/

Route::prefix('products/{product}')->name('products.variants.')->group(function () {
    Route::get('/variants', [ProductVariantController::class, 'index'])->name('index');
    Route::get('/variants/create', [ProductVariantController::class, 'create'])->name('create');
    Route::post('/variants', [ProductVariantController::class, 'store'])->name('store');
    Route::get('/variants/{product_variant}/edit', [ProductVariantController::class, 'edit'])->name('edit');
    Route::put('/variants/{product_variant}', [ProductVariantController::class, 'update'])->name('update');
    Route::delete('/variants/{product_variant}', [ProductVariantController::class, 'destroy'])->name('destroy');
});


/*
|--------------------------------------------------------------------------
| Blogs
|--------------------------------------------------------------------------
*/

Route::resource('blogs', BlogController::class);

Route::post('/blogs/{blog}/publish', [BlogController::class, 'publish'])
    ->name('blogs.publish');

Route::post('/blogs/{blog}/toggle-featured', [BlogController::class, 'toggleFeatured'])
    ->name('blogs.toggle-featured');


/*
|--------------------------------------------------------------------------
| Blog Categories
|--------------------------------------------------------------------------
*/

Route::resource('blog-categories', BlogCategoryController::class);


/*
|--------------------------------------------------------------------------
| Blog Tags
|--------------------------------------------------------------------------
*/

Route::resource('blog-tags', BlogTagController::class);


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

Route::post('/products/sync-schedule', [
    DatabaseController::class,
    'runSyncSchedule'
])->name('products.sync-schedule');


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
| Database Tools
|--------------------------------------------------------------------------
*/

Route::prefix('database-tools')->name('database-tools.')->group(function () {
    Route::get('/backup', [DatabaseToolController::class, 'backupForm'])->name('backup');
    Route::post('/backup', [DatabaseToolController::class, 'backup'])->name('backup.submit');
    Route::get('/restore', [DatabaseToolController::class, 'restoreForm'])->name('restore');
    Route::post('/restore', [DatabaseToolController::class, 'restore'])->name('restore.submit');
    Route::get('/migrate', [DatabaseToolController::class, 'migrateForm'])->name('migrate');
    Route::post('/migrate', [DatabaseToolController::class, 'runMigration'])->name('migrate.submit');
    Route::get('/seed', [DatabaseToolController::class, 'seedForm'])->name('seed');
    Route::post('/seed', [DatabaseToolController::class, 'runSeeder'])->name('seed.submit');
    Route::get('/query', [DatabaseToolController::class, 'queryForm'])->name('query');
    Route::post('/query', [DatabaseToolController::class, 'executeQuery'])->name('query.submit');
    Route::get('/compare', [DatabaseToolController::class, 'compareDatabases'])->name('compare');
    Route::post('/compare', [DatabaseToolController::class, 'compareDatabases'])->name('compare.submit');
});


/*
|--------------------------------------------------------------------------
| Analytics
|--------------------------------------------------------------------------
*/

Route::prefix('analytics')->name('analytics.')->group(function () {
    Route::get('/dashboard', [AnalyticsController::class, 'dashboard'])->name('dashboard');
    Route::get('/products/stats', [AnalyticsController::class, 'productStats'])->name('products.stats');
    Route::get('/sync-stats', [AnalyticsController::class, 'syncStats'])->name('sync-stats');
    Route::get('/export-pdf', [AnalyticsController::class, 'exportPdf'])->name('export-pdf');
});


/*
|--------------------------------------------------------------------------
| Activity Logs
|--------------------------------------------------------------------------
*/

Route::prefix('activity-logs')->name('activity-logs.')->group(function () {
    Route::get('/', [ActivityLogController::class, 'index'])->name('index');
    Route::get('/{activity}', [ActivityLogController::class, 'show'])->name('show');
});


/*
|--------------------------------------------------------------------------
| Original Testing Routes
|--------------------------------------------------------------------------
*/

Route::get('/get-mysql-products', function () {
    $products = DB::connection('mysql')->table('products')->get();

    return response()->json($products);
});


Route::get('/get-mysql-second-products', function () {
    $products = DB::connection('mysql_second')->table('products')->get();

    return response()->json($products);
});


Route::post('/products/{id}/sync-to-primary', [ProductController::class, 'syncToPrimary'])
    ->name('products.sync-to-primary');

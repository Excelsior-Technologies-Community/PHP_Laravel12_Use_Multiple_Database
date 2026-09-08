<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Throwable;

class DatabaseController extends Controller
{
    public function dashboard()
    {
        $defaultDatabase = config('database.connections.mysql.database');
        $secondDatabase = config('database.connections.mysql_second.database');

        $defaultProducts = 0;
        $secondProducts = 0;
        $blogCount = 0;

        $defaultConnected = false;
        $secondConnected = false;

        try {
            DB::connection('mysql')->getPdo();
            $defaultConnected = true;

            $defaultProducts = DB::connection('mysql')->table('products')->count();

            if (DB::connection('mysql')->getSchemaBuilder()->hasTable('blog')) {
                $blogCount = DB::connection('mysql')->table('blog')->count();
            }
        } catch (Throwable $e) {
            Log::error('Default database dashboard error: ' . $e->getMessage());
        }

        try {
            DB::connection('mysql_second')->getPdo();
            $secondConnected = true;

            $secondProducts = DB::connection('mysql_second')->table('products')->count();
        } catch (Throwable $e) {
            Log::error('Second database dashboard error: ' . $e->getMessage());
        }

        $latestDefaultProducts = collect();
        $latestSecondProducts = collect();

        if ($defaultConnected) {
            try {
                $latestDefaultProducts = DB::connection('mysql')
                    ->table('products')
                    ->latest('id')
                    ->limit(5)
                    ->get();
            } catch (Throwable $e) {
                Log::error('Default products error: ' . $e->getMessage());
            }
        }

        if ($secondConnected) {
            try {
                $latestSecondProducts = DB::connection('mysql_second')
                    ->table('products')
                    ->latest('id')
                    ->limit(5)
                    ->get();
            } catch (Throwable $e) {
                Log::error('Second products error: ' . $e->getMessage());
            }
        }

        $syncedCount = 0;
        $pendingSyncCount = 0;

        try {
            $primaryNames = DB::connection('mysql')->table('products')->pluck('name');

            if ($primaryNames->isNotEmpty()) {
                $syncedCount = DB::connection('mysql_second')
                    ->table('products')
                    ->whereIn('name', $primaryNames)
                    ->count();

                $pendingSyncCount = $primaryNames->count() - $syncedCount;

                if ($pendingSyncCount < 0) {
                    $pendingSyncCount = 0;
                }
            }
        } catch (Throwable $e) {
            Log::error('Synchronization statistics failed: ' . $e->getMessage());
        }

        $totalProducts = $defaultProducts + $secondProducts;

        $lastSync = null;

        try {
            $lastSync = \Spatie\Activitylog\Models\Activity::query()
                ->where('description', 'like', '%sync%')
                ->latest()
                ->first()?->created_at;
        } catch (Throwable $e) {
            $lastSync = null;
        }

        $syncSchedule = [
            'enabled' => config('database.sync_schedule.enabled', false),
            'frequency' => config('database.sync_schedule.frequency', 'hourly'),
            'last_run' => $lastSync,
            'next_run' => $lastSync ? $lastSync->addHour() : null,
        ];

        return view(
            'database.dashboard',
            compact(
                'defaultDatabase',
                'secondDatabase',
                'defaultProducts',
                'secondProducts',
                'blogCount',
                'defaultConnected',
                'secondConnected',
                'latestDefaultProducts',
                'latestSecondProducts',
                'syncedCount',
                'pendingSyncCount',
                'totalProducts',
                'syncSchedule'
            )
        );
    }

    public function health()
    {
        $databases = [
            'mysql' => [
                'name' => 'Primary Database',
                'connection' => 'mysql',
            ],
            'mysql_second' => [
                'name' => 'Secondary Database',
                'connection' => 'mysql_second',
            ],
        ];

        $results = [];

        foreach ($databases as $key => $database) {
            $start = microtime(true);

            try {
                $connection = DB::connection($database['connection']);
                $connection->getPdo();

                $databaseName = $connection->getDatabaseName();
                $responseTime = round((microtime(true) - $start) * 1000, 2);

                $productsTableExists = $connection->getSchemaBuilder()->hasTable('products');
                $productCount = $productsTableExists ? $connection->table('products')->count() : 0;

                $results[$key] = [
                    'name' => $database['name'],
                    'connection' => $database['connection'],
                    'status' => true,
                    'database' => $databaseName,
                    'host' => config("database.connections.{$database['connection']}.host"),
                    'port' => config("database.connections.{$database['connection']}.port"),
                    'response_time' => $responseTime,
                    'products_table' => $productsTableExists,
                    'product_count' => $productCount,
                    'error' => null,
                ];
            } catch (Throwable $e) {
                $results[$key] = [
                    'name' => $database['name'],
                    'connection' => $database['connection'],
                    'status' => false,
                    'database' => config("database.connections.{$database['connection']}.database"),
                    'host' => config("database.connections.{$database['connection']}.host"),
                    'port' => config("database.connections.{$database['connection']}.port"),
                    'response_time' => null,
                    'products_table' => false,
                    'product_count' => 0,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return view('database.health', compact('results'));
    }

    public function syncProduct($id)
    {
        try {
            $product = DB::connection('mysql')->table('products')->where('id', $id)->first();

            if (!$product) {
                return redirect()->route('products.index')->with('error', 'Product not found in primary database.');
            }

            $alreadyExists = DB::connection('mysql_second')
                ->table('products')
                ->where('name', $product->name)
                ->exists();

            if ($alreadyExists) {
                return redirect()->route('products.index')->with('warning', "Product '{$product->name}' already exists in the secondary database.");
            }

            DB::connection('mysql_second')->table('products')->insert([
                'name' => $product->name,
                'detail' => $product->detail ?? null,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ]);

            return redirect()->route('products.index')->with('success', "Product '{$product->name}' synchronized successfully.");
        } catch (Throwable $e) {
            Log::error('Product synchronization failed: ' . $e->getMessage());
            return redirect()->route('products.index')->with('error', 'Synchronization failed: ' . $e->getMessage());
        }
    }

    public function syncAll()
    {
        try {
            $products = DB::connection('mysql')->table('products')->get();

            $synced = 0;
            $skipped = 0;

            foreach ($products as $product) {
                $exists = DB::connection('mysql_second')
                    ->table('products')
                    ->where('name', $product->name)
                    ->exists();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                DB::connection('mysql_second')->table('products')->insert([
                    'name' => $product->name,
                    'detail' => $product->detail ?? null,
                    'created_at' => $product->created_at,
                    'updated_at' => $product->updated_at,
                ]);

                $synced++;
            }

            return redirect()->route('products.index')->with('success', "{$synced} product(s) synchronized successfully. {$skipped} existing product(s) skipped.");
        } catch (Throwable $e) {
            Log::error('All products synchronization failed: ' . $e->getMessage());
            return redirect()->route('products.index')->with('error', 'Synchronization failed: ' . $e->getMessage());
        }
    }

    public function runSyncSchedule(Request $request)
    {
        try {
            Artisan::call('sync:products');

            return back()->with('success', 'Sync schedule executed successfully.');
        } catch (Throwable $e) {
            Log::error('Sync schedule failed: ' . $e->getMessage());
            return back()->with('error', 'Sync schedule failed: ' . $e->getMessage());
        }
    }
}

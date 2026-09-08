<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProductController extends Controller
{
    /**
     * Display products from one or both databases.
     *
     * Features:
     * - Search
     * - Database filter
     * - Sorting
     * - Pagination
     * - Sync status
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));

        $database = $request->input('database', 'all');

        if (!in_array($database, [
            'all',
            'mysql',
            'mysql_second'
        ])) {
            $database = 'all';
        }

        $sort = $request->input('sort', 'oldest');

        if (!in_array($sort, [
            'newest',
            'oldest',
            'name_asc',
            'name_desc'
        ])) {
            $sort = 'oldest';
        }

        $perPage = 5;

        $primaryProducts = collect();
        $secondaryProducts = collect();

        /*
        |--------------------------------------------------------------------------
        | Primary Database
        |--------------------------------------------------------------------------
        */

        if ($database === 'all' || $database === 'mysql') {
            try {
                $query = DB::connection('mysql')
                    ->table('products');

                if ($search !== '') {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%')
                            ->orWhere(
                                'detail',
                                'like',
                                '%' . $search . '%'
                            );
                    });
                }

                $query = $this->applySorting($query, $sort);

                $primaryProducts = $query
                    ->paginate($perPage, ['*'], 'primary_page')
                    ->withQueryString();

                /*
                 * Add synchronization status.
                 */
                $primaryProducts->getCollection()->transform(
                    function ($product) {
                        $product->database = 'mysql';

                        $product->database_label = 'Primary';

                        $product->synced = DB::connection(
                            'mysql_second'
                        )
                            ->table('products')
                            ->where('name', $product->name)
                            ->exists();

                        return $product;
                    }
                );
            } catch (Throwable $e) {
                Log::error(
                    'Primary product loading failed: ' .
                        $e->getMessage()
                );

                return back()->with(
                    'error',
                    'Unable to load primary products: ' .
                        $e->getMessage()
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Secondary Database
        |--------------------------------------------------------------------------
        */

        if ($database === 'all' || $database === 'mysql_second') {
            try {
                $query = DB::connection('mysql_second')
                    ->table('products');

                if ($search !== '') {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%')
                            ->orWhere(
                                'detail',
                                'like',
                                '%' . $search . '%'
                            );
                    });
                }

                $query = $this->applySorting($query, $sort);

                $secondaryProducts = $query
                    ->paginate($perPage, ['*'], 'secondary_page')
                    ->withQueryString();

                $secondaryProducts->getCollection()->transform(
                    function ($product) {
                        $product->database = 'mysql_second';

                        $product->database_label = 'Secondary';

                        $product->synced = DB::connection(
                            'mysql'
                        )
                            ->table('products')
                            ->where('name', $product->name)
                            ->exists();

                        return $product;
                    }
                );
            } catch (Throwable $e) {
                Log::error(
                    'Secondary product loading failed: ' .
                        $e->getMessage()
                );

                return back()->with(
                    'error',
                    'Unable to load secondary products: ' .
                        $e->getMessage()
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $primaryTotal = 0;
        $secondaryTotal = 0;

        try {
            $primaryTotal = DB::connection('mysql')
                ->table('products')
                ->count();
        } catch (Throwable $e) {
            $primaryTotal = 0;
        }

        try {
            $secondaryTotal = DB::connection('mysql_second')
                ->table('products')
                ->count();
        } catch (Throwable $e) {
            $secondaryTotal = 0;
        }

        $syncedCount = 0;

        try {
            $primaryNames = DB::connection('mysql')
                ->table('products')
                ->pluck('name');

            if ($primaryNames->isNotEmpty()) {
                $syncedCount = DB::connection('mysql_second')
                    ->table('products')
                    ->whereIn('name', $primaryNames)
                    ->count();
            }
        } catch (Throwable $e) {
            $syncedCount = 0;
        }

        return view(
            'products.index',
            compact(
                'primaryProducts',
                'secondaryProducts',
                'search',
                'database',
                'sort',
                'primaryTotal',
                'secondaryTotal',
                'syncedCount'
            )
        );
    }

    /**
     * Apply sorting.
     */
    private function applySorting($query, string $sort)
    {
        switch ($sort) {
            case 'oldest':
                return $query->orderBy('id', 'asc');

            case 'name_asc':
                return $query->orderBy('name', 'asc');

            case 'name_desc':
                return $query->orderBy('name', 'desc');

            case 'newest':
            default:
                return $query->orderBy('id', 'desc');
        }
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a new product.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'detail' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'database' => [
                'required',
                'in:mysql,mysql_second',
            ],
        ]);

        try {
            DB::connection($validated['database'])
                ->table('products')
                ->insert([
                    'name' => $validated['name'],
                    'detail' => $validated['detail'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

            return redirect()
                ->route('products.index')
                ->with(
                    'success',
                    'Product added successfully to ' .
                        ($validated['database'] === 'mysql'
                            ? 'Primary Database.'
                            : 'Secondary Database.')
                );
        } catch (Throwable $e) {
            Log::error(
                'Product creation failed: ' .
                    $e->getMessage()
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to create product: ' .
                        $e->getMessage()
                );
        }
    }

    /**
     * Show edit form.
     */
    public function edit(string $database, int $id)
    {
        if (!in_array($database, [
            'mysql',
            'mysql_second'
        ])) {
            abort(404);
        }

        try {
            $product = DB::connection($database)
                ->table('products')
                ->where('id', $id)
                ->first();

            if (!$product) {
                return redirect()
                    ->route('products.index')
                    ->with(
                        'error',
                        'Product not found.'
                    );
            }

            return view(
                'products.edit',
                compact(
                    'product',
                    'database'
                )
            );
        } catch (Throwable $e) {
            return back()->with(
                'error',
                'Unable to load product: ' .
                    $e->getMessage()
            );
        }
    }

    /**
     * Update product.
     */
    public function update(
        Request $request,
        string $database,
        int $id
    ) {
        if (!in_array($database, [
            'mysql',
            'mysql_second'
        ])) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'detail' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        try {
            DB::connection($database)
                ->table('products')
                ->where('id', $id)
                ->update([
                    'name' => $validated['name'],
                    'detail' => $validated['detail'] ?? null,
                    'updated_at' => now(),
                ]);

            return redirect()
                ->route('products.index')
                ->with(
                    'success',
                    'Product updated successfully.'
                );
        } catch (Throwable $e) {
            Log::error(
                'Product update failed: ' .
                    $e->getMessage()
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to update product: ' .
                        $e->getMessage()
                );
        }
    }

    /**
     * Delete product.
     */
    public function destroy(
        Request $request,
        string $database,
        int $id
    ) {
        if (!in_array($database, [
            'mysql',
            'mysql_second'
        ])) {
            abort(404);
        }

        try {
            $product = DB::connection($database)
                ->table('products')
                ->where('id', $id)
                ->first();

            if (!$product) {
                return back()->with(
                    'error',
                    'Product not found.'
                );
            }

            DB::connection($database)
                ->table('products')
                ->where('id', $id)
                ->delete();

            return back()->with(
                'success',
                "Product '{$product->name}' deleted successfully."
            );
        } catch (Throwable $e) {
            Log::error(
                'Product deletion failed: ' .
                    $e->getMessage()
            );

            return back()->with(
                'error',
                'Unable to delete product: ' .
                    $e->getMessage()
            );
        }
    }

    /**
     * Original dynamic database connection example.
     */
    public function getRecord()
    {
        $product = new \App\Models\Product();

        $product->setConnection('mysql_second');

        return $product->find(1);
    }

    public function syncToPrimary($id)
    {
        $product = DB::connection('mysql_second')
            ->table('products')
            ->where('id', $id)
            ->first();

        if (!$product) {
            return redirect()
                ->route('products.index')
                ->with('error', 'Product not found in secondary database.');
        }

        $exists = DB::connection('mysql')
            ->table('products')
            ->where('name', $product->name)
            ->exists();

        if ($exists) {
            return redirect()
                ->route('products.index')
                ->with('warning', 'Product already exists in primary database.');
        }

        DB::connection('mysql')->table('products')->insert([
            'name'       => $product->name,
            'description' => $product->description ?? null,
            'price'      => $product->price,
            'created_at' => $product->created_at ?? now(),
            'updated_at' => $product->updated_at ?? now(),
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product synchronized from secondary to primary successfully.');
    }
}

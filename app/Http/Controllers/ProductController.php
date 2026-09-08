<?php

namespace App\Http\Controllers;

use App\Imports\ProductsImport;
use App\Models\ProductCategory;
use App\Models\ProductTag;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));

        $database = $request->input('database', 'all');

        if (!in_array($database, ['all', 'mysql', 'mysql_second'])) {
            $database = 'all';
        }

        $sort = $request->input('sort', 'oldest');

        if (!in_array($sort, ['newest', 'oldest', 'name_asc', 'name_desc'])) {
            $sort = 'oldest';
        }

        $perPage = 10;

        $primaryProducts = collect();
        $secondaryProducts = collect();

        if ($database === 'all' || $database === 'mysql') {
            try {
                $query = DB::connection('mysql')->table('products');

                if ($search !== '') {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%')
                            ->orWhere('detail', 'like', '%' . $search . '%');
                    });
                }

                $query = $this->applySorting($query, $sort);

                $primaryProducts = $query
                    ->paginate($perPage, ['*'], 'primary_page')
                    ->withQueryString();

                $primaryProducts->getCollection()->transform(function ($product) {
                    $product->database = 'mysql';
                    $product->database_label = 'Primary';
                    $product->synced = DB::connection('mysql_second')
                        ->table('products')
                        ->where('name', $product->name)
                        ->exists();

                    return $product;
                });
            } catch (Throwable $e) {
                Log::error('Primary product loading failed: ' . $e->getMessage());
                return back()->with('error', 'Unable to load primary products: ' . $e->getMessage());
            }
        }

        if ($database === 'all' || $database === 'mysql_second') {
            try {
                $query = DB::connection('mysql_second')->table('products');

                if ($search !== '') {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%')
                            ->orWhere('detail', 'like', '%' . $search . '%');
                    });
                }

                $query = $this->applySorting($query, $sort);

                $secondaryProducts = $query
                    ->paginate($perPage, ['*'], 'secondary_page')
                    ->withQueryString();

                $secondaryProducts->getCollection()->transform(function ($product) {
                    $product->database = 'mysql_second';
                    $product->database_label = 'Secondary';
                    $product->synced = DB::connection('mysql')
                        ->table('products')
                        ->where('name', $product->name)
                        ->exists();

                    return $product;
                });
            } catch (Throwable $e) {
                Log::error('Secondary product loading failed: ' . $e->getMessage());
                return back()->with('error', 'Unable to load secondary products: ' . $e->getMessage());
            }
        }

        $primaryTotal = 0;
        $secondaryTotal = 0;

        try {
            $primaryTotal = DB::connection('mysql')->table('products')->count();
        } catch (Throwable $e) {
            $primaryTotal = 0;
        }

        try {
            $secondaryTotal = DB::connection('mysql_second')->table('products')->count();
        } catch (Throwable $e) {
            $secondaryTotal = 0;
        }

        $syncedCount = 0;

        try {
            $primaryNames = DB::connection('mysql')->table('products')->pluck('name');

            if ($primaryNames->isNotEmpty()) {
                $syncedCount = DB::connection('mysql_second')
                    ->table('products')
                    ->whereIn('name', $primaryNames)
                    ->count();
            }
        } catch (Throwable $e) {
            $syncedCount = 0;
        }

        $categories = ProductCategory::all();
        $tags = ProductTag::all();

        $trashed = collect();
        $archived = collect();

        try {
            $trashed = DB::connection('mysql')
                ->table('products')
                ->whereNotNull('deleted_at')
                ->get();
        } catch (Throwable $e) {
            $trashed = collect();
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
                'syncedCount',
                'categories',
                'tags',
                'trashed',
                'archived'
            )
        );
    }

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

    public function create()
    {
        $categories = ProductCategory::all();
        $tags = ProductTag::all();

        return view('products.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'detail' => ['nullable', 'string', 'max:1000'],
            'database' => ['required', 'in:mysql,mysql_second'],
            'category_id' => ['nullable', 'exists:product_categories,id'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:product_tags,id'],
            'media' => ['nullable', 'array'],
            'media.*' => ['file', 'max:10240'],
        ]);

        try {
            DB::connection($validated['database'])->transaction(function () use ($validated, $request) {
                $productId = DB::connection($validated['database'])
                    ->table('products')
                    ->insertGetId([
                        'name' => $validated['name'],
                        'detail' => $validated['detail'] ?? null,
                        'category_id' => $validated['category_id'] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                if ($request->has('tag_ids') && !empty($validated['tag_ids'])) {
                    foreach ($validated['tag_ids'] as $tagId) {
                        DB::connection($validated['database'])
                            ->table('product_tag_product')
                            ->insert([
                                'product_tag_id' => $tagId,
                                'product_id' => $productId,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                    }
                }
            });

            return redirect()
                ->route('products.index')
                ->with('success', 'Product added successfully.');
        } catch (Throwable $e) {
            Log::error('Product creation failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Unable to create product: ' . $e->getMessage());
        }
    }

    public function edit(string $database, int $id)
    {
        if (!in_array($database, ['mysql', 'mysql_second'])) {
            abort(404);
        }

        try {
            $product = DB::connection($database)
                ->table('products')
                ->where('id', $id)
                ->first();

            if (!$product) {
                return redirect()->route('products.index')->with('error', 'Product not found.');
            }

            $categories = ProductCategory::all();
            $tags = ProductTag::all();

            return view('products.edit', compact('product', 'database', 'categories', 'tags'));
        } catch (Throwable $e) {
            return back()->with('error', 'Unable to load product: ' . $e->getMessage());
        }
    }

    public function update(Request $request, string $database, int $id)
    {
        if (!in_array($database, ['mysql', 'mysql_second'])) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'detail' => ['nullable', 'string', 'max:1000'],
            'category_id' => ['nullable', 'exists:product_categories,id'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:product_tags,id'],
        ]);

        try {
            DB::connection($database)->transaction(function () use ($validated, $database, $id, $request) {
                DB::connection($database)
                    ->table('products')
                    ->where('id', $id)
                    ->update([
                        'name' => $validated['name'],
                        'detail' => $validated['detail'] ?? null,
                        'category_id' => $validated['category_id'] ?? null,
                        'updated_at' => now(),
                    ]);

                DB::connection($database)->table('product_tag_product')->where('product_id', $id)->delete();

                if ($request->has('tag_ids') && !empty($validated['tag_ids'])) {
                    foreach ($validated['tag_ids'] as $tagId) {
                        DB::connection($database)
                            ->table('product_tag_product')
                            ->insert([
                                'product_tag_id' => $tagId,
                                'product_id' => $id,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                    }
                }
            });

            return redirect()
                ->route('products.index')
                ->with('success', 'Product updated successfully.');
        } catch (Throwable $e) {
            Log::error('Product update failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Unable to update product: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, string $database, int $id)
    {
        if (!in_array($database, ['mysql', 'mysql_second'])) {
            abort(404);
        }

        try {
            $product = DB::connection($database)
                ->table('products')
                ->where('id', $id)
                ->first();

            if (!$product) {
                return back()->with('error', 'Product not found.');
            }

            DB::connection($database)
                ->table('products')
                ->where('id', $id)
                ->delete();

            return back()->with('success', "Product '{$product->name}' deleted successfully.");
        } catch (Throwable $e) {
            Log::error('Product deletion failed: ' . $e->getMessage());
            return back()->with('error', 'Unable to delete product: ' . $e->getMessage());
        }
    }

    public function importProducts(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,csv,ods', 'max:10240'],
            'database' => ['required', 'in:mysql,mysql_second'],
        ]);

        try {
            Excel::import(new ProductsImport($request->input('database')), $request->file('file'));

            return back()->with('success', 'Products imported successfully.');
        } catch (Throwable $e) {
            Log::error('Product import failed: ' . $e->getMessage());
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function exportProducts(Request $request)
    {
        $database = $request->input('database', 'all');

        if (!in_array($database, ['all', 'mysql', 'mysql_second'])) {
            $database = 'all';
        }

        $filename = 'products_export_' . now()->format('Y_m_d_H_i_s') . '.xlsx';

        return Excel::download(new \App\Exports\ProductsExport($database), $filename);
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
            'database' => ['required', 'in:mysql,mysql_second'],
        ]);

        try {
            $count = DB::connection($request->input('database'))
                ->table('products')
                ->whereIn('id', $request->input('ids'))
                ->count();

            DB::connection($request->input('database'))
                ->table('products')
                ->whereIn('id', $request->input('ids'))
                ->delete();

            return back()->with('success', "{$count} product(s) deleted successfully.");
        } catch (Throwable $e) {
            Log::error('Bulk delete failed: ' . $e->getMessage());
            return back()->with('error', 'Bulk delete failed: ' . $e->getMessage());
        }
    }

    public function bulkSync(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
        ]);

        try {
            $synced = 0;
            $skipped = 0;

            $products = DB::connection('mysql')
                ->table('products')
                ->whereIn('id', $request->input('ids'))
                ->get();

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

            return back()->with('success', "{$synced} product(s) synced. {$skipped} skipped.");
        } catch (Throwable $e) {
            Log::error('Bulk sync failed: ' . $e->getMessage());
            return back()->with('error', 'Bulk sync failed: ' . $e->getMessage());
        }
    }

    public function trash()
    {
        try {
            $trashed = DB::connection('mysql')
                ->table('products')
                ->whereNotNull('deleted_at')
                ->latest('deleted_at')
                ->paginate(15);
        } catch (Throwable $e) {
            $trashed = collect()->paginate(15);
        }

        return view('products.trash', compact('trashed'));
    }

    public function restore(Request $request, int $id)
    {
        try {
            $product = DB::connection('mysql')
                ->table('products')
                ->where('id', $id)
                ->whereNotNull('deleted_at')
                ->first();

            if (!$product) {
                return back()->with('error', 'Product not found in trash.');
            }

            DB::connection('mysql')
                ->table('products')
                ->where('id', $id)
                ->update(['deleted_at' => null]);

            return back()->with('success', 'Product restored successfully.');
        } catch (Throwable $e) {
            Log::error('Product restore failed: ' . $e->getMessage());
            return back()->with('error', 'Restore failed: ' . $e->getMessage());
        }
    }

    public function syncToPrimary($id)
    {
        $product = DB::connection('mysql_second')
            ->table('products')
            ->where('id', $id)
            ->first();

        if (!$product) {
            return redirect()->route('products.index')->with('error', 'Product not found in secondary database.');
        }

        $exists = DB::connection('mysql')
            ->table('products')
            ->where('name', $product->name)
            ->exists();

        if ($exists) {
            return redirect()->route('products.index')->with('warning', 'Product already exists in primary database.');
        }

        DB::connection('mysql')->table('products')->insert([
            'name' => $product->name,
            'detail' => $product->detail ?? null,
            'created_at' => $product->created_at ?? now(),
            'updated_at' => $product->updated_at ?? now(),
        ]);

        return redirect()->route('products.index')->with('success', 'Product synchronized successfully.');
    }
}

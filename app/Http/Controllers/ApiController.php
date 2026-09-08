<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function products(Request $request): JsonResponse
    {
        $database = $request->input('database', 'all');

        if (!in_array($database, ['all', 'mysql', 'mysql_second'])) {
            $database = 'all';
        }

        $query = Product::query();

        if ($database !== 'all') {
            $query->setConnection($database);
        }

        $products = $query->latest()->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    public function productShow(string $database, int $id): JsonResponse
    {
        if (!in_array($database, ['mysql', 'mysql_second'])) {
            return response()->json(['success' => false, 'message' => 'Invalid database.'], 404);
        }

        $product = DB::connection($database)
            ->table('products')
            ->where('id', $id)
            ->first();

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found.'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }

    public function productStore(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'detail' => ['nullable', 'string', 'max:1000'],
            'database' => ['required', 'in:mysql,mysql_second'],
        ]);

        $id = DB::connection($validated['database'])
            ->table('products')
            ->insertGetId([
                'name' => $validated['name'],
                'detail' => $validated['detail'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully.',
            'data' => ['id' => $id],
        ], 201);
    }

    public function productUpdate(Request $request, string $database, int $id): JsonResponse
    {
        if (!in_array($database, ['mysql', 'mysql_second'])) {
            return response()->json(['success' => false, 'message' => 'Invalid database.'], 404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'detail' => ['nullable', 'string', 'max:1000'],
        ]);

        $updated = DB::connection($database)
            ->table('products')
            ->where('id', $id)
            ->update(array_merge($validated, ['updated_at' => now()]));

        if (!$updated) {
            return response()->json(['success' => false, 'message' => 'Product not found or no changes made.'], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully.',
        ]);
    }

    public function productDestroy(string $database, int $id): JsonResponse
    {
        if (!in_array($database, ['mysql', 'mysql_second'])) {
            return response()->json(['success' => false, 'message' => 'Invalid database.'], 404);
        }

        $deleted = DB::connection($database)
            ->table('products')
            ->where('id', $id)
            ->delete();

        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Product not found.'], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully.',
        ]);
    }

    public function blogs(Request $request): JsonResponse
    {
        $query = Blog::query();

        if ($request->filled('published')) {
            $query->where('is_published', $request->boolean('published'));
        }

        $blogs = $query->latest()->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $blogs,
        ]);
    }

    public function blogShow(int $id): JsonResponse
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json(['success' => false, 'message' => 'Blog not found.'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $blog,
        ]);
    }

    public function blogStore(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'is_published' => ['boolean'],
        ]);

        $blog = Blog::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Blog created successfully.',
            'data' => $blog,
        ], 201);
    }

    public function blogUpdate(Request $request, int $id): JsonResponse
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json(['success' => false, 'message' => 'Blog not found.'], 404);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'is_published' => ['boolean'],
        ]);

        $blog->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Blog updated successfully.',
            'data' => $blog,
        ]);
    }

    public function blogDestroy(int $id): JsonResponse
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json(['success' => false, 'message' => 'Blog not found.'], 404);
        }

        $blog->delete();

        return response()->json([
            'success' => true,
            'message' => 'Blog deleted successfully.',
        ]);
    }

    public function categories(Request $request): JsonResponse
    {
        $type = $request->input('type', 'product');

        if ($type === 'blog') {
            $categories = BlogCategory::latest()->paginate(15);
        } else {
            $categories = \App\Models\ProductCategory::latest()->paginate(15);
        }

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    public function categoryStore(Request $request): JsonResponse
    {
        $type = $request->input('type', 'product');

        if ($type === 'blog') {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'slug' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string', 'max:1000'],
            ]);

            $category = BlogCategory::create($validated);
        } else {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'slug' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string', 'max:1000'],
            ]);

            $category = \App\Models\ProductCategory::create($validated);
        }

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully.',
            'data' => $category,
        ], 201);
    }
}

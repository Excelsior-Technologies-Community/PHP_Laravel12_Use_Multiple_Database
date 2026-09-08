<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::orderBy('name')->paginate(20);

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:product_categories,name'],
            'slug' => ['required', 'string', 'max:255', 'unique:product_categories,slug'],
            'description' => ['nullable', 'string', 'max:2000'],
            'is_active' => ['boolean'],
        ]);

        try {
            ProductCategory::create($validated);

            return redirect()->route('categories.index')->with('success', 'Category created successfully.');
        } catch (Throwable $e) {
            Log::error('Category creation failed: ' . $e->getMessage());

            return back()->withInput()->with('error', 'Unable to create category: ' . $e->getMessage());
        }
    }

    public function edit(ProductCategory $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, ProductCategory $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:product_categories,name,' . $category->id],
            'slug' => ['required', 'string', 'max:255', 'unique:product_categories,slug,' . $category->id],
            'description' => ['nullable', 'string', 'max:2000'],
            'is_active' => ['boolean'],
        ]);

        try {
            $category->update($validated);

            return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
        } catch (Throwable $e) {
            Log::error('Category update failed: ' . $e->getMessage());

            return back()->withInput()->with('error', 'Unable to update category: ' . $e->getMessage());
        }
    }

    public function destroy(ProductCategory $category)
    {
        try {
            $category->delete();

            return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
        } catch (Throwable $e) {
            Log::error('Category deletion failed: ' . $e->getMessage());

            return back()->with('error', 'Unable to delete category: ' . $e->getMessage());
        }
    }
}

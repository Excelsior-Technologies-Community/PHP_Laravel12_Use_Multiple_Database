<?php

namespace App\Http\Controllers;

use App\Models\ProductTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProductTagController extends Controller
{
    public function index()
    {
        $tags = ProductTag::orderBy('name')->paginate(20);

        return view('tags.index', compact('tags'));
    }

    public function create()
    {
        return view('tags.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:product_tags,name'],
            'slug' => ['required', 'string', 'max:255', 'unique:product_tags,slug'],
            'color' => ['nullable', 'string', 'max:7'],
        ]);

        try {
            ProductTag::create($validated);

            return redirect()->route('tags.index')->with('success', 'Tag created successfully.');
        } catch (Throwable $e) {
            Log::error('Tag creation failed: ' . $e->getMessage());

            return back()->withInput()->with('error', 'Unable to create tag: ' . $e->getMessage());
        }
    }

    public function edit(ProductTag $tag)
    {
        return view('tags.edit', compact('tag'));
    }

    public function update(Request $request, ProductTag $tag)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:product_tags,name,' . $tag->id],
            'slug' => ['required', 'string', 'max:255', 'unique:product_tags,slug,' . $tag->id],
            'color' => ['nullable', 'string', 'max:7'],
        ]);

        try {
            $tag->update($validated);

            return redirect()->route('tags.index')->with('success', 'Tag updated successfully.');
        } catch (Throwable $e) {
            Log::error('Tag update failed: ' . $e->getMessage());

            return back()->withInput()->with('error', 'Unable to update tag: ' . $e->getMessage());
        }
    }

    public function destroy(ProductTag $tag)
    {
        try {
            $tag->delete();

            return redirect()->route('tags.index')->with('success', 'Tag deleted successfully.');
        } catch (Throwable $e) {
            Log::error('Tag deletion failed: ' . $e->getMessage());

            return back()->with('error', 'Unable to delete tag: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogCategoryRequest;
use App\Http\Requests\UpdateBlogCategoryRequest;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = BlogCategory::query()
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15);

        return view('blog_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('blog_categories.create');
    }

    public function store(StoreBlogCategoryRequest $request)
    {
        $category = BlogCategory::create($request->validated());

        return redirect()
            ->route('blog-categories.index')
            ->with('success', 'Blog category created successfully.');
    }

    public function edit(BlogCategory $blog_category)
    {
        return view('blog_categories.edit', compact('blog_category'));
    }

    public function update(UpdateBlogCategoryRequest $request, BlogCategory $blog_category)
    {
        $blog_category->update($request->validated());

        return redirect()
            ->route('blog-categories.index')
            ->with('success', 'Blog category updated successfully.');
    }

    public function destroy(BlogCategory $blog_category)
    {
        $blog_category->delete();

        return back()->with('success', 'Blog category deleted successfully.');
    }
}

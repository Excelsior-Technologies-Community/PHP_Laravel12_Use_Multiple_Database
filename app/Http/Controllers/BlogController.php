<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));

        $query = Blog::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%");
            });
        }

        $blogs = $query->latest()->paginate(10);

        return view('blogs.index', compact('blogs', 'search'));
    }

    public function create()
    {
        $categories = BlogCategory::orderBy('name')->get();
        $tags = BlogTag::orderBy('name')->get();

        return view('blogs.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:blogs,slug'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'body' => ['required', 'string'],
            'featured_image' => ['nullable', 'string', 'max:255'],
            'is_published' => ['boolean'],
            'is_featured' => ['boolean'],
            'published_at' => ['nullable', 'date'],
            'user_id' => ['nullable', 'exists:users,id'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['exists:blog_categories,id'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:blog_tags,id'],
        ]);

        try {
            $blog = Blog::create($validated);

            if (!empty($validated['category_ids'])) {
                $blog->categories()->attach($validated['category_ids']);
            }

            if (!empty($validated['tag_ids'])) {
                $blog->tags()->attach($validated['tag_ids']);
            }

            return redirect()->route('blogs.index')->with('success', 'Blog created successfully.');
        } catch (Throwable $e) {
            Log::error('Blog creation failed: ' . $e->getMessage());

            return back()->withInput()->with('error', 'Unable to create blog: ' . $e->getMessage());
        }
    }

    public function show(Blog $blog)
    {
        return view('blogs.show', compact('blog'));
    }

    public function edit(Blog $blog)
    {
        $categories = BlogCategory::orderBy('name')->get();
        $tags = BlogTag::orderBy('name')->get();

        return view('blogs.edit', compact('blog', 'categories', 'tags'));
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:blogs,slug,' . $blog->id],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'body' => ['required', 'string'],
            'featured_image' => ['nullable', 'string', 'max:255'],
            'is_published' => ['boolean'],
            'is_featured' => ['boolean'],
            'published_at' => ['nullable', 'date'],
            'user_id' => ['nullable', 'exists:users,id'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['exists:blog_categories,id'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:blog_tags,id'],
        ]);

        try {
            $blog->update($validated);

            $blog->categories()->sync($validated['category_ids'] ?? []);
            $blog->tags()->sync($validated['tag_ids'] ?? []);

            return redirect()->route('blogs.index')->with('success', 'Blog updated successfully.');
        } catch (Throwable $e) {
            Log::error('Blog update failed: ' . $e->getMessage());

            return back()->withInput()->with('error', 'Unable to update blog: ' . $e->getMessage());
        }
    }

    public function destroy(Blog $blog)
    {
        try {
            $blog->categories()->detach();
            $blog->tags()->detach();
            $blog->delete();

            return redirect()->route('blogs.index')->with('success', 'Blog deleted successfully.');
        } catch (Throwable $e) {
            Log::error('Blog deletion failed: ' . $e->getMessage());

            return back()->with('error', 'Unable to delete blog: ' . $e->getMessage());
        }
    }
}

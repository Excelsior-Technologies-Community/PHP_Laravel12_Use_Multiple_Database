<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogTagRequest;
use App\Http\Requests\UpdateBlogTagRequest;
use App\Models\BlogTag;
use Illuminate\Http\Request;

class BlogTagController extends Controller
{
    public function index(Request $request)
    {
        $tags = BlogTag::query()
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15);

        return view('blog_tags.index', compact('tags'));
    }

    public function create()
    {
        return view('blog_tags.create');
    }

    public function store(StoreBlogTagRequest $request)
    {
        $tag = BlogTag::create($request->validated());

        return redirect()
            ->route('blog-tags.index')
            ->with('success', 'Blog tag created successfully.');
    }

    public function edit(BlogTag $blog_tag)
    {
        return view('blog_tags.edit', compact('blog_tag'));
    }

    public function update(UpdateBlogTagRequest $request, BlogTag $blog_tag)
    {
        $blog_tag->update($request->validated());

        return redirect()
            ->route('blog-tags.index')
            ->with('success', 'Blog tag updated successfully.');
    }

    public function destroy(BlogTag $blog_tag)
    {
        $blog_tag->delete();

        return back()->with('success', 'Blog tag deleted successfully.');
    }
}

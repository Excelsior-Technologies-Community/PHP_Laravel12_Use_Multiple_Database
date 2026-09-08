<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:blog,slug'],
            'body' => ['nullable', 'string'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'featured_image' => ['nullable', 'string', 'max:255'],
            'is_published' => ['boolean'],
            'is_featured' => ['boolean'],
            'published_at' => ['nullable', 'date'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['exists:blog_categories,id'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:blog_tags,id'],
        ];
    }
}

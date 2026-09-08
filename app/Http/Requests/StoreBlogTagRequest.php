<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlogTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:blog_tags,name'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:blog_tags,slug'],
        ];
    }
}

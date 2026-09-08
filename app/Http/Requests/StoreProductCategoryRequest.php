<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:product_categories,name'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:product_categories,slug'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}

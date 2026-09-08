<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $category = $this->route('product_category');

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('product_categories', 'name')->ignore($category)],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('product_categories', 'slug')->ignore($category)],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}

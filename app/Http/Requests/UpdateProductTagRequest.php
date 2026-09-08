<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tag = $this->route('product_tag');

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('product_tags', 'name')->ignore($tag)],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('product_tags', 'slug')->ignore($tag)],
        ];
    }
}

@extends('layouts.app')

@section('title', 'Edit Variant')

@section('page-title', 'Edit Variant')

@section('content')

    <div class="max-w-2xl">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 lg:p-8">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Variant</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update variant details.</p>
            </div>

            @if($errors->any())
                <x-alert type="danger" class="mb-6">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-alert>
            @endif

            <form action="{{ route('variants.update', $variant->id) ?? '#' }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-5">
                    <div>
                        <label for="product_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Product</label>
                        <select name="product_id" id="product_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-primary focus:ring-primary shadow-sm">
                            <option value="">Select a product</option>
                            <option value="1" {{ old('product_id', $variant->product_id ?? '') == '1' ? 'selected' : '' }}>Sample Product 1</option>
                            <option value="2" {{ old('product_id', $variant->product_id ?? '') == '2' ? 'selected' : '' }}>Sample Product 2</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="size" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Size</label>
                            <input type="text" name="size" id="size" value="{{ old('size', $variant->size ?? '') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-primary focus:ring-primary shadow-sm">
                        </div>

                        <div>
                            <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Color</label>
                            <input type="text" name="color" id="color" value="{{ old('color', $variant->color ?? '') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-primary focus:ring-primary shadow-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Price</label>
                            <input type="number" name="price" id="price" value="{{ old('price', $variant->price ?? '') }}" step="0.01" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-primary focus:ring-primary shadow-sm">
                        </div>

                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stock</label>
                            <input type="number" name="stock" id="stock" value="{{ old('stock', $variant->stock ?? '') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-primary focus:ring-primary shadow-sm">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6 sm:mt-8">
                    <a href="{{ route('variants.index') ?? '#' }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-primary hover:bg-primary-dark text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                        Update Variant
                    </button>
                </div>

            </form>
        </div>
    </div>

@endsection

@extends('layouts.app')

@section('title', 'Import Products')

@section('page-title', 'Import Products')

@section('content')

    <div class="max-w-2xl">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 lg:p-8">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Bulk Import Products</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Upload a CSV or JSON file to import multiple products at once.</p>
            </div>

            <x-alert type="info" class="mb-6">
                <strong>Supported formats:</strong> CSV (.csv) and JSON (.json). Each row should contain <code class="bg-blue-100 dark:bg-blue-900 px-1 rounded">name</code> and optionally <code class="bg-blue-100 dark:bg-blue-900 px-1 rounded">detail</code>.
            </x-alert>

            <form action="{{ route('products.import') ?? '#' }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label for="database" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Target Database</label>
                    <select name="database" id="database" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-primary focus:ring-primary shadow-sm">
                        <option value="mysql">Primary Database</option>
                        <option value="mysql_second">Secondary Database</option>
                    </select>
                </div>

                <div>
                    <label for="import_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Upload File</label>
                    <input type="file" name="import_file" id="import_file" accept=".csv,.json" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-primary focus:ring-primary shadow-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Maximum file size: 5MB</p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        Import Products
                    </button>
                </div>

            </form>
        </div>
    </div>

@endsection

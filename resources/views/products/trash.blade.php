@extends('layouts.app')

@section('title', 'Trashed Products')

@section('page-title', 'Trashed Products')

@section('content')

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Deleted Products</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Products that have been permanently deleted. Use trash/restore if soft deletes are enabled.</p>
        </div>

        <div class="p-4 sm:p-6">
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No trashed products found. Enable soft deletes on the Product model to use this feature.</p>
                <div class="mt-6">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-primary hover:bg-primary-dark text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                        Back to Products
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection

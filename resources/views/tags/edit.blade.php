@extends('layouts.app')

@section('title', 'Edit Tag')

@section('page-title', 'Edit Tag')

@section('content')

    <div class="max-w-2xl">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 lg:p-8">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Tag</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update tag details.</p>
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

            <form action="{{ route('product-tags.update', $tag->id) ?? '#' }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-5">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tag Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $tag->name ?? '') }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-primary focus:ring-primary shadow-sm">
                    </div>

                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Slug</label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $tag->slug ?? '') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-primary focus:ring-primary shadow-sm">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6 sm:mt-8">
                    <a href="{{ route('product-tags.index') ?? '#' }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-primary hover:bg-primary-dark text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                        Update Tag
                    </button>
                </div>

            </form>
        </div>
    </div>

@endsection

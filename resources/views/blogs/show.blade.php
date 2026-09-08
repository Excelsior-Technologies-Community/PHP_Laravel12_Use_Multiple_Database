@extends('layouts.app')

@section('title', $post->title ?? 'Blog Post')

@section('page-title', 'Post Details')

@section('content')

    <div class="max-w-3xl">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 lg:p-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ $post->title ?? 'Untitled Post' }}</h1>
                    <div class="mt-2 flex flex-wrap items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                        <span>By {{ $post->author ?? 'Admin' }}</span>
                        <span class="hidden sm:inline">|</span>
                        <span>{{ $post->published_at ?? $post->created_at ?? 'Unknown' }}</span>
                        <span class="hidden sm:inline">|</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            {{ $post->status ?? 'Draft' }}
                        </span>
                    </div>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('blogs.edit', $post->id) ?? '#' }}" class="inline-flex items-center justify-center px-3 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                        Edit
                    </a>
                    <a href="{{ route('blogs.index') ?? '#' }}" class="inline-flex items-center justify-center px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        Back
                    </a>
                </div>
            </div>

            <div class="prose dark:prose-invert max-w-none">
                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line leading-relaxed">{{ $post->content ?? 'No content available.' }}</p>
            </div>
        </div>
    </div>

@endsection

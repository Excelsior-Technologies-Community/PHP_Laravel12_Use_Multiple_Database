@extends('layouts.app')

@section('title', 'Activity Log Details')

@section('page-title', 'Activity Log Details')

@section('content')

    <div class="max-w-3xl">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 lg:p-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Activity Log #{{ $activity->id ?? '001' }}</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ $activity->created_at ?? now() }}
                    </p>
                </div>
                <a href="{{ route('activity-logs.index') ?? '#' }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    Back
                </a>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Action</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $activity->action ?? 'Created' }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">User</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $activity->user ?? 'Admin' }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Subject</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $activity->subject ?? 'Product' }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Subject ID</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $activity->subject_id ?? '123' }}</p>
                    </div>
                </div>

                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Changes</p>
                    <pre class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ $activity->changes ?? 'No changes recorded.' }}</pre>
                </div>
            </div>
        </div>
    </div>

@endsection

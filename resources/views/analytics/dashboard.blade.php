@extends('layouts.app')

@section('title', 'Analytics Dashboard')

@section('page-title', 'Analytics Dashboard')

@section('content')

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Products</p>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">1,234</p>
            <p class="mt-1 text-xs text-green-600 dark:text-green-400">+12% from last month</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Sync Rate</p>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">87%</p>
            <p class="mt-1 text-xs text-green-600 dark:text-green-400">+5% from last week</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Blog Posts</p>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">56</p>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">3 published this week</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Categories</p>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">12</p>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Across all products</p>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Product Distribution</h3>
            <div class="h-64 flex items-center justify-center bg-gray-50 dark:bg-gray-700 rounded-lg">
                <p class="text-sm text-gray-500 dark:text-gray-400">Chart placeholder - integrate with Chart.js or similar</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Sync Activity</h3>
            <div class="h-64 flex items-center justify-center bg-gray-50 dark:bg-gray-700 rounded-lg">
                <p class="text-sm text-gray-500 dark:text-gray-400">Chart placeholder - integrate with Chart.js or similar</p>
            </div>
        </div>
    </div>

@endsection

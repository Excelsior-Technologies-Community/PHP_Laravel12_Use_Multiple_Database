@extends('layouts.app')

@section('title', 'Sync Analytics')

@section('page-title', 'Sync Analytics')

@section('content')

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Synced</p>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">856</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending</p>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">23</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Failed</p>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">2</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Success Rate</p>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">97%</p>
        </div>

    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Sync Activity Over Time</h3>
        <div class="h-80 flex items-center justify-center bg-gray-50 dark:bg-gray-700 rounded-lg">
            <p class="text-sm text-gray-500 dark:text-gray-400">Chart placeholder - integrate with Chart.js or similar</p>
        </div>
    </div>

@endsection

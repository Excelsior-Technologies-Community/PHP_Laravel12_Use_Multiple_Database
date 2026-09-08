@extends('layouts.app')

@section('title', 'Run Migrations')

@section('page-title', 'Run Migrations')

@section('content')

    <div class="max-w-3xl">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 lg:p-8">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Run Migrations</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Execute pending database migrations.</p>
            </div>

            <div class="space-y-4 mb-6">
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Primary Database</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">mysql</p>
                    </div>
                    <form action="{{ route('database-tools.migrate.submit') ?? '#' }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="database" value="mysql">
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-primary hover:bg-primary-dark text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                            Run Migrations
                        </button>
                    </form>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Secondary Database</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">mysql_second</p>
                    </div>
                    <form action="{{ route('database-tools.migrate.submit') ?? '#' }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="database" value="mysql_second">
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-secondary hover:bg-secondary-dark text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-secondary focus:ring-offset-2">
                            Run Migrations
                        </button>
                    </form>
                </div>
            </div>

            <x-alert type="info" class="mb-6">
                Migrations will only run if there are pending migration files. Check the output for any errors.
            </x-alert>

            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Migration Status</h3>
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                        <span class="text-sm text-gray-600 dark:text-gray-300">create_products_table</span>
                        <span class="text-xs text-gray-400 ml-auto">Ran</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                        <span class="text-sm text-gray-600 dark:text-gray-300">add_sync_status_to_products</span>
                        <span class="text-xs text-gray-400 ml-auto">Ran</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                        <span class="text-sm text-gray-600 dark:text-gray-300">create_categories_table</span>
                        <span class="text-xs text-amber-600 ml-auto">Pending</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

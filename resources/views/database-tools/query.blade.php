@extends('layouts.app')

@section('title', 'SQL Query')

@section('page-title', 'SQL Query')

@section('content')

    <div class="max-w-4xl">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 lg:p-8">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">SQL Executor</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Run raw SQL queries against your databases. Use with caution.</p>
            </div>

            <x-alert type="warning" class="mb-6">
                <strong>Warning:</strong> This tool executes raw SQL. Always test queries in a development environment first. SELECT queries are recommended.
            </x-alert>

            <form action="{{ route('database-tools.query.submit') ?? '#' }}" method="POST">
                @csrf

                <div class="space-y-5">
                    <div>
                        <label for="database" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Database</label>
                        <select name="database" id="database" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-primary focus:ring-primary shadow-sm">
                            <option value="mysql">Primary Database</option>
                            <option value="mysql_second">Secondary Database</option>
                        </select>
                    </div>

                    <div>
                        <label for="query" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">SQL Query</label>
                        <textarea name="query" id="query" rows="6" placeholder="SELECT * FROM products LIMIT 10;" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-primary focus:ring-primary shadow-sm font-mono">{{ old('query') }}</textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6 sm:mt-8">
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-primary hover:bg-primary-dark text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                        Execute Query
                    </button>
                </div>

            </form>
        </div>
    </div>

@endsection

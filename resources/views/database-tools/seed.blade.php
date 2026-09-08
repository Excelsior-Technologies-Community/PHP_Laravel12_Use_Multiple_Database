@extends('layouts.app')

@section('title', 'Run Seeders')

@section('page-title', 'Run Seeders')

@section('content')

    <div class="max-w-2xl">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 lg:p-8">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Run Seeders</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Execute database seeders to populate your database with sample data.</p>
            </div>

            <x-alert type="warning" class="mb-6">
                <strong>Warning:</strong> Running seeders may insert duplicate or overwrite existing data. Use with caution.
            </x-alert>

            <form action="{{ route('database-tools.seed.submit') ?? '#' }}" method="POST">
                @csrf

                <div class="space-y-5">
                    <div>
                        <label for="class" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Seeder Class (Optional)</label>
                        <input type="text" name="class" id="class" placeholder="e.g. ProductSeeder" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-primary focus:ring-primary shadow-sm">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave empty to run all seeders.</p>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="force" id="force" value="1" class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                        <label for="force" class="text-sm text-gray-700 dark:text-gray-300">Force run in production</label>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6 sm:mt-8">
                    <a href="{{ route('database-tools.seed') ?? '#' }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-primary hover:bg-primary-dark text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                        Run Seeders
                    </button>
                </div>

            </form>
        </div>
    </div>

@endsection

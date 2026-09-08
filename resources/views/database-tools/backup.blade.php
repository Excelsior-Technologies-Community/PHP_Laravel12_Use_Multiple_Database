@extends('layouts.app')

@section('title', 'Database Backup')

@section('page-title', 'Database Backup')

@section('content')

    <div class="max-w-2xl">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 lg:p-8">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Database Backup</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Export a snapshot of your database for safekeeping.</p>
            </div>

            <x-alert type="info" class="mb-6">
                Backups are stored in the <code class="bg-blue-100 dark:bg-blue-900 px-1 rounded">storage/app/backups</code> directory. Ensure you have sufficient disk space.
            </x-alert>

            <form action="{{ route('database-tools.backup.submit') ?? '#' }}" method="POST">
                @csrf

                <div class="space-y-5">
                    <div>
                        <label for="database" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Database</label>
                        <select name="database" id="database" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-primary focus:ring-primary shadow-sm">
                            <option value="mysql">Primary Database</option>
                            <option value="mysql_second">Secondary Database</option>
                            <option value="both">Both Databases</option>
                        </select>
                    </div>

                    <div>
                        <label for="format" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Format</label>
                        <select name="format" id="format" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-primary focus:ring-primary shadow-sm">
                            <option value="sql">SQL</option>
                            <option value="gz">SQL (Gzipped)</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6 sm:mt-8">
                    <a href="{{ route('database-tools.backup') ?? '#' }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-primary hover:bg-primary-dark text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                        Run Backup
                    </button>
                </div>

            </form>
        </div>
    </div>

@endsection

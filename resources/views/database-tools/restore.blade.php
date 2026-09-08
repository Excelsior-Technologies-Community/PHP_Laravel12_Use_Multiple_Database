@extends('layouts.app')

@section('title', 'Restore Database')

@section('page-title', 'Restore Database')

@section('content')

    <div class="max-w-2xl">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 lg:p-8">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Restore Database</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Restore a database from a previous backup file.</p>
            </div>

            <x-alert type="warning" class="mb-6">
                <strong>Warning:</strong> Restoring will overwrite existing data. Make sure you have a current backup before proceeding.
            </x-alert>

            <form action="{{ route('database-tools.restore.submit') ?? '#' }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="space-y-5">
                    <div>
                        <label for="database" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Target Database</label>
                        <select name="database" id="database" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-primary focus:ring-primary shadow-sm">
                            <option value="mysql">Primary Database</option>
                            <option value="mysql_second">Secondary Database</option>
                        </select>
                    </div>

                    <div>
                        <label for="backup_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Backup File</label>
                        <input type="file" name="backup_file" id="backup_file" accept=".sql,.sql.gz" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-primary focus:ring-primary shadow-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Accepted formats: .sql, .sql.gz</p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6 sm:mt-8">
                    <a href="{{ route('database-tools.restore') ?? '#' }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                        Restore Database
                    </button>
                </div>

            </form>
        </div>
    </div>

@endsection

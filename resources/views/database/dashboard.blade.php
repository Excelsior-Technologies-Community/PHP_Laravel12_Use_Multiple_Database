@extends('layouts.app')

@section('title', 'Database Dashboard')

@section('page-title', 'Database Dashboard')

@section('content')

    @if(session('success'))
        <x-alert type="success" class="mb-6">
            {{ session('success') }}
        </x-alert>
    @endif

    @if(session('error'))
        <x-alert type="danger" class="mb-6">
            {{ session('error') }}
        </x-alert>
    @endif

    {{-- Statistics --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 border-l-4 border-l-primary">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Primary Database Products</p>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $defaultProducts ?? 0 }}</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 border-l-4 border-l-secondary">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Secondary Database Products</p>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $secondProducts ?? 0 }}</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 border-l-4 border-l-purple-500">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Products</p>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalProducts ?? (($defaultProducts ?? 0) + ($secondProducts ?? 0)) }}</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 border-l-4 border-l-orange-500">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Blog Records</p>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $blogCount ?? 0 }}</p>
        </div>

    </div>

    {{-- Quick Actions --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 mb-6 sm:mb-8">
        <h2 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white mb-4 sm:mb-6">Quick Actions</h2>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">

            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 sm:p-5">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white mb-2">Manage Products</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Search, filter, sort, create, edit and delete products.</p>
                <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-primary hover:bg-primary-dark text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                    Manage Products
                </a>
            </div>

            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 sm:p-5">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white mb-2">Synchronization</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Synchronize products between Primary and Secondary databases.</p>
                <form action="{{ route('products.sync-all') }}" method="POST" onsubmit="return confirm('Sync all Primary products to Secondary database?')">
                    @csrf
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        Sync All
                    </button>
                </form>
            </div>

            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 sm:p-5">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white mb-2">Database Health</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Check database connection status, response time and tables.</p>
                <a href="{{ route('database.health') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    Check Health
                </a>
            </div>

        </div>
    </div>

    {{-- Database Information --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 mb-6 sm:mb-8">
        <h2 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white mb-4 sm:mb-6">Database Connections</h2>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">

            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 sm:p-5">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white mb-4">Primary Database</h3>

                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Connection</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">mysql</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Database</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ config('database.connections.mysql.database') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Host</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ config('database.connections.mysql.host') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Port</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ config('database.connections.mysql.port') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Products</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $defaultProducts ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 sm:p-5">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white mb-4">Secondary Database</h3>

                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Connection</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">mysql_second</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Database</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ config('database.connections.mysql_second.database') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Host</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ config('database.connections.mysql_second.host') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Port</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ config('database.connections.mysql_second.port') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Products</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $secondProducts ?? 0 }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Synchronization --}}
    <div class="bg-primary-light dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-xl p-4 sm:p-6 mb-6 sm:mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white">Database Synchronization</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Copy all products from the Primary database to the Secondary database.</p>
            </div>
            <form action="{{ route('products.sync-all') }}" method="POST" onsubmit="return confirm('Are you sure you want to synchronize all products?')">
                @csrf
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 w-full sm:w-auto">
                    Sync All Products
                </button>
            </form>
        </div>
    </div>

    {{-- Latest Primary Products --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 mb-6 sm:mb-8">
        <div class="flex items-center justify-between mb-4 sm:mb-6">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white">Latest Primary Products</h2>
            <a href="{{ route('products.index', ['database' => 'mysql']) }}" class="inline-flex items-center justify-center px-4 py-2 bg-primary hover:bg-primary-dark text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                View All
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Detail</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Created At</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($latestDefaultProducts ?? [] as $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $product->id }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $product->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $product->detail ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $product->created_at ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                No products found in Primary Database.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Latest Secondary Products --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
        <div class="flex items-center justify-between mb-4 sm:mb-6">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white">Latest Secondary Products</h2>
            <a href="{{ route('products.index', ['database' => 'mysql_second']) }}" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                View All
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Detail</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Created At</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($latestSecondProducts ?? [] as $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $product->id }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $product->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $product->detail ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $product->created_at ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                No products found in Secondary Database.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

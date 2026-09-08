@extends('layouts.app')

@section('title', 'Product Management')

@section('page-title', 'Product Management')

@section('content')

    @if(session('success'))
        <x-alert type="success" class="mb-6">{{ session('success') }}</x-alert>
    @endif

    @if(session('created'))
        <x-alert type="success" class="mb-6">{{ session('created') }}</x-alert>
    @endif

    @if(session('updated'))
        <x-alert type="success" class="mb-6">{{ session('updated') }}</x-alert>
    @endif

    @if(session('deleted'))
        <x-alert type="success" class="mb-6">{{ session('deleted') }}</x-alert>
    @endif

    @if(session('synced'))
        <x-alert type="success" class="mb-6">{{ session('synced') }}</x-alert>
    @endif

    @if(session('warning'))
        <x-alert type="warning" class="mb-6">{{ session('warning') }}</x-alert>
    @endif

    @if(session('error'))
        <x-alert type="danger" class="mb-6">{{ session('error') }}</x-alert>
    @endif

    @if($errors->any())
        <x-alert type="danger" class="mb-6">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif

    {{-- Statistics --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Primary Products</h3>
            <p class="mt-2 text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ $primaryTotal }}</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Secondary Products</h3>
            <p class="mt-2 text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ $secondaryTotal }}</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Synchronized</h3>
            <p class="mt-2 text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ $syncedCount }}</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Sync</h3>
            <p class="mt-2 text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ max(0, $primaryTotal - $syncedCount) }}</p>
        </div>

    </div>

    {{-- Search / Filter / Sort --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 mb-6">
        <form method="GET" action="{{ route('products.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">
                <div class="lg:col-span-2">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search product name or detail..." class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-primary focus:ring-primary shadow-sm">
                </div>

                <div>
                    <select name="database" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-primary focus:ring-primary shadow-sm">
                        <option value="all" {{ $database === 'all' ? 'selected' : '' }}>All Databases</option>
                        <option value="mysql" {{ $database === 'mysql' ? 'selected' : '' }}>Primary Database</option>
                        <option value="mysql_second" {{ $database === 'mysql_second' ? 'selected' : '' }}>Secondary Database</option>
                    </select>
                </div>

                <div>
                    <select name="sort" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-primary focus:ring-primary shadow-sm">
                        <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Oldest</option>
                        <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                        <option value="name_desc" {{ $sort === 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-primary hover:bg-primary-dark text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                        Search
                    </button>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Action Buttons --}}
    <div class="flex flex-wrap gap-3 mb-6">
        <a href="{{ route('products.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
            + Add Product
        </a>

        <form action="{{ route('products.sync-all') }}" method="POST" onsubmit="return confirm('Synchronize all primary products to secondary database?')" class="inline">
            @csrf
            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-primary hover:bg-primary-dark text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                Sync All Products
            </button>
        </form>
    </div>

    {{-- Database Grid --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        @if($database === 'all' || $database === 'mysql')

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="bg-primary px-4 sm:px-6 py-4">
                    <h2 class="text-lg font-semibold text-white">Primary Database</h2>
                    <small class="text-blue-100">Connection: mysql</small>
                </div>

                @if($primaryProducts->count() > 0)

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Product</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Detail</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Sync</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($primaryProducts as $product)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">#{{ $product->id }}</td>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $product->name }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $product->detail ?? 'No detail' }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            @if($product->synced)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    Synced
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200">
                                                    Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <div class="flex flex-wrap gap-2">

                                                <a href="{{ route('products.edit', ['database' => 'mysql', 'id' => $product->id]) }}" class="inline-flex items-center justify-center px-2.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-medium rounded transition-colors focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                                                    Edit
                                                </a>

                                                @if(!$product->synced)
                                                    <form action="{{ route('products.sync', $product->id) }}" method="POST" onsubmit="return confirm('Synchronize this product to the secondary database?')" class="inline">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center justify-center px-2.5 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                                            Sync
                                                        </button>
                                                    </form>
                                                @endif

                                                <form action="{{ route('products.destroy', ['database' => 'mysql', 'id' => $product->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?')" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center justify-center px-2.5 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                                        Delete
                                                    </button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($primaryProducts->lastPage() > 1)
                        <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-3">
                            <x-pagination :paginator="$primaryProducts" />
                        </div>
                    @endif

                @else

                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No products found in Primary Database.</p>
                    </div>

                @endif

            </div>

        @endif

        @if($database === 'all' || $database === 'mysql_second')

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="bg-secondary px-4 sm:px-6 py-4">
                    <h2 class="text-lg font-semibold text-white">Secondary Database</h2>
                    <small class="text-green-100">Connection: mysql_second</small>
                </div>

                @if($secondaryProducts->count() > 0)

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Product</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Detail</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($secondaryProducts as $product)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $product->id }}</td>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $product->name }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $product->detail ?? 'No detail' }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            @if($product->synced)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    Matched
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200">
                                                    Secondary Only
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <div class="flex flex-wrap gap-2">

                                                <a href="{{ route('products.edit', ['database' => 'mysql_second', 'id' => $product->id]) }}" class="inline-flex items-center justify-center px-2.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-medium rounded transition-colors focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                                                    Edit
                                                </a>

                                                @if(!$product->synced)
                                                    <form action="{{ route('products.sync-to-primary', $product->id) }}" method="POST" onsubmit="return confirm('Synchronize this product to the primary database?')" class="inline">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center justify-center px-2.5 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                                            Sync to Primary
                                                        </button>
                                                    </form>
                                                @endif

                                                <form action="{{ route('products.destroy', ['database' => 'mysql_second', 'id' => $product->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?')" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center justify-center px-2.5 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                                        Delete
                                                    </button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($secondaryProducts->lastPage() > 1)
                        <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-3">
                            <x-pagination :paginator="$secondaryProducts" />
                        </div>
                    @endif

                @else

                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No products found in Secondary Database.</p>
                    </div>

                @endif

            </div>

        @endif

    </div>

@endsection

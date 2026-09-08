@extends('layouts.app')

@section('title', 'Database Health Monitor')

@section('page-title', 'Database Health Monitor')

@section('content')

    <div class="mb-6">
        <a href="{{ route('database.health') }}" class="inline-flex items-center justify-center px-4 py-2 bg-primary hover:bg-primary-dark text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
            Run Health Check Again
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">

        @foreach($results as $result)

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

                <div class="px-4 sm:px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $result['name'] }}</h2>

                    @if($result['status'])
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            Connected
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                            Connection Failed
                        </span>
                    @endif
                </div>

                <div class="p-4 sm:p-6">
                    @if($result['status'])

                        <div class="grid grid-cols-2 gap-y-3 gap-x-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Connection</div>
                            <div class="text-sm text-gray-900 dark:text-white">{{ $result['connection'] }}</div>

                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Database</div>
                            <div class="text-sm text-gray-900 dark:text-white">{{ $result['database'] }}</div>

                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Host</div>
                            <div class="text-sm text-gray-900 dark:text-white">{{ $result['host'] }}</div>

                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Port</div>
                            <div class="text-sm text-gray-900 dark:text-white">{{ $result['port'] }}</div>

                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Response</div>
                            <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $result['response_time'] }} ms</div>

                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Products Table</div>
                            <div class="text-sm">
                                @if($result['products_table'])
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Available
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        Missing
                                    </span>
                                @endif
                            </div>

                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Product Count</div>
                            <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $result['product_count'] }}</div>
                        </div>

                    @else

                        <div class="grid grid-cols-2 gap-y-3 gap-x-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Connection</div>
                            <div class="text-sm text-gray-900 dark:text-white">{{ $result['connection'] }}</div>

                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Database</div>
                            <div class="text-sm text-gray-900 dark:text-white">{{ $result['database'] }}</div>

                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Host</div>
                            <div class="text-sm text-gray-900 dark:text-white">{{ $result['host'] }}</div>

                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Port</div>
                            <div class="text-sm text-gray-900 dark:text-white">{{ $result['port'] }}</div>
                        </div>

                        <div class="mt-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-4">
                            <p class="text-sm text-red-800 dark:text-red-200">
                                <strong>Error:</strong> {{ $result['error'] }}
                            </p>
                        </div>

                    @endif
                </div>

            </div>

        @endforeach

    </div>

@endsection

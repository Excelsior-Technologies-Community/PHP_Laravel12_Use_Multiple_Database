@props([
    'type' => 'info',
])

@php
    $typeClasses = match($type) {
        'success' => 'bg-success-light border border-green-200 text-green-800 dark:bg-green-900 dark:border-green-700 dark:text-green-200',
        'warning' => 'bg-warning-light border border-amber-200 text-amber-800 dark:bg-amber-900 dark:border-amber-700 dark:text-amber-200',
        'danger' => 'bg-danger-light border border-red-200 text-red-800 dark:bg-red-900 dark:border-red-700 dark:text-red-200',
        'info' => 'bg-info-light border border-blue-200 text-blue-800 dark:bg-blue-900 dark:border-blue-700 dark:text-blue-200',
        default => 'bg-gray-50 border border-gray-200 text-gray-800 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200',
    };
@endphp

<div class="{{ $typeClasses }} rounded-lg p-4" role="alert">
    <div class="flex items-start">
        @if($type === 'success')
            <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        @elseif($type === 'warning')
            <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
        @elseif($type === 'danger')
            <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        @else
            <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        @endif
        <div>
            {{ $slot }}
        </div>
    </div>
</div>

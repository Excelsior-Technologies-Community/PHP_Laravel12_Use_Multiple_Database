@props([
    'type' => 'primary',
    'size' => 'md',
])

@php
    $baseClasses = 'inline-flex items-center font-medium rounded-full';

    $sizeClasses = match($size) {
        'sm' => 'px-2 py-0.5 text-xs',
        'lg' => 'px-3 py-1 text-sm',
        default => 'px-2.5 py-0.5 text-xs',
    };

    $typeClasses = match($type) {
        'primary' => 'bg-primary-light text-primary-dark',
        'success' => 'bg-success-light text-green-800',
        'warning' => 'bg-warning-light text-amber-800',
        'danger' => 'bg-danger-light text-red-800',
        'info' => 'bg-info-light text-blue-800',
        'gray' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
        default => 'bg-primary-light text-primary-dark',
    };

    $classes = $baseClasses . ' ' . $sizeClasses . ' ' . $typeClasses;
@endphp

<span class="{{ $classes }}">
    {{ $slot }}
</span>

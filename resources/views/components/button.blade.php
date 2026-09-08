@props([
    'type' => 'primary',
    'size' => 'md',
    'tag' => 'button',
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';

    $sizeClasses = match($size) {
        'sm' => 'px-3 py-1.5 text-sm',
        'lg' => 'px-6 py-3 text-base',
        default => 'px-4 py-2 text-sm',
    };

    $typeClasses = match($type) {
        'primary' => 'bg-primary hover:bg-primary-dark text-white focus:ring-primary',
        'secondary' => 'bg-secondary hover:bg-secondary-dark text-white focus:ring-secondary',
        'success' => 'bg-green-600 hover:bg-green-700 text-white focus:ring-green-500',
        'warning' => 'bg-amber-500 hover:bg-amber-600 text-white focus:ring-amber-400',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500',
        'info' => 'bg-primary hover:bg-primary-dark text-white focus:ring-primary',
        'outline' => 'border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:ring-gray-500',
        'ghost' => 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 focus:ring-gray-500',
        default => 'bg-primary hover:bg-primary-dark text-white focus:ring-primary',
    };

    $classes = trim($baseClasses . ' ' . $sizeClasses . ' ' . $typeClasses);
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</{{ $tag }}>

@props([
    'paginator',
])

@if($paginator->lastPage() > 1)
    <nav class="flex items-center justify-between border-t border-gray-200 dark:border-gray-700 px-4 py-3 sm:px-6" aria-label="Pagination">
        <div class="hidden sm:block">
            <p class="text-sm text-gray-700 dark:text-gray-300">
                Showing
                <span class="font-medium">{{ $paginator->firstItem() ?? 0 }}</span>
                to
                <span class="font-medium">{{ $paginator->lastItem() ?? 0 }}</span>
                of
                <span class="font-medium">{{ $paginator->total() }}</span>
                results
            </p>
        </div>
        <div class="flex flex-1 justify-between sm:justify-end gap-1">
            @if($paginator->onFirstPage())
                <span class="relative inline-flex items-center rounded-lg px-3 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 cursor-default">
                    Previous
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center rounded-lg px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Previous
                </a>
            @endif

            @for($page = 1; $page <= $paginator->lastPage(); $page++)
                @if($page == $paginator->currentPage())
                    <span class="relative inline-flex items-center rounded-lg px-3 py-2 text-sm font-medium text-white bg-primary border border-primary">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $paginator->url($page) }}" class="relative inline-flex items-center rounded-lg px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        {{ $page }}
                    </a>
                @endif
            @endfor

            @if($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center rounded-lg px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Next
                </a>
            @else
                <span class="relative inline-flex items-center rounded-lg px-3 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 cursor-default">
                    Next
                </span>
            @endif
        </div>
    </nav>
@endif

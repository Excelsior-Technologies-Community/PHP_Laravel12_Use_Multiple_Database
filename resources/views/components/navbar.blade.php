<header class="bg-white dark:bg-gray-800 shadow-sm h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 sticky top-0 z-10">

    <div class="flex items-center gap-4">
        <button class="md:hidden text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200" id="openSidebar">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <h1 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white truncate">
            @yield('page-title', 'Dashboard')
        </h1>
    </div>

    <div class="flex items-center gap-3">
        <button id="darkModeToggle" class="p-2 rounded-lg text-gray-500 dark:text-yellow-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" title="Toggle dark mode">
            <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
        </button>

        <div class="hidden sm:flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
            <span>Online</span>
        </div>
    </div>

</header>

<header class="h-16 bg-white/80 dark:bg-dark-900/80 backdrop-blur-xl border-b border-gray-200/60 dark:border-dark-800/80 flex items-center justify-between px-4 sm:px-6 lg:px-8 transition-colors duration-200 sticky top-0 z-30 shadow-sm dark:shadow-glass">
    <div class="flex items-center">
        <!-- Mobile menu button -->
        <button @click="sidebarOpen = true" class="md:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
        
        <!-- Desktop menu button -->
        <button @click="desktopSidebarOpen = !desktopSidebarOpen" class="hidden md:block p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
        
        <!-- Page Title -->
        <div class="hidden sm:block ml-4">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 tracking-tight">@yield('title')</h2>
        </div>
    </div>

    <div class="flex items-center space-x-4">
        <!-- Theme Toggle -->
        <button @click="darkMode = !darkMode" class="p-2 text-gray-400 hover:text-gray-500 rounded-full hover:bg-gray-100 transition-colors">
            <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
            <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </button>

        <!-- Notifications Bell -->
        @php
            $unreadCount = auth()->check() ? auth()->user()->unreadNotifications()->count() : 0;
        @endphp
        <a href="{{ route('notifications.index') }}" class="relative p-2 text-gray-400 hover:text-gray-500 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" id="topbar-notification-bell">
            <span class="sr-only">View notifications</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            @if($unreadCount > 0)
                <span class="absolute top-0 right-0 flex items-center justify-center min-w-[18px] h-[18px] px-1 text-[10px] font-bold text-white bg-red-500 rounded-full ring-2 ring-white dark:ring-gray-900 animate-pulse" id="notification-badge">
                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                </span>
            @endif
        </a>

        <!-- Profile dropdown -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center space-x-2 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                <div class="w-8 h-8 rounded-full bg-primary-500 flex items-center justify-center text-white font-bold text-sm uppercase">
                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                </div>
                <div class="hidden sm:flex flex-col items-start">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ Auth::user()->name ?? 'User' }}
                    </span>
                    @if(Auth::user() && Auth::user()->roles->count() > 0)
                    <span class="text-[10px] uppercase font-bold tracking-wider text-primary-600 dark:text-primary-400">
                        {{ Auth::user()->roles->first()->name }}
                    </span>
                    @endif
                </div>
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            
            <!-- Dropdown menu -->
            <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none transition-all z-50 border border-gray-100 dark:border-gray-700" x-cloak>
                <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700 block sm:hidden">
                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ Auth::user()->name ?? 'User' }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email ?? '' }}</p>
                </div>
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">Profile Settings</a>
                
                @php
                    $logoutRoute = route('logout');
                    if (Auth::check()) {
                        if (Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Admin')) {
                            $logoutRoute = route('admin.logout');
                        } elseif (Auth::user()->hasRole('Analyst')) {
                            $logoutRoute = route('analyst.logout');
                        }
                    }
                @endphp
                <form method="POST" action="{{ $logoutRoute }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        Sign out
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

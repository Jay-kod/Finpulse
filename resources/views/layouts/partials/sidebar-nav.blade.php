{{-- Sidebar Navigation Links --}}
{{-- Extracted for reuse in both mobile and desktop sidebars --}}

@php
    $user = auth()->user();
    
    // Determine which dashboard to point to
    if ($user && $user->hasRole(['Super Admin', 'Admin'])) {
        $dashboardUrl = '/admin/dashboard';
        $reportsUrl = '/analyst/reports';
        $appsUrl = '/admin/apps';
    } elseif ($user && $user->hasRole('Analyst')) {
        $dashboardUrl = '/analyst/dashboard';
        $reportsUrl = '/analyst/reports';
        $appsUrl = '/analyst/apps';
    } else {
        $dashboardUrl = '/viewer/dashboard';
        $reportsUrl = '/viewer/reports';
        $appsUrl = '/viewer/apps';
    }

    $navItems = [
        ['label' => 'Dashboard', 'url' => $dashboardUrl, 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'roles' => ['Super Admin', 'Admin', 'Analyst', 'Viewer']],
        ['label' => 'Analytics', 'url' => '/analyst/analytics', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'roles' => ['Super Admin', 'Admin', 'Analyst']],
        ['label' => 'Datasets', 'url' => '/analyst/datasets', 'icon' => 'M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4', 'roles' => ['Super Admin', 'Admin', 'Analyst']],
        ['label' => 'Reviews', 'url' => '/analyst/reviews', 'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z', 'roles' => ['Super Admin', 'Admin', 'Analyst']],
        ['label' => 'Predictions', 'url' => '/analyst/predictions', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'roles' => ['Super Admin', 'Admin', 'Analyst']],
        ['label' => 'App Directory', 'url' => $appsUrl, 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z', 'roles' => ['Super Admin', 'Admin', 'Analyst', 'Viewer']],
        ['label' => 'Reports', 'url' => $reportsUrl, 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'roles' => ['Super Admin', 'Admin', 'Analyst', 'Viewer']],
        ['label' => 'Fintech Apps', 'url' => '/admin/fintech-apps', 'icon' => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z', 'roles' => ['Super Admin', 'Admin']],
        ['label' => 'Users', 'url' => '/admin/users', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'roles' => ['Super Admin', 'Admin']],
        ['label' => 'Audit Log', 'url' => '/admin/audit-logs', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'roles' => ['Super Admin', 'Admin']],
        ['label' => 'Settings', 'url' => '/admin/settings', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z', 'roles' => ['Super Admin', 'Admin']],
        ['label' => 'Profile', 'url' => '/profile', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'roles' => ['Super Admin', 'Admin', 'Analyst', 'Viewer']],
    ];

    $currentPath = request()->path();
@endphp

@foreach($navItems as $item)
    @hasanyrole($item['roles'])
    @php
        // Improved active route logic: match exact or exact sub-paths
        $isActive = ($item['url'] === '/' && $currentPath === '/') ||
                    ($item['url'] !== '/' && $item['url'] !== '#' && (
                        '/' . $currentPath === $item['url'] ||
                        str_starts_with('/' . $currentPath, $item['url'] . '/')
                    ));

        // Modern, prominent active state styling
        $activeClass = 'bg-gradient-to-r from-primary-600 to-primary-700 text-white shadow-md shadow-primary-500/20 font-medium dark:from-primary-600 dark:to-primary-800';
        $inactiveClass = 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-white/5';
        
        $activeIconClass = 'text-white';
        $inactiveIconClass = 'text-gray-400 group-hover:text-gray-600 dark:text-gray-500 dark:group-hover:text-gray-300';
    @endphp

    <a href="{{ $item['url'] === '/' ? url('/') : $item['url'] }}"
       @if(!($isMobile ?? false))
       :title="!desktopSidebarOpen ? '{{ $item['label'] }}' : ''"
       :class="desktopSidebarOpen ? 'justify-start px-4' : 'justify-center px-0'"
       @endif
       class="flex items-center {{ ($isMobile ?? false) ? 'px-4' : '' }} py-2.5 text-sm rounded-xl transition-all duration-200 group {{ $isActive ? $activeClass : $inactiveClass }}">
        
        <svg class="w-5 h-5 shrink-0 transition-colors duration-200 {{ $isActive ? $activeIconClass : $inactiveIconClass }} @if(!($isMobile ?? false))" :class="desktopSidebarOpen ? 'mr-3' : 'mr-0'" @else mr-3" @endif fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"></path>
        </svg>

        @if(!($isMobile ?? false))
        <span x-show="desktopSidebarOpen" x-transition.opacity.duration.200ms class="whitespace-nowrap">{{ $item['label'] }}</span>
        @else
        <span class="whitespace-nowrap">{{ $item['label'] }}</span>
        @endif
    </a>
    @endhasanyrole
@endforeach

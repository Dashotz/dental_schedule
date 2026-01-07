<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dental Scheduling System')</title>
    
    <!-- Vite CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Heroicons are loaded via blade-heroicons package -->
    <!-- Toggle switch styles are in app.css -->
    @stack('styles')
</head>
<body class="bg-gray-50">
    @if(auth('admin')->check() || auth('web')->check())
        <!-- Sidebar Overlay for Mobile -->
        <div class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden transition-opacity duration-300" id="sidebarOverlay"></div>

        <!-- Sidebar -->
        <aside class="fixed top-0 left-0 h-screen w-64 text-white transition-all duration-300 z-50 shadow-2xl overflow-y-auto scrollbar-dental lg:translate-x-0 -translate-x-full {{ (auth('admin')->check() && auth('admin')->user()->isAdmin()) ? 'bg-gradient-to-b from-gray-800 to-gray-900' : 'bg-gradient-to-b from-dental-teal to-dental-teal-dark' }}" id="sidebar">
            <div class="flex items-center justify-between p-4 border-b border-white/10">
                <div class="flex items-center flex-1 min-w-0">
                    <x-dental-icon name="tooth" class="w-8 h-8 flex-shrink-0 sidebar-tooth-icon" />
                    <span class="sidebar-title ml-2 font-bold text-lg whitespace-nowrap overflow-hidden">
                        @if(auth('admin')->check() && auth('admin')->user()->isAdmin())
                            Admin Panel
                        @else
                            Dental System
                        @endif
                    </span>
                </div>
                <button class="hidden lg:block text-white hover:bg-white/10 p-2 rounded transition-colors flex-shrink-0" id="toggleSidebar" title="Toggle Sidebar">
                    <x-dental-icon name="chevron-left" class="w-5 h-5 transition-transform duration-300" id="toggleIcon" />
                </button>
            </div>

            <ul class="list-none p-0 m-0">
                @if(auth('admin')->check() && auth('admin')->user()->isAdmin())
                    <li class="border-b border-white/10">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link-dental {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <x-dental-icon name="speedometer2" class="w-6 h-6" />
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="border-b border-white/10">
                        <a href="{{ route('admin.subdomains.index') }}" class="nav-link-dental {{ request()->routeIs('admin.subdomains.*') ? 'active' : '' }}">
                            <x-dental-icon name="globe" class="w-6 h-6" />
                            <span>Subdomains</span>
                        </a>
                    </li>
                    <li class="border-b border-white/10">
                        <a href="{{ route('admin.subscriptions.index') }}" class="nav-link-dental {{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
                            <x-dental-icon name="credit-card" class="w-6 h-6" />
                            <span>Subscriptions</span>
                        </a>
                    </li>
                    <li class="border-b border-white/10">
                        <a href="#" class="nav-link-dental flex items-center justify-between analytics-toggle">
                            <div class="flex items-center">
                                <x-dental-icon name="graph-up" class="w-6 h-6" />
                                <span>Analytics</span>
                            </div>
                            <x-dental-icon name="chevron-down" class="w-5 h-5 transition-transform" id="analyticsChevron" />
                        </a>
                        <div class="{{ request()->routeIs('admin.reports.*') || request()->routeIs('admin.insights.*') ? '' : 'hidden' }}" id="analyticsMenu">
                            <ul class="list-none p-0 m-0">
                                <li class="border-b border-white/10">
                                    <a href="{{ route('admin.reports.index') }}" class="nav-link-dental pl-8 {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                                        <x-dental-icon name="bar-chart" class="w-6 h-6" />
                                        <span>Reports</span>
                                    </a>
                                </li>
                                <li class="border-b border-white/10">
                                    <a href="{{ route('admin.insights.index') }}" class="nav-link-dental pl-8 {{ request()->routeIs('admin.insights.*') ? 'active' : '' }}">
                                        <x-dental-icon name="pie-chart" class="w-6 h-6" />
                                        <span>Insights</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @else
                    <li class="border-b border-white/10">
                        <a href="{{ route('dashboard') }}" class="nav-link-dental {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <x-dental-icon name="speedometer2" class="w-6 h-6" />
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="border-b border-white/10">
                        <a href="{{ route('patients.index') }}" class="nav-link-dental {{ request()->routeIs('patients.*') ? 'active' : '' }}">
                            <x-dental-icon name="people" class="w-6 h-6" />
                            <span>Patients</span>
                        </a>
                    </li>
                    <li class="border-b border-white/10">
                        <a href="{{ route('appointments.index') }}" class="nav-link-dental {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                            <x-dental-icon name="calendar-check" class="w-6 h-6" />
                            <span>Appointments</span>
                        </a>
                    </li>
                    <li class="border-b border-white/10">
                        <a href="{{ route('calendar.index') }}" class="nav-link-dental {{ request()->routeIs('calendar.*') ? 'active' : '' }}">
                            <x-dental-icon name="calendar3" class="w-6 h-6" />
                            <span>Calendar</span>
                        </a>
                    </li>
                @endif
            </ul>

            <div class="p-4 border-t border-white/10 mt-auto">
                <div class="flex items-center mb-3">
                    <x-dental-icon name="person-circle" class="w-8 h-8 flex-shrink-0" />
                    <div class="ml-2 user-info-text flex-1 min-w-0">
                        <div class="text-sm font-bold truncate">{{ auth('admin')->check() ? auth('admin')->user()->name : auth('web')->user()->name }}</div>
                        <div class="text-xs text-white/70 truncate">
                            @if(auth('admin')->check() && auth('admin')->user()->isAdmin())
                                Admin
                            @else
                                Doctor
                            @endif
                        </div>
                    </div>
                </div>
                <form action="{{ (auth('admin')->check() && auth('admin')->user()->isAdmin()) ? route('admin.logout') : route('doctor.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-white/10 hover:bg-white/20 text-white font-semibold py-2 px-4 rounded-lg transition-colors border border-white/20 flex items-center justify-center">
                        <x-dental-icon name="box-arrow-right" class="w-5 h-5 mr-2 flex-shrink-0" />
                        <span class="logout-text">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="ml-0 lg:ml-64 transition-all duration-300 min-h-screen" id="mainContent">
            <!-- Top Bar -->
            <div class="bg-white shadow-sm sticky top-0 z-30 flex justify-between items-center p-4">
                <div class="flex items-center">
                    <span class="text-gray-600 hidden md:block">
                        <x-dental-icon name="calendar3" class="w-5 h-5 mr-2" />{{ now()->format('F d, Y') }}
                    </span>
                </div>
                <button class="lg:hidden text-dental-teal text-2xl p-2 hover:bg-gray-100 rounded transition-colors" id="mobileToggleSidebar">
                    <x-dental-icon name="list" class="w-6 h-6" />
                </button>
            </div>

            <!-- Content Area -->
            <div class="p-6">
                @yield('content')
            </div>
        </div>
    @else
        <!-- Public Layout (No Sidebar) -->
        <main class="w-full @if(request()->routeIs('welcome') || request()->is('/')) pt-0 pb-0 @else py-4 @endif">
            @yield('content')
        </main>
    @endif

    <!-- Session messages for JavaScript (hidden) -->
    @if(session('success'))
        <div data-session-success style="display: none;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div data-session-error style="display: none;">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div data-validation-errors style="display: none;">{{ json_encode($errors->all()) }}</div>
    @endif

    <!-- External Dependencies (with defer for better performance) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js" defer></script>
    
    <!-- Core Application JavaScript -->
    <script src="{{ asset('js/app-core.js') }}" defer></script>
    
    @stack('scripts')
</body>
</html>

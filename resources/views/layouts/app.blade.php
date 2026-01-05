<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dental Scheduling System')</title>
    
    @if(config('app.env') === 'local' && file_exists(public_path('hot')))
        {{-- Use Vite in development --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        {{-- Use CDN in production (HelioHost compatible) --}}
        <!-- Vue 3 -->
        <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
        
        <!-- Vuetify CSS -->
        <link href="https://cdn.jsdelivr.net/npm/vuetify@3/dist/vuetify.min.css" rel="stylesheet">
        
        <!-- Material Design Icons -->
        <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7/css/materialdesignicons.min.css" rel="stylesheet">
        
        <!-- Custom CSS (compiled or inline) -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @endif
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @stack('styles')
</head>
<body>
    @auth
        <!-- Sidebar Overlay for Mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Sidebar -->
        <aside class="sidebar {{ auth()->user()->isAdmin() ? 'admin-sidebar' : '' }}" id="sidebar">
            <div class="sidebar-header">
                <div style="display: flex; align-items: center;">
                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #00D4FF, #00A8CC); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 0.75rem; box-shadow: 0 4px 12px rgba(0, 212, 255, 0.4);">
                        <i class="mdi mdi-tooth-outline" style="font-size: 1.5rem; color: white;"></i>
                    </div>
                    <span class="sidebar-title" style="margin-left: 0; font-weight: 700; font-size: 1.1rem; letter-spacing: 0.5px;">
                        @if(auth()->user()->isAdmin())
                            Admin Panel
                        @else
                            <span class="gradient-text" style="background: linear-gradient(135deg, #00D4FF, #00A8CC); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Dental</span> System
                        @endif
                    </span>
                </div>
                <button class="burger-btn" id="toggleSidebar" title="Toggle Sidebar" style="display: none; background: rgba(0, 212, 255, 0.2); border: 1px solid rgba(0, 212, 255, 0.3); color: #00D4FF; cursor: pointer; padding: 0.5rem; border-radius: 8px; transition: all 0.3s;">
                    <i class="mdi mdi-chevron-left" id="toggleIcon"></i>
                </button>
            </div>

            <ul class="sidebar-menu">
                @if(auth()->user()->isAdmin())
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="mdi mdi-view-dashboard"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.subdomains.index') }}" class="{{ request()->routeIs('admin.subdomains.*') ? 'active' : '' }}">
                            <i class="mdi mdi-web"></i>
                            <span>Subdomains</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.subscriptions.index') }}" class="{{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
                            <i class="mdi mdi-credit-card"></i>
                            <span>Subscriptions</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="{{ request()->routeIs('admin.reports.*') || request()->routeIs('admin.insights.*') ? '' : 'collapsed' }}" 
                           onclick="event.preventDefault(); document.getElementById('analyticsMenu').classList.toggle('show'); this.classList.toggle('collapsed');">
                            <i class="mdi mdi-chart-line"></i>
                            <span>Analytics</span>
                            <i class="mdi mdi-chevron-down" style="margin-left: auto; transition: transform 0.3s;"></i>
                        </a>
                        <div id="analyticsMenu" style="display: {{ request()->routeIs('admin.reports.*') || request()->routeIs('admin.insights.*') ? 'block' : 'none' }}; padding-left: 1rem;">
                            <ul class="sidebar-menu" style="padding: 0.5rem 0;">
                                <li><a href="{{ route('admin.reports.index') }}" class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"><i class="mdi mdi-chart-bar"></i> <span>Reports</span></a></li>
                                <li><a href="{{ route('admin.insights.index') }}" class="{{ request()->routeIs('admin.insights.*') ? 'active' : '' }}"><i class="mdi mdi-chart-pie"></i> <span>Insights</span></a></li>
                            </ul>
                        </div>
                    </li>
                @else
                    <li>
                        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="mdi mdi-view-dashboard"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('patients.index') }}" class="{{ request()->routeIs('patients.*') ? 'active' : '' }}">
                            <i class="mdi mdi-account-group"></i>
                            <span>Patients</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('appointments.index') }}" class="{{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                            <i class="mdi mdi-calendar-check"></i>
                            <span>Appointments</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('calendar.index') }}" class="{{ request()->routeIs('calendar.*') ? 'active' : '' }}">
                            <i class="mdi mdi-calendar"></i>
                            <span>Calendar</span>
                        </a>
                    </li>
                @endif
            </ul>

            <div class="sidebar-footer" style="padding: 1.5rem 1.25rem; border-top: 1px solid rgba(0, 212, 255, 0.2); margin-top: auto; position: sticky; bottom: 0; background: linear-gradient(180deg, transparent 0%, rgba(10, 25, 41, 0.8) 100%);">
                <div style="display: flex; align-items: center; margin-bottom: 1rem; padding: 0.75rem; background: rgba(0, 212, 255, 0.1); border-radius: 12px; border: 1px solid rgba(0, 212, 255, 0.2);">
                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #00D4FF, #00A8CC); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 0.75rem;">
                        <i class="mdi mdi-account-circle" style="font-size: 1.5rem; color: white;"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-size: 0.875rem; font-weight: 600; color: white;">{{ auth()->user()->name }}</div>
                        <div style="font-size: 0.75rem; color: rgba(255, 255, 255, 0.7);">{{ ucfirst(auth()->user()->role) }}</div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="modern-btn" style="width: 100%; padding: 0.75rem; background: linear-gradient(135deg, rgba(255, 82, 82, 0.2), rgba(255, 82, 82, 0.1)); border: 1px solid rgba(255, 82, 82, 0.3); color: #FF5252; border-radius: 12px; cursor: pointer; font-weight: 500; transition: all 0.3s;">
                        <i class="mdi mdi-logout" style="margin-right: 0.5rem;"></i> <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="main-content" id="mainContent">
            <!-- Top Bar -->
            <div class="top-bar">
                <div style="display: flex; align-items: center;">
                    <div style="padding: 0.5rem 1rem; background: linear-gradient(135deg, rgba(0, 212, 255, 0.1), rgba(0, 168, 204, 0.05)); border-radius: 12px; border: 1px solid rgba(0, 212, 255, 0.2);">
                        <i class="mdi mdi-calendar" style="margin-right: 0.5rem; color: #00A8CC;"></i>
                        <span style="color: #1A2332; font-weight: 500;">{{ now()->format('F d, Y') }}</span>
                    </div>
                </div>
                <button class="burger-btn" id="mobileToggleSidebar" style="display: none;">
                    <i class="mdi mdi-menu"></i>
                </button>
            </div>

            <!-- Content Area -->
            <div class="content-area">
                @yield('content')
            </div>
        </div>
    @else
        <!-- Public Layout (No Sidebar) -->
        <main style="padding: 2rem; min-height: 100vh;">
            @yield('content')
        </main>
    @endauth

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    
    @if(config('app.env') !== 'local' || !file_exists(public_path('hot')))
        <!-- Vuetify JS (CDN) -->
        <script src="https://cdn.jsdelivr.net/npm/vuetify@3/dist/vuetify.min.js"></script>
    @endif
    
    <script>
        // Global SweetAlert configuration with futuristic theme
        const Swal = window.Swal;
        
        // Show success message from session
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#00A8CC',
                background: 'rgba(255, 255, 255, 0.95)',
                backdrop: 'rgba(0, 212, 255, 0.1)',
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        // Show error message from session
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#FF5252',
                background: 'rgba(255, 255, 255, 0.95)',
                backdrop: 'rgba(255, 82, 82, 0.1)'
            });
        @endif

        // Show validation errors
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: '<ul style="text-align: left;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                confirmButtonColor: '#FF5252',
                background: 'rgba(255, 255, 255, 0.95)',
                backdrop: 'rgba(255, 82, 82, 0.1)'
            });
        @endif

        // Sidebar Toggle Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const toggleBtn = document.getElementById('toggleSidebar');
            const toggleIcon = document.getElementById('toggleIcon');
            const mobileToggle = document.getElementById('mobileToggleSidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const isMobile = window.innerWidth <= 1024;

            // Show/hide toggle button based on screen size
            if (window.innerWidth > 1024) {
                if (toggleBtn) toggleBtn.style.display = 'block';
            }

            // Desktop toggle
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('sidebar-collapsed');
                    if (toggleIcon) {
                        toggleIcon.classList.toggle('mdi-chevron-left');
                        toggleIcon.classList.toggle('mdi-chevron-right');
                    }
                });
            }

            // Mobile toggle
            if (mobileToggle) {
                mobileToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    if (sidebarOverlay) sidebarOverlay.classList.toggle('show');
                });
            }

            // Close sidebar when overlay is clicked (mobile)
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                });
            }

            // Handle window resize
            window.addEventListener('resize', function() {
                const isMobileNow = window.innerWidth <= 1024;
                if (toggleBtn) {
                    toggleBtn.style.display = isMobileNow ? 'none' : 'block';
                }
                if (isMobileNow) {
                    sidebar.classList.remove('collapsed');
                    mainContent.classList.remove('sidebar-collapsed');
                }
            });

            // Add fade-in animation to content
            const contentArea = document.querySelector('.content-area');
            if (contentArea) {
                contentArea.classList.add('fade-in-up');
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>

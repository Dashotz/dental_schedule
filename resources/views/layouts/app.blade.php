<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dental Scheduling System')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background-color: #0d6efd;
            color: white;
            transition: transform 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar-header {
            padding: 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 1rem;
            color: white;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .sidebar-menu a:hover {
            background-color: rgba(255,255,255,0.1);
        }

        .sidebar-menu a.active {
            background-color: rgba(255,255,255,0.2);
        }

        .sidebar-menu i {
            width: 24px;
            margin-right: 12px;
            text-align: center;
        }

        .sidebar.collapsed .sidebar-menu span {
            display: none;
        }

        .sidebar.collapsed .sidebar-menu i {
            margin-right: 0;
        }

        .sidebar.collapsed .sidebar-header .sidebar-title {
            display: none;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
            background-color: #f8f9fa;
        }

        .main-content.sidebar-collapsed {
            margin-left: 70px;
        }

        /* Top Bar */
        .top-bar {
            background-color: white;
            padding: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        /* Burger Button */
        .burger-btn {
            display: none;
            background: none;
            border: none;
            color: #0d6efd;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            margin-left: auto;
        }

        /* Mobile Styles */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
                width: 250px;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar.collapsed {
                width: 250px;
            }

            .main-content {
                margin-left: 0;
            }

            .main-content.sidebar-collapsed {
                margin-left: 0;
            }

            .burger-btn {
                display: block;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0,0,0,0.5);
                z-index: 999;
            }

            .sidebar-overlay.show {
                display: block;
            }
        }

        /* Content Area */
        .content-area {
            padding: 1.5rem;
        }

        /* Admin specific styles */
        .admin-sidebar {
            background-color: #212529;
        }

        .admin-sidebar .sidebar-menu a {
            color: #fff;
        }

        .admin-sidebar .sidebar-menu a:hover {
            background-color: rgba(255,255,255,0.1);
        }
    </style>
    
        <style>
            /* Custom Toggle Switch Styling - Better Design */
            .form-check-input.toggle-status[type="checkbox"] {
                width: 3rem !important;
                height: 1.5rem !important;
                border-radius: 1.5rem !important;
                background-color: #dc3545 !important;
                border: 2px solid #dc3545 !important;
                cursor: pointer;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                position: relative;
                background-image: none !important;
            }
            
            .form-check-input.toggle-status[type="checkbox"]:checked {
                background-color: #198754 !important;
                border-color: #198754 !important;
            }
            
            .form-check-input.toggle-status[type="checkbox"]:focus {
                border-color: #86b7fe;
                outline: 0;
                box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            }
            
            /* Toggle knob animation */
            .form-check-input.toggle-status[type="checkbox"]::after {
                content: '';
                position: absolute;
                width: 1.125rem;
                height: 1.125rem;
                border-radius: 50%;
                background-color: white;
                top: 0.125rem;
                left: 0.125rem;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            }
            
            .form-check-input.toggle-status[type="checkbox"]:checked::after {
                left: calc(100% - 1.25rem);
            }
            
            /* Larger toggle for show page */
            .form-check-input.toggle-status[style*="3.5rem"] {
                width: 3.5rem !important;
                height: 1.75rem !important;
            }
            
            .form-check-input.toggle-status[style*="3.5rem"]::after {
                width: 1.375rem;
                height: 1.375rem;
                top: 0.125rem;
                left: 0.125rem;
            }
            
            .form-check-input.toggle-status[style*="3.5rem"]:checked::after {
                left: calc(100% - 1.5rem);
            }
        </style>
        @stack('styles')
    </head>
    <body>
    @auth
        <!-- Sidebar Overlay for Mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Sidebar -->
        <aside class="sidebar {{ auth()->user()->isAdmin() ? 'admin-sidebar' : '' }}" id="sidebar">
            <div class="sidebar-header">
                <div class="d-flex align-items-center">
                    <i class="bi bi-tooth fs-4"></i>
                    <span class="sidebar-title ms-2 fw-bold">
                        @if(auth()->user()->isAdmin())
                            Admin Panel
                        @else
                            Dental System
                        @endif
                    </span>
                </div>
                <button class="btn btn-sm text-white d-none d-lg-block" id="toggleSidebar" title="Toggle Sidebar">
                    <i class="bi bi-chevron-left" id="toggleIcon"></i>
                </button>
            </div>

            <ul class="sidebar-menu">
                @if(auth()->user()->isAdmin())
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.subdomains.index') }}" class="{{ request()->routeIs('admin.subdomains.*') ? 'active' : '' }}">
                            <i class="bi bi-globe"></i>
                            <span>Subdomains</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.subscriptions.index') }}" class="{{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
                            <i class="bi bi-credit-card"></i>
                            <span>Subscriptions</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="{{ request()->routeIs('admin.reports.*') || request()->routeIs('admin.insights.*') ? '' : 'collapsed' }}" 
                           data-bs-toggle="collapse" 
                           data-bs-target="#analyticsMenu"
                           aria-expanded="{{ request()->routeIs('admin.reports.*') || request()->routeIs('admin.insights.*') ? 'true' : 'false' }}">
                            <i class="bi bi-graph-up"></i>
                            <span>Analytics</span>
                            <i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('admin.reports.*') || request()->routeIs('admin.insights.*') ? 'show' : '' }}" id="analyticsMenu">
                            <ul class="sidebar-menu">
                                <li><a href="{{ route('admin.reports.index') }}" class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"><i class="bi bi-bar-chart"></i> <span>Reports</span></a></li>
                                <li><a href="{{ route('admin.insights.index') }}" class="{{ request()->routeIs('admin.insights.*') ? 'active' : '' }}"><i class="bi bi-pie-chart"></i> <span>Insights</span></a></li>
                            </ul>
                        </div>
                    </li>
                @else
                    <li>
                        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('patients.index') }}" class="{{ request()->routeIs('patients.*') ? 'active' : '' }}">
                            <i class="bi bi-people"></i>
                            <span>Patients</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('appointments.index') }}" class="{{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                            <i class="bi bi-calendar-check"></i>
                            <span>Appointments</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('calendar.index') }}" class="{{ request()->routeIs('calendar.*') ? 'active' : '' }}">
                            <i class="bi bi-calendar3"></i>
                            <span>Calendar</span>
                        </a>
                    </li>
                @endif
            </ul>

            <div class="sidebar-footer p-3 border-top border-light">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-person-circle fs-4"></i>
                    <div class="ms-2">
                        <div class="small fw-bold">{{ auth()->user()->name }}</div>
                        <div class="small text-white-50">{{ ucfirst(auth()->user()->role) }}</div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light w-100">
                        <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="main-content" id="mainContent">
            <!-- Top Bar -->
            <div class="top-bar">
                <div class="d-flex align-items-center">
                    <span class="text-muted d-none d-md-block">
                        <i class="bi bi-calendar3"></i> {{ now()->format('F d, Y') }}
                    </span>
                </div>
                <button class="burger-btn d-lg-none" id="mobileToggleSidebar">
                    <i class="bi bi-list"></i>
                </button>
            </div>

            <!-- Content Area -->
            <div class="content-area">
                @yield('content')
            </div>
        </div>
    @else
        <!-- Public Layout (No Sidebar) -->
        <main class="container-fluid py-4">
            @yield('content')
        </main>
    @endauth

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    
    <script>
        // Global SweetAlert configuration
        const Swal = window.Swal;
        
        // Show success message from session
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#0d6efd',
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
                confirmButtonColor: '#dc3545'
            });
        @endif

        // Show validation errors
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: '<ul class="text-start">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                confirmButtonColor: '#dc3545'
            });
        @endif

        // Sidebar Toggle Functionality
        $(document).ready(function() {
            const sidebar = $('#sidebar');
            const mainContent = $('#mainContent');
            const toggleBtn = $('#toggleSidebar');
            const toggleIcon = $('#toggleIcon');
            const mobileToggle = $('#mobileToggleSidebar');
            const sidebarOverlay = $('#sidebarOverlay');
            const isMobile = window.innerWidth <= 1024;

            // Desktop toggle
            toggleBtn.on('click', function() {
                sidebar.toggleClass('collapsed');
                mainContent.toggleClass('sidebar-collapsed');
                toggleIcon.toggleClass('bi-chevron-left bi-chevron-right');
            });

            // Mobile toggle
            mobileToggle.on('click', function() {
                sidebar.toggleClass('show');
                sidebarOverlay.toggleClass('show');
            });

            // Close sidebar when overlay is clicked (mobile)
            sidebarOverlay.on('click', function() {
                sidebar.removeClass('show');
                sidebarOverlay.removeClass('show');
            });

            // Close sidebar when clicking outside on mobile
            $(document).on('click', function(e) {
                if (isMobile && !$(e.target).closest('#sidebar, #mobileToggleSidebar').length) {
                    sidebar.removeClass('show');
                    sidebarOverlay.removeClass('show');
                }
            });

            // Handle window resize
            $(window).on('resize', function() {
                const isMobileNow = window.innerWidth <= 1024;
                if (isMobileNow) {
                    sidebar.removeClass('collapsed');
                    mainContent.removeClass('sidebar-collapsed');
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>

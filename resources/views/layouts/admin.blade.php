<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - {{ config('app.name', 'Celebrity Agency') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

     @if($appSetting->site_favicon)
        <link rel="icon" type="image/png" href="{{ asset($appSetting->site_favicon) }}">
    @endif
    
    <!-- Custom Admin Styles -->
    <style>
        :root {
            --admin-primary: #1a1a1a;
            --admin-secondary: #2d2d2d;
            --admin-accent: #ffd700;
            --admin-text: #ffffff;
            --admin-text-muted: #b0b0b0;
            --admin-border: #404040;
        }

        body {
            background-color: var(--admin-primary);
            color: var(--admin-text);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            background: linear-gradient(135deg, var(--admin-secondary), #1f1f1f);
            min-height: 100vh;
            box-shadow: 2px 0 10px rgba(0,0,0,0.3);
            border-right: 1px solid var(--admin-border);
            position: fixed;
            top: 0;
            bottom: 0;
            left: -100%;
            z-index: 1000;
            transition: left 0.3s ease-in-out;
            width: 280px;
        }

        .sidebar.show {
            left: 0;
        }

        @media (min-width: 768px) {
            .sidebar {
                position: relative;
                left: 0;
                width: auto;
            }
        }

        .navbar-toggler {
            background: transparent;
            border: none !important;
            font-size: 1.25rem;
            padding: 0.5rem;
            color: var(--admin-accent) !important;
            transition: color 0.3s ease;
        }

        .navbar-toggler:hover {
            color: #ffed4a !important;
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.25) !important;
            outline: none;
        }

        .navbar-toggler:not(:disabled):not(.disabled) {
            cursor: pointer;
        }

        /* Mobile overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
        }

        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        @media (min-width: 768px) {
            .sidebar-overlay {
                display: none;
            }
        }

        .sidebar .nav-link {
            color: var(--admin-text-muted);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 5px 15px;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: var(--admin-accent);
            background-color: rgba(255, 215, 0, 0.1);
            border-left-color: var(--admin-accent);
            transform: translateX(5px);
        }

        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }

        .main-content {
            background-color: var(--admin-primary);
            min-height: 100vh;
            transition: margin-left 0.3s ease-in-out;
        }

        @media (max-width: 767px) {
            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }

        .navbar-admin {
            background: linear-gradient(135deg, var(--admin-secondary), #2a2a2a);
            border-bottom: 1px solid var(--admin-border);
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .card {
            background-color: var(--admin-secondary);
            border: 1px solid var(--admin-border);
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .card-header {
            background: linear-gradient(135deg, var(--admin-accent), #ffed4a);
            color: var(--admin-primary);
            font-weight: bold;
            border-bottom: 1px solid var(--admin-border);
            border-radius: 10px 10px 0 0 !important;
        }

        .table-dark {
            background-color: var(--admin-secondary);
            border-color: var(--admin-border);
        }

        .table-dark td, .table-dark th {
            border-color: var(--admin-border);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--admin-accent), #ffed4a);
            border-color: var(--admin-accent);
            color: var(--admin-primary);
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #ffed4a, var(--admin-accent));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745, #20c997);
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545, #e74c3c);
        }

        .btn-warning {
            background: linear-gradient(135deg, #ffc107, #ffed4a);
            color: var(--admin-primary);
        }

        .form-control, .form-select {
            background-color: #3a3a3a;
            border-color: var(--admin-border);
            color: var(--admin-text);
        }

        .form-control:focus, .form-select:focus {
            background-color: #4a4a4a;
            border-color: var(--admin-accent);
            color: var(--admin-text);
            box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.25);
        }

        .form-label {
            color: var(--admin-text);
            font-weight: 500;
        }

        .text-muted {
            color: var(--admin-text-muted) !important;
        }

        .alert {
            border-radius: 8px;
            border: none;
        }

        .alert-success {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }

        .alert-danger {
            background: linear-gradient(135deg, #dc3545, #e74c3c);
            color: white;
        }

        .badge {
            font-size: 0.8em;
            padding: 5px 10px;
        }

        .stats-card {
            background: linear-gradient(135deg, var(--admin-secondary), #2a2a2a);
            border-left: 4px solid var(--admin-accent);
            transition: transform 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-3px);
        }

        .logo-brand {
            color: var(--admin-accent);
            font-weight: bold;
            font-size: 1.5rem;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar" id="sidebarMenu">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <img src="{{ asset($appSetting->site_logo) }}" alt="" width="100px" height="40px">
                        {{-- <h4 class="logo-brand">
                            <i class="fas fa-star"></i>
                            Celebrity Agency
                        </h4> --}}
                        <p class="text-muted small">Admin Panel</p>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ url('admin/dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.celebrities.*') ? 'active' : '' }}" href="{{ route('admin.celebrities.index') }}">
                                <i class="fas fa-users"></i>
                                Celebrities
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.service-types.*') ? 'active' : '' }}" href="{{ route('admin.service-types.index') }}">
                                <i class="fas fa-list"></i>
                                Service Types
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.payment-methods.*') ? 'active' : '' }}" href="{{ route('admin.payment-methods.index') }}">
                                <i class="fas fa-credit-card"></i>
                                Payment Methods
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}" href="{{ route('admin.sliders.index') }}">
                                <i class="fas fa-sliders-h"></i>
                                Home Sliders
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.images.*') ? 'active' : '' }}" href="{{ route('admin.images.index') }}">
                                <i class="fas fa-images"></i>
                                Images
                            </a>
                        </li> --}}
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}" href="{{ route('admin.bookings.index') }}">
                                <i class="fas fa-calendar-check"></i>
                                Bookings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}" href="{{ route('admin.contacts.index') }}">
                                <i class="fas fa-envelope"></i>
                                Contact Messages
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}" href="{{ route('admin.testimonials.index') }}">
                                <i class="fas fa-quote-right"></i>
                                Testimonials
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.change-password') ? 'active' : '' }}" href="{{ route('admin.change-password') }}">
                                <i class="fas fa-lock"></i>
                                Change Password
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                                <i class="fas fa-cog"></i>
                                Site Settings
                            </a>
                        </li>
                        <li class="nav-item mt-4">
                            <a class="nav-link" href="{{ route('home') }}" target="_blank">
                                <i class="fas fa-external-link-alt"></i>
                                View Website
                            </a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link text-start" style="border: none; background: none; width: 100%;">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-9 px-md-4 main-content">
                <!-- Top Navigation -->
                <nav class="navbar navbar-expand-lg navbar-admin mb-4">
                    <div class="container-fluid">
                        <button class="navbar-toggler border-0 d-md-none" type="button" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="fas fa-bars text-white"></i>
                        </button>
                        
                        <div class="d-none d-md-block">
                            <h5 class="mb-0 text-white">@yield('page-title', 'Dashboard')</h5>
                        </div>
                        
                        <div class="ms-auto">
                            <span class="navbar-text me-3 text-white">
                                Welcome, <strong>{{ Auth::user()->name }}</strong>
                            </span>
                        </div>
                    </div>
                </nav>

                <!-- Alerts -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Page Content -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom border-secondary">
                    <h1 class="h2 d-md-none">@yield('page-title', 'Dashboard')</h1>
                    <div class="d-none d-md-block">
                        <!-- Page title shown in navbar on desktop -->
                    </div>
                    <div class="ms-auto">
                        @yield('page-actions')
                    </div>
                </div>

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Form confirmation for delete actions
        function confirmDelete(form) {
            if (confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                form.submit();
            }
        }

        // Mobile Sidebar Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.querySelector('.navbar-toggler');
            const sidebar = document.getElementById('sidebarMenu');
            const overlay = document.getElementById('sidebarOverlay');

            function toggleSidebar() {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
                
                // Update ARIA attributes
                const isExpanded = sidebar.classList.contains('show');
                sidebarToggle.setAttribute('aria-expanded', isExpanded);
            }

            function closeSidebar() {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
                sidebarToggle.setAttribute('aria-expanded', 'false');
            }

            // Toggle sidebar when button is clicked
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', toggleSidebar);
            }

            // Close sidebar when overlay is clicked
            if (overlay) {
                overlay.addEventListener('click', closeSidebar);
            }

            // Close sidebar on window resize if mobile view changes
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) {
                    closeSidebar();
                }
            });

            // Close sidebar when pressing Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('show')) {
                    closeSidebar();
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
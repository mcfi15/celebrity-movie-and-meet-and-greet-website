<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @php
        $settings = App\Models\SiteSetting::getSetting();
    @endphp
    
    <title>@yield('title', $settings->site_name)</title>
    <meta name="description" content="@yield('description', $settings->site_description)">
    
    @if($appSetting->site_favicon)
        <link rel="icon" type="image/png" href="{{ asset($appSetting->site_favicon) }}">
    @endif
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #FFD700;
            --secondary-color: #1a1a1a;
            --accent-color: #B8860B;
            --dark-bg: #0d1117;
            --card-bg: #161b22;
            --text-light: #f0f6fc;
            --text-unmute: #8b949e;
            --border-color: #30363d;
        }
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: var(--dark-bg);
            color: var(--text-light);
            line-height: 1.6;
        }
        
        .bg-dark-custom {
            background-color: var(--secondary-color) !important;
        }
        
        .bg-card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
        }
        
        .text-gold {
            color: var(--primary-color) !important;
        }
        
        .btn-gold {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: var(--secondary-color);
            font-weight: 600;
        }
        
        .btn-gold:hover {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: var(--secondary-color);
        }
        
        .btn-outline-gold {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }
        
        .btn-outline-gold:hover {
            background-color: var(--primary-color);
            color: var(--secondary-color);
        }
        
        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            color: var(--text-light);
        }
        
        .card-header {
            background-color: rgba(255, 215, 0, 0.1);
            border-bottom: 1px solid var(--border-color);
        }
        
        .form-control, .form-select {
            background-color: var(--card-bg);
            border-color: var(--border-color);
            color: var(--text-light);
        }
        
        .form-control:focus, .form-select:focus {
            background-color: var(--card-bg);
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.25);
            color: var(--text-light);
        }
        
        .navbar-dark .navbar-nav .nav-link {
            color: var(--text-light);
        }
        
        .navbar-dark .navbar-nav .nav-link:hover {
            color: var(--primary-color);
        }
        
        .dropdown-menu {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
        }
        
        .dropdown-item {
            color: var(--text-light);
        }
        
        .dropdown-item:hover {
            background-color: rgba(255, 215, 0, 0.1);
            color: var(--primary-color);
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--dark-bg) 100%);
            min-height: 60vh;
            display: flex;
            align-items: center;
        }
        
        .celebrity-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }
        
        .celebrity-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(255, 215, 0, 0.2);
        }
        
        .celebrity-img {
            height: 250px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .celebrity-card:hover .celebrity-img {
            transform: scale(1.05);
        }
        
        .service-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .testimonial-card {
            background: linear-gradient(135deg, var(--card-bg) 0%, rgba(255, 215, 0, 0.05) 100%);
        }
        
        .footer {
            background-color: var(--secondary-color);
            border-top: 2px solid var(--primary-color);
        }
        
        .pagination .page-link {
            background-color: var(--card-bg);
            border-color: var(--border-color);
            color: var(--text-light);
        }
        
        .pagination .page-link:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: var(--secondary-color);
        }
        
        .pagination .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: var(--secondary-color);
        }
        
        .alert-success {
            background-color: rgba(40, 167, 69, 0.2);
            border-color: #28a745;
            color: #d4edda;
        }
        
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.2);
            border-color: #dc3545;
            color: #f8d7da;
        }
        
        .table-dark {
            --bs-table-bg: var(--card-bg);
        }
        
        .badge {
            font-size: 0.75em;
        }
        
        .loading-spinner {
            border: 4px solid rgba(255, 215, 0, 0.3);
            border-radius: 50%;
            border-top: 4px solid var(--primary-color);
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .gradient-text {
            background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div id="app">
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark-custom fixed-top">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                    @if($settings->site_logo)
                        <img src="{{ asset($settings->site_logo) }}" alt="{{ $settings->site_name }}" height="40" class="me-2">
                    @endif
                    {{-- <span class="text-gold fw-bold">{{ $settings->site_name }}</span> --}}
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active text-gold' : '' }}" href="{{ route('home') }}">
                                <i class="fas fa-home me-1"></i> Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('about') ? 'active text-gold' : '' }}" href="{{ route('about') }}">
                                <i class="fas fa-info-circle me-1"></i> About
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->routeIs(['services', 'celebrities', 'celebrity.*']) ? 'active text-gold' : '' }}" 
                               href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-star me-1"></i> Celebrity Services
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('services') }}">
                                    <i class="fas fa-list me-2"></i> All Services
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('celebrities') }}">
                                    <i class="fas fa-users me-2"></i> Our Celebrities
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('booking.create') }}">
                                    <i class="fas fa-calendar-plus me-2"></i> Book Now
                                </a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('contact') ? 'active text-gold' : '' }}" href="{{ route('contact') }}">
                                <i class="fas fa-envelope me-1"></i> Contact
                            </a>
                        </li>
                    </ul>
                    
                    <ul class="navbar-nav">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">
                                    <i class="fas fa-sign-in-alt me-1"></i> Login
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">
                                    <i class="fas fa-user-plus me-1"></i> Register
                                </a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user me-1"></i> {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    @if(Auth::user()->isAdmin())
                                        <li><a class="dropdown-item" href="{{ url('admin/dashboard') }}">
                                            <i class="fas fa-tachometer-alt me-2"></i> Admin Panel
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                    @endif
                                    <li><a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </a></li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        
        <!-- Main Content -->
        <main style="margin-top: 76px;">
            @yield('content')
        </main>
        
        <!-- Footer -->
        <footer class="footer mt-5 py-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 mb-4">
                        <img src="{{ asset($appSetting->site_logo) }}" alt="">
                        {{-- <h5 class="text-gold mb-3">{{ $settings->site_name }}</h5> --}}
                        <p class="text-unmute">{{ $settings->site_description }}</p>
                        <div class="d-flex gap-3">
                            <a href="#" class="text-gold"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="text-gold"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-gold"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="text-gold"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 mb-4">
                        <h6 class="text-gold mb-3">Quick Links</h6>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('home') }}" class="text-white text-decoration-none">Home</a></li>
                            <li><a href="{{ route('about') }}" class="text-white text-decoration-none">About</a></li>
                            <li><a href="{{ route('services') }}" class="text-white text-decoration-none">Services</a></li>
                            <li><a href="{{ route('celebrities') }}" class="text-white text-decoration-none">Celebrities</a></li>
                            <li><a href="{{ route('contact') }}" class="text-white text-decoration-none">Contact</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <h6 class="text-gold mb-3">Contact Info</h6>
                        <ul class="list-unstyled text-unmute">
                            <li><i class="fas fa-envelope me-2 text-gold"></i> {{ $settings->site_email }}</li>
                            <li><i class="fas fa-phone me-2 text-gold"></i> {{ $settings->site_phone }}</li>
                            <li><i class="fas fa-map-marker-alt me-2 text-gold"></i> {{ $settings->site_address }}</li>
                        </ul>
                    </div>
                    <div class="col-lg-3 mb-4">
                        <h6 class="text-gold mb-3">Newsletter</h6>
                        <p class="text-unmute small">Subscribe to get updates on new celebrities and exclusive events.</p>
                        <form id="newsletter-form">
                            @csrf
                            <div class="input-group">
                                <input type="email" class="form-control" name="email" placeholder="Your email" required>
                                <button class="btn btn-gold" type="submit">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                            <div id="newsletter-message" class="mt-2"></div>
                        </form>
                    </div>
                </div>
                <hr class="my-4" style="border-color: var(--border-color);">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="text-unmute mb-0">&copy; {{ date('Y') }} {{ $settings->site_name }}. All rights reserved.</p>
                    </div>
                    
                </div>
            </div>
        </footer>
    </div>
    
    <!-- Logout Form -->
    @auth
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    @endauth
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Newsletter subscription
        document.getElementById('newsletter-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const messageDiv = document.getElementById('newsletter-message');
            
            fetch('{{ route("newsletter.subscribe") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageDiv.innerHTML = '<small class="text-success">' + data.message + '</small>';
                    this.reset();
                } else {
                    messageDiv.innerHTML = '<small class="text-danger">' + data.message + '</small>';
                }
            })
            .catch(error => {
                messageDiv.innerHTML = '<small class="text-danger">An error occurred. Please try again.</small>';
            });
        });
        
        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                if (alert.classList.contains('alert-success')) {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }
            });
        }, 5000);
    </script>
    
    @stack('scripts')
</body>
</html>
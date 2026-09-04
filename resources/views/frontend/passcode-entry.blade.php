<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Enter Passcode - {{ $settings->site_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #FFD700;
            --accent-color: #B8860B;
            --dark-bg: #0d1117;
            --card-bg: #161b22;
            --border-color: #30363d;
        }

        * { font-family: 'Poppins', sans-serif; }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0d1117 0%, #161b22 60%, #1a1a1a 100%);
            color: #f0f6fc;
            margin: 0;
        }

        .passcode-card {
            width: 100%;
            max-width: 400px;
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }

        .lock-icon {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: rgba(255, 215, 0, 0.1);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin: 0 auto 1rem;
        }

        .brand-logo {
            max-height: 60px;
            max-width: 180px;
            object-fit: contain;
            margin: 0 auto 1.25rem;
            display: block;
        }

        .passcode-input {
            background: #0d1117;
            border: 1px solid var(--border-color);
            color: #f0f6fc;
            text-align: center;
            letter-spacing: 0.6em;
            font-size: 1.25rem;
            padding: 0.75rem;
        }

        .passcode-input:focus {
            background: #0d1117;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.25);
            color: #f0f6fc;
        }

        .btn-gold {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: #1a1a1a;
            font-weight: 600;
        }

        .btn-gold:hover {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: #1a1a1a;
        }

        .alert-danger {
            background-color: rgba(220, 53, 69, 0.2);
            border-color: #dc3545;
            color: #f8d7da;
        }

        .form-text { color: #8b949e; }

        .mb-0 { margin-bottom: 0; }
    </style>
</head>
<body>
    <div class="passcode-card">
        <div class="text-center">
            @if($settings->site_logo)
                <img src="{{ asset($settings->site_logo) }}" alt="{{ $settings->site_name }}" class="brand-logo">
            @endif
            <div class="lock-icon">
                <i class="fas fa-lock"></i>
            </div>
            <h4 class="fw-semibold mb-1">Restricted Access</h4>
            <p class="text-white-50 mb-4">Enter the passcode to continue</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger py-2">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('passcode.verify') }}" method="POST" autocomplete="off">
            @csrf
            <div class="mb-3">
                <label for="passcode" class="form-label visually-hidden">Passcode</label>
                <input type="password"
                       class="form-control passcode-input"
                       id="passcode"
                       name="passcode"
                       placeholder="••••••"
                       autofocus
                       required>
            </div>
            <button type="submit" class="btn btn-gold w-100 py-2">
                <i class="fas fa-unlock me-2"></i> Unlock
            </button>
        </form>

        <p class="text-center small text-white mt-4 mb-0">
            &copy; {{ date('Y') }} {{ $settings->site_name }}. All rights reserved.
        </p>
    </div>
</body>
</html>
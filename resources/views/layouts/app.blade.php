<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Campus Lost & Found') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #4F46E5;
            --secondary: #10B981;
            --accent: #F59E0B;
            --danger: #EF4444;
            --bg-light: #F9FAFB;
            --text-primary: #111827;
            --text-secondary: #6B7280;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--bg-light);
            min-height: 100vh;
        }
        .navbar {
            background-color: var(--primary) !important;
        }
        .navbar-dark .navbar-nav .nav-link {
            color: rgba(255,255,255,0.85);
        }
        .navbar-dark .navbar-nav .nav-link:hover {
            color: #fff;
        }
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        .btn-primary:hover {
            background-color: #4338CA;
            border-color: #4338CA;
        }
        .btn-success {
            background-color: var(--secondary);
            border-color: var(--secondary);
        }
        .btn-success:hover {
            background-color: #059669;
            border-color: #059669;
        }
        .btn-warning {
            background-color: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .card-lost {
            border-left: 4px solid var(--danger);
        }
        .card-found {
            border-left: 4px solid var(--secondary);
        }
        .badge-status {
            font-size: 0.75rem;
            padding: 0.35em 0.65em;
        }
        .status-unclaimed { background-color: #6B7280; }
        .status-pending_claim { background-color: var(--accent); }
        .status-claimed { background-color: var(--secondary); }
        .status-matched { background-color: var(--primary); }
        .status-resolved { background-color: #10B981; }
        .sidebar {
            background: #fff;
            min-height: calc(100vh - 56px);
            border-right: 1px solid #e5e7eb;
        }
        .sidebar .nav-link {
            color: var(--text-secondary);
            padding: 0.75rem 1rem;
            border-radius: 6px;
            margin: 0.25rem 0.5rem;
        }
        .sidebar .nav-link:hover {
            background-color: #F3F4F6;
            color: var(--primary);
        }
        .sidebar .nav-link.active {
            background-color: var(--primary);
            color: #fff;
        }
        .stat-card {
            border-radius: 8px;
            padding: 1.5rem;
            color: #fff;
        }
        .stat-card.primary { background-color: var(--primary); }
        .stat-card.success { background-color: var(--secondary); }
        .stat-card.warning { background-color: var(--accent); }
        .stat-card.danger { background-color: var(--danger); }
        .match-score {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .item-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px 8px 0 0;
        }
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--text-secondary);
        }
        .form-section {
            background: #fff;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-search"></i> Campus Lost & Found
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                @auth
                    @if(Auth::user()->isAdmin())
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.lost-items') }}">
                                    <i class="bi bi-search"></i> Lost Reports
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.found-items') }}">
                                    <i class="bi bi-handbag"></i> Found Reports
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.claims') }}">
                                    <i class="bi bi-file-text"></i> Claims
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.matches') }}">
                                    <i class="bi bi-link"></i> Matches
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.users') }}">
                                    <i class="bi bi-people"></i> Users
                                </a>
                            </li>
                        </ul>
                    @else
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('lost-items.index') }}">
                                    <i class="bi bi-search"></i> Lost Items
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('found-items.index') }}">
                                    <i class="bi bi-handbag"></i> Found Items
                                </a>
                            </li>
                        </ul>
                    @endif
                @endauth
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                                @if(Auth::user()->isAdmin())
                                    <span class="badge bg-warning text-dark ms-1">Admin</span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if(Auth::user()->isAdmin())
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
                                @else
                                    <li><a class="dropdown-item" href="{{ route('dashboard') }}">My Dashboard</a></li>
                                    <li><a class="dropdown-item" href="{{ route('lost-items.my-items') }}">My Lost Reports</a></li>
                                    <li><a class="dropdown-item" href="{{ route('found-items.my-items') }}">My Found Reports</a></li>
                                    <li><a class="dropdown-item" href="{{ route('claims.my-claims') }}">My Claims</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @if(session('success'))
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="container mt-3">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif
        {{ $slot }}
    </main>

    <footer class="bg-white border-top mt-5 py-4">
        <div class="container text-center text-muted">
            <small>Campus Lost & Found System &copy; {{ date('Y') }}</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Campus Lost & Found') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #667eea;
            --primary-dark: #764ba2;
            --secondary: #10B981;
            --accent: #F59E0B;
            --danger: #EF4444;
            --bg-light: #f8f9fc;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
        }
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: var(--bg-light);
            min-height: 100vh;
            padding-top: 56px;
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%) !important;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.25rem;
            margin-left: -0.4rem;
        }
        
        .nav-link {
            font-weight: 500;
            transition: all 0.3s;
            border-radius: 8px;
            margin: 0 4px;
        }
        
        .nav-link:hover {
            background: rgba(255,255,255,0.15);
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            border-radius: 12px;
            padding: 10px;
        }
        
        .dropdown-item {
            border-radius: 8px;
            padding: 10px 15px;
            transition: all 0.2s;
        }
        
        .dropdown-item:hover {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        
        .btn-success {
            background: linear-gradient(135deg, var(--secondary) 0%, #059669 100%);
            border: none;
        }
        
        .btn-danger {
            background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
            border: none;
        }
        
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.12);
        }
        
        .card-lost {
            border-left: 4px solid var(--danger);
        }
        
        .card-found {
            border-left: 4px solid var(--secondary);
        }
        
        .badge-status {
            font-size: 0.75rem;
            padding: 0.4em 0.8em;
            border-radius: 20px;
            font-weight: 500;
        }
        
        .status-unclaimed { background: #e5e7eb; color: #374151; }
        .status-pending_claim { background: #fef3c7; color: #92400e; }
        .status-claimed { background: #d1fae5; color: #065f46; }
        .status-matched { background: #e0e7ff; color: #4338ca; }
        .status-resolved { background: #d1fae5; color: #065f46; }
        
        .stat-card {
            border-radius: 16px;
            padding: 1.5rem;
            color: #fff;
            transition: all 0.3s;
        }
        
        .stat-card:hover {
            transform: scale(1.02);
        }
        
        .stat-card.primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stat-card.success { background: linear-gradient(135deg, #10B981 0%, #059669 100%); }
        .stat-card.warning { background: linear-gradient(135deg, #F59E0B 0%, #d97706 100%); }
        .stat-card.danger { background: linear-gradient(135deg, #EF4444 0%, #dc2626 100%); }
        
        .item-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 16px 16px 0 0;
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem;
            color: var(--text-secondary);
        }
        
        .empty-state i {
            font-size: 4rem;
            opacity: 0.3;
        }
        
        .form-section {
            background: #fff;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        
        .alert {
            border-radius: 12px;
            border: none;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
        }
        
        footer {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem;
            border-radius: 16px;
            color: white;
            margin-bottom: 2rem;
        }
        
        .table thead th {
            background: #f8f9fc;
            border-bottom: 2px solid #e5e7eb;
            color: #374151;
            font-weight: 600;
        }
        
        .table-hover tbody tr {
            transition: all 0.2s;
        }
        
        .table-hover tbody tr:hover {
            background: #f8f9fc;
        }
        
        .btn {
            border-radius: 12px;
            font-weight: 600;
            padding: 0.9rem 1.4rem;
            min-height: 48px;
            transition: all 0.2s;
        }

        .btn-sm {
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            min-height: 44px;
        }

        .btn-group-sm .btn {
            padding: 0.7rem 0.95rem;
        }

        .btn-full {
            width: 100%;
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e5e7eb;
            padding: 12px 15px;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }
        
        .sidebar {
            width: 270px;
            height: calc(100vh - 56px);
            position: fixed;
            top: 56px;
            left: 0;
            background: white;
            border-right: 1px solid #e5e7eb;
            z-index: 1000;
            overflow-y: auto;
        }
        
        .sidebar-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
        }
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .sidebar-link {
            display: block;
            padding: 12px 20px;
            color: var(--text-primary);
            text-decoration: none;
            border-radius: 8px;
            margin: 4px 15px;
            transition: all 0.3s;
            font-weight: 500;
        }
        
        .sidebar-link:hover {
            background: var(--bg-light);
            color: var(--primary);
            transform: translateX(5px);
        }
        
        .sidebar-link.active {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
        }
        
        .main-content {
            flex: 1;
            padding: 2rem 2rem 2rem 1.5rem;
            min-width: 0;
            margin-left: 270px;
        }

        .main-content > .container,
        .main-content .container {
            width: 100%;
            max-width: none;
            padding-left: 0;
            padding-right: 0;
            margin-left: 0;
            margin-right: 0;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid px-3">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Lost & Found" style="width: 32px; height: 32px; margin-right: 8px;">
                <span>Campus Lost & Found</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                @auth
                @endauth
                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right me-1"></i> Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}"><i class="bi bi-person-plus me-1"></i> Register</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <div class="avatar-sm me-2">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                {{ auth()->user()->name }}
                                @if(auth()->user()->isAdmin())
                                    <span class="badge bg-warning text-dark ms-2">Admin</span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if(auth()->user()->isAdmin())
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Admin Dashboard</a></li>
                                @else
                                    <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="bi bi-house me-2"></i>My Dashboard</a></li>
                                    <li><a class="dropdown-item" href="{{ route('lost-items.my-items') }}"><i class="bi bi-search me-2"></i>My Lost Reports</a></li>
                                    <li><a class="dropdown-item" href="{{ route('found-items.my-items') }}"><i class="bi bi-handbag me-2"></i>My Found Reports</a></li>
                                    <li><a class="dropdown-item" href="{{ route('claims.my-claims') }}"><i class="bi bi-file-text me-2"></i>My Claims</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person-gear me-2"></i>Profile</a></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-left me-2"></i>Logout</button>
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
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="container mt-3">
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif
        @auth
            @if(auth()->user()->isAdmin())
                <div class="d-flex">
                    <div class="sidebar">
                        <div class="sidebar-header p-3 border-bottom">
                            <h6 class="mb-0"><i class="bi bi-gear me-2"></i>Admin Panel</h6>
                        </div>
                        <nav class="sidebar-nav">
                            <a class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                            <a class="sidebar-link {{ request()->routeIs('admin.lost-items*') ? 'active' : '' }}" href="{{ route('admin.lost-items') }}">
                                <i class="bi bi-search me-2"></i> Lost Reports
                            </a>
                            <a class="sidebar-link {{ request()->routeIs('admin.found-items*') ? 'active' : '' }}" href="{{ route('admin.found-items') }}">
                                <i class="bi bi-handbag me-2"></i> Found Reports
                            </a>
                            <a class="sidebar-link {{ request()->routeIs('admin.claims*') ? 'active' : '' }}" href="{{ route('admin.claims') }}">
                                <i class="bi bi-file-text me-2"></i> Claims
                            </a>
                            <a class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users') }}">
                                <i class="bi bi-people me-2"></i> Users
                            </a>
                            <a class="sidebar-link {{ request()->routeIs('admin.departments*') ? 'active' : '' }}" href="{{ route('admin.departments') }}">
                                <i class="bi bi-building me-2"></i> Departments
                            </a>
                        </nav>
                    </div>
                    <div class="main-content">
                        {{ $slot }}
                    </div>
                </div>
            @else
                <div class="d-flex">
                    <div class="sidebar">
                        <div class="sidebar-header p-3 border-bottom">
                            <h6 class="mb-0"><i class="bi bi-person me-2"></i>User Panel</h6>
                        </div>
                        <nav class="sidebar-nav">
                            <a class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="bi bi-house me-2"></i> Dashboard
                            </a>
                            <a class="sidebar-link {{ request()->routeIs('lost-items.index') ? 'active' : '' }}" href="{{ route('lost-items.index') }}">
                                <i class="bi bi-search me-2"></i> Lost Items
                            </a>
                            <a class="sidebar-link {{ request()->routeIs('found-items.index') ? 'active' : '' }}" href="{{ route('found-items.index') }}">
                                <i class="bi bi-handbag me-2"></i> Found Items
                            </a>
                            <a class="sidebar-link {{ request()->routeIs('lost-items.my-items') ? 'active' : '' }}" href="{{ route('lost-items.my-items') }}">
                                <i class="bi bi-search me-2"></i> My Lost Reports
                            </a>
                            <a class="sidebar-link {{ request()->routeIs('found-items.my-items') ? 'active' : '' }}" href="{{ route('found-items.my-items') }}">
                                <i class="bi bi-handbag me-2"></i> My Found Reports
                            </a>
                            <a class="sidebar-link {{ request()->routeIs('claims.my-claims') ? 'active' : '' }}" href="{{ route('claims.my-claims') }}">
                                <i class="bi bi-file-text me-2"></i> My Claims
                            </a>
                        </nav>
                    </div>
                    <div class="main-content">
                        {{ $slot }}
                    </div>
                </div>
            @endif
        @else
            {{ $slot }}
        @endauth
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        .avatar-sm {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
        }
    </style>
</body>
</html>
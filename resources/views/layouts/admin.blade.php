<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'DadaGarments') }} Admin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-body">
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <div class="brand-block">
                <div class="brand-mark">DG</div>
                <div>
                    <div class="brand-name">DadaGarments</div>
                    <small>Admin Panel</small>
                </div>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-gauge-high"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="fas fa-gear"></i>
                    <span>Site Settings</span>
                </a>
                <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i>
                    <span>Products</span>
                </a>
                <a href="{{ route('admin.reviews.index') }}" class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                    <i class="fas fa-star"></i>
                    <span>Reviews</span>
                </a>
                <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="fas fa-cart-shopping"></i>
                    <span>Orders</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="user-chip">
                    <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <div>
                        <strong>{{ auth()->user()->name }}</strong>
                        <small>Administrator</small>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-right-from-bracket"></i>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="admin-main">
            <header class="admin-header">
                <div>
                    <h1>{{ isset($pageTitle) ? $pageTitle : 'Dashboard' }}</h1>
                </div>
                <div class="header-actions">
                    <span class="header-pill"><i class="fas fa-circle"></i> Live</span>
                    <span class="header-user">{{ auth()->user()->name }}</span>
                </div>
            </header>

            <div class="admin-content">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>

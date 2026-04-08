<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Perpustakaan' }}</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <div class="sidebar-brand">Perpustakaan</div>

            @if(session('user'))
                <nav class="sidebar-menu">
                    @if(session('user.role') === 'admin')
                        <a href="/admin/dashboard" class="sidebar-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">Dashboard</a>
                        <a href="/admin/books" class="sidebar-link {{ request()->is('admin/books*') ? 'active' : '' }}">Buku</a>
                        <a href="/admin/users" class="sidebar-link {{ request()->is('admin/users*') ? 'active' : '' }}">User</a>
                        <a href="/admin/transactions" class="sidebar-link {{ request()->is('admin/transactions*') ? 'active' : '' }}">Transaksi</a>
                    @else
                        <a href="/user/dashboard" class="sidebar-link {{ request()->is('user/dashboard') ? 'active' : '' }}">Dashboard</a>
                        <a href="/user/books" class="sidebar-link {{ request()->is('user/books') ? 'active' : '' }}">Buku</a>
                        <a href="/user/dashboard" class="sidebar-link {{ request()->is('user/dashboard') ? 'active' : '' }}">Transaksi</a>
                    @endif
                </nav>
            @endif

            <div class="sidebar-footer">
                @if(session('user'))
                    <a href="/logout" class="sidebar-link logout">Logout</a>
                @else
                    <a href="/login" class="sidebar-link">Login</a>
                    <a href="/register" class="sidebar-link">Register</a>
                @endif
            </div>
        </aside>

        <div class="content-area">
            <header class="topbar">
                <div>
                    <h1 class="page-title">{{ $title ?? 'Perpustakaan' }}</h1>
                </div>
                @if(session('user'))
                    <div class="user-info">
                        <span>{{ session('user.name') }}</span>
                        <span class="user-role">{{ strtoupper(session('user.role')) }}</span>
                    </div>
                @endif
            </header>

            <main class="main-content">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-error">{{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-error">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>

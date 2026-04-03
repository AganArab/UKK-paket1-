<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Aplikasi Peminjaman Alat')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-TkR+qGagfL0xoXqUrIh1daEqPfEo7xpJ5F2FbdXf6hbbshxCdn0F5v0uWc0PhdSzUKZ86Z88V+9AUG5FMw5zKg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <div class="sidebar-brand">
                <i class="fa-solid fa-toolbox"></i>
                <span>Workshop Kit</span>
            </div>
            <ul class="sidebar-menu">
                <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}"><i class="fa-solid fa-chart-line"></i><span>Dashboard</span></a>
                </li>
                @if(in_array(Auth::user()->role, ['admin', 'petugas']))
                <li class="menu-item {{ request()->routeIs('equipment.*') ? 'active' : '' }}">
                    <a href="{{ route('equipment.index') }}"><i class="fa-solid fa-screwdriver-wrench"></i><span>Alat</span></a>
                </li>
                <li class="menu-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <a href="{{ route('categories.index') }}"><i class="fa-solid fa-tags"></i><span>Kategori</span></a>
                </li>
                @if(Auth::user()->role === 'admin')
                <li class="menu-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <a href="{{ route('users.index') }}"><i class="fa-solid fa-users"></i><span>User</span></a>
                </li>
                @endif
                @endif
                <li class="menu-item {{ request()->routeIs('borrowings.*') ? 'active' : '' }}">
                    <a href="{{ route('borrowings.index') }}"><i class="fa-solid fa-hand-holding-box"></i><span>Peminjaman</span></a>
                </li>
                <li class="menu-item {{ request()->routeIs('pengembalian.*') ? 'active' : '' }}">
                    <a href="{{ route('pengembalian.index') }}"><i class="fa-solid fa-rotate-right"></i><span>Pengembalian</span></a>
                </li>
            </ul>
        </aside>

        <div class="main-content">
            <header class="top-navbar">
                <div class="brand-title">@yield('title', 'Aplikasi Peminjaman Alat')</div>
                <div class="navbar-actions">
                    <button class="icon-btn"><i class="fa-solid fa-bell"></i></button>
                    <div class="profile">
                        <i class="fa-solid fa-circle-user"></i>
                        <span>{{ Auth::user()->name }}</span>
                        <button class="btn-inline" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </div>
                </div>
            </header>

            <main class="content">
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
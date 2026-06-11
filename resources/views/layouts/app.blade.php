<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CivilWatch') }} — {{ $title ?? 'Dashboard' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-shell">

<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="logo-mark">
            </div>
            <div>
                <div class="logo-text">CivilWatch</div>
                <div class="logo-sub">Pengaduan Infrastruktur</div>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        @auth
            @if(Auth::user()->role === 'admin')
                <span class="nav-section-label">Admin</span>

                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7" rx="1" stroke-width="1.5"/>
                        <rect x="14" y="3" width="7" height="7" rx="1" stroke-width="1.5"/>
                        <rect x="14" y="14" width="7" height="7" rx="1" stroke-width="1.5"/>
                        <rect x="3" y="14" width="7" height="7" rx="1" stroke-width="1.5"/>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('admin.reports.index') }}" class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Manajemen Laporan
                </a>

                <a href="{{ route('admin.districts.index') }}" class="nav-item {{ request()->routeIs('admin.districts.*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Kelola Wilayah
                </a>

            @else
                <span class="nav-section-label">Workspace</span>

                <a href="{{ route('citizen.dashboard') }}" class="nav-item {{ request()->routeIs('citizen.dashboard') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7" rx="1" stroke-width="1.5"/>
                        <rect x="14" y="3" width="7" height="7" rx="1" stroke-width="1.5"/>
                        <rect x="14" y="14" width="7" height="7" rx="1" stroke-width="1.5"/>
                        <rect x="3" y="14" width="7" height="7" rx="1" stroke-width="1.5"/>
                    </svg>
                    Dashboard Saya
                </a>

                <a href="{{ route('citizen.reports.create') }}" class="nav-item {{ request()->routeIs('citizen.reports.create') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9" stroke-width="1.5"/>
                        <path d="M12 8v8m-4-4h8" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    Buat Laporan
                </a>

                <span class="nav-section-label" style="margin-top:8px;">Publik</span>

                <a href="{{ route('feed') }}" class="nav-item {{ request()->routeIs('feed') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Public Feed
                </a>
            @endif

            <span class="nav-section-label" style="margin-top:8px;">Akun</span>

            <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Profil
            </a>
        @endauth
    </nav>

    <div class="sidebar-footer">
        @auth
        <div class="sidebar-user">
            <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <div class="user-info">
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-role">{{ ucfirst(Auth::user()->role) }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" style="margin-top:6px;">
            @csrf
            <button type="submit" class="nav-item">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Keluar
            </button>
        </form>
        @endauth
    </div>
</aside>

<div class="main-wrapper">
    <header class="topbar">
        <div class="topbar-left">
            {{ $breadcrumb ?? '' }}
        </div>
        <div class="topbar-right">
            @auth
            <span class="topbar-role-badge {{ Auth::user()->role === 'admin' ? 'role-admin' : 'role-citizen' }}">
                @if(Auth::user()->role === 'admin')
                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-1px;margin-right:3px;"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" stroke-width="1.5"/><circle cx="12" cy="12" r="3" stroke-width="1.5"/></svg>
                    Admin
                @else
                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-1px;margin-right:3px;"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Warga
                @endif
            </span>
            @endauth
        </div>
    </header>

    <main class="page-content">
        @if(session('success'))
            <div class="alert alert-success">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink:0;"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink:0;"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                {{ session('error') }}
            </div>
        @endif
        {{ $slot }}
    </main>
</div>

</body>
</html>
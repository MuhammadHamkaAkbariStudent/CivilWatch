<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CivilWatch') }} — {{ $title ?? 'Platform Pengaduan Infrastruktur' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=sora:400,500,600,700,800|instrument-serif:400,400i&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --cw-navy: #0f1f3d;
            --cw-blue: #1a56db;
            --cw-accent: #f97316;
            --cw-surface: #f8fafc;
            --cw-border: #e2e8f0;
        }
    </style>
</head>
<body class="bg-[--cw-surface] text-slate-800 antialiased min-h-screen flex flex-col">

    <nav x-data="{ mobileOpen: false, userOpen: false }" class="bg-[--cw-navy] text-white sticky top-0 z-50 shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-8 h-8 bg-[--cw-accent] rounded-lg flex items-center justify-center font-bold text-white text-sm group-hover:scale-105 transition-transform">CW</div>
                    <span class="font-bold text-lg tracking-tight">CivilWatch</span>
                </a>

                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('feed') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-slate-300 hover:text-white hover:bg-white/10 transition-all {{ request()->routeIs('feed') ? 'bg-white/10 text-white' : '' }}">
                        Laporan Publik
                    </a>

                    @auth
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-slate-300 hover:text-white hover:bg-white/10 transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white' : '' }}">
                                Dashboard Admin
                            </a>
                            <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-slate-300 hover:text-white hover:bg-white/10 transition-all">
                                Kelola Laporan
                            </a>
                            <a href="{{ route('admin.districts.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-slate-300 hover:text-white hover:bg-white/10 transition-all">
                                Kelola Wilayah
                            </a>
                        @else
                            <a href="{{ route('citizen.dashboard') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-slate-300 hover:text-white hover:bg-white/10 transition-all {{ request()->routeIs('citizen.dashboard') ? 'bg-white/10 text-white' : '' }}">
                                Dashboard Saya
                            </a>
                            <a href="{{ route('citizen.reports.create') }}" class="px-4 py-2 bg-[--cw-accent] text-white rounded-lg text-sm font-semibold hover:bg-orange-500 transition-all">
                                + Buat Laporan
                            </a>
                        @endif
                    @endauth
                </div>

                <div class="hidden md:flex items-center gap-3">
                    @guest
                        <a href="{{ route('login') }}" class="text-sm text-slate-300 hover:text-white transition">Masuk</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-[--cw-blue] rounded-lg text-sm font-semibold hover:bg-blue-600 transition-all">Daftar</a>
                    @endguest

                    @auth
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                            <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-white/10 transition-all">
                                <div class="w-8 h-8 bg-[--cw-blue] rounded-full flex items-center justify-center text-sm font-bold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span class="text-sm font-medium max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-white text-slate-800 rounded-xl shadow-2xl border border-slate-100 overflow-hidden" style="display:none">
                                <div class="px-4 py-3 border-b border-slate-100">
                                    <p class="text-xs text-slate-500">Login sebagai</p>
                                    <p class="text-sm font-semibold truncate">{{ Auth::user()->name }}</p>
                                    <span class="inline-block mt-1 text-xs px-2 py-0.5 rounded-full font-medium {{ Auth::user()->isAdmin() ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                        {{ Auth::user()->isAdmin() ? 'Admin' : 'Warga' }}
                                    </span>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-slate-50 transition-colors">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Profil Saya
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endauth
                </div>

                <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-lg hover:bg-white/10 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="mobileOpen ? 'hidden' : 'block'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="mobileOpen ? 'block' : 'hidden'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <div x-show="mobileOpen" x-transition class="md:hidden border-t border-white/10 bg-[--cw-navy] px-4 py-3 space-y-1">
            <a href="{{ route('feed') }}" class="block px-3 py-2 rounded-lg text-sm text-slate-300 hover:text-white hover:bg-white/10">Laporan Publik</a>
            @auth
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg text-sm text-slate-300 hover:text-white hover:bg-white/10">Dashboard Admin</a>
                    <a href="{{ route('admin.reports.index') }}" class="block px-3 py-2 rounded-lg text-sm text-slate-300 hover:text-white hover:bg-white/10">Kelola Laporan</a>
                    <a href="{{ route('admin.districts.index') }}" class="block px-3 py-2 rounded-lg text-sm text-slate-300 hover:text-white hover:bg-white/10">Kelola Wilayah</a>
                @else
                    <a href="{{ route('citizen.dashboard') }}" class="block px-3 py-2 rounded-lg text-sm text-slate-300 hover:text-white hover:bg-white/10">Dashboard Saya</a>
                    <a href="{{ route('citizen.reports.create') }}" class="block px-3 py-2 rounded-lg text-sm bg-[--cw-accent] text-white font-semibold text-center">+ Buat Laporan</a>
                @endif
                <div class="border-t border-white/10 pt-2 mt-2">
                    <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-lg text-sm text-slate-300 hover:text-white hover:bg-white/10">Profil Saya</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-sm text-red-400 hover:bg-white/10">Keluar</button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="block px-3 py-2 rounded-lg text-sm text-slate-300 hover:text-white hover:bg-white/10">Masuk</a>
                <a href="{{ route('register') }}" class="block px-3 py-2 rounded-lg text-sm bg-[--cw-blue] text-white text-center font-semibold">Daftar</a>
            @endauth
        </div>
    </nav>

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition class="bg-emerald-50 border-b border-emerald-200">
            <div class="max-w-7xl mx-auto px-4 py-3 flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <p class="text-sm text-emerald-700 font-medium">{{ session('success') }}</p>
                <button @click="show = false" class="ml-auto text-emerald-500 hover:text-emerald-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition class="bg-red-50 border-b border-red-200">
            <div class="max-w-7xl mx-auto px-4 py-3 flex items-center gap-3">
                <svg class="w-5 h-5 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                <button @click="show = false" class="ml-auto text-red-500 hover:text-red-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    @endif

    <main class="flex-1">
        {{ $slot }}
    </main>

    <footer class="bg-[--cw-navy] text-slate-400 mt-auto">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 bg-[--cw-accent] rounded-md flex items-center justify-center text-white text-xs font-bold">CW</div>
                    <div>
                        <p class="text-white font-semibold text-sm">CivilWatch</p>
                        <p class="text-xs">Platform Pengaduan Infrastruktur Publik</p>
                    </div>
                </div>
                <p class="text-xs text-center">© {{ date('Y') }} CivilWatch — Dibuat untuk transparansi dan partisipasi warga Kota Banjarmasin</p>
                <div class="flex gap-4 text-s">
                    <p>Kontak: info@civilwatch.id</p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CivilWatch') }} — {{ $title ?? 'Autentikasi' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=sora:400,500,600,700,800|instrument-serif:400,400i&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --cw-navy: #0f1f3d;
            --cw-blue: #1a56db;
            --cw-accent: #f97316;
        }
    </style>
</head>
<body class="bg-slate-50 antialiased">
    <div class="min-h-screen flex">
        <div class="hidden lg:flex lg:w-1/2 bg-[--cw-navy] flex-col justify-between p-12 relative overflow-hidden">
            <div class="absolute inset-0 opacity-5">
                <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                    <defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/></pattern></defs>
                    <rect width="100%" height="100%" fill="url(#grid)"/>
                </svg>
            </div>

            <div class="absolute -top-32 -right-32 w-96 h-96 bg-[--cw-blue] rounded-full opacity-20 blur-3xl"></div>
            <div class="absolute -bottom-32 -left-16 w-64 h-64 bg-[--cw-accent] rounded-full opacity-15 blur-3xl"></div>

            <div class="relative z-10">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[--cw-accent] rounded-xl flex items-center justify-center font-bold text-white">CW</div>
                    <span class="text-white font-bold text-xl">CivilWatch</span>
                </a>
            </div>

            <div class="relative z-10 space-y-6">
                <h1 class="text-white text-4xl font-bold leading-tight">
                    Suara Warga,<br>
                    <span class="text-[--cw-accent]">Aksi Nyata.</span>
                </h1>
                <p class="text-slate-300 text-base leading-relaxed max-w-xs">
                    Laporkan masalah infrastruktur publik di sekitar Anda. Bersama, kita bangun Banjarmasin yang lebih baik.
                </p>
            </div>

            <div class="relative z-10">
                <p class="text-slate-500 text-xs">© {{ date('Y') }} CivilWatch — Platform Pengaduan Infrastruktur Publik</p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <div class="lg:hidden flex justify-center mb-8">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-[--cw-navy] rounded-xl flex items-center justify-center font-bold text-white">CW</div>
                        <span class="text-[--cw-navy] font-bold text-xl">CivilWatch</span>
                    </a>
                </div>
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
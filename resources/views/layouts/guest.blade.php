<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CivilWatch') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="auth-wrap">

    {{-- ═══ LEFT PANEL ═══ --}}
    <div class="auth-left">
        <div class="auth-brand">
            <div>
                <div class="auth-brand-name">CivilWatch</div>
                <div class="auth-brand-sub">Platform Pengaduan Warga</div>
            </div>
        </div>

        <div class="auth-hero">
            <div class="auth-hero-title">
                Laporkan Masalah,<br><span>Bangun Kota</span><br>Bersama-sama
            </div>
            <div class="auth-hero-desc">
                CivilWatch adalah jembatan antara warga dan pemerintah. Laporkan
                infrastruktur rusak, pantau progres penanganan, dan dukung laporan warga lain.
            </div>
            <div class="auth-features">
                <div class="auth-feature">
                    <div class="auth-feature-icon">
                        <svg width="14" height="14" fill="none" stroke="var(--accent)" viewBox="0 0 24 24">
                            <path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="auth-feature-text">Upload foto bukti kerusakan langsung dari lokasi</div>
                </div>
                <div class="auth-feature">
                    <div class="auth-feature-icon">
                        <svg width="14" height="14" fill="none" stroke="var(--accent)" viewBox="0 0 24 24">
                            <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="auth-feature-text">Pantau status laporan secara real-time</div>
                </div>
                <div class="auth-feature">
                    <div class="auth-feature-icon">
                        <svg width="14" height="14" fill="none" stroke="var(--accent)" viewBox="0 0 24 24">
                            <path d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="auth-feature-text">Dukung laporan urgent dengan fitur upvote</div>
                </div>
            </div>
        </div>

        <div class="auth-stats">
            <div class="auth-stat">
                <div class="auth-stat-value">24 Jam</div>
                <div class="auth-stat-label">Akses Pengaduan</div>
            </div>
            <div class="auth-stat">
                <div class="auth-stat-value">100%</div>
                <div class="auth-stat-label">Gratis Tanpa Biaya</div>
            </div>
        </div>
    </div>

    {{-- ═══ RIGHT PANEL ═══ --}}
    <div class="auth-right">
        <div class="auth-form-wrap">
            {{ $slot }}
        </div>
    </div>

</div>
</body>
</html>
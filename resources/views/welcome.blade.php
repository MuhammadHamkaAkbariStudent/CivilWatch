<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CivilWatch — Pengaduan Infrastruktur Publik</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-grid">

{{-- ═══ NAV ═══ --}}
<nav class="pub-nav">
    <div class="pub-nav-inner">
        <a href="{{ route('home') }}" class="pub-brand">
            <span class="pub-brand-text">CivilWatch</span>
        </a>
        <div class="pub-nav-links">
            <a href="{{ route('feed') }}" class="pub-nav-link">Public Feed</a>
            <a href="#cara-kerja" class="pub-nav-link">Cara Kerja</a>
        </div>
        <div class="pub-nav-auth">
            @auth
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="pub-nav-btn pub-nav-btn-primary">Dashboard</a>
                @else
                    <a href="{{ route('citizen.dashboard') }}" class="pub-nav-btn pub-nav-btn-primary">Dashboard</a>
                @endif
            @else
                <a href="{{ route('login') }}"    class="pub-nav-btn pub-nav-btn-outline">Masuk</a>
                <a href="{{ route('register') }}" class="pub-nav-btn pub-nav-btn-primary">Daftar</a>
            @endauth
        </div>
    </div>
</nav>

{{-- ═══ HERO ═══ --}}
<section>
    <div class="hero-wrap">
        <div class="hero-content">
            <div class="hero-tag">
                <span class="hero-tag-dot"></span>
                Platform Pengaduan Warga Banjarmasin
            </div>
            <h1 class="hero-title">
                Laporkan, <br><span class="accent">Kawal</span>, dan<br>
                <span class="primary">Perbaiki</span> Kota
            </h1>
            <p class="hero-desc">
                CivilWatch memudahkan warga melaporkan kerusakan infrastruktur publik
                secara transparan dan terukur. Setiap laporan langsung diteruskan kepada
                instansi berwenang. Gratis, tidak dipungut biaya. Gratis, tanpa biaya apapun.
            </p>
        </div>

        {{-- Hero Visual --}}
        <div class="hero-visual">
            <div class="hv-header">
                <div class="hv-dot" style="background:#EF4444;"></div>
                <div class="hv-dot" style="background:#F59E0B;"></div>
                <div class="hv-dot" style="background:#10B981;"></div>
                <span class="hv-title">Data Real-Time CivilWatch</span>
            </div>
            <div class="hv-body" style="justify-content:space-around;">
                @foreach([
                    ['count' => number_format($totalReports),      'label' => 'Laporan Masuk',        'bg' => '#DBEAFE', 'color' => '#1E40AF',
                     'icon' => '<path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>'],
                    ['count' => number_format($inProgressReports), 'label' => 'Sedang Ditangani',     'bg' => '#FEE2E2', 'color' => '#991B1B',
                     'icon' => '<path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" stroke-width="1.5"/><circle cx="12" cy="12" r="3" stroke-width="1.5"/>'],
                    ['count' => number_format($resolvedReports),   'label' => 'Berhasil Diselesaikan','bg' => '#D1FAE5', 'color' => '#065F46',
                     'icon' => '<path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>'],
                    ['count' => number_format($totalUpvotes),      'label' => 'Dukungan Warga',       'bg' => '#FFF7ED', 'color' => '#9A3412',
                     'icon' => '<path d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>'],
                ] as $stat)
                <div class="hv-report">
                    <div style="width:36px;height:36px;border-radius:8px;background:{{ $stat['bg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-left:30px;">
                        <svg width="18" height="18" fill="none" stroke="{{ $stat['color'] }}" viewBox="0 0 24 24">{!! $stat['icon'] !!}</svg>
                    </div>
                    <div style="flex:1;">
                        <span style="font-size:25px;font-weight:700;font-family:'IBM Plex Mono',monospace;color:var(--text);line-height:1.1;margin-left:25px;"
                        >{{ $stat['count'] }}</span>
                        <span class="hv-title" style="color:var(--text);display:inline-flex;align-items:center;gap:9px;margin-top:4px;margin-left:25px;font-size:25px;"
                        >{{ $stat['label'] }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ═══ HOW IT WORKS ═══ --}}
<section id="cara-kerja">
    <div class="section-wrap">
        <div class="section-header">
            <div class="section-tag">Cara Kerja</div>
            <div class="section-title">4 Langkah Mudah Melapor</div>
            <div class="section-desc">Dari laporan hingga penyelesaian, semua terpantau secara transparan.</div>
        </div>
        <div class="steps-grid">
            @php
            $steps = [
                [
                    'title' => 'Tulis Laporan',
                    'desc'  => 'Isi judul, deskripsi kronologis, lokasi, dan upload foto bukti kerusakan.',
                    'icon'  => '<path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>',
                ],
                [
                    'title' => 'Verifikasi Admin',
                    'desc'  => 'Tim kami memverifikasi laporan dalam 3 hari dan meneruskan ke instansi berwenang.',
                    'icon'  => '<path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>',
                ],
                [
                    'title' => 'Penanganan',
                    'desc'  => 'Instansi menindaklanjuti dan memberikan update progres yang bisa Anda pantau real-time.',
                    'icon'  => '<path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" stroke-width="1.5"/><circle cx="12" cy="12" r="3" stroke-width="1.5"/>',
                ],
                [
                    'title' => 'Selesai',
                    'desc'  => 'Masalah terselesaikan dan laporan ditutup. Anda mendapat konfirmasi penyelesaian.',
                    'icon'  => '<path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>',
                ],
            ];
            @endphp
            @foreach($steps as $i => $step)
            <div class="step">
                <div class="step-num">
                    <span class="step-badge">{{ $i + 1 }}</span>
                    <svg width="28" height="28" fill="none" stroke="var(--primary)" viewBox="0 0 24 24">{!! $step['icon'] !!}</svg>
                </div>
                <div class="step-title">{{ $step['title'] }}</div>
                <div class="step-desc">{{ $step['desc'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══ CTA ═══ --}}
<div class="cta-section">
    <div class="cta-inner">
        <div class="cta-title">Siap Membuat Kota Lebih Baik?</div>
        <div class="cta-desc">Bergabunglah bersama ribuan warga Banjarmasin yang telah aktif melaporkan dan memperbaiki infrastruktur kota.</div>
        <div class="cta-btns">
            @auth
                <a href="{{ route('citizen.reports.create') }}" class="cta-btn-white">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;margin-right:6px;"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Buat Laporan
                </a>
                <a href="{{ route('feed') }}" class="cta-btn-outline-white">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;margin-right:6px;" class="nav-icon">
                        <path d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Laporan Warga
                </a>
            @else
                <a href="{{ route('register') }}" class="cta-btn-white">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;margin-right:6px;"><path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Daftar Sekarang
                </a>
                <a href="{{ route('login') }}" class="cta-btn-outline-white">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;margin-right:6px;"><path d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Masuk Sekarang
                </a>
            @endauth
        </div>
    </div>
</div>

{{-- ═══ FOOTER ═══ --}}
<footer style="background:#0F172A;padding:40px 32px;">
    <div class="footer-inner">
        <div class="footer-brand" style="display:flex;align-items:center;gap:10px;">
            CivilWatch
        </div>
        <div class="footer-copy">© {{ date('Y') }} CivilWatch. Kota Banjarmasin.</div>
    </div>
</footer>

</body>
</html>
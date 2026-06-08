<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CivilWatch') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=sora:300,400,500,600,700,800|ibm-plex-mono:400,500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary: #1E3A8A; --primary-dark: #162d6e; --primary-light: #3B5FC4;
            --accent: #F97316; --bg: #F8FAFC; --surface: #FFFFFF;
            --text: #334155; --text-muted: #64748B; --text-light: #94A3B8;
            --border: #E2E8F0; --border-strong: #CBD5E1;
            --danger: #EF4444;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Sora', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }
        /* LEFT PANEL */
        .auth-left {
            background: var(--primary);
            padding: 60px;
            display: flex; flex-direction: column; justify-content: space-between;
            position: relative; overflow: hidden;
        }
        .auth-left::before {
            content: '';
            position: absolute; top: -80px; right: -80px;
            width: 400px; height: 400px;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
        }
        .auth-left::after {
            content: '';
            position: absolute; bottom: -120px; left: -60px;
            width: 500px; height: 500px;
            background: rgba(249,115,22,0.08);
            border-radius: 50%;
        }
        .auth-brand {
            display: flex; align-items: center; gap: 12px;
            position: relative; z-index: 1;
        }
        .auth-brand-icon {
            width: 44px; height: 44px; background: var(--accent);
            border-radius: 10px; display: flex; align-items: center; justify-content: center;
            font-size: 22px;
        }
        .auth-brand-name { font-size: 22px; font-weight: 700; color: #fff; }
        .auth-brand-sub { font-size: 11px; color: rgba(255,255,255,0.45); letter-spacing: 1.5px; text-transform: uppercase; }
        .auth-hero { position: relative; z-index: 1; }
        .auth-hero-title {
            font-size: 36px; font-weight: 700; color: #fff;
            line-height: 1.2; letter-spacing: -0.5px; margin-bottom: 16px;
        }
        .auth-hero-title span { color: var(--accent); }
        .auth-hero-desc { font-size: 15px; color: rgba(255,255,255,0.65); line-height: 1.7; margin-bottom: 32px; }
        .auth-stats { display: grid; grid-template-columns: repeat(3,1fr); gap: 16px; position: relative; z-index: 1; }
        .auth-stat {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px; padding: 16px;
        }
        .auth-stat-value { font-size: 24px; font-weight: 700; color: #fff; font-family: 'IBM Plex Mono', monospace; }
        .auth-stat-label { font-size: 11px; color: rgba(255,255,255,0.5); margin-top: 3px; }
        .auth-features { position: relative; z-index: 1; }
        .auth-feature { display: flex; align-items: center; gap: 10px; margin-bottom: 14px; }
        .auth-feature-icon {
            width: 28px; height: 28px; background: rgba(249,115,22,0.15);
            border-radius: 6px; display: flex; align-items: center; justify-content: center;
            font-size: 13px; flex-shrink: 0;
        }
        .auth-feature-text { font-size: 13.5px; color: rgba(255,255,255,0.75); }
        /* RIGHT PANEL */
        .auth-right {
            display: flex; align-items: center; justify-content: center;
            padding: 60px;
        }
        .auth-form-wrap { width: 100%; max-width: 400px; }
        .auth-form-title { font-size: 26px; font-weight: 700; color: var(--text); margin-bottom: 6px; letter-spacing: -0.3px; }
        .auth-form-sub { font-size: 14px; color: var(--text-muted); margin-bottom: 32px; }
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px; }
        .form-input {
            width: 100%; padding: 11px 14px;
            border: 1.5px solid var(--border);
            border-radius: 8px; background: var(--surface);
            font-size: 14px; color: var(--text);
            font-family: 'Sora', sans-serif;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
            outline: none;
        }
        .form-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(30,58,138,0.08); }
        .form-input::placeholder { color: var(--text-light); }
        .form-error { font-size: 12px; color: var(--danger); margin-top: 4px; }
        .btn-auth {
            width: 100%; padding: 12px;
            background: var(--primary); color: #fff;
            border: none; border-radius: 8px;
            font-size: 14px; font-weight: 600; font-family: 'Sora', sans-serif;
            cursor: pointer; transition: background 0.15s;
            margin-top: 8px;
        }
        .btn-auth:hover { background: var(--primary-dark); }
        .auth-link { color: var(--primary); font-weight: 600; text-decoration: none; }
        .auth-link:hover { text-decoration: underline; }
        .auth-footer { margin-top: 28px; text-align: center; font-size: 13.5px; color: var(--text-muted); }
        .auth-divider { display: flex; align-items: center; gap: 12px; margin: 20px 0; }
        .auth-divider-line { flex: 1; height: 1px; background: var(--border); }
        .auth-divider-text { font-size: 12px; color: var(--text-light); }
        .checkbox-row { display: flex; align-items: center; gap: 8px; }
        .checkbox-row input { accent-color: var(--primary); }
        .checkbox-row label { font-size: 13px; color: var(--text-muted); cursor: pointer; }
        .auth-form-actions { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
        .alert { padding: 10px 14px; border-radius: 8px; font-size: 13px; margin-bottom: 16px; }
        .alert-success { background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; }
        .alert-error { background: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; }
        @media (max-width: 900px) {
            body { grid-template-columns: 1fr; }
            .auth-left { display: none; }
        }
    </style>
</head>
<body>
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
                CivilWatch adalah jembatan antara warga dan pemerintah. Laporkan infrastruktur rusak, pantau progres penanganan, dan dukung laporan warga lain.
            </div>
            <div class="auth-features">
                <div class="auth-feature"><div class="auth-feature-icon">📸</div><div class="auth-feature-text">Upload foto bukti kerusakan langsung dari lokasi</div></div>
                <div class="auth-feature"><div class="auth-feature-icon">🔔</div><div class="auth-feature-text">Pantau status laporan secara real-time</div></div>
                <div class="auth-feature"><div class="auth-feature-icon">👍</div><div class="auth-feature-text">Dukung laporan urgent dengan fitur upvote</div></div>
            </div>
        </div>

        <div class="auth-stats">
            <div class="auth-stat"><div class="auth-stat-value">24H+</div><div class="auth-stat-label">Waktu Operasional</div></div>
            <div class="auth-stat"><div class="auth-stat-value">700+</div><div class="auth-stat-label">Instansi</div></div>
            <div class="auth-stat"><div class="auth-stat-value">34</div><div class="auth-stat-label">Provinsi</div></div>
        </div>
    </div>

    <div class="auth-right">
        <div class="auth-form-wrap">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
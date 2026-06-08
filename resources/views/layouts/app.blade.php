<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CivilWatch') }} — {{ $title ?? 'Dashboard' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=sora:300,400,500,600,700,800|ibm-plex-mono:400,500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary: #1E3A8A;
            --primary-dark: #162d6e;
            --primary-light: #3B5FC4;
            --accent: #F97316;
            --accent-dark: #ea6a0e;
            --bg: #F8FAFC;
            --surface: #FFFFFF;
            --text: #334155;
            --text-muted: #64748B;
            --text-light: #94A3B8;
            --border: #E2E8F0;
            --border-strong: #CBD5E1;
            --success: #10B981;
            --warning: #F59E0B;
            --danger: #EF4444;
            --info: #3B82F6;
            --pending-bg: #FEF3C7; --pending-text: #92400E; --pending-border: #F59E0B;
            --published-bg: #DBEAFE; --published-text: #1E40AF; --published-border: #3B82F6;
            --inprog-bg: #FEE2E2; --inprog-text: #991B1B; --inprog-border: #EF4444;
            --resolved-bg: #D1FAE5; --resolved-text: #065F46; --resolved-border: #10B981;
            --rejected-bg: #F1F5F9; --rejected-text: #475569; --rejected-border: #94A3B8;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Sora', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }
        /* ── SIDEBAR ── */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: var(--primary);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
            transition: transform 0.3s ease;
        }
        .sidebar-brand {
            padding: 28px 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-brand .logo-mark {
            display: flex; align-items: center; gap: 10px;
        }
        .sidebar-brand .logo-icon {
            width: 36px; height: 36px;
            background: var(--accent);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
        }
        .sidebar-brand .logo-text {
            font-size: 18px; font-weight: 700;
            color: #fff; letter-spacing: -0.3px;
        }
        .sidebar-brand .logo-sub {
            font-size: 10px; color: rgba(255,255,255,0.45);
            letter-spacing: 1.5px; text-transform: uppercase;
            margin-top: 2px;
        }
        .sidebar-nav { flex: 1; padding: 16px 12px; overflow-y: auto; }
        .nav-section-label {
            font-size: 9px; font-weight: 600;
            color: rgba(255,255,255,0.35);
            letter-spacing: 2px; text-transform: uppercase;
            padding: 12px 12px 6px;
        }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 8px;
            color: rgba(255,255,255,0.65);
            font-size: 13.5px; font-weight: 500;
            text-decoration: none;
            transition: all 0.15s ease;
            margin-bottom: 2px;
            position: relative;
        }
        .nav-item:hover { background: rgba(255,255,255,0.08); color: #fff; }
        .nav-item.active {
            background: rgba(255,255,255,0.12);
            color: #fff;
        }
        .nav-item.active::before {
            content: '';
            position: absolute; left: 0; top: 6px; bottom: 6px;
            width: 3px; background: var(--accent);
            border-radius: 0 2px 2px 0;
        }
        .nav-item .nav-icon { width: 18px; height: 18px; flex-shrink: 0; opacity: 0.75; }
        .nav-item.active .nav-icon, .nav-item:hover .nav-icon { opacity: 1; }
        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-user {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 8px;
            cursor: pointer;
        }
        .sidebar-user:hover { background: rgba(255,255,255,0.08); }
        .user-avatar {
            width: 34px; height: 34px; border-radius: 8px;
            background: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 700; color: #fff;
            flex-shrink: 0;
        }
        .user-info { flex: 1; min-width: 0; }
        .user-name { font-size: 13px; font-weight: 600; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-role { font-size: 11px; color: rgba(255,255,255,0.45); }
        /* ── MAIN CONTENT ── */
        .main-wrapper {
            margin-left: 260px;
            flex: 1;
            min-height: 100vh;
            display: flex; flex-direction: column;
        }
        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 32px;
            height: 64px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 50;
        }
        .topbar-left { display: flex; align-items: center; gap: 8px; }
        .breadcrumb-item {
            font-size: 13px; color: var(--text-muted);
            text-decoration: none;
        }
        .breadcrumb-item:hover { color: var(--primary); }
        .breadcrumb-sep { color: var(--text-light); font-size: 12px; }
        .breadcrumb-current { font-size: 13px; font-weight: 600; color: var(--text); }
        .topbar-right { display: flex; align-items: center; gap: 16px; }
        .topbar-role-badge {
            font-size: 11px; font-weight: 600;
            padding: 4px 10px; border-radius: 20px;
            letter-spacing: 0.5px; text-transform: uppercase;
        }
        .role-admin { background: #EFF6FF; color: var(--primary); border: 1px solid #BFDBFE; }
        .role-citizen { background: #FFF7ED; color: #9A3412; border: 1px solid #FED7AA; }
        .page-content {
            padding: 32px;
            flex: 1;
        }
        /* ── PAGE HEADER ── */
        .page-header { margin-bottom: 28px; }
        .page-title { font-size: 24px; font-weight: 700; color: var(--text); letter-spacing: -0.5px; }
        .page-desc { font-size: 14px; color: var(--text-muted); margin-top: 4px; }
        /* ── CARDS ── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
        }
        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-title { font-size: 15px; font-weight: 600; color: var(--text); }
        .card-body { padding: 24px; }
        /* ── STATS ── */
        .stats-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; margin-bottom: 28px; }
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px 24px;
            position: relative; overflow: hidden;
        }
        .stat-card::after {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 3px;
        }
        .stat-card.s-blue::after { background: var(--primary); }
        .stat-card.s-orange::after { background: var(--accent); }
        .stat-card.s-green::after { background: var(--success); }
        .stat-card.s-amber::after { background: var(--warning); }
        .stat-label { font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-value { font-size: 32px; font-weight: 700; color: var(--text); margin: 6px 0 2px; letter-spacing: -1px; font-family: 'IBM Plex Mono', monospace; }
        .stat-sub { font-size: 12px; color: var(--text-light); }
        .stat-icon {
            position: absolute; right: 20px; top: 50%; transform: translateY(-50%);
            width: 44px; height: 44px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
        }
        .stat-icon.s-blue { background: #EFF6FF; }
        .stat-icon.s-orange { background: #FFF7ED; }
        .stat-icon.s-green { background: #ECFDF5; }
        .stat-icon.s-amber { background: #FFFBEB; }
        /* ── TABLE ── */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th {
            background: var(--bg); padding: 10px 16px;
            font-size: 11px; font-weight: 600; color: var(--text-muted);
            text-transform: uppercase; letter-spacing: 0.5px;
            border-bottom: 1px solid var(--border);
            text-align: left;
        }
        .data-table td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
            font-size: 13.5px; color: var(--text);
        }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tr:hover td { background: #F8FAFC; }
        /* ── BADGES ── */
        .badge {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: 11px; font-weight: 600;
            padding: 3px 9px; border-radius: 20px;
            letter-spacing: 0.3px; white-space: nowrap;
        }
        .badge-dot { width: 6px; height: 6px; border-radius: 50%; }
        .badge-pending { background: var(--pending-bg); color: var(--pending-text); border: 1px solid var(--pending-border); }
        .badge-published { background: var(--published-bg); color: var(--published-text); border: 1px solid var(--published-border); }
        .badge-in-progress { background: var(--inprog-bg); color: var(--inprog-text); border: 1px solid var(--inprog-border); }
        .badge-resolved { background: var(--resolved-bg); color: var(--resolved-text); border: 1px solid var(--resolved-border); }
        .badge-rejected { background: var(--rejected-bg); color: var(--rejected-text); border: 1px solid var(--rejected-border); }
        /* ── BUTTONS ── */
        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            font-size: 13px; font-weight: 600; font-family: 'Sora', sans-serif;
            padding: 9px 18px; border-radius: 8px;
            border: 1.5px solid transparent;
            cursor: pointer; text-decoration: none;
            transition: all 0.15s ease; white-space: nowrap;
        }
        .btn-primary { background: var(--primary); color: #fff; border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }
        .btn-accent { background: var(--accent); color: #fff; border-color: var(--accent); }
        .btn-accent:hover { background: var(--accent-dark); border-color: var(--accent-dark); }
        .btn-outline { background: transparent; color: var(--text); border-color: var(--border-strong); }
        .btn-outline:hover { background: var(--bg); border-color: var(--text-light); }
        .btn-danger { background: #FEF2F2; color: var(--danger); border-color: #FECACA; }
        .btn-danger:hover { background: var(--danger); color: #fff; border-color: var(--danger); }
        .btn-success { background: #ECFDF5; color: var(--success); border-color: #A7F3D0; }
        .btn-success:hover { background: var(--success); color: #fff; border-color: var(--success); }
        .btn-sm { padding: 6px 12px; font-size: 12px; border-radius: 6px; }
        .btn-icon { padding: 8px; border-radius: 6px; }
        /* ── FORMS ── */
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px; }
        .form-label span { color: var(--danger); margin-left: 2px; }
        .form-input, .form-select, .form-textarea {
            width: 100%; padding: 10px 14px;
            border: 1.5px solid var(--border);
            border-radius: 8px; background: var(--surface);
            font-size: 14px; color: var(--text);
            font-family: 'Sora', sans-serif;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
            outline: none;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.08);
        }
        .form-input::placeholder, .form-textarea::placeholder { color: var(--text-light); }
        .form-textarea { resize: vertical; min-height: 120px; }
        .form-error { font-size: 12px; color: var(--danger); margin-top: 5px; }
        /* ── ALERT ── */
        .alert {
            padding: 12px 16px; border-radius: 8px;
            font-size: 13.5px; display: flex; align-items: flex-start; gap: 10px;
            margin-bottom: 20px;
        }
        .alert-success { background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; }
        .alert-error { background: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; }
        .alert-info { background: #EFF6FF; color: #1E40AF; border: 1px solid #BFDBFE; }
        /* ── PAGINATION ── */
        .pagination { display: flex; align-items: center; gap: 4px; }
        .page-link {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 34px; height: 34px; padding: 0 8px;
            border-radius: 6px; font-size: 13px; font-weight: 500;
            text-decoration: none; color: var(--text-muted);
            border: 1px solid var(--border);
            transition: all 0.15s;
        }
        .page-link:hover { background: var(--bg); color: var(--primary); border-color: var(--primary); }
        .page-link.active { background: var(--primary); color: #fff; border-color: var(--primary); }
        /* ── TIMELINE ── */
        .timeline { position: relative; padding-left: 28px; }
        .timeline::before {
            content: ''; position: absolute; left: 8px; top: 8px; bottom: 8px;
            width: 2px; background: var(--border);
        }
        .timeline-item { position: relative; margin-bottom: 24px; }
        .timeline-dot {
            position: absolute; left: -24px; top: 2px;
            width: 16px; height: 16px; border-radius: 50%;
            border: 2px solid var(--surface);
            display: flex; align-items: center; justify-content: center;
        }
        .timeline-date { font-size: 11px; color: var(--text-light); font-family: 'IBM Plex Mono', monospace; margin-bottom: 4px; }
        .timeline-content { background: var(--bg); border: 1px solid var(--border); border-radius: 8px; padding: 12px 14px; }
        .timeline-note { font-size: 13.5px; color: var(--text); line-height: 1.6; }
        /* ── REPORT CARD (Public Feed) ── */
        .report-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px; overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            display: flex; flex-direction: column;
        }
        .report-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(30,58,138,0.08);
        }
        .report-card-img { width: 100%; height: 180px; object-fit: cover; background: var(--bg); }
        .report-card-body { padding: 16px; flex: 1; display: flex; flex-direction: column; }
        .report-card-meta { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
        .report-card-title { font-size: 15px; font-weight: 600; color: var(--text); margin-bottom: 6px; line-height: 1.4; }
        .report-card-desc { font-size: 13px; color: var(--text-muted); line-height: 1.6; flex: 1; }
        .report-card-footer { padding: 12px 16px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .report-district { display: inline-flex; align-items: center; gap: 5px; font-size: 12px; color: var(--text-muted); }
        /* ── UPVOTE BUTTON ── */
        .upvote-btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 12px; border-radius: 20px;
            font-size: 12px; font-weight: 600;
            border: 1.5px solid var(--border);
            background: var(--surface); color: var(--text-muted);
            cursor: pointer; transition: all 0.15s ease;
        }
        .upvote-btn:hover, .upvote-btn.upvoted {
            background: #FFF7ED; border-color: var(--accent); color: var(--accent);
        }
        /* ── DANGER ZONE ── */
        .danger-zone {
            border: 2px solid #FECACA; border-radius: 12px; padding: 20px 24px;
            background: #FFF5F5;
        }
        .danger-zone-title { font-size: 15px; font-weight: 600; color: var(--danger); margin-bottom: 6px; }
        .danger-zone-desc { font-size: 13.5px; color: #7F1D1D; margin-bottom: 16px; }
        /* ── STATUS BUTTONS ── */
        .status-btn-grid { display: grid; grid-template-columns: repeat(2,1fr); gap: 10px; }
        .status-btn {
            padding: 12px 16px; border-radius: 8px;
            font-size: 13px; font-weight: 600; font-family: 'Sora', sans-serif;
            border: 1.5px solid; cursor: pointer;
            text-align: center; transition: all 0.15s;
        }
        /* ── FILTER BAR ── */
        .filter-bar {
            display: flex; align-items: center; gap: 12px;
            margin-bottom: 20px; flex-wrap: wrap;
        }
        .search-box {
            position: relative; flex: 1; min-width: 200px;
        }
        .search-icon {
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
            color: var(--text-light); font-size: 14px;
        }
        .search-input {
            width: 100%; padding: 9px 14px 9px 38px;
            border: 1.5px solid var(--border); border-radius: 8px;
            font-size: 13.5px; font-family: 'Sora', sans-serif; color: var(--text);
            background: var(--surface); outline: none; transition: border-color 0.15s;
        }
        .search-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(30,58,138,0.06); }
        /* ── EMPTY STATE ── */
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state-icon { font-size: 48px; margin-bottom: 16px; opacity: 0.5; }
        .empty-state-title { font-size: 17px; font-weight: 600; color: var(--text); margin-bottom: 6px; }
        .empty-state-desc { font-size: 14px; color: var(--text-muted); margin-bottom: 20px; }
        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-wrapper { margin-left: 0; }
            .stats-grid { grid-template-columns: repeat(2,1fr); }
        }
        /* ── SCROLLBAR ── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border-strong); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-light); }
    </style>
</head>
<body>
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="logo-mark">
                <div>
                    <div class="logo-text">CivilWatch</div>
                    <div class="logo-sub">Pengaduan Infrastruktur</div>
                </div>
            </div>
        </div>

        <nav class="sidebar-nav">
            @auth
                @if(Auth::user()->role === 'admin')
                    <div class="nav-section-label">Admin</div>
                    <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1" stroke-width="1.5"/><rect x="14" y="3" width="7" height="7" rx="1" stroke-width="1.5"/><rect x="14" y="14" width="7" height="7" rx="1" stroke-width="1.5"/><rect x="3" y="14" width="7" height="7" rx="1" stroke-width="1.5"/></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Manajemen Laporan
                    </a>
                    <a href="{{ route('admin.districts.index') }}" class="nav-item {{ request()->routeIs('admin.districts.*') ? 'active' : '' }}">
                        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Kelola Wilayah
                    </a>
                @else
                    <div class="nav-section-label">Workspace</div>
                    <a href="{{ route('citizen.dashboard') }}" class="nav-item {{ request()->routeIs('citizen.dashboard') ? 'active' : '' }}">
                        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1" stroke-width="1.5"/><rect x="14" y="3" width="7" height="7" rx="1" stroke-width="1.5"/><rect x="14" y="14" width="7" height="7" rx="1" stroke-width="1.5"/><rect x="3" y="14" width="7" height="7" rx="1" stroke-width="1.5"/></svg>
                        Dashboard Saya
                    </a>
                    <a href="{{ route('citizen.reports.create') }}" class="nav-item {{ request()->routeIs('citizen.reports.create') ? 'active' : '' }}">
                        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="1.5" stroke-linecap="round"/></svg>
                        Buat Laporan
                    </a>
                    <div class="nav-section-label" style="margin-top:8px;">Publik</div>
                    <a href="{{ route('feed') }}" class="nav-item {{ request()->routeIs('feed') ? 'active' : '' }}">
                        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Public Feed
                    </a>
                @endif
                <div class="nav-section-label" style="margin-top:8px;">Akun</div>
                <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Profil
                </a>
            @endauth
        </nav>

        <div class="sidebar-footer">
            @auth
            <div class="sidebar-user" x-data="{ open: false }" @click="open=!open">
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-role">{{ ucfirst(Auth::user()->role) }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="margin-top:6px;">
                @csrf
                <button type="submit" class="nav-item" style="width:100%; border:none; cursor:pointer; background:transparent;">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Keluar
                </button>
            </form>
            @endauth
        </div>
    </aside>

    <!-- MAIN -->
    <div class="main-wrapper">
        <header class="topbar">
            <div class="topbar-left">
                {{ $breadcrumb ?? '' }}
            </div>
            <div class="topbar-right">
                @auth
                <span class="topbar-role-badge {{ Auth::user()->role === 'admin' ? 'role-admin' : 'role-citizen' }}">
                    {{ Auth::user()->role === 'admin' ? '⚙️ Admin' : '👤 Warga' }}
                </span>
                @endauth
            </div>
        </header>

        <main class="page-content">
            @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="alert alert-error">⚠️ {{ session('error') }}</div>
            @endif
            {{ $slot }}
        </main>
    </div>
</body>
</html>
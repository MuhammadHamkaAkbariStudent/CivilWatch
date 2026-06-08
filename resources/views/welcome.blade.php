<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CivilWatch — Pengaduan Infrastruktur Publik</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=sora:300,400,500,600,700,800|ibm-plex-mono:400,500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary: #1E3A8A; --primary-dark: #162d6e; --accent: #F97316;
            --bg: #F8FAFC; --surface: #FFFFFF; --text: #334155;
            --text-muted: #64748B; --text-light: #94A3B8;
            --border: #E2E8F0; --success: #10B981;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Sora', sans-serif; background: var(--bg); color: var(--text); }

        /* NAV */
        .nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            background: rgba(248,250,252,0.92); backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
        }
        .nav-inner {
            max-width: 1200px; margin: 0 auto;
            padding: 0 32px; height: 68px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .nav-brand { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .nav-brand-icon {
            width: 38px; height: 38px; background: var(--primary);
            border-radius: 9px; display: flex; align-items: center; justify-content: center;
            font-size: 18px;
        }
        .nav-brand-text { font-size: 18px; font-weight: 700; color: var(--primary); }
        .nav-links { display: flex; align-items: center; gap: 32px; }
        .nav-link { font-size: 14px; font-weight: 500; color: var(--text-muted); text-decoration: none; transition: color 0.15s; }
        .nav-link:hover { color: var(--primary); }
        .nav-actions { display: flex; align-items: center; gap: 10px; }
        .nav-btn {
            padding: 8px 18px; border-radius: 8px; font-size: 13.5px;
            font-weight: 600; font-family: 'Sora', sans-serif;
            text-decoration: none; cursor: pointer;
            transition: all 0.15s; border: 1.5px solid;
        }
        .nav-btn-outline { background: transparent; color: var(--primary); border-color: var(--primary); }
        .nav-btn-outline:hover { background: var(--primary); color: #fff; }
        .nav-btn-primary { background: var(--primary); color: #fff; border-color: var(--primary); }
        .nav-btn-primary:hover { background: var(--primary-dark); }

        /* HERO */
        .hero {
            padding: 140px 32px 80px;
            max-width: 1200px; margin: 0 auto;
            display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: center;
        }
        .hero-tag {
            display: inline-flex; align-items: center; gap: 7px;
            background: #EFF6FF; border: 1px solid #BFDBFE;
            color: var(--primary); font-size: 12px; font-weight: 600;
            padding: 5px 12px; border-radius: 20px; margin-bottom: 20px;
            letter-spacing: 0.5px;
        }
        .hero-tag-dot { width: 7px; height: 7px; background: var(--accent); border-radius: 50%; animation: blink 1.5s ease-in-out infinite; }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.3} }
        .hero-title {
            font-size: 52px; font-weight: 800; color: var(--text);
            line-height: 1.1; letter-spacing: -1.5px; margin-bottom: 20px;
        }
        .hero-title .accent { color: var(--accent); }
        .hero-title .primary { color: var(--primary); }
        .hero-desc { font-size: 16px; color: var(--text-muted); line-height: 1.8; margin-bottom: 36px; }
        .hero-cta { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
        .btn-hero-primary {
            padding: 14px 28px; background: var(--primary); color: #fff;
            border: none; border-radius: 10px; font-size: 15px; font-weight: 600;
            font-family: 'Sora', sans-serif; cursor: pointer; text-decoration: none;
            display: inline-flex; align-items: center; gap: 8px;
            transition: background 0.15s, transform 0.15s;
        }
        .btn-hero-primary:hover { background: var(--primary-dark); transform: translateY(-1px); }
        .btn-hero-secondary {
            padding: 14px 28px; background: transparent; color: var(--text);
            border: 1.5px solid var(--border); border-radius: 10px;
            font-size: 15px; font-weight: 600; font-family: 'Sora', sans-serif;
            cursor: pointer; text-decoration: none;
            display: inline-flex; align-items: center; gap: 8px;
            transition: all 0.15s;
        }
        .btn-hero-secondary:hover { border-color: var(--primary); color: var(--primary); }
        .hero-note { font-size: 13px; color: var(--text-light); margin-top: 14px; }
        /* HERO VISUAL */
        .hero-visual {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 16px; overflow: hidden;
            box-shadow: 0 20px 60px rgba(30,58,138,0.1);
        }
        .hv-header {
            background: var(--primary); padding: 14px 20px;
            display: flex; align-items: center; gap: 8px;
        }
        .hv-dot { width: 10px; height: 10px; border-radius: 50%; }
        .hv-title { color: rgba(255,255,255,0.8); font-size: 13px; font-weight: 500; margin-left: 4px; }
        .hv-body { padding: 20px; }
        .hv-report {
            background: var(--bg); border: 1px solid var(--border);
            border-radius: 10px; padding: 14px 16px;
            margin-bottom: 12px; display: flex; gap: 12px; align-items: flex-start;
        }
        .hv-report-img { width: 56px; height: 56px; border-radius: 8px; background: #E2E8F0; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 24px; }
        .hv-report-info { flex: 1; }
        .hv-report-title { font-size: 13.5px; font-weight: 600; color: var(--text); margin-bottom: 4px; }
        .hv-report-loc { font-size: 11.5px; color: var(--text-light); margin-bottom: 6px; }
        .hv-badge { display: inline-flex; align-items: center; gap: 4px; font-size: 10.5px; font-weight: 600; padding: 2px 8px; border-radius: 12px; }
        .hv-upvote { display: flex; align-items: center; gap: 4px; font-size: 11px; color: var(--accent); font-weight: 600; }
        .hv-progress { margin-top: 8px; }
        .hv-prog-bar { height: 4px; background: var(--border); border-radius: 2px; overflow: hidden; }
        .hv-prog-fill { height: 100%; background: var(--success); border-radius: 2px; }

        /* STATS SECTION */
        .stats-section { background: var(--primary); padding: 64px 32px; }
        .stats-inner { max-width: 1200px; margin: 0 auto; }
        .stats-title { text-align: center; font-size: 13px; font-weight: 600; color: rgba(255,255,255,0.5); letter-spacing: 2px; text-transform: uppercase; margin-bottom: 40px; }
        .stats-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 1px; background: rgba(255,255,255,0.1); border-radius: 12px; overflow: hidden; }
        .stat-item { background: rgba(255,255,255,0.04); padding: 32px; text-align: center; }
        .stat-value { font-size: 40px; font-weight: 700; color: #fff; font-family: 'IBM Plex Mono', monospace; letter-spacing: -1px; }
        .stat-value span { font-size: 24px; color: var(--accent); }
        .stat-label { font-size: 13px; color: rgba(255,255,255,0.55); margin-top: 6px; }

        /* HOW IT WORKS */
        .section { padding: 80px 32px; max-width: 1200px; margin: 0 auto; }
        .section-header { text-align: center; margin-bottom: 56px; }
        .section-tag {
            display: inline-block; font-size: 11px; font-weight: 600;
            color: var(--accent); letter-spacing: 2px; text-transform: uppercase;
            background: #FFF7ED; padding: 4px 12px; border-radius: 20px; margin-bottom: 12px;
        }
        .section-title { font-size: 36px; font-weight: 700; color: var(--text); letter-spacing: -0.5px; margin-bottom: 12px; }
        .section-desc { font-size: 16px; color: var(--text-muted); max-width: 520px; margin: 0 auto; line-height: 1.7; }
        .steps-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 32px; position: relative; }
        .steps-grid::before {
            content: '';
            position: absolute; top: 36px; left: 10%; right: 10%; height: 2px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            opacity: 0.2;
        }
        .step { text-align: center; position: relative; }
        .step-num {
            width: 72px; height: 72px; border-radius: 50%;
            background: var(--surface); border: 2px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px; font-size: 28px; position: relative; z-index: 1;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .step:hover .step-num { border-color: var(--primary); box-shadow: 0 0 0 6px rgba(30,58,138,0.06); }
        .step-title { font-size: 15px; font-weight: 600; color: var(--text); margin-bottom: 8px; }
        .step-desc { font-size: 13.5px; color: var(--text-muted); line-height: 1.6; }
        .step-badge {
            position: absolute; top: -4px; right: calc(50% - 42px);
            width: 22px; height: 22px; background: var(--primary);
            border-radius: 50%; font-size: 11px; font-weight: 700; color: #fff;
            display: flex; align-items: center; justify-content: center;
        }

        /* RECENT REPORTS */
        .report-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 24px; }
        .report-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 12px; overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .report-card:hover { transform: translateY(-3px); box-shadow: 0 12px 32px rgba(30,58,138,0.1); }
        .rc-img {
            width: 100%; height: 160px; object-fit: cover;
            background: linear-gradient(135deg, #EFF6FF 0%, #BFDBFE 100%);
            display: flex; align-items: center; justify-content: center; font-size: 40px;
        }
        .rc-body { padding: 18px; }
        .rc-meta { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
        .rc-badge { display: inline-flex; align-items: center; gap: 4px; font-size: 10.5px; font-weight: 600; padding: 3px 9px; border-radius: 12px; }
        .rc-upvote { display: flex; align-items: center; gap: 4px; font-size: 12px; color: var(--accent); font-weight: 600; }
        .rc-title { font-size: 15px; font-weight: 600; color: var(--text); margin-bottom: 6px; }
        .rc-desc { font-size: 13px; color: var(--text-muted); line-height: 1.6; margin-bottom: 14px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .rc-footer { display: flex; align-items: center; justify-content: space-between; padding-top: 14px; border-top: 1px solid var(--border); }
        .rc-district { font-size: 12px; color: var(--text-light); display: flex; align-items: center; gap: 5px; }
        .rc-link { font-size: 13px; font-weight: 600; color: var(--primary); text-decoration: none; }
        .rc-link:hover { text-decoration: underline; }
        /* CTA SECTION */
        .cta-section {
            background: var(--primary); margin: 0; padding: 80px 32px;
        }
        .cta-inner { max-width: 700px; margin: 0 auto; text-align: center; }
        .cta-title { font-size: 40px; font-weight: 700; color: #fff; letter-spacing: -0.5px; margin-bottom: 16px; }
        .cta-desc { font-size: 16px; color: rgba(255,255,255,0.65); margin-bottom: 36px; line-height: 1.7; }
        .cta-btns { display: flex; align-items: center; justify-content: center; gap: 14px; flex-wrap: wrap; }
        .cta-btn-white {
            padding: 14px 28px; background: #fff; color: var(--primary);
            border: none; border-radius: 10px; font-size: 15px; font-weight: 600;
            font-family: 'Sora', sans-serif; cursor: pointer; text-decoration: none;
            transition: all 0.15s;
        }
        .cta-btn-white:hover { background: var(--bg); }
        .cta-btn-outline-white {
            padding: 14px 28px; background: transparent; color: rgba(255,255,255,0.85);
            border: 1.5px solid rgba(255,255,255,0.3); border-radius: 10px;
            font-size: 15px; font-weight: 600; font-family: 'Sora', sans-serif;
            cursor: pointer; text-decoration: none; transition: all 0.15s;
        }
        .cta-btn-outline-white:hover { border-color: #fff; color: #fff; }
        /* FOOTER */
        footer { background: #0F172A; padding: 40px 32px; }
        .footer-inner { max-width: 1200px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; }
        .footer-brand { font-size: 16px; font-weight: 700; color: #fff; }
        .footer-brand span { color: var(--accent); }
        .footer-copy { font-size: 13px; color: rgba(255,255,255,0.35); }
        .footer-links { display: flex; gap: 24px; }
        .footer-link { font-size: 13px; color: rgba(255,255,255,0.5); text-decoration: none; }
        .footer-link:hover { color: rgba(255,255,255,0.85); }

        /* Badges */
        .resolved-badge { background: #D1FAE5; color: #065F46; }
        .in-progress-badge { background: #FEE2E2; color: #991B1B; }
        .published-badge { background: #DBEAFE; color: #1E40AF; }
    </style>
</head>
<body>
    <!-- NAV -->
    <nav class="nav">
        <div class="nav-inner">
            <a href="{{ route('home') }}" class="nav-brand">
                <span class="nav-brand-text">CivilWatch</span>
            </a>
            <div class="nav-links">
                <a href="{{ route('feed') }}" class="nav-link">Public Feed</a>
                <a href="#cara-kerja" class="nav-link">Cara Kerja</a>
            </div>
            <div class="nav-actions">
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="nav-btn nav-btn-primary">Dashboard Admin</a>
                    @else
                        <a href="{{ route('citizen.dashboard') }}" class="nav-btn nav-btn-primary">Dashboard Saya</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="nav-btn nav-btn-outline">Masuk</a>
                    <a href="{{ route('register') }}" class="nav-btn nav-btn-primary">Daftar Gratis</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section>
        <div class="hero">
            <div class="hero-content">
                <div class="hero-tag">
                    <span class="hero-tag-dot"></span>
                    Platform Pengaduan Warga #1 Banjarmasin
                </div>
                <h1 class="hero-title">
                    Laporkan, <br><span class="accent">Kawal</span>, dan<br>
                    <span class="primary">Perbaiki</span> Kota
                </h1>
                <p class="hero-desc">
                    CivilWatch memudahkan warga melaporkan kerusakan infrastruktur publik secara transparan dan terukur. Setiap laporan langsung diteruskan kepada instansi berwenang.
                </p>
                <div class="hero-cta">
                    @auth
                        <a href="{{ route('citizen.reports.create') }}" class="btn-hero-primary">
                            ✍️ Buat Laporan Sekarang
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn-hero-primary">
                            ✍️ Mulai Lapor Sekarang
                        </a>
                    @endauth
                    <a href="{{ route('feed') }}" class="btn-hero-secondary">👀 Lihat Laporan Publik</a>
                </div>
                <p class="hero-note">✅ Gratis untuk semua warga &nbsp;•&nbsp; 🔒 Data aman terlindungi</p>
            </div>
        </div>
    </section>

    <!-- STATS -->
    <div class="stats-section">
        <div class="stats-inner">
            <div class="stats-title">Data Real-Time Platform CivilWatch</div>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-value">{{ number_format($totalReports) }}<span>+</span></div>
                    <div class="stat-label">Total Laporan Masuk</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ number_format($inProgressReports) }}<span></span></div>
                    <div class="stat-label">Sedang Ditangani</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ number_format($resolvedReports) }}<span></span></div>
                    <div class="stat-label">Berhasil Diselesaikan</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ number_format($totalUpvotes) }}<span></span></div>
                    <div class="stat-label">Total Dukungan Warga</div>
                </div>
            </div>
        </div>
    </div>

    <!-- HOW IT WORKS -->
    <section id="cara-kerja">
        <div class="section">
            <div class="section-header">
                <div class="section-tag">Cara Kerja</div>
                <div class="section-title">4 Langkah Mudah Melapor</div>
                <div class="section-desc">Dari laporan hingga penyelesaian, semua terpantau secara transparan melalui platform kami.</div>
            </div>
            <div class="steps-grid">
                <div class="step">
                    <div class="step-num"><span class="step-badge">1</span>✍️</div>
                    <div class="step-title">Tulis Laporan</div>
                    <div class="step-desc">Isi judul, deskripsi kronologis, lokasi, dan upload foto bukti kerusakan dari handphone Anda.</div>
                </div>
                <div class="step">
                    <div class="step-num"><span class="step-badge">2</span>🔍</div>
                    <div class="step-title">Verifikasi Admin</div>
                    <div class="step-desc">Tim kami memverifikasi laporan dalam 3 hari dan meneruskan kepada instansi yang berwenang.</div>
                </div>
                <div class="step">
                    <div class="step-num"><span class="step-badge">3</span>⚙️</div>
                    <div class="step-title">Penanganan</div>
                    <div class="step-desc">Instansi menindaklanjuti dan memberikan update progres dalam 5 hari kerja. Bisa Anda pantau real-time.</div>
                </div>
                <div class="step">
                    <div class="step-num"><span class="step-badge">4</span>✅</div>
                    <div class="step-title">Selesai</div>
                    <div class="step-desc">Masalah terselesaikan dan laporan ditutup. Anda mendapat notifikasi penyelesaian.</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <div class="cta-section">
        <div class="cta-inner">
            <div class="cta-title">Siap Membuat Kota Lebih Baik?</div>
            <div class="cta-desc">Bergabunglah bersama ribuan warga Banjarmasin yang telah aktif melaporkan dan memperbaiki infrastruktur kota.</div>
            <div class="cta-btns">
                @auth
                    <a href="{{ route('citizen.reports.create') }}" class="cta-btn-white">✍️ Buat Laporan</a>
                    <a href="{{ route('feed') }}" class="cta-btn-outline-white">📋 Lihat Public Feed</a>
                @else
                    <a href="{{ route('register') }}" class="cta-btn-white">🚀 Daftar Gratis Sekarang</a>
                    <a href="{{ route('login') }}" class="cta-btn-outline-white">Sudah punya akun? Masuk</a>
                @endauth
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer>
        <div class="footer-inner">
            <div class="footer-brand">Civil<span>Watch</span></div>

            <div class="footer-copy">© {{ date('Y') }} CivilWatch. Kota Banjarmasin.</div>
        </div>
    </footer>
</body>
</html>
@props(['active' => '', 'mode' => 'default'])

<nav class="pub-nav">
    <div class="pub-nav-inner" style="{{ $mode === 'detail' ? 'max-width:1100px;' : '' }}">
        <a href="{{ route('home') }}" class="pub-brand">
            <span class="pub-brand-text">CivilWatch</span>
        </a>

        @if($mode === 'detail')
            <a href="{{ route('feed') }}" class="pub-nav-link" style="display:inline-flex;align-items:center;gap:6px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Kembali ke Laporan Publik
            </a>
        @else
            <div class="pub-nav-links">
                <a href="{{ route('home') }}" class="pub-nav-link {{ $active === 'home' ? 'active' : '' }}">Beranda</a>
                <a href="{{ route('home') }}#cara-kerja" class="pub-nav-link">Cara Kerja</a>
                <a href="{{ route('feed') }}" class="pub-nav-link {{ $active === 'feed' ? 'active' : '' }}">Laporan Publik</a>
            </div>
            <div class="pub-nav-auth">
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="pub-nav-btn pub-nav-btn-outline">Dashboard</a>
                    @else
                        <a href="{{ route('citizen.dashboard') }}" class="pub-nav-btn pub-nav-btn-outline">Dashboard</a>
                        <a href="{{ route('citizen.reports.create') }}" class="pub-nav-btn pub-nav-btn-primary">Buat Laporan</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="pub-nav-btn pub-nav-btn-outline">Masuk</a>
                    <a href="{{ route('register') }}" class="pub-nav-btn pub-nav-btn-primary">Daftar</a>
                @endauth
            </div>
        @endif
    </div>
</nav>

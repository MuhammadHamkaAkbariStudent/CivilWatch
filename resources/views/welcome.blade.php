<x-app-layout>
    <x-slot name="title">Beranda</x-slot>

    {{-- HERO SECTION --}}
    <section class="bg-[--cw-navy] text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                <defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="1" fill="white"/></pattern></defs>
                <rect width="100%" height="100%" fill="url(#dots)"/>
            </svg>
        </div>
        <div class="absolute top-0 right-0 w-1/3 h-full bg-gradient-to-l from-blue-900/30 to-transparent"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full px-4 py-2 text-sm font-medium text-orange-300 mb-8">
                    <span class="w-2 h-2 bg-orange-400 rounded-full animate-pulse"></span>
                    Platform Pengaduan Infrastruktur Kota Banjarmasin
                </div>
                <h1 class="text-5xl lg:text-6xl font-bold leading-tight mb-6" style="font-family: 'Instrument Serif', serif;">
                    Laporkan. Pantau.<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-yellow-400">Selesaikan.</span>
                </h1>
                <p class="text-lg text-slate-300 leading-relaxed mb-10 max-w-2xl">
                    CivilWatch menjembatani komunikasi antara warga dan instansi pemerintah. Laporkan kerusakan fasilitas publik, pantau perkembangannya, dan berikan dukungan untuk masalah yang mendesak.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    @guest
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-[--cw-accent] text-white rounded-xl font-semibold text-base hover:bg-orange-500 transition-all shadow-lg shadow-orange-500/25 hover:shadow-orange-500/40 hover:-translate-y-0.5">
                            Mulai Melapor
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    @else
                        <a href="{{ route('citizen.reports.create') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-[--cw-accent] text-white rounded-xl font-semibold text-base hover:bg-orange-500 transition-all shadow-lg shadow-orange-500/25 hover:shadow-orange-500/40 hover:-translate-y-0.5">
                            Buat Laporan Baru
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    @endauth
                    <a href="{{ route('feed') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white/10 border border-white/20 text-white rounded-xl font-semibold text-base hover:bg-white/20 transition-all backdrop-blur-sm">
                        Lihat Laporan Publik
                    </a>
                </div>
            </div>
        </div>

        {{-- Wave separator --}}
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 80L1440 80L1440 0C1440 0 1080 80 720 40C360 0 0 60 0 60V80Z" fill="#f8fafc"/>
            </svg>
        </div>
    </section>

    {{-- STATISTICS --}}
    <section class="py-16 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $stats = [
                        ['value' => '350+', 'label' => 'Total Laporan', 'color' => 'text-blue-600', 'bg' => 'bg-blue-50', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                        ['value' => '82%', 'label' => 'Terselesaikan', 'color' => 'text-emerald-600', 'bg' => 'bg-emerald-50', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['value' => '5', 'label' => 'Kecamatan', 'color' => 'text-violet-600', 'bg' => 'bg-violet-50', 'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z'],
                        ['value' => '1.2K+', 'label' => 'Warga Aktif', 'color' => 'text-orange-600', 'bg' => 'bg-orange-50', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                    ];
                @endphp
                @foreach($stats as $stat)
                    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between mb-4">
                            <div class="{{ $stat['bg'] }} p-3 rounded-xl">
                                <svg class="w-6 h-6 {{ $stat['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-slate-800">{{ $stat['value'] }}</p>
                        <p class="text-sm text-slate-500 mt-1">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- HOW IT WORKS --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <p class="text-[--cw-accent] font-semibold text-sm uppercase tracking-widest mb-3">Cara Kerja</p>
                <h2 class="text-4xl font-bold text-[--cw-navy]" style="font-family: 'Instrument Serif', serif;">Tiga Langkah Sederhana</h2>
                <p class="text-slate-500 mt-4 max-w-xl mx-auto">Dari laporan hingga penyelesaian, seluruh proses bisa dipantau secara transparan.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                <div class="hidden md:block absolute top-16 left-1/3 right-1/3 h-0.5 bg-gradient-to-r from-blue-200 via-blue-300 to-blue-200"></div>
                @php
                    $steps = [
                        ['num' => '01', 'title' => 'Buat Laporan', 'desc' => 'Foto masalah infrastruktur, tulis deskripsi singkat, dan pilih lokasi kecamatan. Hanya dalam hitungan menit.', 'color' => 'bg-[--cw-blue]'],
                        ['num' => '02', 'title' => 'Verifikasi & Dukungan', 'desc' => 'Tim admin memverifikasi laporan Anda. Warga lain bisa memberikan upvote untuk meningkatkan prioritas penanganan.', 'color' => 'bg-[--cw-accent]'],
                        ['num' => '03', 'title' => 'Pantau & Selesai', 'desc' => 'Ikuti perkembangan penanganan secara real-time melalui linimasa progress yang diperbarui oleh petugas lapangan.', 'color' => 'bg-emerald-500'],
                    ];
                @endphp
                @foreach($steps as $step)
                    <div class="text-center relative z-10">
                        <div class="w-16 h-16 {{ $step['color'] }} text-white rounded-2xl flex items-center justify-center font-bold text-xl mx-auto mb-6 shadow-lg">
                            {{ $step['num'] }}
                        </div>
                        <h3 class="text-xl font-bold text-[--cw-navy] mb-3">{{ $step['title'] }}</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA SECTION --}}
    <section class="py-20 bg-slate-50">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <div class="bg-gradient-to-br from-[--cw-navy] to-blue-900 rounded-3xl p-12 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-[--cw-accent]/20 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl"></div>
                <div class="relative z-10">
                    <h2 class="text-3xl lg:text-4xl font-bold mb-4" style="font-family: 'Instrument Serif', serif;">Jadilah Bagian dari Perubahan</h2>
                    <p class="text-slate-300 mb-8 max-w-xl mx-auto">Bergabunglah dengan ratusan warga yang sudah aktif melaporkan dan mendukung perbaikan infrastruktur kota.</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        @guest
                            <a href="{{ route('register') }}" class="px-8 py-4 bg-[--cw-accent] text-white rounded-xl font-semibold hover:bg-orange-500 transition-all">Daftar Sekarang — Gratis</a>
                            <a href="{{ route('feed') }}" class="px-8 py-4 bg-white/10 border border-white/20 text-white rounded-xl font-semibold hover:bg-white/20 transition-all">Lihat Laporan Publik</a>
                        @else
                            <a href="{{ route('citizen.reports.create') }}" class="px-8 py-4 bg-[--cw-accent] text-white rounded-xl font-semibold hover:bg-orange-500 transition-all">Buat Laporan Baru</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
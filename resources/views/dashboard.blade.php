<x-app-layout>
    <x-slot name="title">Dashboard Saya</x-slot>

    <div class="bg-[--cw-navy] text-white py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-[--cw-accent] rounded-2xl flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-slate-300 text-sm">Selamat datang kembali,</p>
                        <h1 class="text-2xl font-bold" style="font-family: 'Instrument Serif', serif;">{{ Auth::user()->name }}</h1>
                    </div>
                </div>
                <a href="{{ route('citizen.reports.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-[--cw-accent] text-white rounded-xl text-sm font-semibold hover:bg-orange-500 transition-all shadow-lg shadow-orange-500/25">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Buat Laporan Baru
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-10">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="bg-slate-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-slate-800">{{ $myTotal }}</p>
                <p class="text-sm text-slate-500 mt-1">Total Laporan Saya</p>
            </div>

            <div class="bg-white rounded-2xl border border-amber-100 shadow-sm p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="bg-amber-50 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-slate-800">{{ $myPending }}</p>
                <p class="text-sm text-slate-500 mt-1">Menunggu Verifikasi</p>
            </div>

            <div class="bg-white rounded-2xl border border-emerald-100 shadow-sm p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="bg-emerald-50 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-slate-800">{{ $myResolved }}</p>
                <p class="text-sm text-slate-500 mt-1">Berhasil Diselesaikan</p>
            </div>
        </div>

        {{-- Recent Reports --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-[--cw-navy]">Laporan Saya</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Semua laporan yang pernah Anda buat</p>
                </div>
                <a href="{{ route('citizen.reports.index') }}" class="text-sm text-[--cw-blue] font-semibold hover:text-blue-700 transition">Lihat Semua →</a>
            </div>

            @php
                $recentReports = auth()->user()->reports()->with('district')->latest()->take(5)->get();
                $statusConfig = [
                    'pending'     => ['label' => 'Menunggu', 'class' => 'bg-slate-100 text-slate-600'],
                    'published'   => ['label' => 'Terverifikasi', 'class' => 'bg-blue-100 text-blue-700'],
                    'in_progress' => ['label' => 'Ditangani', 'class' => 'bg-amber-100 text-amber-700'],
                    'resolved'    => ['label' => 'Selesai', 'class' => 'bg-emerald-100 text-emerald-700'],
                    'rejected'    => ['label' => 'Ditolak', 'class' => 'bg-red-100 text-red-600'],
                ];
            @endphp

            @if($recentReports->isEmpty())
                <div class="text-center py-16">
                    <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <p class="font-semibold text-slate-600 mb-1">Belum ada laporan</p>
                    <p class="text-xs text-slate-400 mb-4">Mulai berkontribusi dengan membuat laporan pertama Anda</p>
                    <a href="{{ route('citizen.reports.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[--cw-navy] text-white rounded-xl text-sm font-semibold hover:bg-blue-900 transition-all">
                        Buat Laporan Pertama
                    </a>
                </div>
            @else
                <div class="divide-y divide-slate-50">
                    @foreach($recentReports as $report)
                        @php $sc = $statusConfig[$report->status] ?? ['label' => $report->status, 'class' => 'bg-slate-100 text-slate-600']; @endphp
                        <div class="px-6 py-4 flex items-center gap-4 hover:bg-slate-50/50 transition-colors">
                            <div class="w-12 h-12 bg-slate-100 rounded-xl overflow-hidden shrink-0">
                                @if($report->image_url)
                                    <img src="{{ $report->image_url }}" alt="" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-800 truncate">{{ $report->title }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">{{ $report->district->name }} · {{ $report->created_at->format('d M Y') }}</p>
                            </div>
                            <div class="flex items-center gap-3 shrink-0">
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $sc['class'] }}">{{ $sc['label'] }}</span>
                                @if($report->isEditable())
                                    <a href="{{ route('citizen.reports.edit', $report->id) }}" class="text-slate-400 hover:text-[--cw-blue] transition-colors p-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                @endif
                                <a href="{{ route('reports.show', $report->id) }}" class="text-slate-400 hover:text-[--cw-blue] transition-colors p-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
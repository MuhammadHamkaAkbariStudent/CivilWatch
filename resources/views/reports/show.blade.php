<x-app-layout>
    <x-slot name="title">{{ $report->title }}</x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <nav class="flex items-center gap-2 text-sm text-slate-500 mb-8">
            <a href="{{ route('feed') }}" class="hover:text-[--cw-blue] transition">Laporan Publik</a>
            <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-slate-700 font-medium truncate max-w-[200px]">{{ $report->title }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-6">
                @if($report->image)
                    <div class="rounded-2xl overflow-hidden border border-slate-200 shadow-sm">
                        <img src="{{ asset('storage/' . $report->image) }}" alt="{{ $report->title }}" class="w-full object-cover max-h-96">
                    </div>
                @else
                    <div class="rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 h-64 flex items-center justify-center border border-slate-200">
                        <div class="text-center">
                            <svg class="w-12 h-12 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-xs text-slate-400">Tidak ada foto</p>
                        </div>
                    </div>
                @endif

                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    @php
                        $statusConfig = [
                            'published'   => ['label' => 'Terverifikasi', 'class' => 'bg-blue-100 text-blue-700', 'dot' => 'bg-blue-500'],
                            'in_progress' => ['label' => 'Sedang Ditangani', 'class' => 'bg-amber-100 text-amber-700', 'dot' => 'bg-amber-500'],
                            'resolved'    => ['label' => 'Telah Diselesaikan', 'class' => 'bg-emerald-100 text-emerald-700', 'dot' => 'bg-emerald-500'],
                        ];
                        $sc = $statusConfig[$report->status] ?? ['label' => $report->status, 'class' => 'bg-slate-100 text-slate-700', 'dot' => 'bg-slate-400'];
                    @endphp

                    <h1 class="text-2xl font-bold text-[--cw-navy] mb-4">{{ $report->title }}</h1>
                    <p class="text-slate-600 leading-relaxed text-sm">{{ $report->description }}</p>

                    <div class="flex items-center gap-3 mt-6 pt-5 border-t border-slate-100">
                        <div class="w-10 h-10 bg-[--cw-navy] rounded-full flex items-center justify-center text-white font-bold text-sm">
                            {{ strtoupper(substr($report->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-700">{{ $report->user->name }}</p>
                            <p class="text-xs text-slate-400">Pelapor · {{ $report->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>

                @if($report->progressUpdates->isNotEmpty())
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h2 class="font-bold text-[--cw-navy] text-lg mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[--cw-blue]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            Linimasa Penanganan
                        </h2>
                        <div class="relative space-y-6">
                            <div class="absolute left-4 top-4 bottom-0 w-0.5 bg-slate-100"></div>

                            @foreach($report->progressUpdates as $index => $update)
                                <div class="relative flex gap-4 pl-12">
                                    <div class="absolute left-0 w-8 h-8 rounded-full flex items-center justify-center {{ $index === $report->progressUpdates->count() - 1 ? 'bg-[--cw-blue] text-white' : 'bg-slate-100 text-slate-400' }} z-10">
                                        @if($index === $report->progressUpdates->count() - 1)
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        @else
                                            <span class="text-xs font-bold">{{ $index + 1 }}</span>
                                        @endif
                                    </div>
                                    <div class="bg-slate-50 rounded-xl p-4 flex-1">
                                        <p class="text-sm text-slate-700 leading-relaxed">{{ $update->note }}</p>
                                        <p class="text-xs text-slate-400 mt-2">{{ $update->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h2 class="font-bold text-[--cw-navy] text-lg mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[--cw-blue]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            Linimasa Penanganan
                        </h2>
                        <div class="text-center py-8">
                            <svg class="w-10 h-10 text-slate-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-sm text-slate-400">Belum ada pembaruan dari petugas</p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 text-center">
                    <p class="text-5xl font-bold text-[--cw-navy]">{{ $report->upvote_count }}</p>
                    <p class="text-sm text-slate-500 mt-1 mb-5">Warga mendukung laporan ini</p>

                    @auth
                        @php $hasUpvoted = auth()->user()->upvotedReports->contains($report->id); @endphp
                        <form method="POST" action="{{ route('reports.upvote', $report->id) }}">
                            @csrf
                            <button type="submit" class="w-full py-3 rounded-xl text-sm font-semibold transition-all flex items-center justify-center gap-2 {{ $hasUpvoted ? 'bg-rose-100 text-rose-600 hover:bg-rose-200' : 'bg-rose-500 text-white hover:bg-rose-600 shadow-lg shadow-rose-500/25' }}">
                                <svg class="w-5 h-5" fill="{{ $hasUpvoted ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                {{ $hasUpvoted ? 'Batalkan Dukungan' : 'Dukung Laporan Ini' }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block w-full py-3 bg-slate-100 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-200 transition-all">
                            Masuk untuk Mendukung
                        </a>
                    @endauth
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-4">
                    <h3 class="font-bold text-slate-700 text-sm">Informasi Laporan</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between gap-2">
                            <span class="text-slate-400">Kecamatan</span>
                            <span class="font-medium text-slate-700 text-right">{{ $report->district->name }}</span>
                        </div>
                        <div class="flex justify-between gap-2">
                            <span class="text-slate-400">Status</span>
                            <span class="font-semibold {{ str_contains($sc['class'], 'emerald') ? 'text-emerald-600' : (str_contains($sc['class'], 'amber') ? 'text-amber-600' : 'text-blue-600') }}">{{ $sc['label'] }}</span>
                        </div>
                        <div class="flex justify-between gap-2">
                            <span class="text-slate-400">Dilaporkan</span>
                            <span class="font-medium text-slate-700">{{ $report->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between gap-2">
                            <span class="text-slate-400">Progress</span>
                            <span class="font-medium text-slate-700">{{ $report->progressUpdates->count() }} catatan</span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('feed') }}" class="bg-[--cw-navy] flex items-center justify-center gap-2 w-full py-3 border border-slate-200 text-white text-slate-600 rounded-xl text-sm font-medium hover:bg-slate-50 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
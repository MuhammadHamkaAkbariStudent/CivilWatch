<x-app-layout>
    <x-slot name="title">Laporan Publik</x-slot>

    <div class="bg-[--cw-navy] text-white py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-bold" >Laporan Infrastruktur Publik</h1>
                    <p class="text-slate-300 text-sm mt-1">Pantau dan dukung penanganan masalah di seluruh kecamatan</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="bg-white/10 border border-white/20 text-sm px-3 py-1.5 rounded-full text-slate-300">
                        {{ $reports->total() }} laporan ditemukan
                    </span>
                    @auth
                        @if(Auth::user()->isCitizen())
                            <a href="{{ route('citizen.reports.create') }}" class="px-5 py-2.5 bg-[--cw-accent] text-white rounded-xl text-sm font-semibold hover:bg-orange-500 transition-all">
                                + Buat Laporan
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div x-data="{ search: '{{ request('search') }}', district: '{{ request('district_id') }}' }" class="mb-8">
            <form method="GET" action="{{ route('feed') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input
                        type="text" name="search"
                        x-model="search"
                        placeholder="Cari laporan berdasarkan judul..."
                        class="w-full pl-11 pr-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[--cw-blue] focus:border-transparent bg-white transition-all"
                        value="{{ request('search') }}"
                    >
                </div>
                <div class="relative">
                    <select name="district_id" class="pl-4 pr-10 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[--cw-blue] focus:border-transparent bg-white appearance-none min-w-[180px] transition-all text-slate-700">
                        <option value="">Kecamatan</option>
                        @foreach($districts as $district)
                            <option value="{{ $district->id }}" {{ request('district_id') == $district->id ? 'selected' : '' }}>
                                {{ $district->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
                <button type="submit" class="px-6 py-3 bg-[--cw-navy] text-white rounded-xl text-sm font-semibold hover:bg-blue-900 transition-all">
                    Filter
                </button>
                @if(request('search') || request('district_id'))
                    <a href="{{ route('feed') }}" class="px-4 py-3 border border-slate-200 text-slate-600 rounded-xl text-sm font-medium hover:bg-slate-50 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Reset
                    </a>
                @endif
            </form>
        </div>

        @if($reports->isEmpty())
            <div class="text-center py-20">
                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-700">Tidak ada laporan ditemukan</h3>
                <p class="text-sm text-slate-400 mt-1">Coba ubah kata kunci atau filter kecamatan</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($reports as $report)
                    @php
                        $statusConfig = [
                            'published'   => ['label' => 'Terverifikasi', 'class' => 'bg-blue-100 text-blue-700', 'dot' => 'bg-blue-500'],
                            'in_progress' => ['label' => 'Ditangani', 'class' => 'bg-amber-100 text-amber-700', 'dot' => 'bg-amber-500'],
                            'resolved'    => ['label' => 'Selesai', 'class' => 'bg-emerald-100 text-emerald-700', 'dot' => 'bg-emerald-500'],
                        ];
                        $sc = $statusConfig[$report->status] ?? ['label' => $report->status, 'class' => 'bg-slate-100 text-slate-700', 'dot' => 'bg-slate-400'];
                    @endphp
                    <a href="{{ route('reports.show', $report->id) }}" class="group bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5 overflow-hidden flex flex-col">
                        <div class="h-44 bg-slate-100 overflow-hidden">
                            @if($report->image)
                                <img src="{{ asset('storage/' . $report->image) }}" alt="{{ $report->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200">
                                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </div>

                        <div class="p-5 flex flex-col flex-1">
                            <div class="flex items-start justify-between gap-2 mb-3">
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full {{ $sc['class'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $sc['dot'] }}"></span>
                                    {{ $sc['label'] }}
                                </span>
                                <span class="text-xs text-slate-400 shrink-0">{{ $report->district->name }}</span>
                            </div>

                            <h3 class="font-bold text-slate-800 text-sm leading-snug mb-2 group-hover:text-[--cw-blue] transition-colors line-clamp-2">
                                {{ $report->title }}
                            </h3>
                            <p class="text-xs text-slate-500 leading-relaxed line-clamp-2 flex-1">{{ $report->description }}</p>

                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-slate-100">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-[--cw-navy] rounded-full flex items-center justify-center text-white text-xs font-bold">
                                        {{ strtoupper(substr($report->user->name, 0, 1)) }}
                                    </div>
                                    <span class="text-xs text-slate-500 truncate max-w-[100px]">{{ $report->user->name }}</span>
                                </div>
                                <div class="flex items-center gap-3 text-xs text-slate-400">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4 text-rose-400" fill="currentColor" viewBox="0 0 20 20"><path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/></svg>
                                        {{ $report->upvote_count }}
                                    </span>
                                    <span>{{ $report->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $reports->withQueryString()->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
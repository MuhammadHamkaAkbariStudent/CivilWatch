<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="breadcrumb">
        <span class="breadcrumb-current">Dashboard Saya</span>
    </x-slot>

    <div class="page-header" style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;">
        <div>
            <div class="page-title" style="display:flex;align-items:center;gap:8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Halo, {{ Auth::user()->name }}!
            </div>
            <div class="page-desc">Pantau dan kelola semua laporan pengaduan yang pernah Anda buat</div>
        </div>
        <a href="{{ route('citizen.reports.create') }}" class="btn btn-accent" style="flex-shrink:0;display:inline-flex;align-items:center;gap:7px;">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round"/></svg>
            Buat Laporan Baru
        </a>
    </div>

    <!-- PERSONAL MINI MATRIX -->
    <div class="stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:28px;">
        <div class="stat-card s-blue">
            <div class="stat-icon s-blue">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="2" width="6" height="4" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="16" x2="15" y2="16"/></svg>
            </div>
            <div class="stat-label">Total Laporanku</div>
            <div class="stat-value">{{ $myTotal }}</div>
            <div class="stat-sub">Semua laporan yang pernah dibuat</div>
        </div>
        <div class="stat-card s-green">
            <div class="stat-icon s-green">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <div class="stat-label">Laporan Diverifikasi</div>
            <div class="stat-value">{{ $myVerified }}</div>
            <div class="stat-sub">Sudah tayang di public feed</div>
        </div>
        <div class="stat-card s-orange">
            <div class="stat-icon s-orange">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3H14z"/><path d="M7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/></svg>
            </div>
            <div class="stat-label">Upvote Saya Berikan</div>
            <div class="stat-value">{{ $myUpvotesGiven }}</div>
            <div class="stat-sub">Total dukungan ke laporan lain</div>
        </div>
    </div>

    <!-- REPORT TABLE -->
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title" style="display:flex;align-items:center;gap:6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                    Riwayat Laporan Saya
                </div>
            </div>
        </div>

        @if($reports->count() > 0)
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:40%">Judul Laporan</th>
                        <th>Wilayah</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th style="text-align:center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $report)
                    <tr>
                        <td>
                            <div style="font-weight:600;color:var(--text);margin-bottom:3px;">{{ Str::limit($report->title, 55) }}</div>
                            <div style="font-size:12px;color:var(--text-light);">{{ Str::limit($report->description, 60) }}</div>
                        </td>
                        <td>
                            <span style="display:inline-flex;align-items:center;gap:4px;font-size:13px;color:var(--text-muted);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                {{ $report->district->name ?? '-' }}
                            </span>
                        </td>
                        <td>
                            @php
                                $badges = [
                                    'pending'     => ['label'=>'Menunggu',  'class'=>'badge-pending'],
                                    'published'   => ['label'=>'Diterima',  'class'=>'badge-published'],
                                    'in_progress' => ['label'=>'Diproses',  'class'=>'badge-in-progress'],
                                    'resolved'    => ['label'=>'Selesai',   'class'=>'badge-resolved'],
                                    'rejected'    => ['label'=>'Ditolak',   'class'=>'badge-rejected'],
                                ];
                                $b = $badges[$report->status] ?? ['label'=>$report->status,'class'=>'badge-rejected'];
                            @endphp
                            <span class="badge {{ $b['class'] }}">
                                <span class="badge-dot" style="background:currentColor;opacity:.5"></span>
                                {{ $b['label'] }}
                            </span>
                        </td>
                        <td>
                            <span style="font-size:13px;font-family:'IBM Plex Mono',monospace;color:var(--text-muted);">
                                {{ $report->created_at->translatedFormat('d M Y') }}
                            </span>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;justify-content:center;gap:6px;">
                                @if(in_array($report->status, ['published','in_progress','resolved']))
                                <a href="{{ route('reports.show', $report->id) }}" class="btn btn-outline btn-sm btn-icon" title="Lihat Detail Publik">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="1.5"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-width="1.5"/></svg>
                                </a>
                                @endif

                                @if($report->isEditable())
                                <a href="{{ route('citizen.reports.edit', $report->id) }}" class="btn btn-outline btn-sm btn-icon" title="Edit Laporan">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </a>
                                <div x-data="{ confirmDelete: false }" style="display:inline-block;">
                                    <button type="button" @click="confirmDelete = true" class="btn btn-danger btn-sm btn-icon" title="Hapus Laporan">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>

                                    <div x-show="confirmDelete"
                                        style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background-color:rgba(0,0,0,0.5);z-index:9999;"
                                        x-transition.opacity
                                        @keydown.escape.window="confirmDelete = false">

                                        <div @click.away="confirmDelete = false"
                                            style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);background:#FFF;width:90%;max-width:400px;padding:24px;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,0.2);box-sizing:border-box;text-align:left;">

                                            <div style="font-size:18px;font-weight:bold;color:#DC2626;margin-bottom:8px;display:flex;align-items:center;gap:8px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                                Hapus Laporan
                                            </div>

                                            <div style="font-size:14px;color:#475569;margin-bottom:24px;line-height:1.5;white-space:normal;">
                                                Apakah Anda yakin ingin menghapus laporan ini secara permanen? Tindakan ini tidak dapat dibatalkan.
                                            </div>

                                            <form method="POST" action="{{ route('citizen.reports.destroy', $report->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <div style="display:flex;justify-content:center;gap:10px;">
                                                    <button type="button" @click="confirmDelete = false" class="btn btn-outline" style="margin:0;">Batal</button>
                                                    <button type="submit" class="btn btn-danger" style="margin:0;display:inline-flex;align-items:center;gap:6px;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                                        Hapus
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <span style="display:inline-flex;align-items:center;gap:4px;font-size:12px;color:var(--text-light);font-style:italic;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                    Terkunci
                                </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($reports->hasPages())
        <div style="padding:16px 24px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
            <span style="font-size:13px;color:var(--text-muted);">
                Menampilkan {{ $reports->firstItem() }}–{{ $reports->lastItem() }} dari {{ $reports->total() }} laporan
            </span>
            <div style="display:flex;gap:4px;">
                {{ $reports->links('vendor.pagination.simple-tailwind') }}
            </div>
        </div>
        @endif

        @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
            </div>
            <div class="empty-state-title">Belum Ada Laporan</div>
            <div class="empty-state-desc">Anda belum pernah membuat laporan pengaduan. Mulai buat laporan pertama Anda sekarang!</div>
            <a href="{{ route('citizen.reports.create') }}" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:7px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Buat Laporan Pertama
            </a>
        </div>
        @endif
    </div>
</x-app-layout>
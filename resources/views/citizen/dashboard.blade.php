<x-app-layout>
    <x-slot name="title">Dashboard Saya</x-slot>
    <x-slot name="breadcrumb">
        <span class="breadcrumb-current">Dashboard Warga</span>
    </x-slot>

    <div class="page-header" style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;">
        <div>
            <div class="page-title">Halo, {{ Auth::user()->name }}! 👋</div>
            <div class="page-desc">Pantau dan kelola semua laporan pengaduan yang pernah Anda buat</div>
        </div>
        <a href="{{ route('citizen.reports.create') }}" class="btn btn-accent" style="flex-shrink:0;">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round"/></svg>
            Buat Laporan Baru
        </a>
    </div>

    <!-- PERSONAL MINI MATRIX -->
    <div class="stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:28px;">
        <div class="stat-card s-blue">
            <div class="stat-icon s-blue">📋</div>
            <div class="stat-label">Total Laporanku</div>
            <div class="stat-value">{{ $myTotal }}</div>
            <div class="stat-sub">Semua laporan yang pernah dibuat</div>
        </div>
        <div class="stat-card s-green">
            <div class="stat-icon s-green">✅</div>
            <div class="stat-label">Laporan Diverifikasi</div>
            <div class="stat-value">{{ $myVerified }}</div>
            <div class="stat-sub">Sudah tayang di public feed</div>
        </div>
        <div class="stat-card s-orange">
            <div class="stat-icon s-orange">👍</div>
            <div class="stat-label">Upvote Saya Berikan</div>
            <div class="stat-value">{{ $myUpvotesGiven }}</div>
            <div class="stat-sub">Total dukungan ke laporan lain</div>
        </div>
    </div>

    <!-- REPORT TABLE -->
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Riwayat Laporan Saya</div>
                <div style="font-size:13px;color:var(--text-muted);margin-top:2px;">Semua laporan termasuk yang masih pending atau ditolak</div>
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
                                {{ $report->created_at->format('d M Y') }}
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
                                <form method="POST" action="{{ route('citizen.reports.destroy', $report->id) }}" onsubmit="return confirm('Hapus laporan ini secara permanen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Hapus Laporan">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </button>
                                </form>
                                @else
                                <span style="font-size:12px;color:var(--text-light);font-style:italic;">Terkunci</span>
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
            <div class="empty-state-icon">📝</div>
            <div class="empty-state-title">Belum Ada Laporan</div>
            <div class="empty-state-desc">Anda belum pernah membuat laporan pengaduan. Mulai buat laporan pertama Anda sekarang!</div>
            <a href="{{ route('citizen.reports.create') }}" class="btn btn-primary">✍️ Buat Laporan Pertama</a>
        </div>
        @endif
    </div>
</x-app-layout>
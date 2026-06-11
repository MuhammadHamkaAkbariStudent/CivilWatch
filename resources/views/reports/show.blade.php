<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CivilWatch — {{ $report->title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-grid">

{{-- ═══ NAV ═══ --}}
<nav class="pub-nav">
    <div class="pub-nav-inner" style="max-width:1100px;">
        <a href="{{ route('home') }}" class="pub-brand">
            <span class="pub-brand-text">CivilWatch</span>
        </a>
        <a href="{{ route('feed') }}" class="pub-nav-link" style="display:inline-flex;align-items:center;gap:6px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Kembali ke Feed
        </a>
    </div>
</nav>

@php
    $statusMap = [
        'published'   => ['label' => 'Diterima',       'class' => 'badge-published'],
        'in_progress' => ['label' => 'Sedang Diproses', 'class' => 'badge-in-progress'],
        'resolved'    => ['label' => 'Selesai',         'class' => 'badge-resolved'],
        'pending'     => ['label' => 'Menunggu',        'class' => 'badge-pending'],
    ];
    $s = $statusMap[$report->status] ?? ['label' => $report->status, 'class' => 'badge-published'];

    $statusIcons = [
        'published'   => '<svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>',
        'in_progress' => '<svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>',
        'resolved'    => '<svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
        'pending'     => '<svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
    ];
    $si = $statusIcons[$report->status] ?? '<svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/></svg>';
@endphp

{{-- ═══ DETAIL LAYOUT ═══ --}}
<div style="max-width:1100px;margin:0 auto;padding:32px;display:grid;grid-template-columns:1fr 340px;gap:32px;align-items:start;">

    {{-- ── LEFT: Report Main ── --}}
    <div style="display:flex;flex-direction:column;gap:20px;">

        {{-- Image --}}
        @if($report->image)
        <div x-data="{ showModal: false }">
            <img
                src="{{ asset('storage/'.$report->image) }}"
                alt="{{ $report->title }}"
                @click="showModal = true"
                style="width:100%;max-height:420px;border-radius:12px;border:1px solid var(--border);cursor:zoom-in;"
                title="Klik untuk memperbesar"
            >
            {{-- Lightbox --}}
            <div
                x-show="showModal"
                x-cloak
                x-transition.opacity
                @click="showModal = false"
                @keydown.escape.window="showModal = false"
                class="lightbox-overlay"
                style="position: fixed; inset: 0; background: rgba(0,0,0,.85); z-index: 9999; display: grid; place-items: center;">
                <button
                    @click.stop="showModal = false"
                    class="lightbox-close"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
                <img
                    src="{{ asset('storage/'.$report->image) }}"
                    alt="{{ $report->title }}"
                    @click.stop
                    style="max-width:90vw; max-height:90vh; width:auto; height:auto; margin:auto; display:block; border-radius:8px; box-shadow:0 10px 25px rgba(0,0,0,.5); object-fit:contain;"
                >
            </div>
        </div>
        @else
        <div style="width:100%;height:280px;border-radius:12px;background:linear-gradient(135deg,#EFF6FF,#BFDBFE);display:flex;align-items:center;justify-content:center;border:1px solid var(--border);">
            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#93C5FD" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
        </div>
        @endif

        {{-- Report Content --}}
        <div class="card">
            <div class="card-body">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;flex-wrap:wrap;">
                    <span class="badge {{ $s['class'] }}" style="display:inline-flex;align-items:center;gap:4px;">
                        <span class="badge-dot" style="background:currentColor;opacity:.5;"></span>
                        {!! $si !!} {{ $s['label'] }}
                    </span>
                </div>
                <h1 style="font-size:22px;font-weight:700;color:var(--text);letter-spacing:-.3px;margin-bottom:14px;line-height:1.3;">
                    {{ $report->title }}
                </h1>
                <p style="font-size:15px;color:var(--text);line-height:1.8;overflow-wrap:break-word;word-break:break-word;">
                    {{ $report->description }}
                </p>
            </div>
        </div>

        {{-- Progress Timeline --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title" style="display:flex;align-items:center;gap:6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                    Riwayat Penanganan
                </div>
                <span style="font-size:12px;color:var(--text-light);" class="mono">
                    {{ $report->progressUpdates->count() + 1 }} entri
                </span>
            </div>
            <div class="card-body" style="max-height:460px;overflow-y:auto;">
                <div class="timeline">
                    {{-- Initial event --}}
                    <div class="timeline-item">
                        <div class="timeline-dot dot-start"></div>
                        <div class="timeline-date">{{ $report->created_at->format('d M Y, H:i') }}</div>
                        <div class="timeline-content">
                            <div class="timeline-note" style="display:flex;align-items:flex-start;gap:7px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:2px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                <span>Laporan diajukan oleh <strong>{{ $report->user->name ?? 'warga' }}</strong> dan menunggu verifikasi admin.</span>
                            </div>
                        </div>
                    </div>

                    @forelse($report->progressUpdates as $update)
                    <div class="timeline-item">
                        <div class="timeline-dot {{ $loop->last ? 'dot-end' : 'dot-middle' }}"></div>
                        <div class="timeline-date">{{ $update->created_at->format('d M Y, H:i') }}</div>
                        <div class="timeline-content">
                            <div class="timeline-note">{{ $update->note }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state" style="padding:32px 20px;">
                        <div class="empty-state-icon" style="display:flex;justify-content:center;opacity:.45;margin-bottom:10px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        <div class="empty-state-desc">Laporan sedang dalam antrian verifikasi. Belum ada catatan progres.</div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    {{-- ── RIGHT SIDEBAR ── --}}
    <div style="display:flex;flex-direction:column;gap:16px;">

        {{-- Upvote Card --}}
        <div class="card">
            <div class="card-body">
                @auth
                <div x-data="upvote({{ $report->id }}, {{ $report->upvotes_count }}, {{ Auth::user()->upvotedReports->contains($report->id) ? 'true' : 'false' }})">
                    <div style="text-align:center;padding:20px;background:var(--bg);border:2px dashed var(--accent);border-radius:12px;">
                        <div style="font-size:40px;font-weight:700;color:var(--text);font-family:'IBM Plex Mono',monospace;line-height:1;" x-text="count">{{ $report->upvotes_count }}</div>
                        <div style="font-size:13px;color:var(--text-muted);margin-top:4px;margin-bottom:16px;">Total Dukungan Warga</div>
                        <button
                            type="button"
                            @click="toggle()"
                            :disabled="loading"
                            :class="['btn', 'btn-full', upvoted ? 'btn-accent' : 'btn-outline']"
                            style="justify-content:center;display:inline-flex;align-items:center;gap:7px;"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3H14z"/><path d="M7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/></svg>
                            <span x-text="upvoted ? 'Kamu Mendukung Ini' : 'Dukung Laporan Ini'">
                                {{ Auth::user()->upvotedReports->contains($report->id) ? 'Kamu Mendukung Ini' : 'Dukung Laporan Ini' }}
                            </span>
                        </button>
                    </div>
                </div>
                @else
                <div style="text-align:center;padding:20px;background:var(--bg);border:2px dashed var(--accent);border-radius:12px;">
                    <div style="font-size:40px;font-weight:700;color:var(--text);font-family:'IBM Plex Mono',monospace;line-height:1;">{{ $report->upvotes_count }}</div>
                    <div style="font-size:13px;color:var(--text-muted);margin:8px 0 16px;">Total Dukungan Warga</div>
                    <a href="{{ route('login') }}" class="btn btn-outline btn-full" style="justify-content:center;display:inline-flex;align-items:center;gap:7px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                        Masuk untuk Mendukung
                    </a>
                </div>
                @endauth
            </div>
        </div>

        {{-- Info Card --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title" style="display:flex;align-items:center;gap:6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    Informasi Laporan
                </div>
            </div>
            <div class="card-body" style="padding:16px 20px;">
                <div style="display:flex;flex-direction:column;gap:14px;">

                    <div style="padding-bottom:12px;border-bottom:1px solid var(--border);">
                        <div style="font-size:11px;font-weight:600;color:var(--text-light);text-transform:uppercase;letter-spacing:.5px;margin-bottom:3px;">Wilayah / Kecamatan</div>
                        <div style="font-size:13.5px;color:var(--text);font-weight:500;display:flex;align-items:center;gap:5px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            {{ $report->district->name ?? '-' }}
                        </div>
                    </div>

                    <div style="padding-bottom:12px;border-bottom:1px solid var(--border);">
                        <div style="font-size:11px;font-weight:600;color:var(--text-light);text-transform:uppercase;letter-spacing:.5px;margin-bottom:3px;">Pelapor</div>
                        <div style="font-size:13.5px;color:var(--text);font-weight:500;display:flex;align-items:center;gap:5px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            {{ $report->user->name ?? 'Anonim' }}
                        </div>
                    </div>

                    <div style="padding-bottom:12px;border-bottom:1px solid var(--border);">
                        <div style="font-size:11px;font-weight:600;color:var(--text-light);text-transform:uppercase;letter-spacing:.5px;margin-bottom:3px;">Tanggal Laporan</div>
                        <div style="font-size:13.5px;color:var(--text);font-weight:500;display:flex;align-items:center;gap:5px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            {{ $report->created_at->format('d M Y') }}
                        </div>
                    </div>

                    <div style="padding-bottom:12px;border-bottom:1px solid var(--border);">
                        <div style="font-size:11px;font-weight:600;color:var(--text-light);text-transform:uppercase;letter-spacing:.5px;margin-bottom:3px;">Terakhir Diperbarui</div>
                        <div style="font-size:13.5px;color:var(--text);font-weight:500;display:flex;align-items:center;gap:5px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-.18-4.3"/></svg>
                            {{ $report->updated_at->diffForHumans() }}
                        </div>
                    </div>

                    <div style="padding-bottom:12px;border-bottom:1px solid var(--border);">
                        <div style="font-size:11px;font-weight:600;color:var(--text-light);text-transform:uppercase;letter-spacing:.5px;margin-bottom:3px;">Total Catatan</div>
                        <div style="font-size:13.5px;color:var(--text);font-weight:500;display:flex;align-items:center;gap:5px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                            {{ $report->progressUpdates->count() }} catatan progres
                        </div>
                    </div>

                    <div>
                        <div style="font-size:11px;font-weight:600;color:var(--text-light);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Status Saat Ini</div>
                        <span class="badge {{ $s['class'] }}" style="display:inline-flex;align-items:center;gap:4px;">
                            <span class="badge-dot" style="background:currentColor;opacity:.5;"></span>
                            {!! $si !!} {{ $s['label'] }}
                        </span>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>
<x-app-layout>
    <x-slot name="title">Detail Laporan #{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</x-slot>
    <x-slot name="breadcrumb">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item">Dashboard</a>
        <span class="breadcrumb-sep">/</span>
        <a href="{{ route('admin.reports.index') }}" class="breadcrumb-item">Laporan</a>
        <span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-current">#{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</span>
    </x-slot>

    <!-- PAGE HEADER -->
    <div class="page-header" style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;margin-bottom:24px;">
        <div>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                <div class="page-title" style="margin-bottom:0;">Detail Laporan</div>
                <span style="font-size:14px;font-family:'IBM Plex Mono',monospace;color:var(--text-muted);background:var(--bg);border:1px solid var(--border);padding:3px 10px;border-radius:6px;">
                    #{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}
                </span>
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
                <span class="badge {{ $b['class'] }}" style="font-size:12px;padding:4px 12px;">
                    <span class="badge-dot" style="background:currentColor;opacity:.5"></span>
                    {{ $b['label'] }}
                </span>
                @if($report->upvotes_count > 0)
                <span style="display:inline-flex;align-items:center;gap:5px;font-size:13px;font-weight:600;color:var(--accent);background:#FFF7ED;border:1px solid #FED7AA;padding:3px 10px;border-radius:12px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3H14z"/><path d="M7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/></svg>
                    {{ $report->upvotes_count }} Dukungan
                </span>
                @endif
            </div>
        </div>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-outline" style="flex-shrink:0;display:inline-flex;align-items:center;gap:6px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Kembali
        </a>
    </div>

    <div style="display:grid;grid-template-columns:1fr 360px;gap:24px;align-items:start;">

        <!-- LEFT: REPORT DETAIL + PROGRESS -->
        <div style="display:flex;flex-direction:column;gap:20px;">

            <!-- REPORT IMAGE -->
            @if($report->image)
            <div class="card" style="overflow:hidden;" x-data="{ showModal: false }">
                <img
                    src="{{ asset('storage/'.$report->image) }}"
                    alt="{{ $report->title }}"
                    @click="showModal = true"
                    style="width:100%;max-height:360px;object-fit:cover;cursor:zoom-in;"
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
                        type="button"
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
            <div class="card" style="overflow:hidden;">
                <div style="height:200px;background:linear-gradient(135deg,#EFF6FF,#BFDBFE);display:flex;align-items:center;justify-content:center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#93C5FD" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                </div>
                <div style="padding:12px 16px;background:var(--bg);border-top:1px solid var(--border);">
                    <span style="font-size:12.5px;color:var(--text-light);font-style:italic;">Pelapor tidak melampirkan foto</span>
                </div>
            </div>
            @endif

            <!-- REPORT CONTENT -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title" style="display:flex;align-items:center;gap:6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        Konten Laporan
                    </div>
                </div>
                <div class="card-body">
                    <h2 style="font-size:20px;font-weight:700;color:var(--text);letter-spacing:-.3px;margin-bottom:16px;line-height:1.3;overflow-wrap:break-word;word-break:break-word;">{{ $report->title }}</h2>
                    <x-scrollable maxHeight="420px" paddingRight="8px" style="font-size:15px;color:var(--text);line-height:1.8;overflow-wrap:break-word;word-break:break-word;">
                        {{ $report->description }}
                    </x-scrollable>
                </div>
            </div>

            <!-- PROGRESS TIMELINE -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title" style="display:flex;align-items:center;gap:6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="17" y1="2" x2="17" y2="22"/><line x1="7" y1="2" x2="7" y2="22"/><line x1="2" y1="7" x2="22" y2="7"/><line x1="2" y1="17" x2="22" y2="17"/></svg>
                        Linimasa Penanganan
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <span style="font-size:12px;color:var(--text);font-family:'IBM Plex Mono',monospace;">{{ $report->progressUpdates->count() + 1 }} entri</span>
                    </div>
                </div>
                <x-scrollable class="card-body" maxHeight="420px" paddingRight="16px">
                    <div class="timeline" style="position:relative;padding-left:28px;">
                        <div style="position:absolute;left:7px;top:8px;bottom:8px;width:2px;background:var(--border);"></div>

                        <!-- Initial event -->
                        <div style="position:relative;margin-bottom:20px;">
                            <div style="position:absolute;left:-25px;top:3px;width:16px;height:16px;border-radius:50%;background:var(--primary);border:2px solid var(--surface);"></div>
                            <div style="font-size:11px;color:var(--text-light);font-family:'IBM Plex Mono',monospace;margin-bottom:5px;">{{ $report->created_at->translatedFormat('d M Y, H:i') }}</div>
                            <div style="background:var(--bg);border:1px solid var(--border);border-radius:8px;padding:12px 14px;">
                                <div style="font-size:13.5px;color:var(--text);line-height:1.6;overflow-wrap:break-word;word-break:break-word;display:flex;align-items:flex-start;gap:7px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:2px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    Laporan diajukan oleh <strong style="margin-left:3px;">{{ $report->user->name ?? 'warga' }}</strong>&nbsp;dan masuk ke antrian verifikasi.
                                </div>
                            </div>
                        </div>

                        @forelse($report->progressUpdates as $update)
                        <div style="position:relative;margin-bottom:20px;">
                            <div style="position:absolute;left:-25px;top:3px;width:16px;height:16px;border-radius:50%;background:{{ $loop->last ? 'var(--success)' : 'var(--warning)' }};border:2px solid var(--surface);"></div>
                            <div style="font-size:11px;color:var(--text-light);font-family:'IBM Plex Mono',monospace;margin-bottom:5px;">{{ $update->created_at->translatedFormat('d M Y, H:i') }}</div>
                            <div style="background:var(--bg);border:1px solid var(--border);border-radius:8px;padding:12px 14px;">
                                <div style="font-size:13.5px;color:var(--text);line-height:1.6;overflow-wrap:break-word;word-break:break-word;">{{ $update->note }}</div>
                            </div>
                        </div>
                        @empty
                        <div style="text-align:center;padding:28px 20px;color:var(--text-muted);">
                            <div style="margin-bottom:8px;opacity:.4;display:flex;justify-content:center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
                            </div>
                            <div style="font-size:13.5px;">Belum ada catatan progres. Gunakan form di samping untuk menambahkan.</div>
                        </div>
                        @endforelse
                    </div>
                </x-scrollable>

                <!-- ADD NOTE FORM -->
                <div style="padding:20px 24px;border-top:1px solid var(--border);background:var(--bg);">
                    <div style="font-size:14px;font-weight:600;color:var(--text);margin-bottom:12px;display:flex;align-items:center;gap:6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        Tambah Catatan Progres
                    </div>
                    <form method="POST" action="{{ route('admin.reports.progress.store', $report->id) }}">
                        @csrf
                        <div style="display:flex;gap:10px;align-items:flex-start;">
                            <textarea
                                name="note"
                                class="form-textarea"
                                placeholder="Contoh: Tim lapangan telah melakukan survei lokasi. Estimasi perbaikan 3-5 hari kerja..."
                                rows="3"
                                required
                                style="flex:1;min-height:80px;"
                            >{{ old('note') }}</textarea>
                        </div>
                        @error('note')<div class="form-error" style="margin-top:4px;">{{ $message }}</div>@enderror
                        <div style="margin-top:10px;">
                            <button type="submit" class="btn btn-primary" style="font-size:13.5px;display:inline-flex;align-items:center;gap:6px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                Simpan Catatan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDEBAR -->
        <div style="display:flex;flex-direction:column;gap:16px;">

            <!-- STATUS VERIFICATION -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title" style="display:flex;align-items:center;gap:6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                        Verifikasi Status
                    </div>
                </div>
                <div class="card-body">
                    <div style="font-size:13px;color:var(--text-muted);margin-bottom:14px;">Status saat ini:</div>
                    <div style="margin-bottom:16px;">
                        <span class="badge {{ $b['class'] }}" style="font-size:13px;padding:5px 14px;">
                            <span class="badge-dot" style="background:currentColor;opacity:.5"></span>
                            {{ $b['label'] }}
                        </span>
                    </div>
                    <form method="POST" action="{{ route('admin.reports.update-status', $report->id) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" id="selectedStatus" value="">
                        <div class="status-btn-grid">
                            @php
                            $statusButtons = [
                                [
                                    'value'  => 'published',
                                    'label'  => 'Terima Laporan',
                                    'bg'     => '#DBEAFE',
                                    'color'  => '#1E40AF',
                                    'border' => '#93C5FD',
                                    'icon'   => '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>',
                                ],
                                [
                                    'value'  => 'rejected',
                                    'label'  => 'Tolak Laporan',
                                    'bg'     => '#FEF2F2',
                                    'color'  => '#991B1B',
                                    'border' => '#FECACA',
                                    'icon'   => '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',
                                ],
                                [
                                    'value'  => 'in_progress',
                                    'label'  => 'Tandai Diproses',
                                    'bg'     => '#FEF3C7',
                                    'color'  => '#92400E',
                                    'border' => '#FCD34D',
                                    'icon'   => '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>',
                                ],
                                [
                                    'value'  => 'resolved',
                                    'label'  => 'Tandai Selesai',
                                    'bg'     => '#D1FAE5',
                                    'color'  => '#065F46',
                                    'border' => '#6EE7B7',
                                    'icon'   => '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
                                ],
                            ];
                            @endphp
                            @foreach($statusButtons as $sb)
                            <button
                                type="button"
                                onclick="setStatus('{{ $sb['value'] }}', this)"
                                class="status-btn"
                                data-status="{{ $sb['value'] }}"
                                style="background:{{ $sb['bg'] }};color:{{ $sb['color'] }};border-color:{{ $sb['border'] }};font-size:12.5px;padding:10px 12px;display:inline-flex;align-items:center;gap:6px;"
                                onmouseover="this.style.opacity='.8'"
                                onmouseout="this.style.opacity='1'"
                            >{!! $sb['icon'] !!} {{ $sb['label'] }}</button>
                            @endforeach
                        </div>
                        <div id="confirmArea" style="display:none;margin-top:12px;padding:12px;background:var(--bg);border:1px solid var(--border);border-radius:8px;">
                            <div style="font-size:13px;color:var(--text-muted);margin-bottom:10px;">Konfirmasi ubah status menjadi:</div>
                            <div id="confirmLabel" style="font-size:14px;font-weight:600;color:var(--text);margin-bottom:12px;"></div>
                            <div style="display:flex;gap:8px;">
                                <button type="submit" class="btn btn-primary btn-sm" style="flex:1;justify-content:center;display:inline-flex;align-items:center;gap:5px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    Ya, Ubah Sekarang
                                </button>
                                <button type="button" onclick="cancelStatus()" class="btn btn-outline btn-sm">Batal</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- REPORT METADATA -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title" style="display:flex;align-items:center;gap:6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        Info Laporan
                    </div>
                </div>
                <div class="card-body" style="padding:16px 20px;">
                    <div style="display:flex;flex-direction:column;gap:14px;">

                        <div style="display:flex;flex-direction:column;gap:3px;padding-bottom:12px;border-bottom:1px solid var(--border);">
                            <div style="font-size:11px;font-weight:600;color:var(--text-light);text-transform:uppercase;letter-spacing:.5px;">ID Laporan</div>
                            <div style="font-size:13px;font-family:'IBM Plex Mono',monospace;color:var(--text);font-weight:500;">#{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</div>
                        </div>

                        <div style="display:flex;flex-direction:column;gap:3px;padding-bottom:12px;border-bottom:1px solid var(--border);">
                            <div style="font-size:11px;font-weight:600;color:var(--text-light);text-transform:uppercase;letter-spacing:.5px;">Pelapor</div>
                            <div style="font-size:13px;color:var(--text);font-weight:500;display:flex;align-items:center;gap:5px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                {{ $report->user->name ?? '-' }}
                            </div>
                        </div>

                        <div style="display:flex;flex-direction:column;gap:3px;padding-bottom:12px;border-bottom:1px solid var(--border);">
                            <div style="font-size:11px;font-weight:600;color:var(--text-light);text-transform:uppercase;letter-spacing:.5px;">Email</div>
                            <div style="font-size:13px;color:var(--text);font-weight:500;display:flex;align-items:center;gap:5px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                {{ $report->user->email ?? '-' }}
                            </div>
                        </div>

                        <div style="display:flex;flex-direction:column;gap:3px;padding-bottom:12px;border-bottom:1px solid var(--border);">
                            <div style="font-size:11px;font-weight:600;color:var(--text-light);text-transform:uppercase;letter-spacing:.5px;">Kecamatan</div>
                            <div style="font-size:13px;color:var(--text);font-weight:500;display:flex;align-items:center;gap:5px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                {{ $report->district->name ?? '-' }}
                            </div>
                        </div>

                        <div style="display:flex;flex-direction:column;gap:3px;padding-bottom:12px;border-bottom:1px solid var(--border);">
                            <div style="font-size:11px;font-weight:600;color:var(--text-light);text-transform:uppercase;letter-spacing:.5px;">Tanggal Laporan</div>
                            <div style="font-size:13px;font-family:'IBM Plex Mono',monospace;color:var(--text);font-weight:500;display:flex;align-items:center;gap:5px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                {{ $report->created_at->translatedFormat('d M Y, H:i') }}
                            </div>
                        </div>

                        <div style="display:flex;flex-direction:column;gap:3px;padding-bottom:12px;border-bottom:1px solid var(--border);">
                            <div style="font-size:11px;font-weight:600;color:var(--text-light);text-transform:uppercase;letter-spacing:.5px;">Terakhir Update</div>
                            <div style="font-size:13px;color:var(--text);font-weight:500;display:flex;align-items:center;gap:5px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-.18-4.3"/></svg>
                                {{ $report->updated_at->diffForHumans() }}
                            </div>
                        </div>

                        <div style="display:flex;flex-direction:column;gap:3px;padding-bottom:12px;border-bottom:1px solid var(--border);">
                            <div style="font-size:11px;font-weight:600;color:var(--text-light);text-transform:uppercase;letter-spacing:.5px;">Total Upvote</div>
                            <div style="font-size:13px;color:var(--text);font-weight:500;display:flex;align-items:center;gap:5px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3H14z"/><path d="M7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/></svg>
                                {{ $report->upvotes_count }} dukungan warga
                            </div>
                        </div>

                        <div style="display:flex;flex-direction:column;gap:3px;padding-bottom:12px;border-bottom:1px solid var(--border);">
                            <div style="font-size:11px;font-weight:600;color:var(--text-light);text-transform:uppercase;letter-spacing:.5px;">Catatan Progres</div>
                            <div style="font-size:13px;font-family:'IBM Plex Mono',monospace;color:var(--text);font-weight:500;display:flex;align-items:center;gap:5px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                                {{ $report->progressUpdates->count() }} catatan
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- DANGER ZONE -->
            <div class="danger-zone" x-data="{ open: false }">
                <div class="danger-zone-desc">Menghapus laporan ini akan menghapus semua data terkait termasuk foto, catatan progres, dan upvote secara <strong>permanen</strong>. Tindakan ini tidak dapat dibatalkan.</div>

                <button type="button" @click="open = true" class="btn btn-danger" style="width:100%;justify-content:center;margin-top:12px;display:inline-flex;align-items:center;gap:7px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                    Hapus Laporan Ini
                </button>

                <div x-show="open"
                    style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background-color:rgba(0,0,0,0.5);z-index:9999;"
                    x-transition.opacity
                    @keydown.escape.window="open = false">

                    <div @click.away="open = false"
                        style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);background:#FFF;width:90%;max-width:400px;padding:24px;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,0.2);box-sizing:border-box;text-align:left;">

                        <div style="font-size:18px;font-weight:600;color:#991B1B;margin-bottom:8px;display:flex;align-items:center;gap:8px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#991B1B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                            Konfirmasi Penghapusan
                        </div>

                        <div style="font-size:14px;color:#475569;margin-bottom:24px;line-height:1.6;">
                            Anda akan menghapus laporan <strong>"{{ Str::limit($report->title, 40) }}"</strong> secara permanen.
                        </div>

                        <form method="POST" action="{{ route('admin.reports.destroy', $report->id) }}">
                            @csrf
                            @method('DELETE')
                            <div style="display:flex;justify-content:center;gap:10px;">
                                <button type="button" @click="open = false" class="btn btn-outline btn-sm">Batal</button>
                                <button type="submit" class="btn btn-danger btn-sm" style="background:var(--danger);color:#fff;border-color:var(--danger);display:inline-flex;align-items:center;gap:6px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                    Hapus
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        const statusLabels = {
            'published':   'Terima & Publikasi Laporan',
            'rejected':    'Tolak Laporan',
            'in_progress': 'Tandai Sedang Diproses',
            'resolved':    'Tandai Selesai & Tutup Laporan',
        };
        let currentBtn = null;

        function setStatus(val, btn) {
            if (currentBtn) currentBtn.style.outline = 'none';
            currentBtn = btn;
            btn.style.outline = '2px solid #1E3A8A';
            document.getElementById('selectedStatus').value = val;
            document.getElementById('confirmLabel').textContent = statusLabels[val] || val;
            document.getElementById('confirmArea').style.display = 'block';
        }

        function cancelStatus() {
            if (currentBtn) currentBtn.style.outline = 'none';
            currentBtn = null;
            document.getElementById('selectedStatus').value = '';
            document.getElementById('confirmArea').style.display = 'none';
        }
    </script>
</x-app-layout>
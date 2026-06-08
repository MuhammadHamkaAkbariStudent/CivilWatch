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
                    👍 {{ $report->upvotes_count }} Dukungan
                </span>
                @endif
            </div>
        </div>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-outline" style="flex-shrink:0;">← Kembali</a>
    </div>

    <div style="display:grid;grid-template-columns:1fr 360px;gap:24px;align-items:start;">

        <!-- LEFT: REPORT DETAIL + PROGRESS -->
        <div style="display:flex;flex-direction:column;gap:20px;">

            <!-- REPORT IMAGE -->
            @if($report->image)
            <div class="card" style="overflow:hidden;">
                <img src="{{ asset('storage/'.$report->image) }}" alt="{{ $report->title }}" style="width:100%;max-height:360px;object-fit:cover;">
                <div style="padding:12px 16px;background:var(--bg);border-top:1px solid var(--border);display:flex;align-items:center;gap:6px;">
                </div>
            </div>
            @else
            <div class="card" style="overflow:hidden;">
                <div style="height:200px;background:linear-gradient(135deg,#EFF6FF,#BFDBFE);display:flex;align-items:center;justify-content:center;font-size:64px;">🚧</div>
                <div style="padding:12px 16px;background:var(--bg);border-top:1px solid var(--border);">
                    <span style="font-size:12.5px;color:var(--text-light);font-style:italic;">Pelapor tidak melampirkan foto</span>
                </div>
            </div>
            @endif

            <!-- REPORT CONTENT -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">📄 Konten Laporan</div>
                </div>
                <div class="card-body">
                    <h2 style="font-size:20px;font-weight:700;color:var(--text);letter-spacing:-.3px;margin-bottom:16px;line-height:1.3;">{{ $report->title }}</h2>
                    <div style="font-size:15px;color:var(--text);line-height:1.8;">{{ $report->description }}</div>
                </div>
            </div>

            <!-- PROGRESS TIMELINE -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">📋 Linimasa Penanganan</div>
                    <span style="font-size:13px;color:var(--text-muted);">{{ $report->progressUpdates->count() }} catatan</span>
                </div>
                <div class="card-body">
                    <div class="timeline" style="position:relative;padding-left:28px;">
                        <div style="position:absolute;left:7px;top:8px;bottom:8px;width:2px;background:var(--border);"></div>

                        <!-- Initial event -->
                        <div style="position:relative;margin-bottom:20px;">
                            <div style="position:absolute;left:-25px;top:3px;width:16px;height:16px;border-radius:50%;background:var(--primary);border:2px solid var(--surface);"></div>
                            <div style="font-size:11px;color:var(--text-light);font-family:'IBM Plex Mono',monospace;margin-bottom:5px;">{{ $report->created_at->format('d M Y, H:i') }}</div>
                            <div style="background:var(--bg);border:1px solid var(--border);border-radius:8px;padding:12px 14px;">
                                <div style="font-size:13.5px;color:var(--text);line-height:1.6;">✍️ Laporan diajukan oleh <strong>{{ $report->user->name ?? 'warga' }}</strong> dan masuk ke antrian verifikasi.</div>
                            </div>
                        </div>

                        @forelse($report->progressUpdates as $update)
                        <div style="position:relative;margin-bottom:20px;">
                            <div style="position:absolute;left:-25px;top:3px;width:16px;height:16px;border-radius:50%;background:{{ $loop->last ? 'var(--success)' : 'var(--warning)' }};border:2px solid var(--surface);"></div>
                            <div style="font-size:11px;color:var(--text-light);font-family:'IBM Plex Mono',monospace;margin-bottom:5px;">{{ $update->created_at->format('d M Y, H:i') }}</div>
                            <div style="background:var(--bg);border:1px solid var(--border);border-radius:8px;padding:12px 14px;">
                                <div style="font-size:13.5px;color:var(--text);line-height:1.6;">{{ $update->note }}</div>
                            </div>
                        </div>
                        @empty
                        <div style="text-align:center;padding:28px 20px;color:var(--text-muted);">
                            <div style="font-size:28px;margin-bottom:8px;opacity:.4;">📝</div>
                            <div style="font-size:13.5px;">Belum ada catatan progres. Gunakan form di samping untuk menambahkan.</div>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- ADD NOTE FORM -->
                <div style="padding:20px 24px;border-top:1px solid var(--border);background:var(--bg);">
                    <div style="font-size:14px;font-weight:600;color:var(--text);margin-bottom:12px;">➕ Tambah Catatan Progres</div>
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
                            <button type="submit" class="btn btn-primary" style="font-size:13.5px;">
                                📌 Simpan Catatan
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
                    <div class="card-title">⚡ Verifikasi Status</div>
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
                            @foreach([
                                ['published',   '🔵 Terima Laporan',  '#DBEAFE','#1E40AF','#93C5FD'],
                                ['rejected',    '🚫 Tolak Laporan',       '#FEF2F2','#991B1B','#FECACA'],
                                ['in_progress', '⚙️ Tandai Diproses',     '#FEF3C7','#92400E','#FCD34D'],
                                ['resolved',    '✅ Tandai Selesai',      '#D1FAE5','#065F46','#6EE7B7'],
                            ] as [$val, $lbl, $bg, $color, $border])
                            <button
                                type="button"
                                onclick="setStatus('{{ $val }}', this)"
                                class="status-btn"
                                data-status="{{ $val }}"
                                style="background:{{ $bg }};color:{{ $color }};border-color:{{ $border }};font-size:12.5px;padding:10px 12px;"
                                onmouseover="this.style.opacity='.8'"
                                onmouseout="this.style.opacity='1'"
                            >{{ $lbl }}</button>
                            @endforeach
                        </div>
                        <div id="confirmArea" style="display:none;margin-top:12px;padding:12px;background:var(--bg);border:1px solid var(--border);border-radius:8px;">
                            <div style="font-size:13px;color:var(--text-muted);margin-bottom:10px;">Konfirmasi ubah status menjadi:</div>
                            <div id="confirmLabel" style="font-size:14px;font-weight:600;color:var(--text);margin-bottom:12px;"></div>
                            <div style="display:flex;gap:8px;">
                                <button type="submit" class="btn btn-primary btn-sm" style="flex:1;justify-content:center;">✓ Ya, Ubah Sekarang</button>
                                <button type="button" onclick="cancelStatus()" class="btn btn-outline btn-sm">Batal</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- REPORT METADATA -->
            <div class="card">
                <div class="card-header"><div class="card-title">ℹ️ Info Laporan</div></div>
                <div class="card-body" style="padding:16px 20px;">
                    <div style="display:flex;flex-direction:column;gap:14px;">
                        @foreach([
                            ['ID Laporan', '#'.str_pad($report->id,5,'0',STR_PAD_LEFT), true],
                            ['Pelapor', $report->user->name ?? '-', false],
                            ['Email', $report->user->email ?? '-', false],
                            ['Kecamatan', '📍 '.($report->district->name ?? '-'), false],
                            ['Tanggal Laporan', $report->created_at->format('d M Y, H:i'), true],
                            ['Terakhir Update', $report->updated_at->diffForHumans(), false],
                            ['Total Upvote', '👍 '.$report->upvotes_count.' dukungan warga', false],
                            ['Catatan Progres', $report->progressUpdates->count().' catatan', true],
                        ] as [$lbl, $val, $mono])
                        <div style="display:flex;flex-direction:column;gap:3px;padding-bottom:12px;border-bottom:1px solid var(--border);">
                            <div style="font-size:11px;font-weight:600;color:var(--text-light);text-transform:uppercase;letter-spacing:.5px;">{{ $lbl }}</div>
                            <div style="font-size:13.5px;color:var(--text);font-weight:500;font-size:13px;' : '' }}">{{ $val }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- DANGER ZONE -->
            <div class="danger-zone" x-data="{ open: false }">
                <div class="danger-zone-desc">Menghapus laporan ini akan menghapus semua data terkait termasuk foto, catatan progres, dan upvote secara <strong>permanen</strong>. Tindakan ini tidak dapat dibatalkan.</div>
                <button @click="open = true" class="btn btn-danger" style="width:100%;justify-content:center;" x-show="!open">
                    🗑️ Hapus Laporan Ini
                </button>

                <!-- Confirm Delete Dialog -->
                <div x-show="open" x-transition style="background:#fff;border:1px solid #FECACA;border-radius:8px;padding:16px;margin-top:4px;">
                    <div style="font-size:13.5px;font-weight:600;color:#991B1B;margin-bottom:8px;">Konfirmasi Penghapusan</div>
                    <div style="font-size:13px;color:#7F1D1D;margin-bottom:14px;line-height:1.6;">
                        Anda akan menghapus laporan <strong>"{{ Str::limit($report->title, 40) }}"</strong> secara permanen.
                    </div>
                    <form method="POST" action="{{ route('admin.reports.destroy', $report->id) }}">
                        @csrf
                        @method('DELETE')
                        <div style="display:flex;gap:8px;">
                            <button type="submit" class="btn btn-danger btn-sm" style="flex:1;justify-content:center;background:var(--danger);color:#fff;border-color:var(--danger);">
                                Ya, Hapus Permanen
                            </button>
                            <button type="button" @click="open = false" class="btn btn-outline btn-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const statusLabels = {
            'published':   '🔵 Terima & Publikasi Laporan',
            'rejected':    '🚫 Tolak Laporan',
            'in_progress': '⚙️ Tandai Sedang Diproses',
            'resolved':    '✅ Tandai Selesai & Tutup Laporan',
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
<x-app-layout>
    <x-slot name="title">Edit Laporan</x-slot>
    <x-slot name="breadcrumb">
        <a href="{{ route('citizen.dashboard') }}" class="breadcrumb-item">Dashboard</a>
        <span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-current">Edit Laporan</span>
    </x-slot>

    <div class="page-header">
        <div class="page-title">✏️ Edit Laporan</div>
        <div class="page-desc">Perbarui informasi laporan Anda — hanya tersedia selama status masih <strong>Menunggu (Pending)</strong></div>
    </div>

    @if(!$report->isEditable())
    <div class="alert alert-error" style="margin-bottom:24px;">
        ⚠️ Laporan ini sudah tidak dapat diedit karena statusnya telah berubah menjadi <strong>{{ ucfirst($report->status) }}</strong>.
    </div>
    @endif

    <div style="display:grid;grid-template-columns:1fr 300px;gap:24px;align-items:start;">

        <!-- FORM -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Ubah Detail Laporan</div>
                <span class="badge badge-pending">
                    <span class="badge-dot" style="background:currentColor;opacity:.5"></span>
                    Status: Menunggu
                </span>
            </div>
            <div class="card-body">
                <form
                    method="POST"
                    action="{{ route('citizen.reports.update', $report->id) }}"
                    enctype="multipart/form-data"
                    x-data="{
                        previewUrl: {{ $report->image ? '\'' . asset('storage/'.$report->image) . '\'' : 'null' }},
                        hasNewFile: false,
                        handleFile(e) {
                            const file = e.target.files[0];
                            if (!file) return;
                            if (file.size > 2 * 1024 * 1024) {
                                alert('Ukuran file maksimal 2MB!');
                                e.target.value = '';
                                return;
                            }
                            this.hasNewFile = true;
                            const reader = new FileReader();
                            reader.onload = (ev) => { this.previewUrl = ev.target.result; };
                            reader.readAsDataURL(file);
                        }
                    }"
                >
                    @csrf
                    @method('PUT')

                    <!-- TITLE -->
                    <div class="form-group">
                        <label class="form-label" for="title">Judul Laporan <span>*</span></label>
                        <input
                            id="title"
                            type="text"
                            name="title"
                            class="form-input"
                            value="{{ old('title', $report->title) }}"
                            placeholder="Judul laporan yang jelas dan informatif"
                            required
                            maxlength="255"
                            {{ !$report->isEditable() ? 'disabled' : '' }}
                        >
                        @error('title')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <!-- DESCRIPTION -->
                    <div class="form-group">
                        <label class="form-label" for="description">Deskripsi Kronologis <span>*</span></label>
                        <textarea
                            id="description"
                            name="description"
                            class="form-textarea"
                            rows="6"
                            required
                            {{ !$report->isEditable() ? 'disabled' : '' }}
                        >{{ old('description', $report->description) }}</textarea>
                        @error('description')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <!-- DISTRICT -->
                    <div class="form-group">
                        <label class="form-label" for="district_id">Lokasi / Kecamatan <span>*</span></label>
                        <select id="district_id" name="district_id" class="form-select" required {{ !$report->isEditable() ? 'disabled' : '' }}>
                            <option value="">-- Pilih Kecamatan --</option>
                            @foreach($districts as $d)
                                <option value="{{ $d->id }}" {{ (old('district_id', $report->district_id) == $d->id) ? 'selected' : '' }}>
                                    {{ $d->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('district_id')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <!-- PHOTO -->
                    <div class="form-group">
                        <label class="form-label">Foto Bukti</label>
                        <div style="margin-bottom:12px;">
                            <!-- Current / Preview -->
                            <div x-show="previewUrl" style="position:relative;margin-bottom:10px;">
                                <img :src="previewUrl" style="width:100%;max-height:240px;object-fit:cover;border-radius:8px;border:1.5px solid var(--border);" alt="Preview foto">
                                <div
                                    x-show="hasNewFile"
                                    style="position:absolute;top:8px;left:8px;background:var(--accent);color:#fff;font-size:11px;font-weight:600;padding:3px 9px;border-radius:12px;"
                                >📸 Foto Baru</div>
                                <div
                                    x-show="!hasNewFile"
                                    style="position:absolute;top:8px;left:8px;background:rgba(0,0,0,.5);color:#fff;font-size:11px;font-weight:600;padding:3px 9px;border-radius:12px;"
                                >📎 Foto Saat Ini</div>
                            </div>

                            @if($report->isEditable())
                            <!-- Upload new label -->
                            <label style="display:inline-flex;align-items:center;gap:8px;padding:9px 16px;border:1.5px solid var(--border);border-radius:8px;cursor:pointer;font-size:13.5px;font-weight:500;color:var(--text-muted);background:var(--bg);transition:all .15s;" onmouseover="this.style.borderColor='var(--primary)';this.style.color='var(--primary)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-muted)'">
                                📷 Ganti Foto (opsional)
                                <input
                                    type="file"
                                    name="photo"
                                    accept=".jpg,.jpeg,.png"
                                    @change="handleFile($event)"
                                    style="display:none;"
                                >
                            </label>
                            <span style="font-size:12px;color:var(--text-light);margin-left:8px;">JPG, PNG — maks. 2 MB</span>
                            @endif
                        </div>
                        @error('photo')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <!-- ACTIONS -->
                    <div style="display:flex;align-items:center;gap:12px;padding-top:8px;border-top:1px solid var(--border);">
                        @if($report->isEditable())
                        <button type="submit" class="btn btn-primary" style="padding:11px 24px;font-size:14px;">
                            💾 Simpan Perubahan
                        </button>
                        @endif
                        <a href="{{ route('citizen.dashboard') }}" class="btn btn-outline">← Kembali</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- SIDEBAR -->
        <div style="display:flex;flex-direction:column;gap:16px;">
            <!-- Report Summary -->
            <div class="card">
                <div class="card-body" style="padding:18px 20px;">
                    <div style="font-size:14px;font-weight:700;color:var(--text);margin-bottom:14px;">📋 Info Laporan</div>
                    <div style="display:flex;flex-direction:column;gap:12px;">
                        <div>
                            <div style="font-size:11px;font-weight:600;color:var(--text-light);text-transform:uppercase;letter-spacing:.5px;margin-bottom:3px;">ID Laporan</div>
                            <div style="font-size:13px;font-family:'IBM Plex Mono',monospace;color:var(--text);">#{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</div>
                        </div>
                        <div>
                            <div style="font-size:11px;font-weight:600;color:var(--text-light);text-transform:uppercase;letter-spacing:.5px;margin-bottom:3px;">Status</div>
                            <span class="badge badge-pending">
                                <span class="badge-dot" style="background:currentColor;opacity:.5"></span>
                                Menunggu Verifikasi
                            </span>
                        </div>
                        <div>
                            <div style="font-size:11px;font-weight:600;color:var(--text-light);text-transform:uppercase;letter-spacing:.5px;margin-bottom:3px;">Dibuat</div>
                            <div style="font-size:13px;font-family:'IBM Plex Mono',monospace;color:var(--text);">{{ $report->created_at->format('d M Y, H:i') }}</div>
                        </div>
                        <div>
                            <div style="font-size:11px;font-weight:600;color:var(--text-light);text-transform:uppercase;letter-spacing:.5px;margin-bottom:3px;">Wilayah</div>
                            <div style="font-size:13px;color:var(--text);">📍 {{ $report->district->name ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Warning -->
            <div style="background:#FEF3C7;border:1px solid #FCD34D;border-radius:10px;padding:14px 16px;">
                <div style="font-size:13px;font-weight:600;color:#92400E;margin-bottom:6px;">⚠️ Perhatian</div>
                <div style="font-size:12.5px;color:#B45309;line-height:1.7;">
                    Laporan hanya bisa diubah selama masih berstatus <strong>Menunggu</strong>. Setelah admin memverifikasi, perubahan tidak lagi diizinkan.
                </div>
            </div>

            <!-- Delete Section -->
            @if($report->isEditable())
            <div style="background:#FEF2F2;border:1px solid #FECACA;border-radius:10px;padding:14px 16px;">
                <div style="font-size:13px;font-weight:600;color:var(--danger);margin-bottom:6px;">🗑️ Hapus Laporan</div>
                <div style="font-size:12.5px;color:#7F1D1D;line-height:1.7;margin-bottom:12px;">Menghapus laporan akan menghapus semua data termasuk foto secara permanen.</div>
                <form method="POST" action="{{ route('citizen.reports.destroy', $report->id) }}" onsubmit="return confirm('Yakin ingin menghapus laporan ini secara permanen?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center;">
                        🗑️ Hapus Laporan Ini
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
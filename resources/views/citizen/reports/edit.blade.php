<x-app-layout>
    <x-slot name="title">Edit Laporan</x-slot>
    <x-slot name="breadcrumb">
        <a href="{{ route('citizen.dashboard') }}" class="breadcrumb-item">Dashboard</a>
        <span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-current">Edit Laporan</span>
    </x-slot>

    <div class="page-header">
        <div class="page-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-4px;margin-right:6px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Edit Laporan
        </div>
        <div class="page-desc">Perbarui informasi laporan Anda — hanya tersedia selama status masih <strong>Menunggu (Pending)</strong></div>
    </div>

    @if(!$report->isEditable())
    <div class="alert alert-error" style="margin-bottom:24px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-3px;margin-right:6px;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        Laporan ini sudah tidak dapat diedit karena statusnya telah berubah menjadi <strong>{{ ucfirst($report->status) }}</strong>.
    </div>
    @endif

    <div style="display:grid;grid-template-columns:1fr 300px;gap:24px;align-items:start;">

        <!-- FORM -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px;margin-right:5px;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    Ubah Detail Laporan
                </div>
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
                    novalidate
                    x-data="{
                        previewUrl: {{ $report->image ? '\'' . asset('storage/'.$report->image) . '\'' : 'null' }},
                        hasNewFile: false,
                        showModal: false,
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
                        <label class="form-label" for="title">Judul Laporan</label>
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
                        <label class="form-label" for="description">Deskripsi Kronologis</label>
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
                        <label class="form-label" for="district_id">Lokasi / Kecamatan</label>
                        @if(!$report->isEditable())
                            {{-- If not editable, show read-only button and static label --}}
                            <div class="cw-select-wrapper">
                                <button type="button" class="cw-select-trigger" disabled style="width:100%; display:flex; align-items:center; justify-content:space-between; opacity:0.6; cursor:not-allowed;">
                                    <span>{{ $report->district->name ?? '-' }}</span>
                                </button>
                                <input type="hidden" name="district_id" value="{{ $report->district_id }}">
                            </div>
                        @else
                            <div
                                x-data="{
                                    open: false,
                                    selected: '{{ old('district_id', $report->district_id) }}',
                                    selectedLabel: '{{ $districts->firstWhere('id', old('district_id', $report->district_id))?->name ?? '-- Pilih Kecamatan --' }}'
                                }"
                                class="cw-select-wrapper"
                                style="position:relative;"
                                @click.outside="open = false"
                            >
                                <input type="hidden" name="district_id" :value="selected">
                                
                                <button
                                    type="button"
                                    class="cw-select-trigger"
                                    @click="open = !open"
                                    :class="{ 'cw-select-trigger--open': open }"
                                    style="width:100%; display:flex; align-items:center; justify-content:space-between;"
                                >
                                    <span x-text="selectedLabel"></span>
                                    <svg class="cw-select-chevron" :class="{ 'rotated': open }"
                                        width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <path d="M4 6L8 10L12 6" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>

                                <x-scrollable as="ul" class="cw-select-dropdown" maxHeight="200px" paddingRight="0px" x-show="open" x-transition style="width:100%;">
                                    <li
                                        class="cw-select-option"
                                        :class="{ 'cw-select-option--active': selected === '' }"
                                        @click="selected = ''; selectedLabel = '-- Pilih Kecamatan --'; open = false"
                                        @mouseenter="$el.classList.add('cw-select-option--hover')"
                                        @mouseleave="$el.classList.remove('cw-select-option--hover')"
                                    >
                                        -- Pilih Kecamatan --
                                    </li>
                                    @foreach($districts as $d)
                                    <li
                                        class="cw-select-option"
                                        :class="{ 'cw-select-option--active': selected === '{{ $d->id }}' }"
                                        @click="selected = '{{ $d->id }}'; selectedLabel = '{{ $d->name }}'; open = false"
                                        @mouseenter="$el.classList.add('cw-select-option--hover')"
                                        @mouseleave="$el.classList.remove('cw-select-option--hover')"
                                    >
                                        {{ $d->name }}
                                    </li>
                                    @endforeach
                                </x-scrollable>
                            </div>
                        @endif
                        @error('district_id')<div class="form-error" style="margin-top:4px;">{{ $message }}</div>@enderror
                    </div>

                    <!-- PHOTO -->
                    <div class="form-group">
                        <label class="form-label">Foto Bukti</label>
                        <div style="margin-bottom:12px;">
                            <!-- Current / Preview -->
                            <div x-show="previewUrl" style="position:relative;margin-bottom:10px;">
                                <img
                                    :src="previewUrl"
                                    @click="showModal = true"
                                    style="width:100%;max-height:240px;object-fit:cover;border-radius:8px;border:1.5px solid var(--border);cursor:zoom-in;"
                                    alt="Preview foto"
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
                                        :src="previewUrl"
                                        alt="Zoomed preview"
                                        @click.stop
                                        style="max-width:90vw; max-height:90vh; width:auto; height:auto; margin:auto; display:block; border-radius:8px; box-shadow:0 10px 25px rgba(0,0,0,.5); object-fit:contain;"
                                    >
                                </div>
                                <div
                                    x-show="hasNewFile"
                                    style="position:absolute;top:8px;left:8px;background:var(--accent);color:#fff;font-size:11px;font-weight:600;padding:3px 9px;border-radius:12px;display:flex;align-items:center;gap:4px;"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                                    Foto Baru
                                </div>
                                <div
                                    x-show="!hasNewFile"
                                    style="position:absolute;top:8px;left:8px;background:rgba(0,0,0,.5);color:#fff;font-size:11px;font-weight:600;padding:3px 9px;border-radius:12px;display:flex;align-items:center;gap:4px;"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                                    Foto Saat Ini
                                </div>
                            </div>

                            @if($report->isEditable())
                            <!-- Upload new label -->
                            <label style="display:inline-flex;align-items:center;gap:8px;padding:9px 16px;border:1.5px solid var(--border);border-radius:8px;cursor:pointer;font-size:13.5px;font-weight:500;color:var(--text-muted);background:var(--bg);transition:all .15s;" onmouseover="this.style.borderColor='var(--primary)';this.style.color='var(--primary)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-muted)'">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                                Ganti Foto
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
                        <button type="submit" class="btn btn-primary" style="padding:11px 24px;font-size:14px;display:inline-flex;align-items:center;gap:7px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            Simpan Perubahan
                        </button>
                        @endif
                        <a href="{{ route('citizen.dashboard') }}" class="btn btn-outline" style="display:inline-flex;align-items:center;gap:7px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                            Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- SIDEBAR -->
        <div style="display:flex;flex-direction:column;gap:16px;">
            <!-- Report Summary -->
            <div class="card">
                <div class="card-body" style="padding:18px 20px;">
                    <div style="font-size:14px;font-weight:700;color:var(--text);margin-bottom:14px;display:flex;align-items:center;gap:6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="2" width="6" height="4" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="16" x2="15" y2="16"/></svg>
                        Info Laporan
                    </div>
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
                            <div style="font-size:13px;font-family:'IBM Plex Mono',monospace;color:var(--text);">{{ $report->created_at->translatedFormat('d M Y, H:i') }}</div>
                        </div>
                        <div>
                            <div style="font-size:11px;font-weight:600;color:var(--text-light);text-transform:uppercase;letter-spacing:.5px;margin-bottom:3px;">Wilayah</div>
                            <div style="font-size:13px;color:var(--text);display:flex;align-items:center;gap:5px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                {{ $report->district->name ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Warning -->
            <div style="background:#FEF3C7;border:1px solid #FCD34D;border-radius:10px;padding:14px 16px;">
                <div style="font-size:13px;font-weight:600;color:#92400E;margin-bottom:6px;display:flex;align-items:center;gap:6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#92400E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    Perhatian
                </div>
                <div style="font-size:12.5px;color:#B45309;line-height:1.7;">
                    Laporan hanya bisa diubah selama masih berstatus <strong>Menunggu</strong>. Setelah admin memverifikasi, perubahan tidak lagi diizinkan.
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
</x-app-layout>
<x-app-layout>
    <x-slot name="title">Buat Laporan</x-slot>
    <x-slot name="breadcrumb">
        <a href="{{ route('citizen.dashboard') }}" class="breadcrumb-item">Dashboard</a>
        <span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-current">Buat Laporan Baru</span>
    </x-slot>

    <div class="page-header">
        <div class="page-title" style="display:flex;align-items:center;gap:8px;">
            <!-- Edit / write icon -->
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 20h9"/>
                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
            </svg>
            Buat Laporan Baru
        </div>
        <div class="page-desc">Isi formulir di bawah dengan detail yang jelas dan lengkap agar laporan segera diproses</div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start;">

        <!-- FORM -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Detail Laporan</div>
                <div style="font-size:13px;color:var(--text-muted);">Silakan lengkapi formulir di bawah ini</div>
            </div>
            <div class="card-body">
                <form
                    method="POST"
                    action="{{ route('citizen.reports.store') }}"
                    enctype="multipart/form-data"
                    novalidate
                    x-data="{
                        previewUrl: null,
                        handleFile(e) {
                            const file = e.target.files[0];
                            if (!file) return;
                            if (file.size > 2 * 1024 * 1024) {
                                alert('Ukuran file maksimal 2MB!');
                                e.target.value = '';
                                this.previewUrl = null;
                                return;
                            }
                            const reader = new FileReader();
                            reader.onload = (ev) => { this.previewUrl = ev.target.result; };
                            reader.readAsDataURL(file);
                        }
                    }"
                >
                    @csrf

                    <!-- TITLE -->
                    <div class="form-group">
                        <label class="form-label" for="title">Judul Laporan</label>
                        <input
                            id="title"
                            type="text"
                            name="title"
                            class="form-input"
                            value="{{ old('title') }}"
                            placeholder="Contoh: Jalan Berlubang Besar di Depan SDN 7 Banjarmasin"
                            required
                            maxlength="255"
                        >
                        @error('title')<div class="form-error">{{ $message }}</div>@enderror
                        <div style="font-size:12px;color:var(--text-light);margin-top:4px;">Judul yang spesifik membantu tim verifikasi bekerja lebih cepat</div>
                    </div>

                    <!-- DESCRIPTION -->
                    <div class="form-group">
                        <label class="form-label" for="description">Deskripsi Kronologis</label>
                        <textarea
                            id="description"
                            name="description"
                            class="form-textarea"
                            placeholder="Ceritakan secara detail: apa masalahnya, seberapa parah, sudah berapa lama, dan dampaknya terhadap warga sekitar..."
                            required
                            rows="6"
                        >{{ old('description') }}</textarea>
                        @error('description')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <!-- DISTRICT -->
                    <div class="form-group">
                        <label class="form-label" for="district_id">Lokasi / Kecamatan</label>
                        <div
                            x-data="{
                                open: false,
                                selected: '{{ old('district_id') }}',
                                selectedLabel: '{{ $districts->firstWhere('id', old('district_id'))?->name ?? '-- Pilih Kecamatan --' }}'
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
                        @error('district_id')<div class="form-error" style="margin-top:4px;">{{ $message }}</div>@enderror
                    </div>

                    <!-- PHOTO UPLOAD -->
                    <div class="form-group">
                        <label class="form-label">Foto Bukti Kerusakan <span style="font-weight:normal;color:var(--text-muted);font-size:12.5px;margin-left:4px;">(Opsional)</span></label>

                        <!-- Preview Area -->
                        <div x-show="previewUrl" style="margin-bottom:12px;position:relative;">
                            <img
                                :src="previewUrl"
                                style="width:100%;max-height:260px;object-fit:cover;border-radius:8px;border:1.5px solid var(--border);"
                                alt="Preview foto"
                            >
                            <button
                                type="button"
                                @click="previewUrl=null; $refs.photoInput.value=''"
                                style="position:absolute;top:8px;right:8px;width:28px;height:28px;border-radius:50%;background:rgba(0,0,0,.5);color:#fff;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;"
                            >
                                <!-- X close -->
                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round">
                                    <line x1="18" y1="6" x2="6" y2="18"/>
                                    <line x1="6"  y1="6" x2="18" y2="18"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Upload Zone -->
                        <label
                            x-show="!previewUrl"
                            style="display:flex;flex-direction:column;align-items:center;gap:8px;padding:25px 20px;border:2px dashed var(--border);border-radius:10px;cursor:pointer;transition:border-color .15s;background:var(--bg);"
                            onmouseover="this.style.borderColor='var(--primary)';this.style.background='#EFF6FF'"
                            onmouseout="this.style.borderColor='var(--border)';this.style.background='var(--bg)'"
                        >
                            <!-- Camera / photo upload icon -->
                            <span style="font-size:12.5px;color:var(--text-muted);">JPG, PNG — maksimal 2 MB</span>
                            <input
                                x-ref="photoInput"
                                type="file"
                                name="photo"
                                accept=".jpg,.jpeg,.png"
                                @change="handleFile($event)"
                                style="display:none;"
                            >
                        </label>
                        @error('photo')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <!-- ACTIONS -->
                    <div style="display:flex;align-items:center;gap:12px;padding-top:14px;solid var(--border);margin-top:4px;">
                        <button type="submit" class="btn btn-accent" style="padding:11px 24px;font-size:14px;display:inline-flex;align-items:center;gap:7px;">
                            <!-- Send / rocket icon -->
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="22" y1="2" x2="11" y2="13"/>
                                <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                            </svg>
                            Kirim Laporan
                        </button>
                        <a href="{{ route('citizen.dashboard') }}" class="btn btn-outline">Batal</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- SIDEBAR -->
        <div>
            <!-- Status Info -->
            <div style="background:#FEF3C7;border:1px solid #FCD34D;border-radius:10px;padding:14px 16px;">
                <div style="font-size:13px;font-weight:600;color:#92400E;margin-bottom:4px;display:flex;align-items:center;gap:6px;">
                    <!-- Clock icon -->
                    <svg width="14" height="14" fill="none" stroke="#92400E" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                    Status Awal: Menunggu
                </div>
                <div style="font-size:12.5px;color:#B45309;line-height:1.6;">Laporan baru Anda akan berstatus <strong>Pending</strong> dan belum tampil di public feed hingga diverifikasi admin.</div>
            </div>
        </div>

    </div>
</x-app-layout>
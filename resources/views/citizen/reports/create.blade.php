<x-app-layout>
    <x-slot name="title">Buat Laporan</x-slot>
    <x-slot name="breadcrumb">
        <a href="{{ route('citizen.dashboard') }}" class="breadcrumb-item">Dashboard</a>
        <span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-current">Buat Laporan Baru</span>
    </x-slot>

    <div class="page-header">
        <div class="page-title">✍️ Buat Laporan Baru</div>
        <div class="page-desc">Isi formulir di bawah dengan detail yang jelas dan lengkap agar laporan segera diproses</div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start;">

        <!-- FORM -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Detail Laporan</div>
                <div style="font-size:13px;color:var(--text-muted);">Kolom dengan tanda <span style="color:var(--danger)">*</span> wajib diisi</div>
            </div>
            <div class="card-body">
                <form
                    method="POST"
                    action="{{ route('citizen.reports.store') }}"
                    enctype="multipart/form-data"
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

                    <!-- CLASSIFICATION -->
                    <div class="form-group">
                        <label class="form-label">Klasifikasi Laporan <span>*</span></label>
                        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;">
                            @foreach([['Infrastruktur Jalan','🚧'],['Penerangan','💡'],['Drainase','🚰'],['Taman & RTH','🌳'],['Trotoar','🛣️'],['Lainnya','⚠️']] as [$cat, $icon])
                            <label style="display:flex;align-items:center;gap:8px;padding:10px 12px;border:1.5px solid var(--border);border-radius:8px;cursor:pointer;transition:all .15s;" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--border)'">
                                <input type="radio" name="_category_hint" value="{{ $cat }}" style="accent-color:var(--primary);">
                                <span style="font-size:13px;">{{ $icon }} {{ $cat }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- TITLE -->
                    <div class="form-group">
                        <label class="form-label" for="title">Judul Laporan <span>*</span></label>
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
                        <label class="form-label" for="description">Deskripsi Kronologis <span>*</span></label>
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
                        <label class="form-label" for="district_id">Lokasi / Kecamatan <span>*</span></label>
                        <select id="district_id" name="district_id" class="form-select" required>
                            <option value="">-- Pilih Kecamatan --</option>
                            @foreach($districts as $d)
                                <option value="{{ $d->id }}" {{ old('district_id') == $d->id ? 'selected' : '' }}>
                                    {{ $d->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('district_id')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <!-- PHOTO UPLOAD -->
                    <div class="form-group">
                        <label class="form-label">Foto Bukti Kerusakan <span>*</span></label>

                        <!-- Preview Area -->
                        <div
                            x-show="previewUrl"
                            style="margin-bottom:12px;position:relative;"
                        >
                            <img
                                :src="previewUrl"
                                style="width:100%;max-height:260px;object-fit:cover;border-radius:8px;border:1.5px solid var(--border);"
                                alt="Preview foto"
                            >
                            <button
                                type="button"
                                @click="previewUrl=null; $refs.photoInput.value=''"
                                style="position:absolute;top:8px;right:8px;width:28px;height:28px;border-radius:50%;background:rgba(0,0,0,.5);color:#fff;border:none;cursor:pointer;font-size:14px;display:flex;align-items:center;justify-content:center;"
                            >✕</button>
                        </div>

                        <!-- Upload Zone -->
                        <label
                            x-show="!previewUrl"
                            style="display:flex;flex-direction:column;align-items:center;gap:8px;padding:32px 20px;border:2px dashed var(--border);border-radius:10px;cursor:pointer;transition:border-color .15s;background:var(--bg);"
                            onmouseover="this.style.borderColor='var(--primary)';this.style.background='#EFF6FF'"
                            onmouseout="this.style.borderColor='var(--border)';this.style.background='var(--bg)'"
                        >
                            <span style="font-size:36px;">📸</span>
                            <span style="font-size:12.5px;color:var(--text-muted);">JPG, PNG — maksimal 2 MB</span>
                            <input
                                x-ref="photoInput"
                                type="file"
                                name="photo"
                                accept=".jpg,.jpeg,.png"
                                @change="handleFile($event)"
                                style="display:none;"
                                required
                            >
                        </label>
                        @error('photo')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <!-- ACTIONS -->
                    <div style="display:flex;align-items:center;gap:12px;padding-top:8px;border-top:1px solid var(--border);margin-top:4px;">
                        <button type="submit" class="btn btn-accent" style="padding:11px 24px;font-size:14px;">
                            🚀 Kirim Laporan
                        </button>
                        <a href="{{ route('citizen.dashboard') }}" class="btn btn-outline">Batal</a>
                    </div>
                </form>
            </div>
        </div>

            <!-- Status Info -->
            <div style="background:#FEF3C7;border:1px solid #FCD34D;border-radius:10px;padding:14px 16px;">
                <div style="font-size:13px;font-weight:600;color:#92400E;margin-bottom:4px;">⏳ Status Awal: Menunggu</div>
                <div style="font-size:12.5px;color:#B45309;line-height:1.6;">Laporan baru Anda akan berstatus <strong>Pending</strong> dan belum tampil di public feed hingga diverifikasi admin.</div>
            </div>
        </div>
    </div>
</x-app-layout>
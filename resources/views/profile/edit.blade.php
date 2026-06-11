<x-app-layout>
    <x-slot name="title">Profil Saya</x-slot>
    <x-slot name="breadcrumb">
        <span class="breadcrumb-item">Akun</span>
        <span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-current">Profil</span>
    </x-slot>

    <div class="page-header">
        <div class="page-title">Pengaturan Profil</div>
        <div class="page-desc">Kelola informasi akun dan keamanan login Anda</div>
    </div>

    <div style="display:grid; grid-template-columns:280px 1fr; gap:24px; align-items:start;">

        <!-- Profile Sidebar Card -->
        <div class="card" style="text-align:center; padding:32px 24px;">
            <div style="width:80px; height:80px; background:var(--primary); border-radius:20px; display:flex; align-items:center; justify-content:center; margin:0 auto 16px; font-size:32px; font-weight:700; color:#fff;">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div style="font-size:17px; font-weight:700; color:var(--text); margin-bottom:4px;">{{ Auth::user()->name }}</div>
            <div style="font-size:13px; color:var(--text-muted); margin-bottom:12px;">{{ Auth::user()->email }}</div>

            @if(Auth::user()->role === 'admin')
            {{-- Admin badge --}}
            <span style="display:inline-flex; align-items:center; gap:5px; font-size:11.5px; font-weight:600; padding:4px 12px; border-radius:20px; background:#EFF6FF; color:#1E3A8A; border:1px solid #BFDBFE;">
                <!-- Settings / gear -->
                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06-.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                </svg>
                Administrator
            </span>
            @else
            {{-- Citizen badge --}}
            <span style="display:inline-flex; align-items:center; gap:5px; font-size:11.5px; font-weight:600; padding:4px 12px; border-radius:20px; background:#FFF7ED; color:#9A3412; border:1px solid #FED7AA;">
                <!-- User / person -->
                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                Warga
            </span>
            @endif

            <div style="margin-top:20px; padding-top:20px; border-top:1px solid var(--border);">
                <div style="font-size:12px; color:var(--text-light); margin-bottom:6px;">Bergabung sejak</div>
                <div style="font-size:13px; font-weight:500; color:var(--text);">{{ Auth::user()->created_at->format('d M Y') }}</div>
            </div>
        </div>

        <!-- Forms -->
        <div style="display:flex; flex-direction:column; gap:20px;">

            <!-- Update Profile Info -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Informasi Profil</div>
                    <div style="font-size:13px; color:var(--text-muted);">Perbarui nama dan email akun</div>
                </div>
                <div class="card-body">
                    @if(session('status') === 'profile-updated')
                        <div class="alert alert-success" style="display:flex;align-items:center;gap:8px;">
                            <!-- Circle check -->
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                <polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                            Profil berhasil diperbarui.
                        </div>
                    @endif
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                            <div class="form-group" style="margin-bottom:0">
                                <label class="form-label">Nama Lengkap <span style="color:var(--danger)">*</span></label>
                                <input type="text" name="name" class="form-input" value="{{ old('name', Auth::user()->name) }}" required>
                                @error('name')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group" style="margin-bottom:0">
                                <label class="form-label">Alamat Email <span style="color:var(--danger)">*</span></label>
                                <input type="email" name="email" class="form-input" value="{{ old('email', Auth::user()->email) }}" required>
                                @error('email')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div style="margin-top:20px;">
                            <button type="submit" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:7px;">
                                <!-- Save / floppy disk -->
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                                    <polyline points="17 21 17 13 7 13 7 21"/>
                                    <polyline points="7 3 7 8 15 8"/>
                                </svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Update Password -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Ubah Kata Sandi</div>
                    <div style="font-size:13px; color:var(--text-muted);">Pastikan akun Anda menggunakan kata sandi yang kuat</div>
                </div>
                <div class="card-body">
                    @if(session('status') === 'password-updated')
                        <div class="alert alert-success" style="display:flex;align-items:center;gap:8px;">
                            <!-- Circle check -->
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                <polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                            Kata sandi berhasil diubah.
                        </div>
                    @endif
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label class="form-label">Kata Sandi Saat Ini</label>
                            <input type="password" name="current_password" class="form-input" placeholder="••••••••" autocomplete="current-password">
                            @error('current_password', 'updatePassword')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                            <div class="form-group" style="margin-bottom:0">
                                <label class="form-label">Kata Sandi Baru</label>
                                <input type="password" name="password" class="form-input" placeholder="Minimal 8 karakter" autocomplete="new-password">
                                @error('password', 'updatePassword')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group" style="margin-bottom:0">
                                <label class="form-label">Konfirmasi Kata Sandi</label>
                                <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi kata sandi baru" autocomplete="new-password">
                            </div>
                        </div>
                        <div style="margin-top:20px;">
                            <button type="submit" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:7px;">
                                <!-- Key icon -->
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/>
                                </svg>
                                Perbarui Kata Sandi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Account -->
            <div class="danger-zone" x-data="{ confirm: false }">
                <div class="danger-zone-title" style="display:flex;align-items:center;gap:7px;">
                    <!-- Warning triangle -->
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                    Hapus Akun
                </div>
                <div class="danger-zone-desc">Setelah akun dihapus, semua data dan laporan yang Anda buat akan ikut terhapus secara permanen dan tidak dapat dipulihkan.</div>

                <button @click="confirm = true" class="btn btn-danger" style="margin-top:12px;">Hapus Akun Saya</button>

                <div x-show="confirm"
                    style="display:none; position:fixed; inset:0; background-color:rgba(0,0,0,0.5); z-index:50; display:flex; align-items:center; justify-content:center;"
                    x-transition.opacity
                    @keydown.escape.window="confirm = false">

                    <div @click.away="confirm = false"
                        style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); background:#FFF; width:90%; max-width:400px; padding:24px; border-radius:12px; box-shadow:0 10px 25px rgba(0,0,0,0.2); box-sizing:border-box;">

                        <div style="display:flex;align-items:center;gap:8px;font-size:18px;font-weight:bold;color:#DC2626;margin-bottom:8px;">
                            <!-- Trash icon -->
                            <svg width="18" height="18" fill="none" stroke="#DC2626" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                <path d="M10 11v6M14 11v6"/>
                                <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                            </svg>
                            Konfirmasi Hapus Akun
                        </div>

                        <div style="font-size:14px; color:var(--text); margin-bottom:20px; line-height:1.5;">
                            Ketik kata sandi Anda untuk mengkonfirmasi penghapusan akun. Tindakan ini <b>tidak dapat dibatalkan</b>.
                        </div>

                        <form method="POST" action="{{ route('profile.destroy') }}">
                            @csrf
                            @method('DELETE')

                            <input type="password" name="password" class="form-input" placeholder="Kata sandi" style="width:100%; margin-bottom:16px;">

                            @error('password', 'userDeletion')
                                <div class="form-error" style="color:#DC2626; font-size:12px; margin-top:-10px; margin-bottom:16px;">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div style="display:flex; justify-content:flex-end; gap:10px;">
                                <button type="button" @click="confirm = false" class="btn btn-outline">Batal</button>
                                <button type="submit" class="btn btn-danger" style="display:inline-flex;align-items:center;gap:6px;">
                                    <!-- Trash icon small -->
                                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6"/>
                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                        <path d="M10 11v6M14 11v6"/>
                                        <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                                    </svg>
                                    Hapus Permanen
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
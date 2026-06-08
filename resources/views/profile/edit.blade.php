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
            <span style="display:inline-flex; align-items:center; gap:5px; font-size:11.5px; font-weight:600; padding:4px 12px; border-radius:20px; {{ Auth::user()->role === 'admin' ? 'background:#EFF6FF; color:#1E3A8A; border:1px solid #BFDBFE' : 'background:#FFF7ED; color:#9A3412; border:1px solid #FED7AA' }}">
                {{ Auth::user()->role === 'admin' ? '⚙️ Administrator' : '👤 Warga' }}
            </span>
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
                        <div class="alert alert-success">✅ Profil berhasil diperbarui.</div>
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
                            <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
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
                        <div class="alert alert-success">✅ Kata sandi berhasil diubah.</div>
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
                            <button type="submit" class="btn btn-primary">🔑 Perbarui Kata Sandi</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Account -->
            <div class="danger-zone">
                <div class="danger-zone-title">⚠️ Hapus Akun</div>
                <div class="danger-zone-desc">Setelah akun dihapus, semua data dan laporan yang Anda buat akan ikut terhapus secara permanen dan tidak dapat dipulihkan.</div>
                <div x-data="{ confirm: false }">
                    <button @click="confirm=true" class="btn btn-danger" x-show="!confirm">Hapus Akun Saya</button>
                    <div x-show="confirm" style="background:#FFF; border:1px solid #FECACA; border-radius:8px; padding:16px; margin-top:10px;">
                        <div style="font-size:13.5px; color:var(--text); margin-bottom:12px; font-weight:500;">Ketik kata sandi Anda untuk mengkonfirmasi penghapusan akun:</div>
                        <form method="POST" action="{{ route('profile.destroy') }}" style="display:flex; gap:10px;">
                            @csrf
                            @method('DELETE')
                            <input type="password" name="password" class="form-input" placeholder="Kata sandi" style="flex:1">
                            <button type="submit" class="btn btn-danger">Hapus Permanen</button>
                            <button type="button" @click="confirm=false" class="btn btn-outline">Batal</button>
                        </form>
                        @error('password', 'userDeletion')<div class="form-error" style="margin-top:6px;">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
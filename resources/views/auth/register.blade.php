<x-guest-layout>
    <div class="auth-form-title">Daftar Akun Baru</div>
    <div class="auth-form-sub">Bergabunglah dan mulai laporkan masalah infrastruktur di sekitar Anda</div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label class="form-label" for="name">Nama Lengkap <span style="color:#EF4444">*</span></label>
            <input
                id="name"
                class="form-input"
                type="text"
                name="name"
                value="{{ old('name') }}"
                placeholder="Nama lengkap Anda"
                required autofocus autocomplete="name"
            >
            @error('name')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="email">Alamat Email <span style="color:#EF4444">*</span></label>
            <input
                id="email"
                class="form-input"
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="email@aktif.com"
                required autocomplete="username"
            >
            @error('email')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Kata Sandi <span style="color:#EF4444">*</span></label>
            <input
                id="password"
                class="form-input"
                type="password"
                name="password"
                placeholder="Minimal 8 karakter"
                required autocomplete="new-password"
            >
            @error('password')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password_confirmation">Konfirmasi Kata Sandi <span style="color:#EF4444">*</span></label>
            <input
                id="password_confirmation"
                class="form-input"
                type="password"
                name="password_confirmation"
                placeholder="Ulangi kata sandi"
                required autocomplete="new-password"
            >
            @error('password_confirmation')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn-auth">Buat Akun Sekarang</button>
    </form>

    <div class="auth-footer">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="auth-link">Masuk di sini</a>
    </div>
</x-guest-layout>
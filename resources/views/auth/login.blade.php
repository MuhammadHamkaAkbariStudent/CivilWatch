<x-guest-layout>
    <div class="auth-form-title">Selamat Datang Kembali</div>
    <div class="auth-form-sub">Masuk ke akun CivilWatch Anda untuk melanjutkan</div>

    <!-- Session Status -->
    @if(session('status'))
        <div class="alert alert-success" style="margin-bottom:16px">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label class="form-label" for="email">Alamat Email</label>
            <input
                id="email"
                class="form-input"
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="warga@email.com"
                required autofocus autocomplete="username"
            >
            @error('email')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:6px;">
                <label class="form-label" for="password" style="margin-bottom:0">Kata Sandi</label>
                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="auth-link" style="font-size:13px;">Lupa password?</a>
                @endif
            </div>
            <input
                id="password"
                class="form-input"
                type="password"
                name="password"
                placeholder="••••••••"
                required autocomplete="current-password"
            >
            @error('password')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="checkbox-row" style="margin-bottom:20px;">
            <input id="remember_me" type="checkbox" name="remember" style="width:16px;height:16px;">
            <label for="remember_me">Ingat saya di perangkat ini</label>
        </div>

        <button type="submit" class="btn-auth">Masuk ke Akun</button>
    </form>

    <div class="auth-footer">
        Belum punya akun?
        <a href="{{ route('register') }}" class="auth-link">Daftar gratis sekarang</a>
    </div>

</x-guest-layout>
<x-guest-layout>
    <div class="auth-form-title">Lupa Kata Sandi?</div>
    <div class="auth-form-sub">Masukkan email Anda dan kami akan mengirimkan link untuk mengatur ulang kata sandi Anda.</div>

    <!-- Session Status -->
    @if(session('status'))
        <div class="alert alert-success" style="margin-bottom:16px">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" novalidate>
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label class="form-label" for="email">Alamat Email</label>
            <input
                id="email"
                class="form-input"
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="warga@email.com"
                required autofocus
            >
            @error('email')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn-auth" style="margin-top: 16px;">Kirim Link Reset Password</button>
    </form>

    <div class="auth-footer">
        Kembali ke halaman
        <a href="{{ route('login') }}" class="auth-link">Masuk</a>
    </div>
</x-guest-layout>

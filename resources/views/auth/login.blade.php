<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Selamat Datang</h2>
        <p class="text-sm text-slate-500 mt-1.5">Silakan masuk menggunakan akun Anda</p>
    </div>

    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" class="block text-sm font-medium text-slate-700" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1.5 w-full rounded-xl border-slate-300 shadow-sm focus:border-[--cw-blue] focus:ring focus:ring-[--cw-blue] focus:ring-opacity-40 transition-colors" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"/>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
        </div>

        <div>
            <x-input-label for="password" class="block text-sm font-medium text-slate-700" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1.5 w-full rounded-xl border-slate-300 shadow-sm focus:border-[--cw-blue] focus:ring focus:ring-[--cw-blue] focus:ring-opacity-40 transition-colors"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
        </div>

        <div class="flex items-center justify-between pt-1">
            <label class="flex items-center gap-2 cursor-pointer group">
                <input type="checkbox" name="remember" class="w-4 h-4 text-[--cw-blue] border-slate-300 rounded focus:ring-[--cw-blue] cursor-pointer transition">
                <span class="text-sm text-slate-600 group-hover:text-slate-800 transition">Remember me</span>
            </label>
            @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-[--cw-blue] hover:underline font-medium transition">Forgot Password?</a>
            @endif
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full py-3.5 bg-[--cw-navy] text-white rounded-xl font-semibold text-sm hover:opacity-95 active:scale-[0.98] transition-all shadow-md shadow-slate-900/10 hover:shadow-lg hover:shadow-slate-900/20 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                Sign in
            </button>
        </div>

        <div class="pt-5 mt-2 text-center border-t border-slate-100">
            <p class="text-sm text-slate-500">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-[--cw-blue] font-semibold hover:underline transition">Register now</a>
            </p>
        </div>
    </form>
</x-guest-layout>
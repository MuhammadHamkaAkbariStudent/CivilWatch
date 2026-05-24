<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="name" class="block text-sm font-medium text-slate-700" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1.5 w-full rounded-xl border-slate-300 shadow-sm focus:border-[--cw-blue] focus:ring focus:ring-[--cw-blue] focus:ring-opacity-40 transition-colors" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-600" />
        </div>

        <div>
            <x-input-label for="email" class="block text-sm font-medium text-slate-700" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1.5 w-full rounded-xl border-slate-300 shadow-sm focus:border-[--cw-blue] focus:ring focus:ring-[--cw-blue] focus:ring-opacity-40 transition-colors" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
        </div>

        <div>
            <x-input-label for="password" class="block text-sm font-medium text-slate-700" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1.5 w-full rounded-xl border-slate-300 shadow-sm focus:border-[--cw-blue] focus:ring focus:ring-[--cw-blue] focus:ring-opacity-40 transition-colors"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
        </div>

        <div>
            <x-input-label for="password_confirmation" class="block text-sm font-medium text-slate-700" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1.5 w-full rounded-xl border-slate-300 shadow-sm focus:border-[--cw-blue] focus:ring focus:ring-[--cw-blue] focus:ring-opacity-40 transition-colors"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-600" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full py-3.5 bg-[--cw-navy] text-white rounded-xl font-semibold text-sm hover:opacity-95 active:scale-[0.98] transition-all shadow-md shadow-slate-900/10 hover:shadow-lg hover:shadow-slate-900/20 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                Sign up
            </button>
        </div>

        <div class="pt-5 mt-2 text-center border-t border-slate-100">
            <p class="text-sm text-slate-500">
                Already have an account?
                <a href="{{ route('login') }}" class="text-[--cw-blue] font-semibold hover:underline transition">Sign in</a>
            </p>
        </div>
    </form>
</x-guest-layout>
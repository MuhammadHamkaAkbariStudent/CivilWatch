<x-app-layout>
    <x-slot name="title">Profil</x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div>
                <h1 class="text-3xl font-bold text-[--cw-navy]">Profil Saya</h1>
                <p class="text-slate-500 mt-1 text-sm">Kelola informasi akun dan keamanan Anda</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="bg-gradient-to-r from-[--cw-navy] to-blue-800 px-6 py-8 flex items-center gap-5">
                    <div class="w-16 h-16 bg-[--cw-accent] rounded-2xl flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="text-white text-xl font-bold">{{ Auth::user()->name }}</h2>
                        <p class="text-slate-300 text-sm">{{ Auth::user()->email }}</p>
                        <span class="inline-block mt-2 text-xs px-3 py-1 rounded-full font-semibold {{ Auth::user()->isAdmin() ? 'bg-blue-500/30 text-blue-200' : 'bg-emerald-500/30 text-emerald-200' }}">
                            {{ Auth::user()->isAdmin() ? '🛡️ Administrator' : '👤 Warga' }}
                        </span>
                    </div>
                </div>
            </div>

            @include('profile.partials.update-profile-information-form')

            @include('profile.partials.update-password-form')

            @include('profile.partials.delete-user-form')

        </div>
    </div>
</x-app-layout>
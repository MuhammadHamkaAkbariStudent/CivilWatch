<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-[--cw-navy] to-blue-800 px-6 py-8 flex items-center gap-5">
            <div class="w-16 h-16 bg-[--cw-accent] rounded-2xl flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-white text-xl font-bold">{{ Auth::user()->name }}</h2>
                <p class="text-slate-300 text-sm">{{ Auth::user()->email }}</p>
                <span class="inline-block mt-2 text-xs px-3 py-1 rounded-full font-semibold {{ Auth::user()->isAdmin() ? 'bg-blue-500/30 text-blue-200' : 'bg-emerald-500/30 text-emerald-200' }}">
                    {{ Auth::user()->isAdmin() ? '🛡️ Administrator' : '👤 Warga (Citizen)' }}
                </span>
            </div>
        </div>
    </div>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

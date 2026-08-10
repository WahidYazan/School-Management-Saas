<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Lupa Password?</h2>
        <p class="mt-1 text-sm text-gray-500">Masukkan email Anda dan kami akan mengirimkan tautan untuk mengatur ulang password.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <div class="relative mt-1">
                <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </span>
                <x-text-input id="email" class="block w-full pl-10" type="email" name="email" :value="old('email')" required autofocus placeholder="nama@sekolah.sch.id" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-primary-button class="w-full justify-center py-2.5">
                {{ __('Kirim Tautan Reset') }}
            </x-primary-button>
        </div>
    </form>

    <p class="mt-6 text-center text-sm text-gray-600">
        <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-700">&larr; Kembali ke halaman masuk</a>
    </p>
</x-guest-layout>

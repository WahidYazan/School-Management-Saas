<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Verifikasi Email</h2>
        <p class="mt-1 text-sm text-gray-500">Terima kasih telah mendaftar! Sebelum memulai, verifikasi email Anda dengan mengklik tautan yang kami kirimkan. Jika tidak menerimanya, kirim ulang tautan verifikasi.</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 px-4 py-3 rounded-xl bg-green-50 border border-green-200 font-medium text-sm text-green-800">
            Tautan verifikasi baru telah dikirim ke email Anda.
        </div>
    @endif

    <div class="space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button class="w-full justify-center py-2.5">
                {{ __('Kirim Ulang Email Verifikasi') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-center text-sm font-medium text-gray-600 hover:text-gray-900">
                {{ __('Keluar') }}
            </button>
        </form>
    </div>
</x-guest-layout>

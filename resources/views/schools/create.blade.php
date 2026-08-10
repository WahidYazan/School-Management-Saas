<x-app-layout>
    @section('title', 'Tambah Sekolah')
    @section('page-title', 'Tambah Sekolah')

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('schools.store') }}" class="bg-white rounded-2xl border border-gray-200 p-6">
            @csrf

            <div>
                <x-input-label for="name" :value="__('Nama Sekolah')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="npsn" :value="__('NPSN')" />
                    <x-text-input id="npsn" name="npsn" type="text" class="mt-1 block w-full" :value="old('npsn')" />
                    <x-input-error :messages="$errors->get('npsn')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="phone" :value="__('Telepon')" />
                    <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone')" />
                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                </div>
            </div>

            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="address" :value="__('Alamat')" />
                <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address')" />
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>

            <div class="mt-6 flex items-center gap-3">
                <x-primary-button>Simpan</x-primary-button>
                <a href="{{ route('schools.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>

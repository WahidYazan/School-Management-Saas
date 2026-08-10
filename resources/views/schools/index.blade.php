<x-app-layout>
    @section('title', 'Sekolah')
    @section('page-title', 'Kelola Sekolah')

    <div class="space-y-6">
        <form method="POST" action="{{ route('schools.store') }}" class="p-5 bg-white border border-gray-200 rounded-2xl">
            @csrf
            <div class="grid items-end grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <x-input-label for="name" :value="__('Nama Sekolah')" />
                    <x-text-input id="name" name="name" type="text" class="block w-full mt-1 pl-3.5 " :value="old('name')" placeholder="SMA Negeri 2 Jakarta" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="npsn" :value="__('NPSN')" />
                    <x-text-input id="npsn" name="npsn" type="text" class="block w-full mt-1 pl-3.5 " :value="old('npsn')" placeholder="20123457" />
                    <x-input-error :messages="$errors->get('npsn')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" name="email" type="email" class="block w-full mt-1 pl-3.5 " :value="old('email')" placeholder="info@sma2.test" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="phone" :value="__('Telepon')" />
                    <x-text-input id="phone" name="phone" type="text" class="block w-full mt-1 pl-3.5 " :value="old('phone')" placeholder="021-5559876" />
                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                </div>
            </div>
            <div class="mt-4">
                <x-input-label for="address" :value="__('Alamat')" />
                <x-text-input id="address" name="address" type="text" class="block w-full mt-1 pl-3.5 " :value="old('address')" placeholder="Jl. Contoh No. 2, Jakarta" />
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>
            <div class="mt-4">
                <x-primary-button>Tambah Sekolah</x-primary-button>
            </div>
        </form>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($schools as $school)
                <div class="p-5 bg-white border border-gray-200 rounded-2xl">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $school->name }}</h3>
                            <p class="mt-1 text-xs text-gray-500">{{ $school->npsn ? 'NPSN ' . $school->npsn : 'Tanpa NPSN' }}</p>
                        </div>
                        @if (session('active_school_id') == $school->id)
                            <span class="shrink-0 inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">Aktif</span>
                        @endif
                    </div>
                    <div class="grid grid-cols-3 gap-2 mt-4 text-center">
                        <div>
                            <p class="text-lg font-bold text-gray-900">{{ number_format($school->students_count) }}</p>
                            <p class="text-xs text-gray-500">Siswa</p>
                        </div>
                        <div>
                            <p class="text-lg font-bold text-gray-900">{{ number_format($school->teachers_count) }}</p>
                            <p class="text-xs text-gray-500">Guru</p>
                        </div>
                        <div>
                            <p class="text-lg font-bold text-gray-900">{{ number_format($school->classes_count) }}</p>
                            <p class="text-xs text-gray-500">Kelas</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 mt-4">
                        <a href="{{ route('schools.edit', $school) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg border border-gray-300 text-xs font-medium text-gray-700 hover:bg-gray-50">Edit</a>
                        <form method="POST" action="{{ route('schools.destroy', $school) }}" onsubmit="return confirm('Hapus sekolah {{ $school->name }} beserta seluruh datanya?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 rounded-lg border border-red-300 text-xs font-medium text-red-600 hover:bg-red-50">Hapus</button>
                        </form>
                        <form method="POST" action="{{ route('superadmin.switch-school') }}" class="ml-auto">
                            @csrf
                            <input type="hidden" name="school_id" value="{{ $school->id }}">
                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-xs font-medium hover:bg-indigo-700">Kelola</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-10 text-sm text-center text-gray-500 bg-white border border-gray-200 col-span-full rounded-2xl">Belum ada sekolah. Tambahkan sekolah pertama di form di atas.</div>
            @endforelse
        </div>
    </div>
</x-app-layout>

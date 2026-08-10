<x-app-layout>
    @section('title', 'Tambah Siswa')
    @section('page-title', 'Tambah Siswa')

    <form method="POST" action="{{ route('students.store') }}" class="max-w-3xl space-y-6">
        @csrf

        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-base font-semibold text-gray-900 mb-4">Data Pribadi</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <x-input-label for="name" :value="__('Nama Lengkap')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="nis" :value="__('NIS')" />
                    <x-text-input id="nis" name="nis" type="text" class="mt-1 block w-full" :value="old('nis')" />
                    <x-input-error :messages="$errors->get('nis')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="nisn" :value="__('NISN')" />
                    <x-text-input id="nisn" name="nisn" type="text" class="mt-1 block w-full" :value="old('nisn')" />
                    <x-input-error :messages="$errors->get('nisn')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="gender" :value="__('Jenis Kelamin')" />
                    <select id="gender" name="gender" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white">
                        <option value="L" @selected(old('gender', 'L') === 'L')>Laki-laki</option>
                        <option value="P" @selected(old('gender') === 'P')>Perempuan</option>
                    </select>
                    <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="birth_place" :value="__('Tempat Lahir')" />
                    <x-text-input id="birth_place" name="birth_place" type="text" class="mt-1 block w-full" :value="old('birth_place')" />
                </div>

                <div>
                    <x-input-label for="birth_date" :value="__('Tanggal Lahir')" />
                    <x-text-input id="birth_date" name="birth_date" type="date" class="mt-1 block w-full" :value="old('birth_date')" />
                    <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                </div>

                <div class="sm:col-span-2">
                    <x-input-label for="address" :value="__('Alamat')" />
                    <textarea id="address" name="address" rows="2" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white">{{ old('address') }}</textarea>
                </div>

                <div>
                    <x-input-label for="phone" :value="__('No. HP Siswa')" />
                    <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone')" />
                </div>

                <div>
                    <x-input-label for="enrolled_at" :value="__('Tanggal Masuk')" />
                    <x-text-input id="enrolled_at" name="enrolled_at" type="date" class="mt-1 block w-full" :value="old('enrolled_at')" />
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-base font-semibold text-gray-900 mb-4">Akademik</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="class_id" :value="__('Kelas')" />
                    <select id="class_id" name="class_id" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white">
                        <option value="">Pilih kelas</option>
                        @foreach ($classes as $cls)
                            <option value="{{ $cls->id }}" @selected(old('class_id') == $cls->id)>{{ $cls->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label for="major_id" :value="__('Jurusan')" />
                    <select id="major_id" name="major_id" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white">
                        <option value="">Pilih jurusan</option>
                        @foreach ($majors as $major)
                            <option value="{{ $major->id }}" @selected(old('major_id') == $major->id)>{{ $major->code }} — {{ $major->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label for="status" :value="__('Status Siswa')" />
                    <select id="status" name="status" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white">
                        <option value="active" @selected(old('status', 'active') === 'active')>Aktif</option>
                        <option value="alumni" @selected(old('status') === 'alumni')>Alumni</option>
                        <option value="mutasi" @selected(old('status') === 'mutasi')>Mutasi</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-base font-semibold text-gray-900 mb-4">Data Orang Tua / Wali</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="parent_name" :value="__('Nama Orang Tua / Wali')" />
                    <x-text-input id="parent_name" name="parent_name" type="text" class="mt-1 block w-full" :value="old('parent_name')" />
                </div>

                <div>
                    <x-input-label for="parent_phone" :value="__('No. HP Orang Tua')" />
                    <x-text-input id="parent_phone" name="parent_phone" type="text" class="mt-1 block w-full" :value="old('parent_phone')" />
                </div>

                <div class="sm:col-span-2">
                    <x-input-label for="parent_email" :value="__('Email Orang Tua')" />
                    <x-text-input id="parent_email" name="parent_email" type="email" class="mt-1 block w-full" :value="old('parent_email')" />
                    <x-input-error :messages="$errors->get('parent_email')" class="mt-2" />
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('students.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</a>
            <x-primary-button>Simpan Siswa</x-primary-button>
        </div>
    </form>
</x-app-layout>

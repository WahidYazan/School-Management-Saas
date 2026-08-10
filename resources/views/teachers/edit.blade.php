<x-app-layout>
    @section('title', 'Edit Guru')
    @section('page-title', 'Edit Guru')

    <form method="POST" action="{{ route('teachers.update', $teacher) }}" class="max-w-3xl space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-base font-semibold text-gray-900 mb-4">Profil Guru</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="name" :value="__('Nama Lengkap')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $teacher->name)" required autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="nip" :value="__('NIP')" />
                    <x-text-input id="nip" name="nip" type="text" class="mt-1 block w-full" :value="old('nip', $teacher->nip)" />
                    <x-input-error :messages="$errors->get('nip')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="gender" :value="__('Jenis Kelamin')" />
                    <select id="gender" name="gender" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white">
                        <option value="L" @selected(old('gender', $teacher->gender) === 'L')>Laki-laki</option>
                        <option value="P" @selected(old('gender', $teacher->gender) === 'P')>Perempuan</option>
                    </select>
                    <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="phone" :value="__('No. HP')" />
                    <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $teacher->phone)" />
                </div>

                <div class="sm:col-span-2">
                    <x-input-label for="address" :value="__('Alamat')" />
                    <textarea id="address" name="address" rows="2" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white">{{ old('address', $teacher->address) }}</textarea>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-base font-semibold text-gray-900 mb-4">Mata Pelajaran &amp; Wali Kelas</h2>
            <div class="space-y-4">
                <div>
                    <x-input-label for="subject_ids" :value="__('Mata Pelajaran yang Diampu')" />
                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                        @foreach ($subjects as $subject)
                            <label class="flex items-center gap-2 p-2 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="subject_ids[]" value="{{ $subject->id }}" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                       @checked(in_array($subject->id, old('subject_ids', $teacher->subjects->pluck('id')->all())))>
                                <span class="text-sm text-gray-700">{{ $subject->code }} — {{ $subject->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('subject_ids')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="homeroom_class_ids" :value="__('Wali Kelas dari Kelas')" />
                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                        @foreach ($classes as $cls)
                            <label class="flex items-center gap-2 p-2 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="homeroom_class_ids[]" value="{{ $cls->id }}" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                       @checked(in_array($cls->id, old('homeroom_class_ids', $teacher->homeroomClasses->pluck('id')->all())))>
                                <span class="text-sm text-gray-700">{{ $cls->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('homeroom_class_ids')" class="mt-2" />
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('teachers.show', $teacher) }}" class="px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</a>
            <x-primary-button>Simpan Perubahan</x-primary-button>
        </div>
    </form>
</x-app-layout>

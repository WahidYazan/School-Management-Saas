<x-app-layout>
    @section('title', 'Edit Kelas')
    @section('page-title', 'Edit Kelas')

    <form method="POST" action="{{ route('classes.update', $class) }}" class="max-w-xl space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <div class="space-y-4">
                <div>
                    <x-input-label for="name" :value="__('Nama Kelas')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $class->name)" required autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="major_id" :value="__('Jurusan')" />
                    <select id="major_id" name="major_id" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white">
                        <option value="">Tanpa jurusan</option>
                        @foreach ($majors as $major)
                            <option value="{{ $major->id }}" @selected(old('major_id', $class->major_id) == $major->id)>{{ $major->code }} — {{ $major->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label for="homeroom_teacher_id" :value="__('Wali Kelas')" />
                    <select id="homeroom_teacher_id" name="homeroom_teacher_id" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white">
                        <option value="">Belum ada</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}" @selected(old('homeroom_teacher_id', $class->homeroom_teacher_id) == $teacher->id)>{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('classes.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</a>
            <x-primary-button>Simpan Perubahan</x-primary-button>
        </div>
    </form>
</x-app-layout>

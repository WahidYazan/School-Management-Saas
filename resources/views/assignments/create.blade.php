<x-app-layout>
    @section('title', 'Buat Tugas')
    @section('page-title', 'Buat Tugas')

    <form method="POST" action="{{ route('assignments.store') }}" class="max-w-2xl space-y-6">
        @csrf

        <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
            <div>
                <x-input-label for="title" :value="__('Judul Tugas')" />
                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required autofocus />
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="subject_id" :value="__('Mata Pelajaran')" />
                    <select id="subject_id" name="subject_id" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white">
                        <option value="">Umum</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}" @selected(old('subject_id') == $subject->id)>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label for="class_id" :value="__('Kelas')" />
                    <select id="class_id" name="class_id" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white">
                        <option value="">Semua kelas</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}" @selected(old('class_id') == $class->id)>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <x-input-label for="due_at" :value="__('Tenggat Pengumpulan')" />
                <x-text-input id="due_at" name="due_at" type="datetime-local" class="mt-1 block w-full" :value="old('due_at')" />
                <x-input-error :messages="$errors->get('due_at')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="description" :value="__('Deskripsi / Petunjuk')" />
                <textarea id="description" name="description" rows="8" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white" required>{{ old('description') }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('assignments.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</a>
            <x-primary-button>Simpan Tugas</x-primary-button>
        </div>
    </form>
</x-app-layout>

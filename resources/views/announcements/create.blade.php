<x-app-layout>
    @section('title', 'Buat Pengumuman')
    @section('page-title', 'Buat Pengumuman')

    <form method="POST" action="{{ route('announcements.store') }}" class="max-w-2xl space-y-6">
        @csrf

        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <div class="space-y-4">
                <div>
                    <x-input-label for="title" :value="__('Judul')" />
                    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required autofocus />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="body" :value="__('Isi Pengumuman')" />
                    <textarea id="body" name="body" rows="6" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white" required>{{ old('body') }}</textarea>
                    <x-input-error :messages="$errors->get('body')" class="mt-2" />
                </div>

                <div>
                    <x-input-label :value="__('Ditujukan Untuk')" />
                    <div class="mt-2 grid grid-cols-2 sm:grid-cols-4 gap-2">
                        @foreach (['all' => 'Semua', 'teachers' => 'Guru', 'students' => 'Siswa', 'parents' => 'Orang Tua'] as $key => $label)
                            <label class="flex items-center gap-2 p-2 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="audience[]" value="{{ $key }}" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                       @checked(in_array($key, old('audience', ['all'])))>
                                <span class="text-sm text-gray-700">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('audience')" class="mt-2" />
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('announcements.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</a>
            <x-primary-button>Publikasikan</x-primary-button>
        </div>
    </form>
</x-app-layout>

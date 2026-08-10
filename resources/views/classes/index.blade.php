<x-app-layout>
    @section('title', 'Kelas')
    @section('page-title', 'Kelas')

    <div class="space-y-6">
        <div class="flex items-center justify-between gap-4">
            <p class="text-sm text-gray-500">{{ $classes->total() }} kelas terdaftar</p>
            <a href="{{ route('classes.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Kelas
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($classes as $class)
                <div class="bg-white rounded-2xl border border-gray-200 p-5 hover:shadow-sm transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $class->name }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ $class->major?->name ?? 'Tanpa jurusan' }}</p>
                        </div>
                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">{{ $class->students_count }} siswa</span>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ $class->homeroomTeacher?->name ?? 'Belum ada wali kelas' }}
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('students.index', ['class_id' => $class->id]) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Siswa</a>
                            <a href="{{ route('classes.edit', $class) }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Edit</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-2xl border border-gray-200 p-10 text-center text-sm text-gray-500">Belum ada kelas. Tambahkan kelas baru.</div>
            @endforelse
        </div>

        <div>
            {{ $classes->links() }}
        </div>
    </div>
</x-app-layout>

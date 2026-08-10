<x-app-layout>
    @section('title', 'Detail Guru')
    @section('page-title', $teacher->name)

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-4">
                <span class="flex items-center justify-center w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-700 font-bold text-xl">{{ strtoupper(substr($teacher->name, 0, 1)) }}</span>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $teacher->name }}</h2>
                    <p class="text-sm text-gray-500">NIP {{ $teacher->nip ?? '-' }} &middot; {{ $teacher->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('teachers.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50">Kembali</a>
                @if (auth()->user()->canManageSchoolData())
                    <a href="{{ route('teachers.edit', $teacher) }}" class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">Edit</a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Profil</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between gap-4"><dt class="text-gray-500">NIP</dt><dd class="font-medium text-gray-900">{{ $teacher->nip ?? '-' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-gray-500">Jenis Kelamin</dt><dd class="font-medium text-gray-900">{{ $teacher->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-gray-500">No. HP</dt><dd class="font-medium text-gray-900">{{ $teacher->phone ?? '-' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-gray-500">Alamat</dt><dd class="font-medium text-gray-900 text-right">{{ $teacher->address ?? '-' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-gray-500">Akun Login</dt><dd class="font-medium text-gray-900">{{ $teacher->user?->email ?? '-' }}</dd></div>
                </dl>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Mata Pelajaran</h3>
                @forelse ($teacher->subjects as $subject)
                    <div class="flex items-center gap-3 py-2 border-b border-gray-100 last:border-0">
                        <span class="inline-flex px-2.5 py-1 rounded-lg bg-indigo-100 text-indigo-700 text-xs font-semibold">{{ $subject->code }}</span>
                        <span class="text-sm text-gray-700">{{ $subject->name }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Belum ada mata pelajaran.</p>
                @endforelse
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Wali Kelas</h3>
                @forelse ($teacher->homeroomClasses as $cls)
                    <div class="flex items-center gap-3 py-2 border-b border-gray-100 last:border-0">
                        <span class="inline-flex px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-700 text-xs font-semibold">{{ $cls->name }}</span>
                        <span class="text-sm text-gray-500">{{ $cls->students_count }} siswa</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Belum menjadi wali kelas.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>

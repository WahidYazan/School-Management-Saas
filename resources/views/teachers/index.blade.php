<x-app-layout>
    @section('title', 'Data Guru')
    @section('page-title', 'Guru')

    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <form method="GET" action="{{ route('teachers.index') }}" class="flex flex-1 max-w-md gap-3">
                <input class="pl-3.5" type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIP..."
                       class="bg-white border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white transition-colors bg-indigo-600 rounded-lg hover:bg-indigo-700">Cari</button>
                @if (request('search'))
                    <a href="{{ route('teachers.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">Reset</a>
                @endif
            </form>

            @if (auth()->user()->canManageSchoolData())
                <a href="{{ route('teachers.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white transition-colors bg-indigo-600 rounded-lg hover:bg-indigo-700 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Guru
                </a>
            @endif
        </div>

        <div class="overflow-hidden bg-white border border-gray-200 rounded-2xl">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">Nama</th>
                            <th class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">NIP</th>
                            <th class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">Mata Pelajaran</th>
                            <th class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">Wali Kelas</th>
                            <th class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">Kontak</th>
                            <th class="px-5 py-3 text-xs font-semibold tracking-wider text-right text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($teachers as $teacher)
                            <tr class="transition-colors hover:bg-gray-50/70">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <span class="flex items-center justify-center text-sm font-semibold rounded-full w-9 h-9 bg-emerald-100 text-emerald-700">{{ strtoupper(substr($teacher->name, 0, 1)) }}</span>
                                        <div class="leading-tight">
                                            <p class="text-sm font-medium text-gray-900">{{ $teacher->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $teacher->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-sm text-gray-700">{{ $teacher->nip ?? '-' }}</td>
                                <td class="px-5 py-3">
                                    <div class="flex flex-wrap max-w-xs gap-1">
                                        @forelse ($teacher->subjects as $subject)
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">{{ $subject->code }}</span>
                                        @empty
                                            <span class="text-sm text-gray-400">-</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-sm text-gray-700">
                                    @forelse ($teacher->homeroomClasses as $cls)
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 mr-1">{{ $cls->name }}</span>
                                    @empty
                                        <span class="text-gray-400">-</span>
                                    @endforelse
                                </td>
                                <td class="px-5 py-3 text-sm text-gray-700">{{ $teacher->phone ?? '-' }}</td>
                                <td class="px-5 py-3 text-right whitespace-nowrap">
                                    <a href="{{ route('teachers.show', $teacher) }}" class="inline-flex items-center gap-1 text-sm font-medium text-indigo-600 hover:text-indigo-800">Detail</a>
                                    @if (auth()->user()->canManageSchoolData())
                                        <a href="{{ route('teachers.edit', $teacher) }}" class="inline-flex items-center gap-1 ml-3 text-sm font-medium text-gray-600 hover:text-gray-900">Edit</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-16 text-sm text-center text-gray-500">Tidak ada data guru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            {{ $teachers->links() }}
        </div>
    </div>
</x-app-layout>

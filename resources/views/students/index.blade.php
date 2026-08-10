<x-app-layout>
    @section('title', 'Data Siswa')
    @section('page-title', 'Siswa')

    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <form method="GET" action="{{ route('students.index') }}" class="flex flex-col flex-1 max-w-2xl gap-3 sm:flex-row">
                <input class="pl-3.5" type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIS, atau NISN..."
                       class="bg-white border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <select name="class_id" class="bg-white border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua Kelas</option>
                    @foreach ($classes as $cls)
                        <option value="{{ $cls->id }}" @selected(request('class_id') == $cls->id)>{{ $cls->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="bg-white border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua Status</option>
                    <option value="active" @selected(request('status') === 'active')>Aktif</option>
                    <option value="alumni" @selected(request('status') === 'alumni')>Alumni</option>
                    <option value="mutasi" @selected(request('status') === 'mutasi')>Mutasi</option>
                </select>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white transition-colors bg-indigo-600 rounded-lg hover:bg-indigo-700">Cari</button>
                @if (request()->hasAny(['search', 'class_id', 'status']))
                    <a href="{{ route('students.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">Reset</a>
                @endif
            </form>

            @if (auth()->user()->canManageSchoolData())
                <a href="{{ route('students.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white transition-colors bg-indigo-600 rounded-lg hover:bg-indigo-700 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Siswa
                </a>
            @endif
        </div>

        <div class="overflow-hidden bg-white border border-gray-200 rounded-2xl">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">Nama</th>
                            <th class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">NIS / NISN</th>
                            <th class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">Kelas</th>
                            <th class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">Jurusan</th>
                            <th class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">Kontak</th>
                            <th class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">Status</th>
                            <th class="px-5 py-3 text-xs font-semibold tracking-wider text-right text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($students as $student)
                            <tr class="transition-colors hover:bg-gray-50/70">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <span class="flex items-center justify-center text-sm font-semibold text-indigo-700 bg-indigo-100 rounded-full w-9 h-9">{{ strtoupper(substr($student->name, 0, 1)) }}</span>
                                        <div class="leading-tight">
                                            <p class="text-sm font-medium text-gray-900">{{ $student->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-sm text-gray-700">
                                    <span class="font-medium">{{ $student->nis ?? '-' }}</span>
                                    <span class="block text-xs text-gray-400">{{ $student->nisn ?? '-' }}</span>
                                </td>
                                <td class="px-5 py-3 text-sm text-gray-700">{{ $student->class?->name ?? '-' }}</td>
                                <td class="px-5 py-3 text-sm text-gray-700">{{ $student->major?->name ?? '-' }}</td>
                                <td class="px-5 py-3 text-sm text-gray-700">
                                    @if ($student->phone)
                                        <p class="text-gray-700">{{ $student->phone }}</p>
                                        <p class="text-xs text-gray-400">{{ $student->parent_phone ?? '' }}</p>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    @php
                                        $badges = ['active' => 'bg-emerald-100 text-emerald-700', 'alumni' => 'bg-gray-100 text-gray-600', 'mutasi' => 'bg-orange-100 text-orange-700'];
                                        $labels = ['active' => 'Aktif', 'alumni' => 'Alumni', 'mutasi' => 'Mutasi'];
                                    @endphp
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $badges[$student->status] }}">{{ $labels[$student->status] }}</span>
                                </td>
                                <td class="px-5 py-3 text-right whitespace-nowrap">
                                    <a href="{{ route('students.show', $student) }}" class="inline-flex items-center gap-1 text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                        Detail
                                    </a>
                                    @if (auth()->user()->canManageSchoolData() || auth()->user()->isTeacher())
                                        <a href="{{ route('students.edit', $student) }}" class="inline-flex items-center gap-1 ml-3 text-sm font-medium text-gray-600 hover:text-gray-900">Edit</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-16 text-sm text-center text-gray-500">Tidak ada data siswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            {{ $students->links() }}
        </div>
    </div>
</x-app-layout>

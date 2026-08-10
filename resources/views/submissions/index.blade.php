<x-app-layout>
    @section('title', 'Pengumpulan Siswa')
    @section('page-title', 'Pengumpulan Siswa')

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <form method="GET" action="{{ route('submissions.index') }}" class="flex flex-col sm:flex-row gap-3 flex-1 max-w-xl">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIS..."
                       class="rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white">
                <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition-colors">Cari</button>
                @if (request('search'))
                    <a href="{{ route('submissions.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50">Reset</a>
                @endif
            </form>

            <div class="flex items-center gap-2">
                <span class="inline-flex px-3 py-1.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">{{ $summary['students'] }} siswa mengumpulkan</span>
                <span class="inline-flex px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">{{ $summary['submissions'] }} pengumpulan</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Siswa</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kelas</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah Tugas Dikumpulkan</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Terakhir Mengumpulkan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($students as $row)
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <span class="flex items-center justify-center w-9 h-9 rounded-full bg-indigo-100 text-indigo-700 font-semibold text-sm">{{ strtoupper(substr($row->student?->name ?? '-', 0, 1)) }}</span>
                                        <div class="leading-tight">
                                            <p class="text-sm font-medium text-gray-900">{{ $row->student?->name ?? '-' }}</p>
                                            <p class="text-xs text-gray-500">NIS {{ $row->student?->nis ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-sm text-gray-700">{{ $row->student?->class?->name ?? '-' }}</td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">{{ $row->total }} tugas</span>
                                </td>
                                <td class="px-5 py-3 text-sm text-gray-700">
                                    {{ $row->last_submitted_at ? \Illuminate\Support\Carbon::parse($row->last_submitted_at)->translatedFormat('d M Y, H:i') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-16 text-center text-sm text-gray-500">Belum ada siswa yang mengumpulkan tugas.</td>
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

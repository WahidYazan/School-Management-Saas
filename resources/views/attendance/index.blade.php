<x-app-layout>
    @section('title', 'Absensi')
    @section('page-title', 'Absensi')

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <form method="GET" action="{{ route('attendance.index') }}" class="flex flex-col sm:flex-row gap-3 flex-1 max-w-2xl">
                <div>
                    <x-text-input type="date" name="date" :value="request('date', now()->toDateString())" class="block w-full" />
                </div>
                <select name="class_id" class="rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white">
                    <option value="">Semua Kelas</option>
                    @foreach ($classes as $cls)
                        <option value="{{ $cls->id }}" @selected(request('class_id') == $cls->id)>{{ $cls->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition-colors">Tampilkan</button>
                @if (request()->hasAny(['date', 'class_id']))
                    <a href="{{ route('attendance.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50">Reset</a>
                @endif
            </form>

            <a href="{{ route('attendance.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition-colors shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Input Absensi
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Siswa</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kelas</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($attendances as $att)
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="px-5 py-3 text-sm font-medium text-gray-900">{{ $att->student->name }}</td>
                                <td class="px-5 py-3 text-sm text-gray-700">{{ $att->class?->name ?? '-' }}</td>
                                <td class="px-5 py-3 text-sm text-gray-700">{{ $att->date->translatedFormat('d M Y') }}</td>
                                <td class="px-5 py-3">
                                    @php
                                        $map = ['hadir' => 'bg-emerald-100 text-emerald-700', 'sakit' => 'bg-orange-100 text-orange-700', 'izin' => 'bg-sky-100 text-sky-700', 'alpa' => 'bg-red-100 text-red-700', 'terlambat' => 'bg-amber-100 text-amber-700'];
                                    @endphp
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $map[$att->status] }}">{{ App\Models\Attendance::STATUS_LABELS[$att->status] }}</span>
                                </td>
                                <td class="px-5 py-3 text-sm text-gray-600">{{ $att->note ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-16 text-center text-sm text-gray-500">Tidak ada data absensi pada tanggal ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            {{ $attendances->links() }}
        </div>
    </div>
</x-app-layout>

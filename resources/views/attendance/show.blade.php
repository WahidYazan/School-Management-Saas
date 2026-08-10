<x-app-layout>
    @section('title', 'Absensi ' . $class->name)
    @section('page-title', 'Absensi ' . $class->name)

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <p class="text-lg font-semibold text-gray-900">{{ \Illuminate\Support\Carbon::parse($date)->translatedFormat('l, d M Y') }}</p>
                <p class="text-sm text-gray-500">{{ $class->major?->name ?? '-' }}</p>
            </div>
            <a href="{{ route('attendance.create', ['class_id' => $class->id, 'date' => $date]) }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                Edit Absensi
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
            @foreach (['hadir' => ['Hadir', 'bg-emerald-100 text-emerald-700'], 'sakit' => ['Sakit', 'bg-orange-100 text-orange-700'], 'izin' => ['Izin', 'bg-sky-100 text-sky-700'], 'alpa' => ['Alpa', 'bg-red-100 text-red-700'], 'terlambat' => ['Terlambat', 'bg-amber-100 text-amber-700']] as $key => [$label, $badge])
                <div class="bg-white rounded-2xl border border-gray-200 p-4">
                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $badge }}">{{ $label }}</span>
                    <p class="mt-2 text-2xl font-bold text-gray-900">{{ $summary[$key] }}</p>
                </div>
            @endforeach
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">NIS</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($attendances as $att)
                            <tr>
                                <td class="px-5 py-3 text-sm text-gray-500">{{ $loop->iteration }}</td>
                                <td class="px-5 py-3 text-sm text-gray-700">{{ $att->student->nis ?? '-' }}</td>
                                <td class="px-5 py-3 text-sm font-medium text-gray-900">{{ $att->student->name }}</td>
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
                                <td colspan="5" class="px-5 py-16 text-center text-sm text-gray-500">Belum ada data absensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

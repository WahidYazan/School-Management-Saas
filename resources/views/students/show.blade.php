<x-app-layout>
    @section('title', 'Detail Siswa')
    @section('page-title', $student->name)

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-4">
                <span class="flex items-center justify-center w-14 h-14 rounded-2xl bg-indigo-100 text-indigo-700 font-bold text-xl">{{ strtoupper(substr($student->name, 0, 1)) }}</span>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $student->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $student->class?->name ?? 'Tanpa kelas' }} &middot; {{ $student->major?->name ?? 'Tanpa jurusan' }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('students.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50">Kembali</a>
                @if (auth()->user()->canManageSchoolData() || auth()->user()->isTeacher())
                    <a href="{{ route('students.edit', $student) }}" class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">Edit</a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Data Pribadi</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between gap-4"><dt class="text-gray-500">NIS</dt><dd class="font-medium text-gray-900">{{ $student->nis ?? '-' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-gray-500">NISN</dt><dd class="font-medium text-gray-900">{{ $student->nisn ?? '-' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-gray-500">Jenis Kelamin</dt><dd class="font-medium text-gray-900">{{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-gray-500">Tempat, Tgl Lahir</dt><dd class="font-medium text-gray-900">{{ $student->birth_place ?? '-' }}{{ $student->birth_date ? ', ' . $student->birth_date->translatedFormat('d M Y') : '' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-gray-500">Alamat</dt><dd class="font-medium text-gray-900 text-right">{{ $student->address ?? '-' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-gray-500">No. HP</dt><dd class="font-medium text-gray-900">{{ $student->phone ?? '-' }}</dd></div>
                </dl>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Akademik</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between gap-4"><dt class="text-gray-500">Kelas</dt><dd class="font-medium text-gray-900">{{ $student->class?->name ?? '-' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-gray-500">Jurusan</dt><dd class="font-medium text-gray-900">{{ $student->major?->name ?? '-' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-gray-500">Wali Kelas</dt><dd class="font-medium text-gray-900">{{ $student->class?->homeroomTeacher?->name ?? '-' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-gray-500">Status</dt>
                        <dd>
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $student->status === 'active' ? 'bg-emerald-100 text-emerald-700' : ($student->status === 'alumni' ? 'bg-gray-100 text-gray-600' : 'bg-orange-100 text-orange-700') }}">
                                {{ ucfirst($student->status) }}
                            </span>
                        </dd>
                    </div>
                    <div class="flex justify-between gap-4"><dt class="text-gray-500">Tanggal Masuk</dt><dd class="font-medium text-gray-900">{{ $student->enrolled_at?->translatedFormat('d M Y') ?? '-' }}</dd></div>
                </dl>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Orang Tua / Wali</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between gap-4"><dt class="text-gray-500">Nama</dt><dd class="font-medium text-gray-900">{{ $student->parent_name ?? '-' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-gray-500">No. HP</dt><dd class="font-medium text-gray-900">{{ $student->parent_phone ?? '-' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-gray-500">Email</dt><dd class="font-medium text-gray-900 break-all text-right">{{ $student->parent_email ?? '-' }}</dd></div>
                </dl>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-gray-900">Riwayat Absensi Terakhir</h3>
                <a href="{{ route('attendance.index', ['class_id' => $student->class_id]) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">Lihat semua</a>
            </div>

            @if ($student->attendance->isEmpty())
                <p class="text-sm text-gray-500">Belum ada catatan absensi.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($student->attendance as $att)
                                <tr>
                                    <td class="px-4 py-2.5 text-sm text-gray-700">{{ $att->date->translatedFormat('d M Y') }}</td>
                                    <td class="px-4 py-2.5">
                                        @php
                                            $map = ['hadir' => 'bg-emerald-100 text-emerald-700', 'sakit' => 'bg-orange-100 text-orange-700', 'izin' => 'bg-sky-100 text-sky-700', 'alpa' => 'bg-red-100 text-red-700', 'terlambat' => 'bg-amber-100 text-amber-700'];
                                        @endphp
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $map[$att->status] }}">{{ App\Models\Attendance::STATUS_LABELS[$att->status] }}</span>
                                    </td>
                                    <td class="px-4 py-2.5 text-sm text-gray-600">{{ $att->note ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

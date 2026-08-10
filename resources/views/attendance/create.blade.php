<x-app-layout>
    @section('title', 'Input Absensi')
    @section('page-title', 'Input Absensi')

    <div class="space-y-6">
        <form method="GET" action="{{ route('attendance.create') }}" class="bg-white rounded-2xl border border-gray-200 p-5">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                <div>
                    <x-input-label for="class_id" :value="__('Kelas')" />
                    <select id="class_id" name="class_id" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white">
                        @foreach ($classes as $cls)
                            <option value="{{ $cls->id }}" @selected($classId == $cls->id)>{{ $cls->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="date" :value="__('Tanggal')" />
                    <x-text-input id="date" name="date" type="date" class="mt-1 block w-full" :value="$date" max="{{ now()->toDateString() }}" />
                </div>
                <div>
                    <button type="submit" class="w-full px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition-colors">Muat Daftar Siswa</button>
                </div>
            </div>
        </form>

        @if ($students->isNotEmpty())
            <form method="POST" action="{{ route('attendance.store') }}">
                @csrf
                <input type="hidden" name="class_id" value="{{ $classId }}">
                <input type="hidden" name="date" value="{{ $date }}">

                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm text-gray-600">
                        {{ $students->count() }} siswa &middot; <span class="font-medium text-gray-900">{{ $classes->firstWhere('id', $classId)?->name }}</span> &middot; {{ \Illuminate\Support\Carbon::parse($date)->translatedFormat('l, d M Y') }}
                    </p>
                    @if ($existing->isNotEmpty())
                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">Sudah ada absensi — simpan untuk memperbarui</span>
                    @endif
                </div>

                <div class="space-y-3">
                    @foreach ($students as $student)
                        @php
                            $current = $existing->get($student->id);
                            $currentStatus = $current?->status ?? 'hadir';
                        @endphp
                        <div class="bg-white rounded-2xl border border-gray-200 p-4">
                            <div class="flex items-center gap-3">
                                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 font-semibold text-sm shrink-0">{{ strtoupper(substr($student->name, 0, 1)) }}</span>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $student->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $student->nis ?? 'No NIS' }}</p>
                                </div>
                            </div>

                            <div class="mt-3 grid grid-cols-5 gap-1.5 sm:gap-2">
                                @foreach (App\Models\Attendance::STATUSES as $status)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="statuses[{{ $student->id }}]" value="{{ $status }}" class="peer sr-only"
                                               @checked($currentStatus === $status)>
                                        @php
                                            $map = [
                                                'hadir' => ['Hadir', 'peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:border-emerald-600 bg-white border-gray-200 text-gray-700'],
                                                'sakit' => ['Sakit', 'peer-checked:bg-orange-600 peer-checked:text-white peer-checked:border-orange-600 bg-white border-gray-200 text-gray-700'],
                                                'izin' => ['Izin', 'peer-checked:bg-sky-600 peer-checked:text-white peer-checked:border-sky-600 bg-white border-gray-200 text-gray-700'],
                                                'alpa' => ['Alpa', 'peer-checked:bg-red-600 peer-checked:text-white peer-checked:border-red-600 bg-white border-gray-200 text-gray-700'],
                                                'terlambat' => ['Terlambat', 'peer-checked:bg-amber-600 peer-checked:text-white peer-checked:border-amber-600 bg-white border-gray-200 text-gray-700'],
                                            ];
                                            [$label, $btnClasses] = $map[$status];
                                        @endphp
                                        <span class="flex items-center justify-center px-1 py-2.5 rounded-lg border text-xs font-medium transition-colors {{ $btnClasses }} peer-focus-visible:ring-2 peer-focus-visible:ring-indigo-500">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>

                            <input type="text" name="notes[{{ $student->id }}]" placeholder="Keterangan (opsional) — mis. demam, izin keluarga"
                                   value="{{ $current?->note }}" class="mt-2 w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white">
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <a href="{{ route('attendance.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</a>
                    <x-primary-button class="px-8 py-3 text-base">Simpan Absensi</x-primary-button>
                </div>
            </form>
        @elseif ($classId)
            <div class="bg-white rounded-2xl border border-gray-200 p-10 text-center text-sm text-gray-500">
                Tidak ada siswa aktif di kelas ini.
            </div>
        @endif
    </div>
</x-app-layout>

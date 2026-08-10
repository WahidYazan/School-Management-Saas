<x-app-layout>
    @section('page-title', 'Dashboard')

    @section('title', 'Dashboard')

    <div class="space-y-8">
        @if (isset($schools))
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <div class="mb-6">
                    <h2 class="text-base font-semibold text-gray-900">Semua Sekolah</h2>
                    <p class="mt-1 text-sm text-gray-500">Pilih sekolah untuk mengelola data dan absensinya.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                    @forelse ($schools as $school)
                        <form method="POST" action="{{ route('superadmin.switch-school') }}" class="bg-white rounded-2xl border border-gray-200 p-5 hover:border-indigo-300 hover:shadow-sm transition">
                            @csrf
                            <input type="hidden" name="school_id" value="{{ $school->id }}">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $school->name }}</h3>
                                    <p class="mt-1 text-xs text-gray-500">{{ $school->npsn ? 'NPSN ' . $school->npsn : '-' }}</p>
                                </div>
                                <button type="submit" class="shrink-0 inline-flex items-center px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-xs font-medium hover:bg-indigo-700 transition-colors">Kelola</button>
                            </div>
                            <div class="mt-4 grid grid-cols-3 gap-2 text-center">
                                <div>
                                    <p class="text-lg font-bold text-gray-900">{{ number_format($school->students_count) }}</p>
                                    <p class="text-xs text-gray-500">Siswa</p>
                                </div>
                                <div>
                                    <p class="text-lg font-bold text-gray-900">{{ number_format($school->teachers_count) }}</p>
                                    <p class="text-xs text-gray-500">Guru</p>
                                </div>
                                <div>
                                    <p class="text-lg font-bold text-gray-900">{{ number_format($school->classes_count) }}</p>
                                    <p class="text-xs text-gray-500">Kelas</p>
                                </div>
                            </div>
                        </form>
                    @empty
                        <div class="col-span-full text-center py-10 text-sm text-gray-500">Belum ada sekolah terdaftar.</div>
                    @endforelse
                </div>
            </div>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <a href="{{ route('students.index') }}" class="group bg-white rounded-2xl border border-gray-200 p-5 hover:border-indigo-300 hover:shadow-sm transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Jumlah Siswa</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($studentCount) }}</p>
                        <p class="mt-1 text-xs text-gray-500">Status aktif</p>
                    </div>
                    <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </span>
                </div>
            </a>

            <a href="{{ route('teachers.index') }}" class="group bg-white rounded-2xl border border-gray-200 p-5 hover:border-emerald-300 hover:shadow-sm transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Jumlah Guru</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($teacherCount) }}</p>
                        <p class="mt-1 text-xs text-gray-500">Tenaga pengajar</p>
                    </div>
                    <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </span>
                </div>
            </a>

            @unless (auth()->user()->isStudent())
            <a href="{{ route('attendance.index') }}" class="group bg-white rounded-2xl border border-gray-200 p-5 hover:border-amber-300 hover:shadow-sm transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Absensi Hari Ini</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($attendanceCount) }}</p>
                        <p class="mt-1 text-xs text-gray-500">{{ now()->translatedFormat('l, d M Y') }}</p>
                    </div>
                    <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-amber-100 text-amber-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </span>
                </div>
            </a>
            @endunless

            <div class="bg-white rounded-2xl border border-gray-200 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Statistik Akademik</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($classCount + $subjectCount) }}</p>
                        <p class="mt-1 text-xs text-gray-500">{{ $classCount }} kelas &middot; {{ $subjectCount }} mapel</p>
                    </div>
                    <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-violet-100 text-violet-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M4 21V8l8-4 8 4v13M9 21v-5h6v5M9 10h.01M15 10h.01"></path>
                        </svg>
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @unless (auth()->user()->isStudent())
            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-base font-semibold text-gray-900">Ringkasan Absensi Hari Ini</h2>
                    <a href="{{ route('attendance.create') }}" class="inline-flex items-center gap-1 text-sm font-medium text-indigo-600 hover:text-indigo-700">
                        Input Absensi
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>

                @if ($attendanceCount === 0)
                    <div class="text-center py-10 text-sm text-gray-500">
                        Belum ada data absensi hari ini.
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                        @foreach (['hadir' => ['Hadir', 'bg-emerald-100 text-emerald-700', 'bg-emerald-500'], 'sakit' => ['Sakit', 'bg-orange-100 text-orange-700', 'bg-orange-500'], 'izin' => ['Izin', 'bg-sky-100 text-sky-700', 'bg-sky-500'], 'alpa' => ['Alpa', 'bg-red-100 text-red-700', 'bg-red-500'], 'terlambat' => ['Terlambat', 'bg-amber-100 text-amber-700', 'bg-amber-500']] as $key => [$label, $badge, $bar])
                            <div class="rounded-xl border border-gray-200 p-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $badge }}">{{ $label }}</span>
                                <p class="mt-3 text-2xl font-bold text-gray-900">{{ $attendanceSummary[$key] }}</p>
                                <div class="mt-2 h-1.5 rounded-full bg-gray-100 overflow-hidden">
                                    <div class="h-full rounded-full {{ $bar }}" style="width: {{ $attendanceCount ? round(($attendanceSummary[$key] / $attendanceCount) * 100) : 0 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            @endunless

            <div class="{{ auth()->user()->isStudent() ? 'lg:col-span-3' : '' }} bg-white rounded-2xl border border-gray-200 p-6">
                <h2 class="text-base font-semibold text-gray-900 mb-6">Pengumuman</h2>

                @forelse ($recentAnnouncements as $announcement)
                    <div class="border-l-2 border-indigo-400 pl-4 py-1 mb-4">
                        <h3 class="text-sm font-semibold text-gray-900">{{ $announcement->title }}</h3>
                        <p class="mt-1 text-sm text-gray-600 line-clamp-2">{{ $announcement->body }}</p>
                        <p class="mt-1 text-xs text-gray-400">{{ $announcement->created_at->diffForHumans() }}</p>
                    </div>
                @empty
                    <div class="text-center py-10 text-sm text-gray-500">
                        Belum ada pengumuman.
                    </div>
                @endforelse

                @if (auth()->user()->canManageSchoolData())
                    <a href="{{ route('announcements.create') }}" class="mt-2 inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-700">
                        + Buat pengumuman
                    </a>
                @endif
            </div>
        </div>

        @if (auth()->user()->isTeacher() || auth()->user()->isSuperAdmin())
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <h2 class="text-base font-semibold text-gray-900 mb-6">Distribusi Siswa per Kelas</h2>

                @if ($studentClassDistribution->isEmpty())
                    <div class="text-center py-8 text-sm text-gray-500">Belum ada data siswa.</div>
                @else
                    <div class="space-y-3">
                        @foreach ($studentClassDistribution as $className => $count)
                            <div class="flex items-center gap-4">
                                <span class="w-32 text-sm font-medium text-gray-700 truncate">{{ $className }}</span>
                                <div class="flex-1 h-6 rounded-lg bg-gray-100 overflow-hidden">
                                    <div class="h-full rounded-lg bg-gradient-to-r from-indigo-500 to-violet-500" style="width: {{ round(($count / $studentClassDistribution->max()) * 100) }}%"></div>
                                </div>
                                <span class="w-8 text-right text-sm font-semibold text-gray-900">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @elseif (auth()->user()->isStudent())
            @php $student = auth()->user()->student; @endphp
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <h2 class="text-base font-semibold text-gray-900 mb-1">Kehadiran Kelas Anda</h2>
                <p class="mb-5 text-sm text-gray-500">{{ $student?->class?->name ?? '-' }} &middot; {{ now()->translatedFormat('l, d M Y') }}</p>

                @if (array_sum($classAttendanceSummary) === 0)
                    <div class="text-center py-8 text-sm text-gray-500">Belum ada data kehadiran hari ini.</div>
                @else
                    <div class="grid grid-cols-3 gap-3">
                        @foreach (['izin' => ['Izin', 'bg-sky-100 text-sky-700'], 'terlambat' => ['Terlambat', 'bg-amber-100 text-amber-700'], 'alpa' => ['Alpa', 'bg-red-100 text-red-700']] as $key => [$label, $badge])
                            <div class="rounded-xl border border-gray-200 p-4 text-center">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $badge }}">{{ $label }}</span>
                                <p class="mt-3 text-2xl font-bold text-gray-900">{{ $classAttendanceSummary[$key] }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
        @endif
    </div>
</x-app-layout>

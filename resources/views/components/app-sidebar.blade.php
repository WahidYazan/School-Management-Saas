<nav id="app-sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-gray-200 transform -translate-x-full lg:translate-x-0 transition-transform duration-200">
    <div class="flex flex-col h-full">
        <div class="h-16 flex items-center gap-2 px-5 border-b border-gray-200">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-600 text-white font-bold">P</span>
                <div class="leading-tight">
                    <span class="block text-sm font-semibold text-gray-900">ProductSchool</span>
                    @php
                        $user = auth()->user();
                        $activeSchoolId = session('active_school_id');
                        $activeSchool = $user->isSuperAdmin() && $activeSchoolId ? App\Models\School::find($activeSchoolId) : null;
                    @endphp
                    <span class="block text-xs text-gray-500">
                        @if ($user->isSuperAdmin())
                            {{ $activeSchool?->name ?? 'Semua Sekolah' }}
                        @else
                            {{ $user->school?->name ?? 'SaaS' }}
                        @endif
                    </span>
                </div>
            </a>
            <button id="app-sidebar-close" type="button" class="lg:hidden ml-auto p-1 rounded-lg text-gray-500 hover:bg-gray-100" aria-label="Tutup menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        @if ($user->isSuperAdmin())
            <div class="px-4 pt-5 pb-4 border-b border-gray-200 bg-gradient-to-b from-indigo-50/60 to-transparent">
                <label class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Sekolah Aktif</label>
                <form method="POST" action="{{ route('superadmin.switch-school') }}">
                    @csrf
                    <div class="relative">
                        <select name="school_id" onchange="this.form.submit()"
                                class="w-full appearance-none pl-3.5 pr-9 py-2.5 rounded-xl bg-white border border-indigo-200 text-sm font-medium text-gray-900 cursor-pointer transition-all shadow-sm hover:border-indigo-300 focus:outline-none focus:ring-1 focus:ring-indigo-200 focus:border-indigo-300">
                            @foreach (App\Models\School::orderBy('name')->get() as $school)
                                <option value="{{ $school->id }}" @selected($activeSchoolId == $school->id)>{{ $school->name }}</option>
                            @endforeach
                        </select>
                        <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </form>
                @if ($activeSchoolId)
                    <form method="POST" action="{{ route('superadmin.clear-school') }}" class="mt-2.5">
                        @csrf
                        <button type="submit" class="w-full text-left text-xs font-medium text-gray-500 hover:text-indigo-600 transition-colors">Lihat semua sekolah &rarr;</button>
                    </form>
                @endif
            </div>
        @endif

        <div class="flex-1 overflow-y-auto px-3 py-4 space-y-6">
            <nav class="space-y-1">
                @php
                    $isAdmin = auth()->user()->canManageSchoolData();
                    $role = auth()->user()->role;
                    $navGroups = [
                        'Utama' => [
                            ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z M4 10h16 M9 15l2 2 4-4'],
                        ],
                        'Data Sekolah' => [
                            ['route' => 'students.index', 'label' => 'Siswa', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857 M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                            ['route' => 'teachers.index', 'label' => 'Guru', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'admin' => true],
                        ],
                        'Akademik' => [
                            ['route' => 'assignments.index', 'label' => 'Tugas', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 13l2 2 4-4'],
                            ['route' => 'submissions.index', 'label' => 'Pengumpulan Siswa', 'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'only_for' => ['teacher', 'school_admin', 'super_admin']],
                            ['route' => 'classes.index', 'label' => 'Kelas', 'icon' => 'M3 21h18M4 21V8l8-4 8 4v13M9 21v-5h6v5M9 10h.01M15 10h.01', 'admin' => true],
                            ['route' => 'majors.index', 'label' => 'Jurusan', 'icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z', 'admin' => true],
                            ['route' => 'subjects.index', 'label' => 'Mata Pelajaran', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'admin' => true],
                        ],
                        'Operasional' => [
                            ['route' => 'attendance.index', 'label' => 'Absensi', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'hide_for' => ['student']],
                            ['route' => 'announcements.index', 'label' => 'Pengumuman', 'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
                        ],
                    ];
                @endphp

                @foreach ($navGroups as $group => $links)
                    @php
                        $visibleLinks = array_filter($links, function ($l) use ($isAdmin, $role) {
                            $adminOk = ! isset($l['admin']) || $l['admin'] === $isAdmin;
                            $roleOk = (! isset($l['hide_for']) || ! in_array($role, $l['hide_for'], true))
                                && (! isset($l['only_for']) || in_array($role, $l['only_for'], true));

                            return $adminOk && $roleOk;
                        });
                    @endphp
                    @if (count($visibleLinks) === 0)
                        @continue
                    @endif
                    <div class="mt-4 mb-1 px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ $group }}</div>
                    @foreach ($links as $link)
                        @if (isset($link['admin']) && $link['admin'] !== $isAdmin)
                            @continue
                        @endif
                        @if (isset($link['hide_for']) && in_array($role, $link['hide_for'], true))
                            @continue
                        @endif
                        @if (isset($link['only_for']) && ! in_array($role, $link['only_for'], true))
                            @continue
                        @endif
                        @php
                            $active = request()->routeIs($link['route']) || (str_contains($link['route'], '.') && request()->routeIs($link['route'].'.*'));
                        @endphp
                        <a href="{{ route($link['route']) }}" data-app-sidebar-link
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ $active ? 'bg-indigo-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}"></path>
                            </svg>
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                @endforeach

                @if ($user->isSuperAdmin())
                    <div class="mt-4 mb-1 px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Platform</div>
                    @php
                        $schoolsActive = request()->routeIs('schools.*');
                    @endphp
                    <a href="{{ route('schools.index') }}" data-app-sidebar-link
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ $schoolsActive ? 'bg-indigo-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M5 6l7-3 7 3M4 10v11h16V10M9 6h6"></path>
                        </svg>
                        Sekolah
                    </a>
                @endif
            </nav>
        </div>

        <div class="border-t border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <span class="flex items-center justify-center w-9 h-9 rounded-full bg-gray-100 text-gray-700 font-semibold text-sm">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                <div class="flex-1 min-w-0 leading-tight">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->roleLabel() }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="w-full text-center px-3 py-2 rounded-lg text-sm font-medium text-gray-700 border border-gray-300 hover:bg-gray-100 transition-colors">Keluar</button>
            </form>
        </div>
    </div>
</nav>

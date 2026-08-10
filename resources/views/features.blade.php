@extends('layouts.public')

@section('title', 'Fitur')

@section('content')
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <h1 class="text-4xl lg:text-5xl font-bold text-gray-900">Fitur-Fitur Lengkap</h1>
            <p class="mt-4 text-lg text-gray-600">
                Semua kebutuhan administrasi sekolah dalam satu platform, untuk setiap peran pengguna.
            </p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $features = [
                    [
                        'icon' => 'chart',
                        'title' => 'Dashboard Sekolah',
                        'desc' => 'Ringkasan data dalam satu layar: jumlah siswa aktif, guru, kelas, mata pelajaran, rekap kehadiran hari ini, dan pengumuman terbaru.',
                    ],
                    [
                        'icon' => 'building',
                        'title' => 'Manajemen Sekolah',
                        'desc' => 'Kelola data sekolah secara lengkap, dari nama, NPSN, alamat, hingga kontak. Satu aplikasi untuk banyak sekolah.',
                    ],
                    [
                        'icon' => 'users',
                        'title' => 'Data Siswa & Guru',
                        'desc' => 'Pendataan siswa dan guru yang rapi, lengkap dengan NIP, kontak, kelas, jurusan, dan status aktif.',
                    ],
                    [
                        'icon' => 'book',
                        'title' => 'Kelas & Jurusan',
                        'desc' => 'Bagi siswa ke dalam kelas per tingkat dan jurusan, lengkap dengan penunjukan wali kelas.',
                    ],
                    [
                        'icon' => 'calendar',
                        'title' => 'Absensi Harian',
                        'desc' => 'Catat kehadiran siswa per kelas setiap hari dengan status hadir, sakit, izin, alpa, dan terlambat.',
                    ],
                    [
                        'icon' => 'clipboard',
                        'title' => 'Tugas & Pengumpulan',
                        'desc' => 'Guru membagikan tugas, siswa mengumpulkan jawaban, dan guru bisa menilai serta mengunduhnya.',
                    ],
                    [
                        'icon' => 'megaphone',
                        'title' => 'Pengumuman',
                        'desc' => 'Bagikan informasi ke seluruh sekolah dan tampilkan langsung di dashboard.',
                    ],
                    [
                        'icon' => 'shield',
                        'title' => 'Akses Sesuai Peran',
                        'desc' => 'Super Admin, Admin Sekolah, Guru, Siswa, dan Orang Tua memiliki tampilan dan menu masing-masing.',
                    ],
                    [
                        'icon' => 'mail',
                        'title' => 'Kontak Tim',
                        'desc' => 'Form kontak untuk menghubungi tim sekolah, mudah dan terpusat.',
                    ],
                ];
            @endphp

            @foreach ($features as $feature)
                <div class="p-6 rounded-2xl bg-white border border-gray-200 hover:shadow-lg hover:-translate-y-1 transition-all">
                    <div class="w-10 h-10 mb-4 rounded-lg bg-gray-100 text-gray-900 flex items-center justify-center">
                        @switch($feature['icon'])
                            @case('chart')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                @break
                            @case('building')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                @break
                            @case('users')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                @break
                            @case('book')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                @break
                            @case('calendar')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                @break
                            @case('clipboard')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                                @break
                            @case('megaphone')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                </svg>
                                @break
                            @case('shield')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                @break
                            @case('mail')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                @break
                        @endswitch
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2 text-lg">{{ $feature['title'] }}</h3>
                    <p class="text-sm text-gray-600">{{ $feature['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </section>
@endsection

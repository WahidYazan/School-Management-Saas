@extends('layouts.public')

@section('title', 'Beranda')

@section('content')
    {{-- Hero --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 -z-10 bg-gradient-to-b from-gray-100 to-transparent"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 mb-6 rounded-full text-xs font-medium bg-gray-50 text-gray-900 border border-gray-200">
                <span class="w-2 h-2 rounded-full bg-gray-900 animate-pulse"></span>
                Produk SaaS #1 untuk tim Anda
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-gray-900">
                Tingkatkan Produktivitas
                <span class="text-gray-900">Tim Anda</span>
            </h1>

            <p class="mt-6 max-w-2xl mx-auto text-lg text-gray-600">
                ProductSaaS membantu Anda mengelola produk, tim, dan pelanggan dalam satu platform yang sederhana, cepat, dan aman.
            </p>

            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="/register" class="px-8 py-3 rounded-lg bg-gray-900 text-white font-medium hover:bg-gray-800 transition-colors w-full sm:w-auto text-center">
                    Mulai Gratis
                </a>
                <a href="/features" class="px-8 py-3 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-colors w-full sm:w-auto text-center">
                    Lihat Fitur
                </a>
            </div>

            <div class="mt-14 flex items-center justify-center gap-8 text-sm text-gray-500">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Gratis untuk dicoba
                </span>
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Tanpa kartu kredit
                </span>
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Batal kapan saja
                </span>
            </div>
        </div>
    </section>

    {{-- Fitur Unggulan --}}
    <section class="py-20 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <h2 class="text-3xl font-bold text-gray-900">Mengapa memilih ProductSaaS?</h2>
                <p class="mt-4 text-gray-600">Semua yang Anda butuhkan untuk membangun dan mengembangkan produk, dalam satu tempat.</p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="p-6 rounded-2xl bg-white border border-gray-200 hover:shadow-lg hover:-translate-y-1 transition-all">
                    <div class="w-10 h-10 mb-4 rounded-lg bg-gray-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Cepat &amp; Responsif</h3>
                    <p class="text-sm text-gray-600">Dibangun dengan teknologi modern untuk performa terbaik di semua perangkat.</p>
                </div>

                <div class="p-6 rounded-2xl bg-white border border-gray-200 hover:shadow-lg hover:-translate-y-1 transition-all">
                    <div class="w-10 h-10 mb-4 rounded-lg bg-gray-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Keamanan Terjamin</h3>
                    <p class="text-sm text-gray-600">Data Anda dienkripsi dan dilindungi dengan standar keamanan industri.</p>
                </div>

                <div class="p-6 rounded-2xl bg-white border border-gray-200 hover:shadow-lg hover:-translate-y-1 transition-all">
                    <div class="w-10 h-10 mb-4 rounded-lg bg-gray-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Kolaborasi Tim</h3>
                    <p class="text-sm text-gray-600">Kerja sama tanpa batas dengan manajemen peran dan izin yang fleksibel.</p>
                </div>

                <div class="p-6 rounded-2xl bg-white border border-gray-200 hover:shadow-lg hover:-translate-y-1 transition-all">
                    <div class="w-10 h-10 mb-4 rounded-lg bg-gray-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Analitik Real-time</h3>
                    <p class="text-sm text-gray-600">Pantau performa produk Anda dengan dashboard analitik yang informatif.</p>
                </div>

                <div class="p-6 rounded-2xl bg-white border border-gray-200 hover:shadow-lg hover:-translate-y-1 transition-all">
                    <div class="w-10 h-10 mb-4 rounded-lg bg-gray-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h9.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01.502.418 2 2 0 00.786.786l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Integrasi Mudah</h3>
                    <p class="text-sm text-gray-600">Hubungkan dengan alat favorit Anda melalui API dan integrasi yang tersedia.</p>
                </div>

                <div class="p-6 rounded-2xl bg-white border border-gray-200 hover:shadow-lg hover:-translate-y-1 transition-all">
                    <div class="w-10 h-10 mb-4 rounded-lg bg-gray-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 0a2.978 2.978 0 01-3.475.434l-1.711-1.711a3 3 0 00-3.475.434L2.636 11.364a1 1 0 000 1.414l8.586 8.586a1 1 0 001.414 0l3.535-3.535a3 3 0 00.434-3.475l-1.711-1.711a2.978 2.978 0 01.434-3.475l3.536-3.536a1 1 0 011.414 0l3.536 3.536a1 1 0 010 1.414z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Dukungan 24/7</h3>
                    <p class="text-sm text-gray-600">Tim dukungan kami siap membantu Anda kapan saja, di mana saja.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl bg-gray-900 p-10 lg:p-14 text-center text-white">
                <h2 class="text-3xl font-bold mb-4">Siap memulai perjalanan Anda?</h2>
                <p class="mb-8 text-gray-100">Gabung dengan ribuan tim yang sudah lebih produktif bersama ProductSaaS.</p>
                <a href="/register" class="inline-block px-8 py-3 rounded-lg bg-white text-gray-900 font-semibold hover:bg-gray-50 transition-colors">
                    Daftar Gratis Sekarang
                </a>
            </div>
        </div>
    </section>
@endsection

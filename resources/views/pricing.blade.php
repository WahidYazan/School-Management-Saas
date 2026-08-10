@extends('layouts.public')

@section('title', 'Harga')

@section('content')
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <h1 class="text-4xl lg:text-5xl font-bold text-gray-900">Harga Transparan</h1>
            <p class="mt-4 text-lg text-gray-600">
                Pilih paket yang sesuai dengan kebutuhan tim Anda. Upgrade atau downgrade kapan saja.
            </p>
        </div>

        <div class="grid lg:grid-cols-3 gap-8 max-w-5xl mx-auto items-stretch">
            @php
                $plans = [
                    [
                        'name' => 'Starter',
                        'price' => 'Gratis',
                        'period' => '',
                        'desc' => 'Untuk individu dan proyek kecil.',
                        'features' => ['1 proyek', 'Hingga 3 anggota tim', 'Penyimpanan 1 GB', 'Dukungan komunitas'],
                        'featured' => false,
                        'cta' => 'Mulai Gratis',
                        'cta_class' => 'border border-gray-300 text-gray-700 hover:bg-gray-50',
                    ],
                    [
                        'name' => 'Pro',
                        'price' => 'Rp 99.000',
                        'period' => '/bulan',
                        'desc' => 'Untuk tim yang sedang berkembang.',
                        'features' => ['Proyek tanpa batas', 'Hingga 20 anggota tim', 'Penyimpanan 100 GB', 'Analitik lanjutan', 'Dukungan prioritas', 'Integrasi API'],
                        'featured' => true,
                        'cta' => 'Pilih Pro',
                        'cta_class' => 'bg-gray-900 text-white hover:bg-gray-800',
                    ],
                    [
                        'name' => 'Enterprise',
                        'price' => 'Kustom',
                        'period' => '',
                        'desc' => 'Untuk organisasi berskala besar.',
                        'features' => ['Semua fitur Pro', 'Anggota tim tanpa batas', 'Penyimpanan tanpa batas', 'SSO & SAML', 'SLA 99,9%', 'Manajer akun khusus'],
                        'featured' => false,
                        'cta' => 'Hubungi Kami',
                        'cta_class' => 'border border-gray-300 text-gray-700 hover:bg-gray-50',
                    ],
                ];
            @endphp

            @foreach ($plans as $plan)
                <div class="relative flex flex-col p-8 rounded-2xl border {{ $plan['featured'] ? 'border-gray-900 bg-gray-50/50 shadow-xl lg:scale-105' : 'border-gray-200 bg-white' }}">
                    @if ($plan['featured'])
                        <span class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-1 rounded-full bg-gray-900 text-white text-xs font-semibold">Paling Populer</span>
                    @endif

                    <h3 class="text-lg font-semibold text-gray-900">{{ $plan['name'] }}</h3>
                    <p class="mt-1 text-sm text-gray-600">{{ $plan['desc'] }}</p>

                    <div class="mt-6 mb-8">
                        <span class="text-4xl font-bold text-gray-900">{{ $plan['price'] }}</span>
                        @if ($plan['period'])
                            <span class="text-gray-500">{{ $plan['period'] }}</span>
                        @endif
                    </div>

                    <ul class="space-y-3 mb-8 flex-1">
                        @foreach ($plan['features'] as $feature)
                            <li class="flex items-start gap-3 text-sm text-gray-700">
                                <svg class="w-5 h-5 text-gray-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>

                    <a href="/contact" class="block text-center px-4 py-3 rounded-lg font-medium transition-colors {{ $plan['cta_class'] }}">
                        {{ $plan['cta'] }}
                    </a>
                </div>
            @endforeach
        </div>

        <p class="mt-12 text-center text-sm text-gray-500">
            Ada pertanyaan tentang harga? <a href="/contact" class="text-gray-900 hover:underline">Hubungi kami</a>.
        </p>
    </section>
@endsection

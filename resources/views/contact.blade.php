@extends('layouts.public')

@section('title', 'Kontak')

@section('content')
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
        <div class="max-w-2xl mx-auto text-center mb-16">
            <h1 class="text-4xl lg:text-5xl font-bold text-gray-900">Hubungi Kami</h1>
            <p class="mt-4 text-lg text-gray-600">
                Punya pertanyaan atau butuh bantuan? Tim kami siap membantu Anda.
            </p>
        </div>

        <div class="grid lg:grid-cols-2 gap-12 max-w-4xl mx-auto">
            {{-- Info kontak --}}
            <div class="space-y-6">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Email</h3>
                        <a href="mailto:halo@productsaas.com" class="text-sm text-gray-600 hover:text-gray-900">halo@productsaas.com</a>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Telepon</h3>
                        <a href="tel:+6281234567890" class="text-sm text-gray-600 hover:text-gray-900">+62 812 3456 7890</a>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Alamat</h3>
                        <p class="text-sm text-gray-600">Jl. Teknologi No. 123, Jakarta, Indonesia</p>
                    </div>
                </div>
            </div>

            {{-- Form kontak --}}
            @if (session('status'))
                <div class="lg:col-span-2 mb-4 px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-700">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('contact.submit') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block mb-1.5 text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" id="name" name="name" required value="{{ old('name') }}"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                </div>

                <div>
                    <label for="email" class="block mb-1.5 text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" required value="{{ old('email') }}"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                </div>

                <div>
                    <label for="subject" class="block mb-1.5 text-sm font-medium text-gray-700">Subjek</label>
                    <input type="text" id="subject" name="subject" required value="{{ old('subject') }}"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                </div>

                <div>
                    <label for="message" class="block mb-1.5 text-sm font-medium text-gray-700">Pesan</label>
                    <textarea id="message" name="message" rows="5" required
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent">{{ old('message') }}</textarea>
                </div>

                <button type="submit"
                    class="w-full px-6 py-3 rounded-lg bg-gray-900 text-white font-medium hover:bg-gray-800 transition-colors">
                    Kirim Pesan
                </button>
            </form>
        </div>
    </section>
@endsection

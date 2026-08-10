<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'ProductSaaS')) — {{ config('app.name', 'ProductSaaS') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white text-gray-900 antialiased min-h-screen flex flex-col">

    <x-navbar />

    <main class="flex-1">
        @yield('content')
    </main>

    <footer class="border-t border-gray-200 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex items-center gap-2">
                    <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-900 text-white font-bold">P</span>
                    <span class="font-semibold text-gray-900">ProductSaaS</span>
                </div>

                <div class="flex flex-wrap items-center gap-6 text-sm">
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">Beranda</a>
                    <a href="{{ route('features') }}" class="text-gray-600 hover:text-gray-900">Fitur</a>
                    <a href="{{ route('pricing') }}" class="text-gray-600 hover:text-gray-900">Harga</a>
                    <a href="{{ route('contact') }}" class="text-gray-600 hover:text-gray-900">Kontak</a>
                </div>

                <p class="text-sm text-gray-500">&copy; {{ date('Y') }} ProductSaaS. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

</body>
</html>

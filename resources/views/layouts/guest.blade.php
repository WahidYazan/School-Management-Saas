<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ProductSaaS') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50">
        <div class="min-h-screen flex flex-col items-center justify-center px-4 py-10">
            <div class="w-full {{ ($wide ?? $attributes->get('wide')) ? 'max-w-lg' : 'max-w-md' }}">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 justify-center mb-6">
                    <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-indigo-600 text-white font-bold text-lg shadow-sm">P</span>
                    <span class="text-xl font-semibold text-gray-900">{{ config('app.name', 'ProductSaaS') }}</span>
                </a>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                    {{ $slot }}
                </div>

                <p class="mt-6 text-center text-xs text-gray-400">&copy; {{ date('Y') }} {{ config('app.name', 'ProductSaaS') }}. Semua hak dilindungi.</p>
            </div>
        </div>
    </body>
</html>

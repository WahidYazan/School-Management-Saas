<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'ProductSchool')) — {{ config('app.name', 'ProductSchool') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900 min-h-screen">
        <div id="app-sidebar-backdrop" class="fixed inset-0 z-30 bg-gray-900/50 hidden lg:hidden"></div>

        <x-app-sidebar />

        <div class="lg:pl-64 flex flex-col min-h-screen">
            <header class="sticky top-0 z-20 bg-white/80 backdrop-blur-md border-b border-gray-200">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center gap-3">
                        <button id="app-sidebar-toggle" type="button" class="lg:hidden p-2 rounded-lg text-gray-700 hover:bg-gray-100" aria-label="Buka menu">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <h1 class="text-lg font-semibold text-gray-900 truncate">
                            @yield('page-title', 'Dashboard')
                        </h1>
                    </div>

                    <a href="{{ route('dashboard') }}" class="hidden sm:inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V10"></path>
                        </svg>
                        Beranda
                    </a>
                </div>
            </header>

            <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                @if (session('success'))
                    <div class="mb-6 flex items-center justify-between gap-3 px-4 py-3 rounded-xl bg-green-50 border border-green-200 text-sm text-green-800">
                        <span>{{ session('success') }}</span>
                        <button type="button" class="text-green-600 hover:text-green-900 font-bold" onclick="this.parentElement.remove()">&times;</button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 flex items-center justify-between gap-3 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-sm text-red-800">
                        <span>{{ session('error') }}</span>
                        <button type="button" class="text-red-600 hover:text-red-900 font-bold" onclick="this.parentElement.remove()">&times;</button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-sm text-red-800">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>

        <script>
            (function () {
                var sidebar = document.getElementById('app-sidebar');
                var backdrop = document.getElementById('app-sidebar-backdrop');
                var toggle = document.getElementById('app-sidebar-toggle');
                var close = document.getElementById('app-sidebar-close');
                if (!sidebar) return;

                function open() {
                    sidebar.classList.remove('-translate-x-full');
                    backdrop.classList.remove('hidden');
                }

                function closeMenu() {
                    sidebar.classList.add('-translate-x-full');
                    backdrop.classList.add('hidden');
                }

                if (toggle) toggle.addEventListener('click', open);
                if (close) close.addEventListener('click', closeMenu);
                if (backdrop) backdrop.addEventListener('click', closeMenu);
                sidebar.querySelectorAll('[data-app-sidebar-link]').forEach(function (link) {
                    link.addEventListener('click', closeMenu);
                });
            })();
        </script>
    </body>
</html>

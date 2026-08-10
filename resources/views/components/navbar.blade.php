<nav id="navbar" class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center gap-8">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-600 text-white font-bold">P</span>
                    <span class="text-lg font-semibold text-gray-900">ProductSaaS</span>
                </a>

                <nav class="hidden md:flex items-center gap-6">
                    <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Beranda</a>
                    <a href="{{ route('features') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Fitur</a>
                    <a href="{{ route('pricing') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Harga</a>
                    <a href="{{ route('contact') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Kontak</a>
                </nav>
            </div>

            <div class="hidden md:flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Dashboard</a>
                    <a href="{{ route('profile.edit') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm font-medium px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Masuk</a>
                    <a href="{{ route('register') }}" class="text-sm font-medium px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition-colors">Daftar Gratis</a>
                @endauth
            </div>

            <button id="navbar-menu-toggle" type="button" class="md:hidden inline-flex items-center justify-center p-2 rounded-lg text-gray-700 hover:bg-gray-100" aria-label="Menu" aria-expanded="false">
                <svg id="navbar-icon-open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                <svg id="navbar-icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    <div id="navbar-mobile-menu" class="md:hidden hidden border-t border-gray-200">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('dashboard') }}" data-navbar-link class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100">Beranda</a>
            <a href="{{ route('features') }}" data-navbar-link class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100">Fitur</a>
            <a href="{{ route('pricing') }}" data-navbar-link class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100">Harga</a>
            <a href="{{ route('contact') }}" data-navbar-link class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100">Kontak</a>
            <div class="pt-3 pb-1 border-t border-gray-200 space-y-2">
                @auth
                    <a href="{{ route('profile.edit') }}" data-navbar-link class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100">Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" data-navbar-link class="w-full text-center px-3 py-2 rounded-lg text-sm font-medium text-gray-700 border border-gray-300 hover:bg-gray-100">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" data-navbar-link class="block text-center px-3 py-2 rounded-lg text-sm font-medium text-gray-700 border border-gray-300 hover:bg-gray-100">Masuk</a>
                    <a href="{{ route('register') }}" data-navbar-link class="block text-center px-3 py-2 rounded-lg text-sm font-medium bg-indigo-600 text-white hover:bg-indigo-700">Daftar Gratis</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<script>
    (function () {
        var nav = document.getElementById('navbar');
        if (!nav) return;

        var toggle = document.getElementById('navbar-menu-toggle');
        var menu = document.getElementById('navbar-mobile-menu');
        var iconOpen = document.getElementById('navbar-icon-open');
        var iconClose = document.getElementById('navbar-icon-close');

        function close() {
            menu.classList.add('hidden');
            iconOpen.classList.remove('hidden');
            iconClose.classList.add('hidden');
            toggle.setAttribute('aria-expanded', 'false');
        }

        toggle.addEventListener('click', function () {
            var isOpen = toggle.getAttribute('aria-expanded') === 'true';
            if (isOpen) {
                close();
            } else {
                menu.classList.remove('hidden');
                iconOpen.classList.add('hidden');
                iconClose.classList.remove('hidden');
                toggle.setAttribute('aria-expanded', 'true');
            }
        });

        nav.querySelectorAll('[data-navbar-link]').forEach(function (link) {
            link.addEventListener('click', close);
        });
    })();
</script>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AmikomEventHub - Temukan Event Seru!</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
        }
    </style>
    <!-- PWA Config -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#4f46e5">
</head>

<body class="bg-slate-50 text-slate-900 overflow-x-hidden">

    <!-- Navigation Header (Responsive Mobile & Desktop) -->
    <nav id="navbar"
        class="glass sticky top-3 z-50 mx-2 sm:mx-4 px-3 sm:px-6 py-3 rounded-2xl border border-white/20 shadow-lg flex justify-between items-center transition-all duration-300">
        <a href="/" class="flex items-center gap-2 min-w-0">
            <div
                class="w-9 h-9 sm:w-10 sm:h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-lg sm:text-xl shrink-0">
                AH</div>
            <span class="text-sm sm:text-xl font-bold tracking-tight truncate">AmikomEventHub</span>
        </a>
        <div class="hidden md:flex gap-8 font-medium">
            <a href="/#events" class="hover:text-indigo-600 transition">Jelajahi</a>
            <a href="/#categories" class="hover:text-indigo-600 transition">Kategori</a>
            <a href="/#about" class="hover:text-indigo-600 transition">Tentang Kami</a>
        </div>
        <div class="flex items-center gap-1.5 sm:gap-3 shrink-0">
            @if(auth()->check())
                <a href="{{ route('ticket') }}" class="px-3 py-2 sm:px-4 sm:py-2 bg-indigo-50 text-indigo-600 rounded-xl font-bold hover:bg-indigo-100 transition text-xs sm:text-sm flex items-center gap-1">
                    <span>🎟️</span> Tiket Saya
                </a>

                @php
                    $role = auth()->user()->role ?? 'user';
                @endphp
                @if(in_array($role, ['superadmin', 'admin', 'organizer', 'panitia']))
                    <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 sm:px-4 sm:py-2 bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition text-xs sm:text-sm flex items-center gap-1">
                        <span>👑</span> Dashboard
                    </a>
                @endif

                <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-2.5 py-2 text-slate-500 hover:text-rose-600 font-bold text-xs sm:text-sm transition">
                        Keluar
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="px-2.5 py-2 text-indigo-600 hover:text-indigo-800 font-bold text-xs sm:text-sm transition">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="px-3.5 py-2 sm:px-5 sm:py-2.5 bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition text-xs sm:text-sm flex items-center gap-1 whitespace-nowrap">
                    <span>✨</span> Daftar Akun
                </a>
            @endif
        </div>
    </nav>

    @yield('content')

    <!-- Footer -->
    <footer class="bg-indigo-900 text-indigo-100 py-20 px-6 mt-20">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-5 gap-12">
            <div class="space-y-4 col-span-1 md:col-span-2">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-indigo-900 font-bold text-xl">
                        AH</div>
                    <span class="text-2xl font-bold text-white">AmikomEventHub</span>
                </div>
                <p class="max-w-xs text-indigo-300">Platform reservasi tiket event online terbaik untuk mahasiswa dan penyelenggara profesional.</p>
            </div>

            <div>
                <h4 class="text-white font-bold mb-6">Navigasi</h4>
                <ul class="space-y-4">
                    <li><a href="/" class="hover:text-white transition">Home</a></li>
                    <li><a href="/#events" class="hover:text-white transition">Semua Event</a></li>
                    <li><a href="/#about" class="hover:text-white transition">Tentang Kami</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold mb-6">Kategori</h4>
                <ul class="space-y-4">
                    <li><a href="/?category=seminar-it" class="hover:text-white transition">Seminar IT</a></li>
                    <li><a href="/?category=entertainment" class="hover:text-white transition">Entertainment</a></li>
                    <li><a href="/?category=kompetisi" class="hover:text-white transition">Kompetisi</a></li>
                    <li><a href="/?category=hiburan" class="hover:text-white transition">Hiburan</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold mb-6">Hubungi Kami</h4>
                <ul class="space-y-4">
                    <li><a href="mailto:support@eventtiket.com" class="hover:text-white transition">support@eventtiket.com</a></li>
                    <li><a href="https://wa.me/6281234567890" class="hover:text-white transition">+62 812 3456 7890</a></li>
                </ul>
            </div>
        </div>

        <div class="max-w-7xl mx-auto pt-12 mt-12 border-t border-indigo-800 text-center text-indigo-400 text-sm">
            &copy; 2024 AmikomEventHub. Built with Laravel & Tailwind CSS.
        </div>
    </footer>

    <!-- Service Worker Script -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js').then(function(registration) {
                    console.log('PWA ServiceWorker registered with scope: ', registration.scope);
                }, function(err) {
                    console.log('PWA ServiceWorker registration failed: ', err);
                });
            });
        }
    </script>
</body>

</html>
@extends('layouts.app')

@section('content')

   <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-6 py-20 flex flex-col md:flex-row items-center gap-12">
        <div class="flex-1 space-y-8">
            <span
                class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-sm font-bold uppercase tracking-wider">#1
                Event Platform</span>
            <h1 class="text-5xl md:text-7xl font-extrabold leading-tight">
                Temukan & Pesan <span class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">Tiket Event</span> Impianmu.
            </h1>
            <p class="text-lg text-slate-500 max-w-lg leading-relaxed">
                Dari konser musik hingga workshop teknologi, semua ada di genggamanmu. Pesan aman & cepat dengan
                Midtrans.
            </p>
            <div class="flex gap-4">
                <a href="#events"
                    class="px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-2xl font-bold text-lg shadow-xl shadow-indigo-200 hover:shadow-indigo-300 hover:scale-105 transition-all">
                    Mulai Jelajah
                </a>
                <a href="#about"
                    class="px-8 py-4 border-2 border-slate-200 bg-white text-slate-700 rounded-2xl font-bold text-lg hover:border-indigo-600 hover:text-indigo-600 transition shadow-sm">
                    Tentang Kami
                </a>
            </div>
        </div>
        <div class="flex-1 relative">
            <div
                class="absolute -top-10 -left-10 w-64 h-64 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob">
            </div>
            <div
                class="absolute -bottom-10 -right-10 w-64 h-64 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000">
            </div>
            <img src="assets/concert.png" alt="Concert"
                class="rounded-[2rem] shadow-2xl relative z-10 w-full object-cover aspect-[4/5] object-center border-4 border-white"
                onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=800&auto=format&fit=crop'">

            <div class="absolute -bottom-6 -left-6 glass p-6 rounded-2xl shadow-xl z-20 border border-white">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Terverifikasi</p>
                        <p class="font-extrabold text-slate-800">Pembayaran Aman via Midtrans</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Events Grid & Filter Categories -->
    <section id="events" class="max-w-7xl mx-auto px-6 py-20">
        <!-- Section Header & Filter Kategori -->
        <div id="categories" class="text-center max-w-xl mx-auto mb-12 scroll-mt-24">
            <span class="inline-block px-4 py-1.5 bg-indigo-50 text-indigo-700 rounded-full text-xs font-bold uppercase tracking-wider mb-3">Pilihan Event</span>
            <h2 class="text-4xl font-extrabold text-slate-800 leading-tight">Jelajahi Berdasarkan Kategori</h2>
        </div>

        <!-- Blok Navigasi Filter Kategori -->
        <div class="mb-12 flex flex-wrap gap-3 justify-center">
            <a href="/#events" class="px-6 py-3 {{ !request('category') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200 font-bold' : 'bg-white text-slate-600 border border-slate-200 hover:border-indigo-600 hover:text-indigo-600' }} rounded-2xl font-bold transition-all text-sm">Semua Kategori</a>
            @foreach($categories as $cat)
                <a href="/?category={{ $cat->slug }}#events" 
                   class="px-6 py-3 {{ request('category') === $cat->slug ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-white text-slate-600 border border-slate-200 hover:border-indigo-600 hover:text-indigo-600' }} rounded-2xl font-bold transition-all text-sm">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($events as $event)
            <div
                class="group bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-300 overflow-hidden">
                <div class="relative overflow-hidden aspect-[3/4]">
                    <img src="{{ ($event->poster_path && Storage::disk('public')->exists($event->poster_path))
                    ? asset('storage/' . $event->poster_path)
                         : 'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?w=600&auto=format&fit=crop' }}" alt="{{ $event->title }}"
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div
                        class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur rounded-lg text-xs font-bold uppercase text-indigo-600">
                        {{ $event->category->name ?? 'General' }}</div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2 group-hover:text-indigo-600 transition">{{ $event->title }}</h3>
                    <div class="flex items-center gap-2 text-slate-500 text-sm mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ \Carbon\Carbon::parse($event->date)->format('d-m-Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t">
                        <span class="text-2xl font-black text-indigo-600">Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                        <a href="{{ route('events.show', $event->id) }}" class="px-5 py-2 bg-indigo-50 text-indigo-600 rounded-xl font-bold hover:bg-indigo-600 hover:text-white transition">Lihat Detail</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- Tentang Kami Section -->
    <section id="about" class="max-w-7xl mx-auto px-6 py-20 border-t border-slate-100 scroll-mt-24">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-xs font-bold uppercase tracking-wider">Tentang Kami</span>
                <h2 class="text-4xl md:text-5xl font-extrabold text-slate-800 leading-tight">Platform Event & Reservasi Tiket Terpercaya</h2>
                <p class="text-slate-600 leading-relaxed text-base">
                    <strong>AmikomEventHub</strong> diciptakan untuk mempermudah civitas akademika, komunitas, dan umum dalam menemukan, mendaftar, serta mengelola tiket berbagai kegiatan menarik dari seminar teknologi hingga pentas hiburan.
                </p>
                <div class="grid grid-cols-2 gap-6 pt-4">
                    <div class="p-6 bg-indigo-50/60 rounded-3xl border border-indigo-100">
                        <div class="text-3xl font-black text-indigo-600">100%</div>
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">Pembayaran Aman</div>
                    </div>
                    <div class="p-6 bg-purple-50/60 rounded-3xl border border-purple-100">
                        <div class="text-3xl font-black text-purple-600">Instant</div>
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">E-Ticket & QR Code</div>
                    </div>
                </div>
            </div>
            <div class="relative">
                <div class="bg-gradient-to-tr from-indigo-600 via-purple-600 to-pink-600 p-8 md:p-10 rounded-[2.5rem] text-white shadow-2xl space-y-6">
                    <h3 class="text-2xl font-bold">Keunggulan AmikomEventHub</h3>
                    <ul class="space-y-4 text-sm font-medium">
                        <li class="flex items-center gap-3">
                            <span class="w-7 h-7 bg-white/20 rounded-full flex items-center justify-center font-bold text-xs">✓</span>
                            <span>Integrasi Pembayaran Otomatis via Midtrans</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="w-7 h-7 bg-white/20 rounded-full flex items-center justify-center font-bold text-xs">✓</span>
                            <span>Penerbitan E-Tiket Instan dengan QR Code unik</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="w-7 h-7 bg-white/20 rounded-full flex items-center justify-center font-bold text-xs">✓</span>
                            <span>Sistem Check-in & Scanner QR terintegrasi Panitia</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Trusted Partners Section (Perfect Centering & Real Amikom Organizations) -->
    <section class="max-w-7xl mx-auto px-6 py-20 border-t border-slate-100">
        <div class="text-center max-w-xl mx-auto mb-16">
            <span class="inline-block px-4 py-1.5 bg-indigo-50 text-indigo-700 rounded-full text-xs font-bold uppercase tracking-wider mb-3">Kolaborasi Terbaik</span>
            <h2 class="text-4xl font-extrabold text-slate-800 leading-tight">Partner & Sponsor Resmi</h2>
            <p class="text-sm text-slate-500 mt-2">Platform kami didukung oleh berbagai komunitas, UKM, dan instansi terpercaya di Amikom.</p>
        </div>

        <!-- Flexbox Container for Perfect Centering -->
        <div class="flex flex-wrap justify-center gap-6 items-center max-w-6xl mx-auto">
            @php
                $realPartners = [
                    ['name' => 'WCS Amikom', 'subtitle' => 'Web Community System', 'icon' => '🌐', 'color' => 'from-indigo-600 to-blue-600'],
                    ['name' => 'Abysena Tech', 'subtitle' => 'Technology Partner', 'icon' => '⚡', 'color' => 'from-purple-600 to-pink-600'],
                    ['name' => 'AMCC', 'subtitle' => 'Amikom Computer Club', 'icon' => '💻', 'color' => 'from-blue-600 to-cyan-500'],
                    ['name' => 'KOMA Amikom', 'subtitle' => 'Komunitas Multimedia', 'icon' => '🎬', 'color' => 'from-amber-500 to-orange-600'],
                    ['name' => 'FOSSIL Amikom', 'subtitle' => 'Open Source League', 'icon' => '🐧', 'color' => 'from-emerald-500 to-teal-600'],
                    ['name' => 'BEM Amikom', 'subtitle' => 'Badan Eksekutif Mhs', 'icon' => '🏛️', 'color' => 'from-rose-500 to-red-600'],
                ];
            @endphp

            @foreach($realPartners as $partner)
                <div class="w-[170px] sm:w-[190px] bg-white rounded-3xl p-6 flex flex-col items-center justify-center border border-slate-200/80 hover:border-indigo-500/40 shadow-sm hover:shadow-xl hover:shadow-indigo-500/10 hover:-translate-y-1.5 transition-all duration-300 group cursor-pointer relative overflow-hidden">
                    <div class="w-14 h-14 bg-gradient-to-tr {{ $partner['color'] }} rounded-2xl flex items-center justify-center text-white text-2xl shadow-md group-hover:scale-110 transition-transform duration-300 mb-3">
                        {{ $partner['icon'] }}
                    </div>
                    <span class="text-xs font-black text-slate-800 group-hover:text-indigo-600 text-center uppercase tracking-wide truncate w-full transition">{{ $partner['name'] }}</span>
                    <span class="text-[10px] font-bold text-slate-400 mt-1 text-center truncate w-full">{{ $partner['subtitle'] }}</span>
                </div>
            @endforeach
        </div>
    </section>

@endsection
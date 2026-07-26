@extends('layouts.app')

@section('content')
<main class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Left: Poster -->
        <div class="lg:col-span-1">
            <div class="sticky top-32">
                <img src="{{ ($event->poster_path && Storage::disk('public')->exists($event->poster_path))
                  ? asset('storage/' . $event->poster_path)
                  : 'https://placehold.co/200x600' }}" alt="{{ $event->title }}" class="w-full rounded-[2.5rem] shadow-2xl border-8 border-white object-cover aspect-[3/4]">
                <div class="mt-8 p-6 bg-white rounded-3xl border border-slate-100 shadow-sm">
                    <h4 class="font-bold mb-4">Penyelenggara</h4>
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold">
                            AB</div>
                        <div>
                            <p class="font-bold text-slate-800">ABP Productions</p>
                            <p class="text-xs text-slate-500">Verified Organizer</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Details -->
        <div class="lg:col-span-2 space-y-12">
            <div class="space-y-4">
                <span
                    class="px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-sm font-bold uppercase tracking-wider">{{ $event->category->name }}</span>
                <h1 class="text-4xl md:text-5xl font-black leading-tight">{{ $event->title }}</h1>
                <div class="flex flex-wrap gap-6 text-slate-500 font-medium">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span>{{ \Carbon\Carbon::parse($event->date)->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>{{ $event->location }}</span>
                    </div>
                </div>
            </div>

            <div class="prose prose-slate max-w-none">
                <h3 class="text-2xl font-bold mb-4">Deskripsi Event</h3>
                <p class="text-lg text-slate-600 leading-relaxed">
                    {{ $event->description }}
                </p>
            </div>

            <div
                class="bg-indigo-600 rounded-[2.5rem] p-8 md:p-12 text-white shadow-2xl shadow-indigo-200 relative overflow-hidden">
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
                    <div>
                        <p class="text-indigo-200 font-bold uppercase tracking-widest text-sm mb-2">Harga Tiket</p>
                        <h2 class="text-5xl font-black">Rp {{ number_format($event->price, 0, ',', '.') }} <span class="text-lg font-medium text-indigo-200">/
                                orang</span></h2>
                        <p class="mt-4 text-indigo-100 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Sisa stok: <span class="font-bold underline">{{ $event->stock }} Tiket lagi!</span>
                        </p>
                    </div>
                    <div>
                        <a href="{{url('checkout/'.$event->id)}}"
                            class="inline-block px-10 py-5 bg-white text-indigo-600 rounded-2xl font-black text-xl hover:scale-105 transition-transform shadow-xl">
                            Pesan Sekarang
                        </a>
                    </div>
                </div>
                <!-- Decoration -->
                <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-white opacity-10 rounded-full"></div>
                <div class="absolute -left-10 -top-10 w-32 h-32 bg-indigo-400 opacity-20 rounded-full"></div>
            </div>

            <div class="space-y-4">
                <h3 class="text-xl font-bold">Kebijakan Tiket</h3>
                <ul class="space-y-3 text-slate-500">
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        E-Ticket akan dikirimkan otomatis setelah pembayaran berhasil.
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Tiket dapat discan di pintu masuk (Check-in).
                    </li>
                    <li class="flex items-start gap-2 text-rose-500">
                        <svg class="w-5 h-5 text-rose-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Tiket yang sudah dibeli tidak dapat direfund.
                    </li>
                </ul>
            </div>
        </div>

        <!-- Reviews Section (Full Width) -->
        <div class="lg:col-span-3 mt-12 pt-12 border-t">
            <h3 class="text-3xl font-black mb-8">Ulasan & Penilaian ({{ $event->reviews->count() }})</h3>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Display Reviews -->
                <div class="space-y-6">
                    @forelse($event->reviews as $review)
                    <div class="p-6 bg-white rounded-3xl border border-slate-100 shadow-sm">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold">
                                {{ strtoupper(substr($review->reviewer_name, 0, 1)) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800">{{ $review->reviewer_name }}</h4>
                                <div class="flex text-yellow-400 text-sm">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            ★
                                        @else
                                            <span class="text-slate-300">★</span>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            <span class="ml-auto text-xs text-slate-400">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-slate-600 italic">"{{ $review->comment }}"</p>
                    </div>
                    @empty
                    <p class="text-slate-500 italic">Belum ada ulasan untuk event ini.</p>
                    @endforelse
                </div>

                <!-- Review Form -->
                <div>
                    @if(\Carbon\Carbon::parse($event->date)->isPast())
                        <div class="p-8 bg-indigo-50 rounded-[2.5rem]">
                            <h4 class="text-2xl font-bold mb-6">Bagikan Pengalaman Anda</h4>
                            
                            @if(session('success'))
                                <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-xl font-bold">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <form action="{{ route('reviews.store', $event->id) }}" method="POST" class="space-y-4">
                                @csrf
                                
                                @if(!auth()->check())
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Anda</label>
                                    <input type="text" name="reviewer_name" class="w-full px-5 py-4 bg-white border-2 border-slate-200 rounded-2xl outline-none" required>
                                </div>
                                @endif

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Penilaian (1-5)</label>
                                    <select name="rating" class="w-full px-5 py-4 bg-white border-2 border-slate-200 rounded-2xl outline-none font-bold text-lg text-slate-700" required>
                                        <option value="5">5 Bintang (Sangat Bagus)</option>
                                        <option value="4">4 Bintang (Bagus)</option>
                                        <option value="3">3 Bintang (Cukup)</option>
                                        <option value="2">2 Bintang (Kurang)</option>
                                        <option value="1">1 Bintang (Sangat Kurang)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Ulasan</label>
                                    <textarea name="comment" rows="3" class="w-full px-5 py-4 bg-white border-2 border-slate-200 rounded-2xl outline-none resize-none" placeholder="Ceritakan pengalaman anda..."></textarea>
                                </div>

                                <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black text-xl shadow-xl hover:bg-indigo-700 transition">Kirim Ulasan</button>
                            </form>
                        </div>
                    @else
                        <div class="p-8 border-2 border-dashed border-slate-200 rounded-[2.5rem] text-center">
                            <h4 class="text-xl font-bold text-slate-500 mb-2">Fitur Ulasan Belum Dibuka</h4>
                            <p class="text-slate-400">Anda dapat memberikan ulasan setelah event selesai dilaksanakan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
@endsection
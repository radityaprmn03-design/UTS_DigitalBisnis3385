@extends('layouts.app')
@section('title', 'Tiket Saya - AmikomEventHub')

@section('content')
<main class="max-w-4xl mx-auto px-6 py-16">
    <div class="mb-10 text-center">
        <span class="px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-xs font-bold uppercase tracking-wider">Tiket Saya</span>
        <h1 class="text-4xl font-black mt-3">E-Ticket Terbitan Anda</h1>
        <p class="text-slate-500 mt-2">Tunjukkan QR Code E-Ticket di bawah ini kepada panitia saat registrasi di lokasi acara.</p>
    </div>

    @forelse($tickets as $trx)
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden mb-8 grid grid-cols-1 md:grid-cols-3">
        <!-- Left Banner / Image -->
        <div class="relative bg-slate-900 p-6 flex flex-col justify-between text-white">
            <div>
                <span class="px-3 py-1 bg-indigo-500 text-white rounded-lg text-[10px] font-black uppercase tracking-widest">
                    {{ $trx->event->category->name ?? 'Event' }}
                </span>
                <h3 class="text-2xl font-black mt-4 leading-tight">{{ $trx->event->title ?? 'Nama Event' }}</h3>
                <p class="text-xs text-indigo-200 mt-2">📍 {{ $trx->event->location ?? '-' }}</p>
            </div>
            <div class="mt-6 pt-4 border-t border-slate-800">
                <p class="text-[10px] text-slate-400 font-bold uppercase">Tanggal Event</p>
                <p class="font-extrabold text-sm text-indigo-300">
                    {{ $trx->event ? \Carbon\Carbon::parse($trx->event->date)->format('d M Y, H:i') : '-' }}
                </p>
            </div>
        </div>

        <!-- Middle Info -->
        <div class="p-6 flex flex-col justify-between border-b md:border-b-0 md:border-r border-slate-100">
            <div class="space-y-4">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nama Pemegang Tiket</p>
                    <p class="font-black text-lg text-slate-800">{{ $trx->customer_name }}</p>
                    <p class="text-xs text-slate-500">{{ $trx->customer_email }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Order ID / Kode Transaksi</p>
                    <p class="font-mono font-bold text-indigo-600 text-sm">{{ $trx->order_id }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status Tiket</p>
                    @if($trx->status === 'used')
                        <span class="inline-block px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold uppercase">Sudah Terpakai (Checked-in)</span>
                    @else
                        <span class="inline-block px-3 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold uppercase">Valid / Siap Gunakan</span>
                    @endif
                </div>
            </div>

            <div class="mt-6">
                <button onclick="window.print()" class="w-full py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
                    🖨️ Cetak E-Ticket
                </button>
            </div>
        </div>

        <!-- Right QR Code -->
        <div class="p-6 bg-slate-50 flex flex-col items-center justify-center text-center">
            <p class="text-[10px] font-bold text-slate-400 uppercase mb-3 tracking-wider">Scan untuk Check-in Hari-H</p>
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data={{ urlencode($trx->order_id) }}" 
                 alt="QR Code" 
                 class="w-40 h-40 bg-white p-3 rounded-2xl shadow-sm border border-slate-200">
            <p class="font-mono text-xs font-bold text-slate-500 mt-3">{{ $trx->order_id }}</p>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 shadow-sm">
        <span class="text-5xl block mb-4">🎟️</span>
        <h3 class="text-xl font-bold text-slate-800">Belum Ada Tiket Dimiliki</h3>
        <p class="text-slate-500 text-sm mt-2 mb-6">Anda belum memiliki e-ticket terdaftar. Silakan jelajahi event seru dan lakukan pemesanan tiket.</p>
        <a href="/" class="px-6 py-3 bg-indigo-600 text-white font-bold text-sm rounded-xl shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition">
            Jelajahi Event Sekarang
        </a>
    </div>
    @endforelse
</main>
@endsection
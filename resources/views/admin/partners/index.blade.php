@extends('layouts.admin')
@section('title', 'Kelola Partner - Admin')
@section('page_title', 'Kelola Partner')
@section('page_subtitle', 'Hubungkan acara Anda dengan mitra terpercaya.')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <!-- Form Input (Kiri) -->
    <div class="lg:col-span-4">
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm sticky top-28">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-slate-800">
                    {{ $editPartner ? 'Edit Data Partner' : 'Daftar Partner Baru' }}
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    {{ $editPartner ? 'Ubah informasi mitra kerja sama yang sudah terdaftar.' : 'Lengkapi formulir di bawah ini untuk menambahkan mitra kerja sama baru.' }}
                </p>
            </div>

            @if($editPartner)
                <form action="{{ route('admin.partners.update', $editPartner->id) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Nama Partner</label>
                        <input type="text" name="name" value="{{ old('name', $editPartner->name) }}" placeholder="Contoh: PT Intel Indonesia" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium" required>
                        @error('name') <span class="text-rose-500 text-xs mt-1 block font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">URL Logo Partner</label>
                        <input type="url" id="logo_url" name="logo_url" value="{{ old('logo_url', $editPartner->logo_url) }}" placeholder="https://placehold.co/200x200" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium" required>
                        <p class="text-[10px] text-slate-400 mt-1.5">Gunakan URL eksternal atau klik salah satu preset di bawah.</p>
                        @error('logo_url') <span class="text-rose-500 text-xs mt-1 block font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <!-- Preset Logo Url Buttons -->
                    <div class="pt-1">
                        <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Preset Cepat</span>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" onclick="setPresetLogo('Google')" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-800 text-xs font-bold rounded-xl transition">
                                Google
                            </button>
                            <button type="button" onclick="setPresetLogo('Microsoft')" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-800 text-xs font-bold rounded-xl transition">
                                Microsoft
                            </button>
                            <button type="button" onclick="setPresetLogo('Amazon')" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-800 text-xs font-bold rounded-xl transition">
                                Amazon
                            </button>
                            <button type="button" onclick="setPresetLogo('Netflix')" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-800 text-xs font-bold rounded-xl transition">
                                Netflix
                            </button>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex gap-3">
                        <a href="{{ route('admin.partners.index') }}" class="w-1/2 py-4 bg-slate-100 hover:bg-slate-200 text-slate-600 text-center rounded-2xl font-bold transition">
                            Batal
                        </a>
                        <button type="submit" class="w-1/2 py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 active:scale-98 transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            @else
                <form action="{{ route('admin.partners.store') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Nama Partner</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: PT Intel Indonesia" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium" required>
                        @error('name') <span class="text-rose-500 text-xs mt-1 block font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">URL Logo Partner</label>
                        <input type="url" id="logo_url" name="logo_url" value="{{ old('logo_url') }}" placeholder="https://placehold.co/200x200" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium" required>
                        <p class="text-[10px] text-slate-400 mt-1.5">Gunakan URL eksternal atau klik salah satu preset di bawah.</p>
                        @error('logo_url') <span class="text-rose-500 text-xs mt-1 block font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <!-- Preset Logo Url Buttons -->
                    <div class="pt-1">
                        <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Preset Cepat</span>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" onclick="setPresetLogo('Google')" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-800 text-xs font-bold rounded-xl transition">
                                Google
                            </button>
                            <button type="button" onclick="setPresetLogo('Microsoft')" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-800 text-xs font-bold rounded-xl transition">
                                Microsoft
                            </button>
                            <button type="button" onclick="setPresetLogo('Amazon')" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-800 text-xs font-bold rounded-xl transition">
                                Amazon
                            </button>
                            <button type="button" onclick="setPresetLogo('Netflix')" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-800 text-xs font-bold rounded-xl transition">
                                Netflix
                            </button>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 active:scale-98 transition">
                            Simpan Partner
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <!-- Tabel Data & Search (Kanan) -->
    <div class="lg:col-span-8">
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
            
            <!-- Header & Search Bar -->
            <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="font-black text-slate-800 text-lg">Mitra Terdaftar</h3>
                    <p class="text-xs text-slate-400">Total: {{ count($partners) }} partner kerja sama.</p>
                </div>
                
                <!-- Search Form -->
                <form action="{{ route('admin.partners.index') }}" method="GET" class="relative max-w-xs w-full">
                    @if($editPartner)
                        <input type="hidden" name="edit" value="{{ $editPartner->id }}">
                    @endif
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari partner..." class="w-full pl-11 pr-4 py-3 bg-white border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition text-sm font-medium shadow-sm">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    @if($search)
                        <a href="{{ route('admin.partners.index', $editPartner ? ['edit' => $editPartner->id] : []) }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </a>
                    @endif
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50/50 text-slate-400 uppercase text-[10px] font-black tracking-widest border-b">
                        <tr>
                            <th class="px-8 py-4 w-16 text-center">No</th>
                            <th class="px-8 py-4 w-28">Logo</th>
                            <th class="px-8 py-4">Nama Partner</th>
                            <th class="px-8 py-4">Bergabung Pada</th>
                            <th class="px-8 py-4 w-28 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($partners as $index => $partner)
                        <tr class="hover:bg-slate-50/50 transition {{ $editPartner && $editPartner->id == $partner->id ? 'bg-indigo-50/30' : '' }}">
                            <td class="px-8 py-6 font-bold text-slate-400 text-center">{{ $index + 1 }}</td>
                            <td class="px-8 py-6">
                                <div class="w-16 h-16 rounded-2xl border bg-white p-1.5 flex items-center justify-center shadow-sm overflow-hidden">
                                    <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}" class="max-w-full max-h-full object-contain rounded-lg" onerror="this.onerror=null; this.src='https://placehold.co/100x100?text={{ urlencode($partner->name) }}'">
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <p class="font-black text-slate-800 text-base">{{ $partner->name }}</p>
                                <p class="text-xs text-slate-400 select-all font-mono mt-0.5 truncate max-w-xs">{{ $partner->logo_url }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <p class="font-semibold text-slate-600 text-sm">{{ $partner->created_at->format('d M Y') }}</p>
                                <p class="text-xs text-slate-400">{{ $partner->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Edit Button -->
                                    <a href="{{ route('admin.partners.index', ['edit' => $partner->id, 'search' => $search]) }}" class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition" title="Edit Partner">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>

                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.partners.destroy', $partner->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus partner ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition" title="Hapus Partner">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-16 text-center">
                                <div class="max-w-sm mx-auto">
                                    <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <h4 class="font-bold text-slate-700">Partner Tidak Ditemukan</h4>
                                    <p class="text-xs text-slate-400 mt-1">Coba gunakan kata kunci pencarian lain atau tambahkan partner baru.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function setPresetLogo(name) {
        let slug = name.toLowerCase();
        let url = `https://placehold.co/200x200?text=${name}`;
        
        // Custom premium logo placeholders based on names
        if (slug === 'google') {
            url = 'https://placehold.co/200x200/4285F4/FFFFFF?text=Google';
        } else if (slug === 'microsoft') {
            url = 'https://placehold.co/200x200/F25022/FFFFFF?text=Microsoft';
        } else if (slug === 'amazon') {
            url = 'https://placehold.co/200x200/FF9900/FFFFFF?text=Amazon';
        } else if (slug === 'netflix') {
            url = 'https://placehold.co/200x200/E50914/FFFFFF?text=Netflix';
        }
        
        document.getElementById('logo_url').value = url;
    }
</script>
@endsection

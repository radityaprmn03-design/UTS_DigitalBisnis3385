@extends('layouts.admin')
@section('title', 'Kelola Kategori - Admin')
@section('page_title', 'Kelola Kategori')
@section('page_subtitle', 'Kelompokkan event Anda ke dalam kategori yang sesuai.')

@section('content')
@if(session('error'))
    <div class="bg-rose-100 text-rose-700 p-4 rounded-xl mb-6 font-bold text-sm">
        {{ session('error') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <!-- Form Input (Kiri) -->
    <div class="lg:col-span-4">
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm sticky top-28">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-slate-800">
                    {{ $editCategory ? 'Edit Kategori' : 'Tambah Kategori Baru' }}
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    {{ $editCategory ? 'Ubah nama kategori yang sudah ada.' : 'Lengkapi formulir di bawah ini untuk menambahkan kategori baru.' }}
                </p>
            </div>

            @if($editCategory)
                <form action="{{ route('admin.categories.update', $editCategory->id) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Nama Kategori</label>
                        <input type="text" name="name" value="{{ old('name', $editCategory->name) }}" placeholder="Contoh: IT & Programming" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium" required>
                        @error('name') <span class="text-rose-500 text-xs mt-1 block font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex gap-3">
                        <a href="{{ route('admin.categories.index') }}" class="w-1/2 py-4 bg-slate-100 hover:bg-slate-200 text-slate-600 text-center rounded-2xl font-bold transition">
                            Batal
                        </a>
                        <button type="submit" class="w-1/2 py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 active:scale-98 transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            @else
                <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Nama Kategori</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: IT & Programming" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium" required>
                        @error('name') <span class="text-rose-500 text-xs mt-1 block font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 active:scale-98 transition">
                            Simpan Kategori
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
                    <h3 class="font-black text-slate-800 text-lg">Daftar Kategori</h3>
                    <p class="text-xs text-slate-400">Total: {{ count($categories) }} kategori.</p>
                </div>
                
                <!-- Search Form -->
                <form action="{{ route('admin.categories.index') }}" method="GET" class="relative max-w-xs w-full">
                    @if($editCategory)
                        <input type="hidden" name="edit" value="{{ $editCategory->id }}">
                    @endif
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari kategori..." class="w-full pl-11 pr-4 py-3 bg-white border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition text-sm font-medium shadow-sm">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    @if($search)
                        <a href="{{ route('admin.categories.index', $editCategory ? ['edit' => $editCategory->id] : []) }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition">
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
                            <th class="px-8 py-4 w-20 text-center">ID</th>
                            <th class="px-8 py-4">Nama Kategori</th>
                            <th class="px-8 py-4">Dibuat Pada</th>
                            <th class="px-8 py-4">Diperbarui Pada</th>
                            <th class="px-8 py-4 w-28 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($categories as $index => $category)
                        <tr class="hover:bg-slate-50/50 transition {{ $editCategory && $editCategory->id == $category->id ? 'bg-indigo-50/30' : '' }}">
                            <td class="px-8 py-6 font-bold text-slate-400 text-center">{{ $index + 1 }}</td>
                            <td class="px-8 py-6 text-slate-400 text-center font-semibold text-sm">#{{ $category->id }}</td>
                            <td class="px-8 py-6">
                                <p class="font-black text-slate-800 text-base">{{ $category->name }}</p>
                                <p class="text-xs text-slate-400 font-mono mt-0.5 select-all">slug: {{ $category->slug }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <p class="font-semibold text-slate-600 text-sm">{{ $category->created_at->format('d M Y') }}</p>
                                <p class="text-xs text-slate-400">{{ $category->created_at->format('H:i') }} WIB</p>
                            </td>
                            <td class="px-8 py-6">
                                <p class="font-semibold text-slate-600 text-sm">{{ $category->updated_at->format('d M Y') }}</p>
                                <p class="text-xs text-slate-400">{{ $category->updated_at->format('H:i') }} WIB</p>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Edit Button -->
                                    <a href="{{ route('admin.categories.index', ['edit' => $category->id, 'search' => $search]) }}" class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition" title="Edit Kategori">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>

                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition" title="Hapus Kategori">
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
                            <td colspan="6" class="px-8 py-16 text-center">
                                <div class="max-w-sm mx-auto">
                                    <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                    </div>
                                    <h4 class="font-bold text-slate-700">Kategori Tidak Ditemukan</h4>
                                    <p class="text-xs text-slate-400 mt-1">Coba gunakan kata kunci pencarian lain atau tambahkan kategori baru.</p>
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
@endsection

@extends('layouts.app')

@section('title', 'Tambah Barang')
@section('page_title', 'Tambah Barang Baru')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('items.index') }}" class="inline-flex items-center gap-2 text-xs text-slate-500 hover:text-slate-700 transition-colors duration-150 font-bold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 md:p-8 shadow-sm">
        <form action="{{ route('items.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Item Name -->
            <div>
                <label for="item_name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Barang</label>
                <input type="text" name="item_name" id="item_name" required value="{{ old('item_name') }}"
                       class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 transition-all duration-200 text-sm"
                       placeholder="Contoh: PC All-in-One Dell">
            </div>

            <!-- Item Type (inventory/bhp) -->
            <div>
                <label for="type" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tipe Barang</label>
                <select name="type" id="type" required
                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 transition-all duration-200 text-sm">
                    <option value="" disabled selected>Pilih Tipe Barang...</option>
                    <option value="inventory" {{ old('type') === 'inventory' ? 'selected' : '' }}>🖥️ Inventaris (Aset Tetap)</option>
                    <option value="bhp" {{ old('type') === 'bhp' ? 'selected' : '' }}>📦 BHP (Barang Habis Pakai)</option>
                </select>
                <p class="text-[10px] text-slate-400 font-medium mt-1.5">*Inventaris: aset tetap bernomor label. BHP: stok habis pakai seperti tinta, kabel, dsb.</p>
            </div>

            <!-- Category/Description -->
            <div>
                <label for="category" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kategori / Deskripsi</label>
                <input type="text" name="category" id="category" required value="{{ old('category') }}"
                       class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 transition-all duration-200 text-sm"
                       placeholder="Contoh: Elektronik, Jaringan, Furniture...">
            </div>

            <!-- Stock -->
            <div>
                <label for="stock" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jumlah Stok</label>
                <input type="number" name="stock" id="stock" required min="0" value="{{ old('stock', 0) }}"
                       class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 transition-all duration-200 text-sm"
                       placeholder="Masukkan jumlah stok awal...">
            </div>

            <!-- Room ID -->
            <div>
                <label for="room_id" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Lokasi Ruangan</label>
                <select name="room_id" id="room_id" required
                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 transition-all duration-200 text-sm">
                    <option value="" disabled selected>Pilih Ruangan Penyimpanan</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room['id'] }}" {{ old('room_id') == $room['id'] ? 'selected' : '' }}>
                            {{ $room['room_name'] }} ({{ $room['location'] }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('items.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-500 hover:text-slate-700 text-xs font-bold transition-all duration-150">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-500 active:scale-[0.98] text-white text-xs font-bold transition-all duration-150 shadow-md shadow-violet-600/10">
                    Simpan Barang
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Edit Barang')
@section('page_title', 'Edit Data Barang')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('items.index') }}" class="inline-flex items-center gap-2 text-xs text-slate-400 hover:text-slate-200 transition-colors duration-150">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-slate-950/40 border border-slate-800 rounded-2xl p-6 md:p-8 shadow-xl">
        <form action="{{ route('items.update', $item['id']) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Item Name -->
            <div>
                <label for="item_name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Nama Barang</label>
                <input type="text" name="item_name" id="item_name" required value="{{ old('item_name', $item['item_name']) }}"
                       class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-slate-100 placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200 text-sm"
                       placeholder="Contoh: PC All-in-One Dell">
            </div>

            <!-- Category -->
            <div>
                <label for="category" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Kategori</label>
                <input type="text" name="category" id="category" required value="{{ old('category', $item['category']) }}"
                       class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-slate-100 placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200 text-sm"
                       placeholder="Contoh: Elektronik, Furniture, Jaringan...">
            </div>

            <!-- Stock -->
            <div>
                <label for="stock" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Jumlah Stok</label>
                <input type="number" name="stock" id="stock" required min="0" value="{{ old('stock', $item['stock']) }}"
                       class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-slate-100 placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200 text-sm"
                       placeholder="Masukkan jumlah stok...">
            </div>

            <!-- Room ID -->
            <div>
                <label for="room_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Lokasi Ruangan</label>
                <select name="room_id" id="room_id" required
                        class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-slate-100 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200 text-sm">
                    @foreach($rooms as $room)
                        <option value="{{ $room['id'] }}" {{ old('room_id', $item['room_id']) == $room['id'] ? 'selected' : '' }}>
                            {{ $room['room_name'] }} ({{ $room['location'] }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 border-t border-slate-800 flex items-center justify-end gap-3">
                <a href="{{ route('items.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-800 hover:bg-slate-900 text-slate-400 hover:text-slate-200 text-xs font-semibold transition-all duration-150">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-500 active:scale-[0.98] text-white text-xs font-semibold transition-all duration-150 shadow-lg shadow-violet-600/25">
                    Perbarui Barang
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

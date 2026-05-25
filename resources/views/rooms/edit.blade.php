@extends('layouts.app')

@section('title', 'Edit Ruangan')
@section('page_title', 'Edit Data Ruangan')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('rooms.index') }}" class="inline-flex items-center gap-2 text-xs text-slate-400 hover:text-slate-200 transition-colors duration-150">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-slate-950/40 border border-slate-800 rounded-2xl p-6 md:p-8 shadow-xl">
        <form action="{{ route('rooms.update', $room['id']) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Room Name -->
            <div>
                <label for="room_name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Nama Ruangan</label>
                <input type="text" name="room_name" id="room_name" required value="{{ old('room_name', $room['room_name']) }}"
                       class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-slate-100 placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200 text-sm"
                       placeholder="Contoh: Laboratorium Komputer A">
            </div>

            <!-- Location -->
            <div>
                <label for="location" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Lokasi / Gedung</label>
                <input type="text" name="location" id="location" required value="{{ old('location', $room['location']) }}"
                       class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-slate-100 placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200 text-sm"
                       placeholder="Contoh: Gedung Baru Lantai 2">
            </div>

            <!-- Capacity -->
            <div>
                <label for="capacity" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Kapasitas (Orang)</label>
                <input type="number" name="capacity" id="capacity" required min="1" value="{{ old('capacity', $room['capacity']) }}"
                       class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-slate-100 placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200 text-sm"
                       placeholder="Masukkan kapasitas maksimal ruangan...">
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 border-t border-slate-800 flex items-center justify-end gap-3">
                <a href="{{ route('rooms.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-800 hover:bg-slate-900 text-slate-400 hover:text-slate-200 text-xs font-semibold transition-all duration-150">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 active:scale-[0.98] text-white text-xs font-semibold transition-all duration-150 shadow-lg shadow-emerald-600/25">
                    Perbarui Ruangan
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

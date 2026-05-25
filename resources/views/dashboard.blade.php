@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Overview')

@section('content')
<div class="space-y-8">
    <!-- Welcome Panel -->
    <div class="relative bg-gradient-to-r from-slate-900 to-indigo-950 border border-indigo-900/40 p-6 md:p-8 rounded-3xl overflow-hidden shadow-2xl shadow-indigo-950/20">
        <div class="absolute w-80 h-80 rounded-full bg-indigo-500/10 blur-[80px] top-[-50%] right-[-10%] pointer-events-none"></div>
        
        <div class="relative max-w-2xl">
            <h2 class="text-2xl md:text-3xl font-extrabold text-white mb-2">Selamat Datang, {{ session('user')['name'] }}!</h2>
            <p class="text-slate-400 text-sm leading-relaxed mb-6">
                Ini adalah halaman utama Panel Admin Capstone. Di sini Anda dapat mengelola data master pengguna (users), data ruangan (rooms), dan barang inventaris (items) dengan cepat melalui koneksi REST API Node.js secara aman.
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('items.create') }}" class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold shadow-lg shadow-indigo-600/20 transition-all duration-150">
                    + Tambah Barang Baru
                </a>
                <a href="{{ route('rooms.create') }}" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition-all duration-150 border border-slate-700">
                    + Tambah Ruangan
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Users Widget -->
        <div class="bg-slate-950/40 border border-slate-800 p-5 rounded-2xl flex items-center justify-between shadow-xl">
            <div class="space-y-1">
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Users</p>
                <h3 class="text-2xl font-bold text-slate-100">{{ $stats['users_count'] }}</h3>
                <p class="text-xs text-slate-400">Pengguna terdaftar</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
        </div>

        <!-- Rooms Widget -->
        <div class="bg-slate-950/40 border border-slate-800 p-5 rounded-2xl flex items-center justify-between shadow-xl">
            <div class="space-y-1">
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Ruangan</p>
                <h3 class="text-2xl font-bold text-slate-100">{{ $stats['rooms_count'] }}</h3>
                <p class="text-xs text-slate-400">Lokasi / Laboratorium</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
        </div>

        <!-- Items Widget -->
        <div class="bg-slate-950/40 border border-slate-800 p-5 rounded-2xl flex items-center justify-between shadow-xl">
            <div class="space-y-1">
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Jenis Barang</p>
                <h3 class="text-2xl font-bold text-slate-100">{{ $stats['items_count'] }}</h3>
                <p class="text-xs text-slate-400">Kategori master barang</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-violet-500/10 border border-violet-500/20 flex items-center justify-center text-violet-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
        </div>

        <!-- Stock Widget -->
        <div class="bg-slate-950/40 border border-slate-800 p-5 rounded-2xl flex items-center justify-between shadow-xl">
            <div class="space-y-1">
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Stok</p>
                <h3 class="text-2xl font-bold text-slate-100">{{ $stats['total_stock'] }}</h3>
                <p class="text-xs text-slate-400">Unit terinventarisasi</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
        </div>
    </div>

    <!-- Quick Navigation -->
    <div class="bg-slate-950/30 border border-slate-800 rounded-2xl p-6">
        <h3 class="text-lg font-bold text-slate-200 mb-4">Navigasi Cepat Kelola Master</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Card 1 -->
            <a href="{{ route('users.index') }}" class="group block p-5 rounded-xl bg-slate-950/50 hover:bg-indigo-950/10 border border-slate-800 hover:border-indigo-500/20 transition-all duration-200">
                <h4 class="font-bold text-slate-200 group-hover:text-indigo-400 transition-colors duration-150 mb-1 flex items-center gap-2">
                    Kelola Users 
                    <svg class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </h4>
                <p class="text-xs text-slate-500 leading-relaxed">Tambah, edit, dan hapus akun pengguna yang dapat login ke sistem.</p>
            </a>

            <!-- Card 2 -->
            <a href="{{ route('rooms.index') }}" class="group block p-5 rounded-xl bg-slate-950/50 hover:bg-emerald-950/10 border border-slate-800 hover:border-emerald-500/20 transition-all duration-200">
                <h4 class="font-bold text-slate-200 group-hover:text-emerald-400 transition-colors duration-150 mb-1 flex items-center gap-2">
                    Kelola Ruangan
                    <svg class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </h4>
                <p class="text-xs text-slate-500 leading-relaxed">Kelola laboratorium atau lokasi penyimpanan barang inventaris.</p>
            </a>

            <!-- Card 3 -->
            <a href="{{ route('items.index') }}" class="group block p-5 rounded-xl bg-slate-950/50 hover:bg-violet-950/10 border border-slate-800 hover:border-violet-500/20 transition-all duration-200">
                <h4 class="font-bold text-slate-200 group-hover:text-violet-400 transition-colors duration-150 mb-1 flex items-center gap-2">
                    Kelola Barang
                    <svg class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </h4>
                <p class="text-xs text-slate-500 leading-relaxed">Mendata inventaris barang, stok unit, dan menetapkan ke ruangan.</p>
            </a>
        </div>
    </div>
</div>
@endsection

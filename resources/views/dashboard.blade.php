@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-8">

    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-600 text-sm font-semibold">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    <!-- Welcome Banner -->
    @php $user = session('user'); $roleId = $user['role_id'] ?? 0; @endphp
    <div class="rounded-2xl overflow-hidden bg-gradient-to-br from-indigo-600 to-violet-600 p-8 text-white shadow-lg shadow-indigo-500/20">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <p class="text-indigo-200 font-medium text-sm mb-1">Selamat Datang Kembali 👋</p>
                <h2 class="text-2xl font-bold">{{ $user['name'] ?? 'Pengguna' }}</h2>
                <p class="text-indigo-200 mt-1 text-sm font-medium">
                    Role: <span class="font-bold text-white">{{ $user['role'] ?? '-' }}</span>
                </p>
            </div>
            <div class="text-right">
                <p class="text-indigo-200 text-xs font-medium">Sistem Manajemen Aset & BHP</p>
                <p class="text-white font-bold text-sm">Laboratorium Informatika</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        @if($roleId == 1)
        <!-- Admin-only: Users Count -->
        <a href="{{ route('users.index') }}" class="block bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Users</span>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ $stats['users_count'] }}</p>
            <p class="text-xs text-slate-400 font-medium mt-1">Pengguna Terdaftar</p>
        </a>
        @endif

        <!-- Rooms Card (Admin & Kalab) -->
        @if($roleId == 1 || $roleId == 2)
        <a href="{{ route('rooms.index') }}" class="block bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Ruangan</span>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ $stats['rooms_count'] }}</p>
            <p class="text-xs text-slate-400 font-medium mt-1">Laboratorium Terdaftar</p>
        </a>
        @endif

        <!-- Items Card (All) -->
        <a href="{{ route('items.index') }}" class="block bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-violet-50 border border-violet-100 flex items-center justify-center text-violet-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Jenis Barang</span>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ $stats['items_count'] }}</p>
            <p class="text-xs text-slate-400 font-medium mt-1">Master Barang & BHP</p>
        </a>

        <!-- Total Stock (All) -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Stok</span>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ $stats['total_stock'] }}</p>
            <p class="text-xs text-slate-400 font-medium mt-1">Unit (Inventori + BHP)</p>
        </div>

        <!-- Procurement Drafts (Kalab, Kaprodi, Admin) -->
        <a href="{{ route('procurements.index') }}" class="block bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pengadaan</span>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ $stats['procurement_count'] }}</p>
            <p class="text-xs text-slate-400 font-medium mt-1">Draf Pengadaan Barang</p>
        </a>
    </div>

    <!-- Quick Action Card based on Role -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <h3 class="text-sm font-bold text-slate-800 mb-4">Aksi Cepat</h3>
        <div class="flex flex-wrap gap-3">

            @if($roleId == 1)
                <!-- Admin Quick Actions -->
                <a href="{{ route('users.create') }}" class="px-4 py-2.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold transition-all duration-150 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah User Baru
                </a>
                <a href="{{ route('rooms.create') }}" class="px-4 py-2.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold transition-all duration-150 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Ruangan
                </a>
                <a href="{{ route('items.create') }}" class="px-4 py-2.5 rounded-xl bg-violet-50 hover:bg-violet-100 text-violet-700 text-xs font-bold transition-all duration-150 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Barang
                </a>
            @endif

            @if($roleId == 2)
                <!-- Kepala Lab Quick Actions -->
                <a href="{{ route('procurements.create') }}" class="px-4 py-2.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold transition-all duration-150 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Buat Draf Pengadaan Baru
                </a>
                <a href="{{ route('procurements.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all duration-150 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                    Lihat Semua Draf
                </a>
            @endif

            @if($roleId == 3)
                <!-- Kaprodi Quick Actions -->
                <a href="{{ route('procurements.index') }}" class="px-4 py-2.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-bold transition-all duration-150 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Review Draf Pengadaan
                </a>
            @endif

            <!-- Common for all -->
            <a href="{{ route('items.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all duration-150 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Lihat Daftar Barang
            </a>
        </div>
    </div>

</div>
@endsection

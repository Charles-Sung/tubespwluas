<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - SILAB Admin</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col md:flex-row">

    <!-- Sidebar -->
    <aside class="w-full md:w-64 bg-white border-b md:border-b-0 md:border-r border-slate-200 flex flex-col shrink-0">
        <!-- Logo Header -->
        <div class="h-16 flex items-center px-6 border-b border-slate-100 bg-white justify-between">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center font-bold text-white shadow-md shadow-indigo-600/10">
                    S
                </div>
                <span class="font-bold text-lg text-slate-800">SILAB</span>
            </div>
            <span class="text-indigo-600 bg-indigo-50 text-xs px-2.5 py-0.5 rounded-full font-medium">Portal</span>
        </div>

        <!-- Sidebar Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
            <!-- Dashboard Link -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="font-semibold text-sm">Dashboard</span>
            </a>

            <!-- Master Data Section (Admin Only) -->
            @if(session()->has('user') && (strtolower(session('user')['role']) === 'admin' || strtolower(session('user')['role']) === 'administrator'))
                <div class="pt-4 pb-2 px-4">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Master Data</p>
                </div>

                <!-- Users CRUD -->
                <a href="{{ route('users.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('users.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span class="font-semibold text-sm">Kelola Users</span>
                </a>

                <!-- Rooms CRUD -->
                <a href="{{ route('rooms.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('rooms.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span class="font-semibold text-sm">Kelola Ruangan</span>
                </a>

                <!-- Items CRUD -->
                <a href="{{ route('items.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('items.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <span class="font-semibold text-sm">Kelola Barang</span>
                </a>
            @endif

            <!-- Procurement Section (Admin, Kalab, Kaprodi) -->
            <div class="pt-4 pb-2 px-4">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Transaksi Aset</p>
            </div>

            <!-- Draf Pengadaan Link -->
            @if(session()->has('user') && (session('user')['role_id'] == 1 || session('user')['role_id'] == 2 || session('user')['role_id'] == 3 || strtolower(session('user')['role']) === 'administrator' || strtolower(session('user')['role']) === 'kepala laboratorium' || strtolower(session('user')['role']) === 'ketua program studi'))
                <a href="{{ route('procurements.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('procurements.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    <span class="font-semibold text-sm">Pengadaan Barang</span>
                </a>
            @endif

            <!-- Penerimaan Barang Link (Admin, Staf Administrasi) -->
            @if(session()->has('user') && (session('user')['role_id'] == 1 || session('user')['role_id'] == 4 || strtolower(session('user')['role']) === 'administrator' || strtolower(session('user')['role']) === 'staf administrasi'))
                <a href="{{ route('receipts.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('receipts.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    <span class="font-semibold text-sm">Penerimaan Barang</span>
                </a>
            @endif

            <!-- Kelola Stok BHP Link (Admin, Staf Laboratorium) -->
            @if(session()->has('user') && (session('user')['role_id'] == 1 || session('user')['role_id'] == 5 || strtolower(session('user')['role']) === 'administrator' || strtolower(session('user')['role']) === 'staf laboratorium'))
                <a href="{{ route('bhp.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('bhp.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <span class="font-semibold text-sm">Kelola Stok BHP</span>
                </a>
            @endif

            <!-- Log Maintenance Link (Admin, Staf Laboratorium) -->
            @if(session()->has('user') && (session('user')['role_id'] == 1 || session('user')['role_id'] == 5 || strtolower(session('user')['role']) === 'administrator' || strtolower(session('user')['role']) === 'staf laboratorium'))
                <a href="{{ route('maintenance.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('maintenance.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span class="font-semibold text-sm">Log Maintenance</span>
                </a>
            @endif
        </nav>

        <!-- User Profile & Logout -->
        <div class="p-4 border-t border-slate-100 bg-white flex flex-col gap-3">
            @if(session()->has('user'))
                <div class="flex items-center gap-3 px-2">
                    <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-indigo-600">
                        {{ strtoupper(substr(session('user')['name'], 0, 2)) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-bold text-slate-800 truncate">{{ session('user')['name'] }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ session('user')['email'] }}</p>
                    </div>
                </div>
            @endif
            
            <form action="{{ route('logout') }}" method="POST" class="w-full">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-rose-600 hover:bg-rose-50 hover:text-rose-700 transition-all duration-200 font-semibold text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>Sign Out</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col min-h-0 bg-slate-50">
        <!-- Top Navbar -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 md:px-8">
            <h1 class="text-xl font-bold text-slate-800">@yield('page_title', 'Dashboard')</h1>
            <div class="flex items-center gap-4">
                <span class="text-xs bg-indigo-50 text-indigo-600 border border-indigo-100 px-3.5 py-1 rounded-full font-semibold">Role: {{ ucfirst(session('user')['role'] ?? 'Guest') }}</span>
            </div>
        </header>

        <!-- Main Body -->
        <div class="flex-1 p-6 md:p-8 overflow-y-auto">
            <!-- Toast Notifications / Alerts -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 flex items-center gap-3 shadow-sm">
                    <svg class="w-5 h-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-100 text-rose-700 flex items-center gap-3 shadow-sm">
                    <svg class="w-5 h-5 shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-sm font-semibold">{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-100 text-rose-700 shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <svg class="w-5 h-5 shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-sm font-bold">Terjadi Kesalahan:</span>
                    </div>
                    <ul class="list-disc list-inside text-sm text-rose-600 pl-5 font-medium">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

</body>
</html>

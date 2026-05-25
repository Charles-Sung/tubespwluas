@extends('layouts.app')

@section('title', 'Edit User')
@section('page_title', 'Edit Data User')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 text-xs text-slate-400 hover:text-slate-200 transition-colors duration-150">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-slate-950/40 border border-slate-800 rounded-2xl p-6 md:p-8 shadow-xl">
        <form action="{{ route('users.update', $user['id']) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Nama Lengkap</label>
                <input type="text" name="name" id="name" required value="{{ old('name', $user['name']) }}"
                       class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-slate-100 placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200 text-sm"
                       placeholder="Masukkan nama lengkap...">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Email Address</label>
                <input type="email" name="email" id="email" required value="{{ old('email', $user['email']) }}"
                       class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-slate-100 placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200 text-sm"
                       placeholder="contoh@gmail.com">
            </div>

            <!-- Password -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label for="password" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Password (Opsional)</label>
                    <span class="text-[10px] text-slate-500 font-medium">Kosongkan jika tidak ingin mengubah</span>
                </div>
                <input type="password" name="password" id="password" minlength="6"
                       class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-slate-100 placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200 text-sm"
                       placeholder="Minimal 6 karakter...">
            </div>

            <!-- Role -->
            <div>
                <label for="role" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Role Akses</label>
                <select name="role" id="role" required
                        class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-slate-100 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200 text-sm">
                    <option value="user" {{ old('role', $user['role']) === 'user' ? 'selected' : '' }}>User biasa</option>
                    <option value="admin" {{ old('role', $user['role']) === 'admin' ? 'selected' : '' }}>Administrator</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 border-t border-slate-800 flex items-center justify-end gap-3">
                <a href="{{ route('users.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-800 hover:bg-slate-900 text-slate-400 hover:text-slate-200 text-xs font-semibold transition-all duration-150">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 active:scale-[0.98] text-white text-xs font-semibold transition-all duration-150 shadow-lg shadow-indigo-600/25">
                    Perbarui User
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

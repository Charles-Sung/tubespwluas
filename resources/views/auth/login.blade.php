<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SILAB Portal</title>
    
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
<body class="bg-slate-50 text-slate-800 min-h-screen flex items-center justify-center relative px-4">

    <!-- Decorative radial gradients -->
    <div class="absolute w-[500px] h-[500px] rounded-full bg-indigo-100 blur-[100px] top-[-10%] left-[-10%] pointer-events-none"></div>
    <div class="absolute w-[600px] h-[600px] rounded-full bg-violet-100 blur-[110px] bottom-[-20%] right-[-10%] pointer-events-none"></div>

    <!-- Login Container -->
    <div class="w-full max-w-md bg-white border border-slate-200 p-8 rounded-3xl shadow-xl relative z-10">
        <!-- Logo and Title -->
        <div class="text-center mb-8">
            <div class="w-12 h-12 rounded-2xl bg-indigo-600 flex items-center justify-center font-bold text-white shadow-lg shadow-indigo-600/20 mx-auto mb-4 text-xl">
                S
            </div>
            <h2 class="text-2xl font-bold text-slate-800">SILAB Login</h2>
            <p class="text-sm text-slate-400 mt-1.5">Sistem Manajemen Aset & BHP Laboratorium</p>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->has('login_error'))
            <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-100 text-rose-700 text-sm font-semibold">
                {{ $errors->first('login_error') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-100 text-rose-700 text-sm font-semibold">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/></svg>
                    </div>
                    <input type="email" name="email" id="email" required value="{{ old('email') }}"
                           class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 transition-all duration-200 text-sm" 
                           placeholder="admin@example.com">
                </div>
                @error('email')
                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <input type="password" name="password" id="password" required
                           class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 transition-all duration-200 text-sm"
                           placeholder="••••••••">
                </div>
                @error('password')
                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember & Forgot -->
            <div class="flex items-center justify-between text-xs text-slate-500 font-semibold">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" class="rounded border-slate-200 text-indigo-600 focus:ring-indigo-500/20 focus:ring-offset-white">
                    <span>Remember me</span>
                </label>
                <span class="hover:text-indigo-600 cursor-pointer transition-colors duration-150">Forgot password?</span>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-500 active:scale-[0.98] text-white font-bold rounded-xl transition-all duration-150 shadow-md shadow-indigo-600/10 text-sm">
                Sign In
            </button>
        </form>

        <!-- Dummy credentials hint -->
        <div class="mt-6 pt-5 border-t border-slate-100 text-center">
            <span class="text-xs text-slate-400">Default Admin: <strong class="text-slate-500 font-bold">admin@example.com / password</strong></span>
        </div>
    </div>

</body>
</html>

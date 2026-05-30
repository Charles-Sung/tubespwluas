@extends('layouts.app')

@section('title', 'Draf Pengadaan')
@section('page_title', 'Draf Pengadaan Aset & BHP')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <p class="text-sm text-slate-500 font-medium">Buat draf pengadaan tahunan baru dan review persetujuan dari Kaprodi.</p>
        </div>
        
        <!-- Only Kepala Lab and Admin can create new drafts -->
        @if(session('user')['role_id'] == 2 || session('user')['role_id'] == 1 || strtolower(session('user')['role']) === 'kepala laboratorium')
            <a href="{{ route('procurements.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 active:scale-[0.98] transition-all duration-150 text-white text-xs font-bold rounded-xl flex items-center gap-2 shadow-md shadow-indigo-600/10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Buat Draf Pengadaan
            </a>
        @endif
    </div>

    <!-- Data Table Card -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Judul Pengadaan</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tahun</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Diajukan Oleh</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 text-center uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($drafts as $index => $draft)
                        <tr class="hover:bg-slate-50 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-slate-500 font-semibold">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-slate-800">{{ $draft['title'] }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-semibold">
                                {{ $draft['year'] }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-slate-800">{{ $draft['user']['name'] }}</div>
                                <div class="text-xs text-slate-400 font-medium">{{ $draft['user']['email'] }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($draft['status'] === 'draft')
                                    <span class="px-2.5 py-1 rounded-full bg-slate-100 border border-slate-200 text-xs font-bold text-slate-600">
                                        📁 Draf Baru
                                    </span>
                                @elseif($draft['status'] === 'submitted')
                                    <span class="px-2.5 py-1 rounded-full bg-amber-50 border border-amber-100 text-xs font-bold text-amber-600">
                                        ⏳ Menunggu Review
                                    </span>
                                @elseif($draft['status'] === 'reviewed')
                                    <span class="px-2.5 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-xs font-bold text-indigo-600">
                                        🔍 Direview Sebagian
                                    </span>
                                @elseif($draft['status'] === 'finalized')
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-50 border border-emerald-100 text-xs font-bold text-emerald-600">
                                        🔒 Selesai & Dikunci
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('procurements.show', $draft['id']) }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-600 text-xs font-bold transition-all duration-150">
                                    Lihat Detail & Aksi
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400 text-sm font-medium">Belum ada draf pengadaan diajukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

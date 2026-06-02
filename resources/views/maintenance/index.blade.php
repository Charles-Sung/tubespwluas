@extends('layouts.app')

@section('title', 'Log Maintenance')
@section('page_title', 'Riwayat Maintenance Barang')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <p class="text-sm text-slate-500 font-medium">Lihat riwayat perbaikan dan pemeliharaan aset inventaris laboratorium.</p>
        </div>
        <a href="{{ route('maintenance.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 active:scale-[0.98] transition-all duration-150 text-white text-xs font-bold rounded-xl flex items-center gap-2 shadow-md shadow-indigo-600/10">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Catat Maintenance Baru
        </a>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Aset / Inventaris</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Petugas (Staf Lab)</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Perubahan Kondisi</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">BHP yang Digunakan</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $index => $log)
                        <tr class="hover:bg-slate-50 transition-colors duration-150 align-top">
                            <td class="px-6 py-4 text-sm text-slate-500 font-semibold">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-800 text-sm">
                                        {{ $log['inventory']['item']['name'] ?? 'Barang Terhapus' }}
                                    </span>
                                    <span class="text-xs text-indigo-600 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-md font-bold mt-1 w-max">
                                        {{ $log['inventory']['label_number'] ?? 'N/A' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-medium">
                                {{ \Carbon\Carbon::parse($log['maintenance_date'])->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700 font-semibold">
                                {{ $log['user']['name'] ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1.5 text-xs">
                                    <span class="px-2 py-0.5 font-bold rounded-full {{ $log['previous_condition'] === 'good' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : ($log['previous_condition'] === 'maintenance' ? 'bg-amber-50 text-amber-700 border border-amber-100' : 'bg-rose-50 text-rose-700 border border-rose-100') }}">
                                        {{ ucfirst($log['previous_condition']) }}
                                    </span>
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    <span class="px-2 py-0.5 font-bold rounded-full {{ $log['new_condition'] === 'good' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : ($log['new_condition'] === 'maintenance' ? 'bg-amber-50 text-amber-700 border border-amber-100' : 'bg-rose-50 text-rose-700 border border-rose-100') }}">
                                        {{ ucfirst($log['new_condition']) }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                @if(!empty($log['maintenance_bhps']) && count($log['maintenance_bhps']) > 0)
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach($log['maintenance_bhps'] as $bhp)
                                            <li class="font-medium text-slate-700">
                                                {{ $bhp['item']['name'] ?? 'BHP Terhapus' }} 
                                                <span class="text-xs bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded-full font-bold ml-1">
                                                    x{{ $bhp['quantity'] }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-slate-400 text-xs italic font-medium">Tidak ada BHP digunakan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-medium max-w-xs break-words">
                                {{ $log['description'] }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-slate-400 text-sm font-medium">Belum ada riwayat pencatatan maintenance.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

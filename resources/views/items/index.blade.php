@extends('layouts.app')

@section('title', 'Kelola Barang')
@section('page_title', 'Master Data Barang')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <p class="text-sm text-slate-500 font-medium">Daftar inventaris barang dan lokasi ruangan penyimpanan.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('items.index') }}" class="px-3 py-1.5 {{ !request('type') ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }} rounded-lg text-xs font-bold transition-colors">Semua</a>
            <a href="{{ route('items.index', ['type' => 'bhp']) }}" class="px-3 py-1.5 {{ request('type') === 'bhp' ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }} rounded-lg text-xs font-bold transition-colors">BHP</a>
            <a href="{{ route('items.index', ['type' => 'inventory']) }}" class="px-3 py-1.5 {{ request('type') === 'inventory' ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }} rounded-lg text-xs font-bold transition-colors">Inventory</a>
            
            <a href="{{ route('items.create') }}" class="ml-2 px-4 py-2.5 bg-violet-600 hover:bg-violet-500 active:scale-[0.98] transition-all duration-150 text-white text-xs font-bold rounded-xl flex items-center gap-2 shadow-md shadow-violet-600/10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Barang Baru
            </a>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Barang</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Stok</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Ruangan</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 text-center uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($items as $index => $item)
                        <tr class="hover:bg-slate-50 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-slate-500 font-semibold">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-violet-50 border border-violet-100 flex items-center justify-center text-violet-600">
                                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    </div>
                                    <span class="text-sm font-bold text-slate-800">{{ $item['item_name'] }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded bg-slate-100 border border-slate-200 text-xs font-semibold text-slate-600">
                                    {{ $item['category'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold {{ $item['stock'] > 5 ? 'text-slate-700' : 'text-amber-600' }}">
                                    {{ $item['stock'] }} Unit
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if(isset($item['room']))
                                    <div class="text-sm text-slate-800 font-bold">{{ $item['room']['room_name'] }}</div>
                                    <div class="text-xs text-slate-400 font-medium">{{ $item['room']['location'] }}</div>
                                @else
                                    <span class="text-xs text-rose-500 font-bold italic">Tanpa Ruangan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('items.edit', $item['id']) }}" class="p-2 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-600 transition-all duration-150" title="Edit Barang">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('items.destroy', $item['id']) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 transition-all duration-150" title="Hapus Barang">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-400 text-sm font-medium">Belum ada data barang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

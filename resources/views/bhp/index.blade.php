@extends('layouts.app')

@section('title', 'Kelola Stok BHP')
@section('page_title', 'Stok Bahan Habis Pakai (BHP)')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <p class="text-sm text-slate-500 font-medium">Pantau dan kelola tingkat ketersediaan Bahan Habis Pakai (BHP) laboratorium.</p>
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
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Deskripsi / Kategori</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Jumlah Stok</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 text-center uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($bhpItems as $index => $item)
                        <tr class="hover:bg-slate-50 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-slate-500 font-semibold">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    </div>
                                    <span class="text-sm font-bold text-slate-800">{{ $item['name'] }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-medium">{{ $item['description'] ?: '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $item['stock'] > 10 ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : ($item['stock'] > 0 ? 'bg-amber-50 text-amber-700 border border-amber-100' : 'bg-rose-50 text-rose-700 border border-rose-100') }}">
                                    {{ $item['stock'] }} Unit
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center">
                                    <button onclick="openEditModal({{ $item['id'] }}, '{{ addslashes($item['name']) }}', {{ $item['stock'] }})" class="px-3 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-600 text-xs font-semibold transition-all duration-150 flex items-center gap-1.5" title="Sesuaikan Stok">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Update Stok
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-400 text-sm font-medium">Belum ada data barang bertipe BHP.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Stock Modal -->
<div id="editStockModal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-200 w-full max-w-md overflow-hidden shadow-xl animate-in fade-in zoom-in duration-200">
        <!-- Modal Header -->
        <div class="h-14 border-b border-slate-100 px-6 flex items-center justify-between">
            <h3 class="font-bold text-slate-800">Update Stok BHP</h3>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Modal Form -->
        <form action="{{ route('bhp.update') }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="item_id" id="modal_item_id">

            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Barang</label>
                    <input type="text" id="modal_item_name" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-semibold text-sm text-slate-600" readonly>
                </div>

                <div>
                    <label for="quantity" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kuantitas Stok Baru</label>
                    <input type="number" name="quantity" id="modal_quantity" min="0" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 transition-all font-semibold text-sm text-slate-800">
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-white hover:bg-slate-50 active:scale-[0.98] transition-all border border-slate-200 text-xs font-bold text-slate-600 rounded-xl">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 active:scale-[0.98] transition-all text-white text-xs font-bold rounded-xl shadow-md shadow-indigo-600/10">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(itemId, itemName, currentStock) {
        document.getElementById('modal_item_id').value = itemId;
        document.getElementById('modal_item_name').value = itemName;
        document.getElementById('modal_quantity').value = currentStock;
        document.getElementById('editStockModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editStockModal').classList.add('hidden');
    }

    // Close on click outside modal
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('editStockModal');
        if (event.target === modal) {
            closeEditModal();
        }
    });
</script>
@endsection

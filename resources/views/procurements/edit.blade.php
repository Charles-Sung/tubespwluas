@extends('layouts.app')

@section('title', 'Edit Draf Pengadaan')
@section('page_title', 'Edit Draf Pengadaan')

@section('content')
<div class="space-y-6" x-data="procurementForm()">
    <div class="flex justify-between items-center">
        <a href="{{ route('procurements.index') }}" class="inline-flex items-center gap-2 text-xs text-slate-500 hover:text-slate-700 transition-colors duration-150 font-bold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar
        </a>

        <button type="button" @click="addItem()" class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 text-xs font-bold rounded-xl transition-all duration-150 flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Baris Barang
        </button>
    </div>

    @if($errors->has('api_error'))
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-600 text-sm font-semibold">
            ⚠️ {{ $errors->first('api_error') }}
        </div>
    @endif

    <form action="{{ route('procurements.update', $draft['id']) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Header Specs -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
            <h3 class="text-sm font-bold text-slate-800">Detail Rencana Pengadaan Tahunan</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Judul Dokumen Pengadaan</label>
                    <input type="text" name="title" id="title" required value="{{ old('title', $draft['title']) }}"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 transition-all duration-200 text-sm"
                           placeholder="Contoh: Pengadaan PC & BHP Lab Komputer Tahun Anggaran 2026">
                </div>

                <!-- Year -->
                <div>
                    <label for="year" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tahun Anggaran</label>
                    <input type="number" name="year" id="year" required min="2020" max="2100" value="{{ old('year', $draft['year']) }}"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 transition-all duration-200 text-sm">
                </div>
            </div>
        </div>

        <!-- Details Table -->
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-1/3">Nama Barang</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-20">Quantity</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-36">Harga Satuan (Rp)</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-1/3">Link Pembelian / Toko</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right w-32">Estimasi Total</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 text-center uppercase tracking-wider w-14">Hapus</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="(row, index) in rows" :key="index">
                            <tr class="hover:bg-slate-50/50 transition-colors duration-150">
                                <!-- Select Item -->
                                <td class="px-6 py-4">
                                    <select :name="'items['+index+'][item_id]'" required x-model="row.item_id" @change="onItemSelect(row)"
                                            class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/10 text-xs">
                                        <option value="" disabled selected>Pilih Barang...</option>
                                        @foreach($items as $item)
                                            <option value="{{ $item['id'] }}">{{ $item['name'] }} ({{ $item['type'] }})</option>
                                        @endforeach
                                    </select>

                                    <!-- Replaced Inventory Option (Conditional) -->
                                    <div class="mt-2.5 p-2.5 bg-slate-50 border border-slate-200/60 rounded-lg" x-show="isInventory(row.item_id)">
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Menggantikan Aset (Opsional):</label>
                                        <select :name="'items['+index+'][replaced_inventory_id]'" x-model="row.replaced_inventory_id"
                                                class="w-full px-2 py-1 bg-white border border-slate-200 rounded text-slate-600 focus:outline-none focus:border-indigo-500 text-[10px] font-medium">
                                            <option value="">-- Tidak menggantikan aset --</option>
                                            <template x-for="inv in getInventoriesForItem(row.item_id)" :key="inv.id">
                                                <option :value="inv.id" x-text="inv.label_number + ' - ' + inv.room.name + ' (' + inv.condition.toUpperCase() + ')'"></option>
                                            </template>
                                        </select>
                                    </div>
                                </td>

                                <!-- Quantity -->
                                <td class="px-6 py-4">
                                    <input type="number" :name="'items['+index+'][quantity]'" required min="1" x-model.number="row.quantity"
                                           class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/10 text-xs text-center">
                                </td>

                                <!-- Price -->
                                <td class="px-6 py-4">
                                    <input type="number" :name="'items['+index+'][price]'" required min="0" x-model.number="row.price"
                                           class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/10 text-xs">
                                </td>

                                <!-- Purchase Link -->
                                <td class="px-6 py-4">
                                    <input type="url" :name="'items['+index+'][purchase_link]'" x-model="row.purchase_link"
                                           class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/10 text-xs"
                                           placeholder="https://tokopedia.com/...">
                                </td>

                                <!-- Total Estimate -->
                                <td class="px-6 py-4 text-right text-xs font-bold text-slate-700 font-mono">
                                    Rp <span x-text="formatNumber(row.quantity * row.price)"></span>
                                </td>

                                <!-- Remove Action -->
                                <td class="px-6 py-4 text-center">
                                    <button type="button" @click="removeItem(index)" class="p-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 transition-colors duration-150">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Total Card Summary -->
            <div class="p-6 bg-slate-50 border-t border-slate-200 flex justify-between items-center">
                <span class="text-sm font-bold text-slate-500">ESTIMASI TOTAL ANGGARAN:</span>
                <span class="text-lg font-bold text-indigo-600 font-mono">Rp <span x-text="formatNumber(calculateGrandTotal())"></span></span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('procurements.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-500 hover:text-slate-700 text-xs font-bold transition-all duration-150 bg-white">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 active:scale-[0.98] text-white text-xs font-bold transition-all duration-150 shadow-md shadow-indigo-600/10">
                Perbarui Draf
            </button>
        </div>
    </form>
</div>

<script>
    function procurementForm() {
        return {
            rows: {!! json_encode(array_map(function($detail) {
                return [
                    'item_id' => $detail['item_id'],
                    'quantity' => $detail['quantity'],
                    'price' => $detail['price'],
                    'purchase_link' => $detail['purchase_link'] ?? '',
                    'replaced_inventory_id' => $detail['replaced_inventory_id'] ?? ''
                ];
            }, $draft['details'] ?? [])) !!},
            inventoriesList: @json($inventories),
            itemsList: @json($items),
            
            addItem() {
                this.rows.push({ item_id: '', quantity: 1, price: 0, purchase_link: '', replaced_inventory_id: '' });
            },
            
            removeItem(index) {
                if (this.rows.length > 1) {
                    this.rows.splice(index, 1);
                }
            },
            
            onItemSelect(row) {
                row.replaced_inventory_id = '';
            },
            
            isInventory(itemId) {
                if (!itemId) return false;
                const match = this.itemsList.find(i => i.id == itemId);
                return match && match.type === 'inventory';
            },
            
            getInventoriesForItem(itemId) {
                if (!itemId) return [];
                return this.inventoriesList.filter(inv => inv.item_id == itemId);
            },
            
            calculateGrandTotal() {
                return this.rows.reduce((sum, row) => sum + (row.quantity * row.price), 0);
            },
            
            formatNumber(num) {
                return new Intl.NumberFormat('id-ID').format(num);
            }
        }
    }
</script>
@endsection

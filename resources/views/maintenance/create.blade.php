@extends('layouts.app')

@section('title', 'Catat Maintenance')
@section('page_title', 'Catat Maintenance & Perbaikan')

@section('content')
<div class="max-w-3xl" x-data="maintenanceForm()">
    <div class="mb-6">
        <a href="{{ route('maintenance.index') }}" class="inline-flex items-center gap-2 text-xs text-slate-500 hover:text-slate-700 transition-colors duration-150 font-bold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Riwayat
        </a>
    </div>

    @if($errors->has('api_error'))
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-600 text-sm font-semibold">
            ⚠️ {{ $errors->first('api_error') }}
        </div>
    @endif

    <form action="{{ route('maintenance.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- General Maintenance Form -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
            <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-3">Informasi Pemeliharaan</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Dropdown Inventaris -->
                <div class="md:col-span-2">
                    <label for="inventory_id" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Barang Inventaris</label>
                    <select name="inventory_id" id="inventory_id" required
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 transition-all duration-200 text-sm">
                        <option value="" disabled selected>Pilih Barang Inventaris...</option>
                        @foreach($inventories as $inv)
                            <option value="{{ $inv['id'] }}">
                                {{ $inv['label_number'] }} - {{ $inv['item']['name'] ?? 'N/A' }} (Kondisi saat ini: {{ ucfirst($inv['condition']) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal Maintenance -->
                <div>
                    <label for="maintenance_date" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Pemeliharaan</label>
                    <input type="date" name="maintenance_date" id="maintenance_date" required value="{{ old('maintenance_date', date('Y-m-d')) }}"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 transition-all duration-200 text-sm">
                </div>

                <!-- Kondisi Baru -->
                <div>
                    <label for="new_condition" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kondisi Baru Setelah Maintenance</label>
                    <select name="new_condition" id="new_condition" required
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 transition-all duration-200 text-sm">
                        <option value="good" selected>Baik (Good)</option>
                        <option value="maintenance">Pemeliharaan (Maintenance)</option>
                        <option value="broken">Rusak (Broken)</option>
                    </select>
                </div>

                <!-- Deskripsi Perbaikan -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Deskripsi / Tindakan Perbaikan</label>
                    <textarea name="description" id="description" rows="3" required
                              class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 transition-all duration-200 text-sm"
                              placeholder="Jelaskan tindakan pemeliharaan yang dilakukan (misal: install ulang OS, ganti thermal paste, dsb)...">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        <!-- BHP Usage Block -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Bahan Habis Pakai (BHP) yang Digunakan</h3>
                    <p class="text-xs text-slate-400 mt-0.5 font-medium">Opsional. Pilih bahan habis pakai yang terpakai selama proses maintenance.</p>
                </div>
                <button type="button" @click="addBhpRow()" class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-700 text-xs font-bold rounded-xl flex items-center gap-1.5 transition-all duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah BHP
                </button>
            </div>

            <!-- Dynamic BHP rows -->
            <div class="space-y-3">
                <template x-for="(row, index) in bhps" :key="index">
                    <div class="flex flex-col sm:flex-row gap-4 p-4 bg-slate-50 border border-slate-200 rounded-xl items-end sm:items-center relative">
                        <!-- Select BHP Item -->
                        <div class="flex-1 w-full">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Pilih BHP</label>
                            <select :name="'bhps['+index+'][item_id]'" x-model="row.item_id" @change="updateStockLabel(index)" required
                                    class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-indigo-500">
                                <option value="" disabled selected>Pilih item BHP...</option>
                                @foreach($bhpItems as $bhp)
                                    <option value="{{ $bhp['id'] }}" data-stock="{{ $bhp['stock'] }}">
                                        {{ $bhp['name'] }} (Stok tersedia: {{ $bhp['stock'] }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Quantity Used -->
                        <div class="w-full sm:w-32">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Jumlah Digunakan</label>
                            <input type="number" :name="'bhps['+index+'][quantity]'" x-model.number="row.quantity" min="1" :max="row.maxStock" required
                                   class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-indigo-500"
                                   placeholder="Qty...">
                        </div>

                        <!-- Remove Row Button -->
                        <button type="button" @click="removeBhpRow(index)" class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg transition-all self-end sm:self-center" title="Hapus">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </template>

                <div x-show="bhps.length === 0" class="text-center py-6 text-slate-400 text-xs italic font-medium">
                    Tidak ada BHP yang ditambahkan untuk perbaikan ini.
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('maintenance.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-500 hover:text-slate-700 text-xs font-bold transition-all duration-150 bg-white">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 active:scale-[0.98] text-white text-xs font-bold transition-all duration-150 shadow-md shadow-indigo-600/10">
                Simpan Log Maintenance
            </button>
        </div>
    </form>
</div>

<script>
    function maintenanceForm() {
        return {
            bhps: [],
            
            addBhpRow() {
                this.bhps.push({
                    item_id: '',
                    quantity: 1,
                    maxStock: 9999
                });
            },
            
            removeBhpRow(index) {
                this.bhps.splice(index, 1);
            },
            
            updateStockLabel(index) {
                const row = this.bhps[index];
                // Wait for next tick so x-model updates the DOM
                setTimeout(() => {
                    const selects = document.querySelectorAll(`select[name="bhps[${index}][item_id]"]`);
                    if (selects.length > 0) {
                        const select = selects[0];
                        const selectedOption = select.options[select.selectedIndex];
                        const stock = parseInt(selectedOption.getAttribute('data-stock')) || 0;
                        row.maxStock = stock;
                        if (row.quantity > stock) {
                            row.quantity = stock;
                        }
                    }
                }, 50);
            }
        }
    }
</script>
@endsection

@extends('layouts.app')

@section('title', 'Catat Penerimaan Barang')
@section('page_title', 'Catat Penerimaan Barang Baru')

@section('content')
<div class="max-w-3xl" x-data="receiptForm()">
    <div class="mb-6">
        <a href="{{ route('receipts.index') }}" class="inline-flex items-center gap-2 text-xs text-slate-500 hover:text-slate-700 transition-colors duration-150 font-bold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar
        </a>
    </div>

    @if($errors->has('api_error'))
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-600 text-sm font-semibold">
            ⚠️ {{ $errors->first('api_error') }}
        </div>
    @endif

    <form action="{{ route('receipts.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- General Receipt Specs -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
            <h3 class="text-sm font-bold text-slate-800">Detail Kelengkapan Pengiriman</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Dropdown Barang yang Disetujui -->
                <div class="md:col-span-2">
                    <label for="procurement_detail_id" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Barang dari Pengadaan</label>
                    <select name="procurement_detail_id" id="procurement_detail_id" required x-model="selectedItemId" @change="onItemChange()"
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 transition-all duration-200 text-sm">
                        <option value="" disabled selected>Pilih Barang yang Disetujui...</option>
                        @foreach($pendingItems as $item)
                            <option value="{{ $item['id'] }}" 
                                    data-type="{{ $item['item_type'] }}" 
                                    data-remaining="{{ $item['quantity_remaining'] }}"
                                    data-name="{{ $item['item_name'] }}">
                                {{ $item['item_name'] }} (Pengadaan: {{ $item['draft_title'] }} [Sisa Pesanan: {{ $item['quantity_remaining'] }} Unit])
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal Penerimaan -->
                <div>
                    <label for="receipt_date" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Terima</label>
                    <input type="date" name="receipt_date" id="receipt_date" required value="{{ old('receipt_date', date('Y-m-d')) }}"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 transition-all duration-200 text-sm">
                </div>

                <!-- Jumlah Diterima -->
                <div>
                    <label for="quantity_received" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jumlah Diterima (Unit)</label>
                    <input type="number" name="quantity_received" id="quantity_received" required min="1" :max="maxRemaining" x-model.number="quantityReceived" @input="onQuantityInput()"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 transition-all duration-200 text-sm"
                           placeholder="Kuantitas...">
                    <p class="text-[10px] text-indigo-600 font-bold mt-1.5" x-show="maxRemaining > 0">
                        *Maksimal sisa barang yang bisa diterima: <span x-text="maxRemaining"></span> Unit.
                    </p>
                </div>
            </div>
        </div>

        <!-- Dynamic Inventories Cards (Only visible if selected item is type 'inventory') -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6" x-show="itemType === 'inventory'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2">
            <div class="border-b border-slate-100 pb-4">
                <h3 class="text-sm font-bold text-slate-800">Alokasi & Label Inventaris Baru</h3>
                <p class="text-xs text-slate-400 mt-1 font-semibold">Tentukan ruangan penempatan dan masukkan kode label fisik aset.</p>
            </div>

            <!-- Choose Lab Room -->
            <div>
                <label for="room_id" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alokasi Ruangan Laboratorium</label>
                <select name="room_id" id="room_id" :required="itemType === 'inventory'"
                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 transition-all duration-200 text-sm">
                    <option value="" disabled selected>Pilih Laboratorium Penempatan...</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room['id'] }}">{{ $room['room_name'] }} ({{ $room['location'] ?? 'Tanpa Deskripsi' }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Dynamic Label List -->
            <div class="space-y-4">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Input Nomor Label Unik (Per-Unit)</label>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <template x-for="(label, i) in labelNumbers" :key="i">
                        <div class="space-y-2 p-4 bg-slate-50 border border-slate-200 rounded-2xl">
                            <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full" x-text="'Unit ke-' + (i + 1)"></span>
                            <input type="text" name="label_numbers[]" required x-model="labelNumbers[i]"
                                   class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/10 text-xs font-mono tracking-wide"
                                   placeholder="Contoh: INV-PC-001">
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Notes Log -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
            <div>
                <label for="notes" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Catatan Tambahan (Opsional)</label>
                <textarea name="notes" id="notes" rows="3"
                          class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 transition-all duration-200 text-sm"
                          placeholder="Tulis kondisi kemasan pengiriman, cacat pabrik jika ada, atau informasi tambahan lainnya..."></textarea>
            </div>
        </div>

        <!-- Form Action -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('receipts.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-500 hover:text-slate-700 text-xs font-bold transition-all duration-150 bg-white">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 active:scale-[0.98] text-white text-xs font-bold transition-all duration-150 shadow-md shadow-indigo-600/10">
                Simpan Penerimaan
            </button>
        </div>
    </form>
</div>

<script>
    function receiptForm() {
        return {
            selectedItemId: '',
            itemType: '',
            itemName: '',
            maxRemaining: 0,
            quantityReceived: 1,
            labelNumbers: [''],
            
            onItemChange() {
                const select = document.getElementById('procurement_detail_id');
                const selectedOption = select.options[select.selectedIndex];
                
                this.itemType = selectedOption.getAttribute('data-type') || '';
                this.itemName = selectedOption.getAttribute('data-name') || '';
                this.maxRemaining = parseInt(selectedOption.getAttribute('data-remaining')) || 0;
                
                this.quantityReceived = 1;
                this.onQuantityInput();
            },
            
            onQuantityInput() {
                // Ensure value stays within allowed bounds
                if (this.quantityReceived > this.maxRemaining) {
                    this.quantityReceived = this.maxRemaining;
                }
                if (this.quantityReceived < 1) {
                    this.quantityReceived = 1;
                }
                
                // Regenerate array of labels according to quantity
                this.labelNumbers = Array.from({ length: this.quantityReceived }, (_, i) => {
                    // Try to auto-generate a sleek suggested code
                    const cleanName = this.itemName.toUpperCase().replace(/[^A-Z0-9]/g, '-').substring(0, 8);
                    const numString = String(i + 1).padStart(3, '0');
                    return `INV-${cleanName}-${numString}`;
                });
            }
        }
    }
</script>
@endsection

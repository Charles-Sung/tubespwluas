@extends('layouts.app')

@section('title', 'Penerimaan & Inventarisasi Barang')
@section('page_title', 'Penerimaan & Inventarisasi Barang')

@section('content')
<div class="space-y-6">

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-600 text-sm font-semibold">
            ✨ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-600 text-sm font-semibold">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    <!-- Header Actions -->
    <div class="flex justify-between items-center bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Riwayat Penerimaan Barang</h2>
            <p class="text-xs text-slate-500 mt-1 font-medium">Log pencatatan barang datang dan pelabelan otomatis inventaris baru.</p>
        </div>
        
        @if(session('user')['role_id'] == 1 || session('user')['role_id'] == 4 || strtolower(session('user')['role']) === 'administrator' || strtolower(session('user')['role']) === 'staf administrasi')
            <a href="{{ route('receipts.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold rounded-xl shadow-md shadow-indigo-600/10 transition-all duration-150 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Catat Penerimaan Baru
            </a>
        @endif
    </div>

    <!-- Table Logs -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Datang</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Barang</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tipe</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Jumlah Diterima</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Pengadaan Asal</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Staf Penerima</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Catatan</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">QR Code</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($receipts as $index => $receipt)
                        <tr class="hover:bg-slate-50/30 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-slate-500 font-semibold">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-bold">
                                {{ \Carbon\Carbon::parse($receipt['receipt_date'])->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-slate-800">{{ $receipt['detail']['item']['name'] ?? 'Barang Terhapus' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if(($receipt['detail']['item']['type'] ?? '') === 'inventory')
                                    <span class="px-2.5 py-0.5 rounded-full bg-violet-50 border border-violet-100 text-xs font-bold text-violet-600">
                                        🖥️ Inventaris
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 border border-emerald-100 text-xs font-bold text-emerald-600">
                                        📦 BHP
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-bold">
                                {{ $receipt['quantity_received'] }} Unit
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500 font-medium truncate max-w-[200px]" title="{{ $receipt['detail']['draft']['title'] ?? '-' }}">
                                {{ $receipt['detail']['draft']['title'] ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-medium">
                                {{ $receipt['user']['name'] ?? 'Staf' }}
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400 italic max-w-[180px] truncate" title="{{ $receipt['notes'] }}">
                                {{ $receipt['notes'] ?? 'Tidak ada' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(($receipt['detail']['item']['type'] ?? '') === 'inventory')
                                    <button onclick="showReceiptQR('{{ $receipt['detail']['item']['name'] }}', {{ $receipt['quantity_received'] }})" 
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-violet-50 hover:bg-violet-100 border border-violet-100 rounded text-violet-600 text-xs font-bold transition-all duration-150">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h.01M16 20h2M4 4h4v4H4V4zm12 0h4v4h-4V4zM4 16h4v4H4v-4z"/></svg>
                                        Lihat QR
                                    </button>
                                @else
                                    <span class="text-xs text-slate-400 italic font-medium">BHP (Bukan Aset)</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-8 text-center text-sm font-semibold text-slate-400">
                                📭 Belum ada riwayat penerimaan barang dicatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Premium Interactive QR Code Modal -->
<div id="qrModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden transition-opacity duration-300">
    <div class="bg-white rounded-3xl p-8 max-w-sm w-full mx-4 shadow-2xl transform scale-95 transition-transform duration-300 relative space-y-6">
        <button onclick="closeQRModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition-colors p-1.5 bg-slate-50 rounded-full">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <div class="text-center space-y-2">
            <h3 class="text-lg font-bold text-slate-800">QR Code Label Inventaris</h3>
            <p id="qrItemName" class="text-xs text-indigo-600 font-bold bg-indigo-50 px-3 py-1 rounded-full inline-block"></p>
            <p class="text-[11px] text-slate-400 font-medium">Pindai kode QR untuk melihat info inventaris lab.</p>
        </div>

        <!-- QR Containers dynamically populated -->
        <div id="qrCodesList" class="flex flex-col gap-6 items-center max-h-[300px] overflow-y-auto p-2 scrollbar-thin">
            <!-- QR content will be generated dynamically here -->
        </div>

        <div class="pt-2">
            <button onclick="closeQRModal()" class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all duration-150">
                Tutup Jendela
            </button>
        </div>
    </div>
</div>

<!-- Load CDN QR Code Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    function showReceiptQR(itemName, quantity) {
        const modal = document.getElementById('qrModal');
        const qrList = document.getElementById('qrCodesList');
        const titleName = document.getElementById('qrItemName');
        
        // Setup title
        titleName.innerText = itemName;
        
        // Reset container
        qrList.innerHTML = '';
        
        // Generate QR Code for each item
        for (let i = 1; i <= quantity; i++) {
            const labelNumber = 'INV-' + itemName.toUpperCase().replace(/\s+/g, '-') + '-00' + i;
            
            // Create item box
            const qrBox = document.createElement('div');
            qrBox.className = "flex flex-col items-center p-4 bg-slate-50 border border-slate-200 rounded-2xl w-full space-y-3";
            
            // Label tag text
            const labelSpan = document.createElement('span');
            labelSpan.className = "text-[11px] font-bold text-slate-600 font-mono tracking-wider";
            labelSpan.innerText = labelNumber;
            qrBox.appendChild(labelSpan);

            // QR element
            const qrDiv = document.createElement('div');
            qrDiv.id = 'qrcode-' + i;
            qrDiv.className = 'p-2 bg-white rounded-xl shadow-sm border border-slate-100';
            qrBox.appendChild(qrDiv);
            
            qrList.appendChild(qrBox);

            // Trigger QR draw
            new QRCode(qrDiv, {
                text: labelNumber,
                width: 120,
                height: 120,
                colorDark : "#4f46e5", // Indigo-600 brand color
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
        }
        
        // Display Modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeQRModal() {
        const modal = document.getElementById('qrModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endsection

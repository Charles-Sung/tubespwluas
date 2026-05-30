@extends('layouts.app')

@section('title', 'Detail Draf Pengadaan')
@section('page_title', 'Detail Draf Pengadaan')

@section('content')
<div class="space-y-6 max-w-5xl">
    <!-- Back Navigation -->
    <div class="mb-2">
        <a href="{{ route('procurements.index') }}" class="inline-flex items-center gap-2 text-xs text-slate-500 hover:text-slate-700 transition-colors duration-150 font-bold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar
        </a>
    </div>

    <!-- Alert Success / Error -->
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

    <!-- Draft Status Banner Card -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 md:p-8 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="space-y-2">
            <div class="flex items-center gap-3">
                <h2 class="text-xl font-bold text-slate-800">{{ $draft['title'] }}</h2>
                
                @if($draft['status'] === 'draft')
                    <span class="px-2.5 py-1 rounded-full bg-slate-100 border border-slate-200 text-xs font-bold text-slate-600">
                        📁 Draf Baru
                    </span>
                @elseif($draft['status'] === 'submitted')
                    <span class="px-2.5 py-1 rounded-full bg-amber-50 border border-amber-100 text-xs font-bold text-amber-600">
                        ⏳ Menunggu Review Kaprodi
                    </span>
                @elseif($draft['status'] === 'reviewed')
                    <span class="px-2.5 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-xs font-bold text-indigo-600">
                        🔍 Sedang Direview
                    </span>
                @elseif($draft['status'] === 'finalized')
                    <span class="px-2.5 py-1 rounded-full bg-emerald-50 border border-emerald-100 text-xs font-bold text-emerald-600">
                        🔒 Selesai & Dikunci
                    </span>
                @endif
            </div>
            
            <div class="flex flex-wrap items-center gap-x-6 gap-y-1 text-sm text-slate-500 font-medium">
                <div>Tahun Anggaran: <span class="font-bold text-slate-700">{{ $draft['year'] }}</span></div>
                <div>•</div>
                <div>Diajukan oleh: <span class="font-bold text-slate-700">{{ $draft['user']['name'] }} (Kepala Lab)</span></div>
            </div>
        </div>

        <!-- Action Button based on Status & Role -->
        <div class="flex gap-3">
            <!-- Case 1: Draft Baru & Pembuatnya adalah Kepala Lab/Admin -> Kalab Submit -->
            @if($draft['status'] === 'draft' && (session('user')['role_id'] == 2 || session('user')['role_id'] == 1 || strtolower(session('user')['role']) === 'kepala laboratorium'))
                <form action="{{ route('procurements.submit', $draft['id']) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold rounded-xl shadow-md shadow-indigo-600/10 active:scale-[0.98] transition-all duration-150">
                        🚀 Ajukan Pengadaan ke Kaprodi
                    </button>
                </form>
            @endif

            <!-- Case 2: Status Submitted/Reviewed & User yang Login adalah Kaprodi/Admin -> Kaprodi Finalize -->
            @if(($draft['status'] === 'submitted' || $draft['status'] === 'reviewed') && (session('user')['role_id'] == 3 || session('user')['role_id'] == 1 || strtolower(session('user')['role']) === 'ketua program studi'))
                <form action="{{ route('procurements.finalize', $draft['id']) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memfinalisasi dan mengunci draf pengadaan ini? Log pengadaan tidak akan bisa diubah lagi.');">
                    @csrf
                    <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-600/10 active:scale-[0.98] transition-all duration-150">
                        🔒 Finalisasi & Kunci Pengadaan
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Items Detail Table -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-sm font-bold text-slate-800">Daftar Rincian Barang yang Diajukan</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Barang</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Qty</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Harga Satuan</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Toko / Link</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Total Harga</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Status Barang</th>
                        @if(($draft['status'] === 'submitted' || $draft['status'] === 'reviewed') && (session('user')['role_id'] == 3 || session('user')['role_id'] == 1 || strtolower(session('user')['role']) === 'ketua program studi'))
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Review Kaprodi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php $grandTotal = 0; @endphp
                    @foreach($draft['details'] as $index => $detail)
                        @php $subtotal = $detail['quantity'] * $detail['price']; $grandTotal += $subtotal; @endphp
                        <tr class="hover:bg-slate-50/30 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-slate-500 font-semibold">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-slate-800">{{ $detail['item']['name'] }}</span>
                                @if(!empty($detail['replaced_inventory']))
                                    <div class="mt-1.5 text-[10px] text-amber-600 bg-amber-50 border border-amber-200/60 px-2 py-0.5 rounded inline-flex items-center gap-1 font-bold">
                                        🔄 Menggantikan: {{ $detail['replaced_inventory']['label_number'] }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded bg-slate-100 border border-slate-200 text-xs font-semibold text-slate-600">
                                    {{ $detail['item']['type'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-semibold">
                                {{ $detail['quantity'] }} Unit
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-semibold">
                                Rp {{ number_format($detail['price'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($detail['purchase_link'])
                                    <a href="{{ $detail['purchase_link'] }}" target="_blank" class="text-indigo-600 hover:text-indigo-700 hover:underline font-bold inline-flex items-center gap-1">
                                        Tokopedia / Shopee
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                @else
                                    <span class="text-slate-400 italic">Tidak ada</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-slate-700">
                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($detail['status'] === 'pending')
                                    <span class="px-2.5 py-0.5 rounded-full bg-amber-50 border border-amber-100 text-xs font-bold text-amber-600">
                                        ⏳ Pending
                                    </span>
                                @elseif($detail['status'] === 'approved')
                                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 border border-emerald-100 text-xs font-bold text-emerald-600">
                                        ✅ Disetujui
                                    </span>
                                @elseif($detail['status'] === 'rejected')
                                    <span class="px-2.5 py-0.5 rounded-full bg-rose-50 border border-rose-100 text-xs font-bold text-rose-600">
                                        ❌ Ditolak
                                    </span>
                                @endif
                            </td>

                            <!-- Kaprodi Action Buttons -->
                            @if(($draft['status'] === 'submitted' || $draft['status'] === 'reviewed') && (session('user')['role_id'] == 3 || session('user')['role_id'] == 1 || strtolower(session('user')['role']) === 'ketua program studi'))
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Approve Form -->
                                        <form action="{{ route('procurements.review', $detail['id']) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="draft_id" value="{{ $draft['id'] }}">
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="px-2.5 py-1 rounded bg-emerald-50 hover:bg-emerald-100 border border-emerald-100 text-emerald-600 text-xs font-bold transition-all duration-150" title="Setujui Barang">
                                                Setujui
                                            </button>
                                        </form>

                                        <!-- Reject Form -->
                                        <form action="{{ route('procurements.review', $detail['id']) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="draft_id" value="{{ $draft['id'] }}">
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="px-2.5 py-1 rounded bg-rose-50 hover:bg-rose-100 border border-rose-100 text-rose-600 text-xs font-bold transition-all duration-150" title="Tolak Barang">
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Total Card Summary -->
        <div class="p-6 bg-slate-50 border-t border-slate-200 flex justify-between items-center">
            <span class="text-sm font-bold text-slate-500">ESTIMASI TOTAL ANGGARAN:</span>
            <span class="text-lg font-bold text-indigo-600">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
        </div>
    </div>
</div>
@endsection

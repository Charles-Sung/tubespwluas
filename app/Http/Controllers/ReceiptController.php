<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ReceiptController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_BASE_URL', 'http://localhost:3000/api');
    }

    public function index()
    {
        $token = session('jwt_token');

        try {
            $response = Http::withToken($token)->get("{$this->apiUrl}/receipts");
            $receipts = $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            $receipts = [];
            session()->flash('error', 'Gagal terhubung ke API backend untuk mengambil data penerimaan.');
        }

        return view('receipts.index', compact('receipts'));
    }

    public function create()
    {
        $token = session('jwt_token');

        try {
            // Fetch pending receipt items
            $pendingResponse = Http::withToken($token)->get("{$this->apiUrl}/receipts/pending");
            $pendingItems = $pendingResponse->successful() ? $pendingResponse->json() : [];

            // Fetch rooms for allocation dropdown
            $roomsResponse = Http::withToken($token)->get("{$this->apiUrl}/rooms");
            $rooms = $roomsResponse->successful() ? $roomsResponse->json() : [];
        } catch (\Exception $e) {
            $pendingItems = [];
            $rooms = [];
            session()->flash('error', 'Gagal memuat data pendukung form penerimaan.');
        }

        return view('receipts.create', compact('pendingItems', 'rooms'));
    }

    public function store(Request $request)
    {
        $token = session('jwt_token');

        $request->validate([
            'procurement_detail_id' => 'required',
            'quantity_received' => 'required|integer|min:1',
            'receipt_date' => 'required|date',
            'room_id' => 'nullable|integer',
            'label_numbers' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        try {
            $response = Http::withToken($token)->post("{$this->apiUrl}/receipts", [
                'procurement_detail_id' => $request->procurement_detail_id,
                'quantity_received' => $request->quantity_received,
                'receipt_date' => $request->receipt_date,
                'room_id' => $request->room_id,
                'label_numbers' => $request->label_numbers,
                'notes' => $request->notes,
            ]);

            if ($response->successful()) {
                return redirect()->route('receipts.index')->with('success', 'Penerimaan barang berhasil dicatat dan stok/inventaris otomatis terupdate!');
            }

            $errorMessage = $response->json()['message'] ?? 'Gagal mencatat penerimaan barang.';
            return back()->withErrors(['api_error' => $errorMessage])->withInput();

        } catch (\Exception $e) {
            return back()->withErrors(['api_error' => 'Gagal terhubung ke API backend.'])->withInput();
        }
    }
}

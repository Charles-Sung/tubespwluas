<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ItemController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_BASE_URL', 'http://localhost:3000/api');
    }

    private function getHeaders()
    {
        return [
            'Authorization' => 'Bearer ' . session('jwt_token')
        ];
    }

    public function index(Request $request)
    {
        try {
            $type = $request->query('type');
            if ($type) {
                $url .= "?type=" . urlencode($type);
            }
            $response = Http::withHeaders($this->getHeaders())->get($url);
            if ($response->successful()) {
                $items = $response->json();
                return view('items.index', compact('items'));
            }
            return redirect()->route('dashboard')->with('error', 'Gagal memuat data barang.');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Koneksi ke backend gagal.');
        }
    }

    public function create()
    {
        try {
            $roomsResponse = Http::withHeaders($this->getHeaders())->get("{$this->apiUrl}/rooms");
            if ($roomsResponse->successful()) {
                $rooms = $roomsResponse->json();
                return view('items.create', compact('rooms'));
            }
            return redirect()->route('items.index')->with('error', 'Gagal memuat data ruangan untuk form barang.');
        } catch (\Exception $e) {
            return redirect()->route('items.index')->with('error', 'Koneksi ke backend gagal.');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required',
            'category' => 'required',
            'stock' => 'required|integer|min:0',
            'room_id' => 'required|integer',
            'type' => 'required|in:inventory,bhp'
        ]);

        try {
            $response = Http::withHeaders($this->getHeaders())->post("{$this->apiUrl}/items", [
                'item_name' => $request->item_name,
                'category' => $request->category,
                'stock' => (int) $request->stock,
                'room_id' => (int) $request->room_id,
                'type' => $request->type
            ]);

            if ($response->successful()) {
                return redirect()->route('items.index')->with('success', 'Barang berhasil ditambahkan.');
            }

            $msg = $response->json()['message'] ?? 'Gagal menambahkan barang.';
            return back()->withErrors(['api_error' => $msg])->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['api_error' => 'Koneksi ke backend gagal.'])->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $itemResponse = Http::withHeaders($this->getHeaders())->get("{$this->apiUrl}/items/{$id}");
            $roomsResponse = Http::withHeaders($this->getHeaders())->get("{$this->apiUrl}/rooms");

            if ($itemResponse->successful() && $roomsResponse->successful()) {
                $item = $itemResponse->json();
                $rooms = $roomsResponse->json();
                return view('items.edit', compact('item', 'rooms'));
            }
            return redirect()->route('items.index')->with('error', 'Gagal memuat data barang atau data ruangan.');
        } catch (\Exception $e) {
            return redirect()->route('items.index')->with('error', 'Koneksi ke backend gagal.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'item_name' => 'required',
            'category' => 'required',
            'stock' => 'required|integer|min:0',
            'room_id' => 'required|integer',
            'type' => 'required|in:inventory,bhp'
        ]);

        try {
            $response = Http::withHeaders($this->getHeaders())->put("{$this->apiUrl}/items/{$id}", [
                'item_name' => $request->item_name,
                'category' => $request->category,
                'stock' => (int) $request->stock,
                'room_id' => (int) $request->room_id,
                'type' => $request->type
            ]);

            if ($response->successful()) {
                return redirect()->route('items.index')->with('success', 'Barang berhasil diperbarui.');
            }

            $msg = $response->json()['message'] ?? 'Gagal memperbarui barang.';
            return back()->withErrors(['api_error' => $msg])->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['api_error' => 'Koneksi ke backend gagal.'])->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())->delete("{$this->apiUrl}/items/{$id}");

            if ($response->successful()) {
                return redirect()->route('items.index')->with('success', 'Barang berhasil dihapus.');
            }

            $msg = $response->json()['message'] ?? 'Gagal menghapus barang.';
            return redirect()->route('items.index')->with('error', $msg);
        } catch (\Exception $e) {
            return redirect()->route('items.index')->with('error', 'Koneksi ke backend gagal.');
        }
    }
}

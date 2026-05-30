<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProcurementController extends Controller
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

    public function index()
    {
        try {
            $response = Http::withHeaders($this->getHeaders())->get("{$this->apiUrl}/procurements");
            if ($response->successful()) {
                $drafts = $response->json();
                return view('procurements.index', compact('drafts'));
            }
            return redirect()->route('dashboard')->with('error', 'Gagal memuat draf pengadaan.');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Koneksi ke backend gagal.');
        }
    }

    public function create()
    {
        try {
            // Fetch items list to let Kalab choose which items to request
            $response = Http::withHeaders($this->getHeaders())->get("{$this->apiUrl}/items");
            $items = $response->successful() ? $response->json() : [];

            // Fetch physical inventories list to let Kalab choose what inventory to replace
            $invResponse = Http::withHeaders($this->getHeaders())->get("{$this->apiUrl}/inventories");
            $inventories = $invResponse->successful() ? $invResponse->json() : [];
            
            return view('procurements.create', compact('items', 'inventories'));
        } catch (\Exception $e) {
            return redirect()->route('procurements.index')->with('error', 'Koneksi ke backend gagal.');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'year' => 'required|integer|min:2020|max:2100',
            'items' => 'required|array',
            'items.*.item_id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.purchase_link' => 'nullable|url',
            'items.*.replaced_inventory_id' => 'nullable|integer'
        ]);

        try {
            // Reformat items array to match API requirements
            $details = [];
            foreach ($request->items as $item) {
                $details[] = [
                    'item_id' => (int) $item['item_id'],
                    'quantity' => (int) $item['quantity'],
                    'price' => (float) $item['price'],
                    'purchase_link' => $item['purchase_link'] ?? null,
                    'replaced_inventory_id' => !empty($item['replaced_inventory_id']) ? (int) $item['replaced_inventory_id'] : null
                ];
            }

            $response = Http::withHeaders($this->getHeaders())->post("{$this->apiUrl}/procurements", [
                'title' => $request->title,
                'year' => (int) $request->year,
                'details' => $details
            ]);

            if ($response->successful()) {
                return redirect()->route('procurements.index')->with('success', 'Draf pengadaan berhasil disimpan.');
            }

            $msg = $response->json()['message'] ?? 'Gagal menyimpan draf pengadaan.';
            return back()->withErrors(['api_error' => $msg])->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['api_error' => 'Koneksi ke backend gagal.'])->withInput();
        }
    }

    public function show($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())->get("{$this->apiUrl}/procurements/{$id}");
            if ($response->successful()) {
                $draft = $response->json();
                return view('procurements.show', compact('draft'));
            }
            return redirect()->route('procurements.index')->with('error', 'Draf pengadaan tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->route('procurements.index')->with('error', 'Koneksi ke backend gagal.');
        }
    }

    public function submit($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())->put("{$this->apiUrl}/procurements/{$id}/submit");
            if ($response->successful()) {
                return redirect()->route('procurements.show', $id)->with('success', 'Draf pengadaan berhasil diajukan ke Kaprodi.');
            }
            $msg = $response->json()['message'] ?? 'Gagal mengajukan draf pengadaan.';
            return redirect()->route('procurements.show', $id)->with('error', $msg);
        } catch (\Exception $e) {
            return redirect()->route('procurements.show', $id)->with('error', 'Koneksi ke backend gagal.');
        }
    }

    public function review(Request $request, $detailId)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'draft_id' => 'required|integer'
        ]);

        try {
            $response = Http::withHeaders($this->getHeaders())->put("{$this->apiUrl}/procurements/detail/{$detailId}/review", [
                'status' => $request->status
            ]);

            if ($response->successful()) {
                $statusMsg = $request->status === 'approved' ? 'disetujui' : 'ditolak';
                return redirect()->route('procurements.show', $request->draft_id)->with('success', "Status barang berhasil di-{$statusMsg}.");
            }
            $msg = $response->json()['message'] ?? 'Gagal mereview barang.';
            return redirect()->route('procurements.show', $request->draft_id)->with('error', $msg);
        } catch (\Exception $e) {
            return redirect()->route('procurements.show', $request->draft_id)->with('error', 'Koneksi ke backend gagal.');
        }
    }

    public function finalize($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())->put("{$this->apiUrl}/procurements/{$id}/finalize");
            if ($response->successful()) {
                return redirect()->route('procurements.show', $id)->with('success', 'Draf pengadaan berhasil di-finalisasi dan dikunci.');
            }
            $msg = $response->json()['message'] ?? 'Gagal memfinalisasi draf pengadaan.';
            return redirect()->route('procurements.show', $id)->with('error', $msg);
        } catch (\Exception $e) {
            return redirect()->route('procurements.show', $id)->with('error', 'Koneksi ke backend gagal.');
        }
    }
}

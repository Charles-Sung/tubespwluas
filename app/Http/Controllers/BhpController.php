<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BhpController extends Controller
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
            $response = Http::withToken($token)->get("{$this->apiUrl}/bhp");
            $bhpItems = $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            $bhpItems = [];
            session()->flash('error', 'Gagal terhubung ke API backend untuk mengambil data stok BHP.');
        }

        return view('bhp.index', compact('bhpItems'));
    }

    public function update(Request $request)
    {
        $token = session('jwt_token');

        $request->validate([
            'item_id' => 'required|integer',
            'quantity' => 'required|integer|min:0',
        ]);

        try {
            $response = Http::withToken($token)->put("{$this->apiUrl}/bhp", [
                'item_id' => $request->item_id,
                'quantity' => $request->quantity,
            ]);

            if ($response->successful()) {
                return redirect()->route('bhp.index')->with('success', 'Stok BHP berhasil diperbarui.');
            }

            $errorMessage = $response->json()['message'] ?? 'Gagal memperbarui stok BHP.';
            return back()->withErrors(['api_error' => $errorMessage]);

        } catch (\Exception $e) {
            return back()->withErrors(['api_error' => 'Gagal terhubung ke API backend.']);
        }
    }
}

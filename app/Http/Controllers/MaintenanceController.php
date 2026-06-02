<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MaintenanceController extends Controller
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
            $response = Http::withToken($token)->get("{$this->apiUrl}/maintenance");
            $logs = $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            $logs = [];
            session()->flash('error', 'Gagal terhubung ke API backend untuk mengambil log maintenance.');
        }

        return view('maintenance.index', compact('logs'));
    }

    public function create()
    {
        $token = session('jwt_token');

        try {
            // Get all inventories for selection
            $invResponse = Http::withToken($token)->get("{$this->apiUrl}/inventories");
            $inventories = $invResponse->successful() ? $invResponse->json() : [];

            // Get all BHP items to allow selecting which BHP is used
            $bhpResponse = Http::withToken($token)->get("{$this->apiUrl}/bhp");
            $bhpItems = $bhpResponse->successful() ? $bhpResponse->json() : [];
        } catch (\Exception $e) {
            $inventories = [];
            $bhpItems = [];
            session()->flash('error', 'Gagal memuat data pendukung form maintenance.');
        }

        return view('maintenance.create', compact('inventories', 'bhpItems'));
    }

    public function store(Request $request)
    {
        $token = session('jwt_token');

        $request->validate([
            'inventory_id' => 'required|integer',
            'description' => 'required|string',
            'new_condition' => 'required|in:good,maintenance,broken',
            'maintenance_date' => 'required|date',
            'bhps' => 'nullable|array',
            'bhps.*.item_id' => 'nullable|integer',
            'bhps.*.quantity' => 'nullable|integer|min:1',
        ]);

        // Filter out empty BHP uses
        $bhpsUsed = [];
        if ($request->has('bhps')) {
            foreach ($request->bhps as $bhp) {
                if (!empty($bhp['item_id']) && !empty($bhp['quantity'])) {
                    $bhpsUsed[] = [
                        'item_id' => (int)$bhp['item_id'],
                        'quantity' => (int)$bhp['quantity']
                    ];
                }
            }
        }

        try {
            $response = Http::withToken($token)->post("{$this->apiUrl}/maintenance", [
                'inventory_id' => (int)$request->inventory_id,
                'description' => $request->description,
                'new_condition' => $request->new_condition,
                'maintenance_date' => $request->maintenance_date,
                'bhps_used' => $bhpsUsed
            ]);

            if ($response->successful()) {
                return redirect()->route('maintenance.index')->with('success', 'Log maintenance berhasil dicatat dan stok BHP berhasil disesuaikan!');
            }

            $errorMessage = $response->json()['message'] ?? 'Gagal mencatat log maintenance.';
            return back()->withErrors(['api_error' => $errorMessage])->withInput();

        } catch (\Exception $e) {
            return back()->withErrors(['api_error' => 'Gagal terhubung ke API backend.'])->withInput();
        }
    }
}

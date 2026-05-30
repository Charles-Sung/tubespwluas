<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_BASE_URL', 'http://localhost:3000/api');
    }

    public function index()
    {
        $token = session('jwt_token');
        $user = session('user');
        $roleId = $user['role_id'] ?? null;

        $stats = [
            'users_count' => 0,
            'rooms_count' => 0,
            'items_count' => 0,
            'total_stock' => 0,
            'procurement_count' => 0,
        ];

        try {
            // Use backend dashboard endpoint (accessible by all roles)
            $dashResponse = Http::withToken($token)->get("{$this->apiUrl}/dashboard");
            if ($dashResponse->successful()) {
                $data = $dashResponse->json();
                $stats['rooms_count'] = $data['rooms_count'] ?? 0;
                $stats['items_count'] = $data['items_count'] ?? 0;
                $stats['total_stock'] = $data['total_stock'] ?? 0;
                $stats['users_count'] = $data['users_count'] ?? 0;
            }

            // Fetch procurement drafts count (all roles)
            $procResponse = Http::withToken($token)->get("{$this->apiUrl}/procurements");
            if ($procResponse->successful()) {
                $stats['procurement_count'] = count($procResponse->json());
            }

        } catch (\Exception $e) {
            session()->flash('error', 'Koneksi ke backend API terputus.');
        }

        return view('dashboard', compact('stats'));
    }
}

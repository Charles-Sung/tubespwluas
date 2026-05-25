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
        
        $stats = [
            'users_count' => 0,
            'rooms_count' => 0,
            'items_count' => 0,
            'total_stock' => 0,
        ];

        try {
            // Fetch users
            $usersResponse = Http::withToken($token)->get("{$this->apiUrl}/users");
            if ($usersResponse->successful()) {
                $stats['users_count'] = count($usersResponse->json());
            }

            // Fetch rooms
            $roomsResponse = Http::withToken($token)->get("{$this->apiUrl}/rooms");
            if ($roomsResponse->successful()) {
                $stats['rooms_count'] = count($roomsResponse->json());
            }

            // Fetch items
            $itemsResponse = Http::withToken($token)->get("{$this->apiUrl}/items");
            if ($itemsResponse->successful()) {
                $items = $itemsResponse->json();
                $stats['items_count'] = count($items);
                $stats['total_stock'] = array_sum(array_column($items, 'stock'));
            }

        } catch (\Exception $e) {
            // If connection to API fails, we still render dashboard but with 0 count
            session()->flash('error', 'Koneksi ke backend API terputus. Beberapa data mungkin tidak dapat ditampilkan.');
        }

        return view('dashboard', compact('stats'));
    }
}

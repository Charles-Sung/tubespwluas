<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RoomController extends Controller
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
            $response = Http::withHeaders($this->getHeaders())->get("{$this->apiUrl}/rooms");
            if ($response->successful()) {
                $rooms = $response->json();
                return view('rooms.index', compact('rooms'));
            }
            return redirect()->route('dashboard')->with('error', 'Gagal memuat data ruangan.');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Koneksi ke backend gagal.');
        }
    }

    public function create()
    {
        return view('rooms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_name' => 'required',
            'location' => 'required',
            'capacity' => 'required|integer|min:1'
        ]);

        try {
            $response = Http::withHeaders($this->getHeaders())->post("{$this->apiUrl}/rooms", [
                'room_name' => $request->room_name,
                'location' => $request->location,
                'capacity' => (int) $request->capacity,
            ]);

            if ($response->successful()) {
                return redirect()->route('rooms.index')->with('success', 'Ruangan berhasil ditambahkan.');
            }

            $msg = $response->json()['message'] ?? 'Gagal menambahkan ruangan.';
            return back()->withErrors(['api_error' => $msg])->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['api_error' => 'Koneksi ke backend gagal.'])->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())->get("{$this->apiUrl}/rooms/{$id}");
            if ($response->successful()) {
                $room = $response->json();
                return view('rooms.edit', compact('room'));
            }
            return redirect()->route('rooms.index')->with('error', 'Ruangan tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->route('rooms.index')->with('error', 'Koneksi ke backend gagal.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'room_name' => 'required',
            'location' => 'required',
            'capacity' => 'required|integer|min:1'
        ]);

        try {
            $response = Http::withHeaders($this->getHeaders())->put("{$this->apiUrl}/rooms/{$id}", [
                'room_name' => $request->room_name,
                'location' => $request->location,
                'capacity' => (int) $request->capacity,
            ]);

            if ($response->successful()) {
                return redirect()->route('rooms.index')->with('success', 'Ruangan berhasil diperbarui.');
            }

            $msg = $response->json()['message'] ?? 'Gagal memperbarui ruangan.';
            return back()->withErrors(['api_error' => $msg])->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['api_error' => 'Koneksi ke backend gagal.'])->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())->delete("{$this->apiUrl}/rooms/{$id}");

            if ($response->successful()) {
                return redirect()->route('rooms.index')->with('success', 'Ruangan berhasil dihapus.');
            }

            $msg = $response->json()['message'] ?? 'Gagal menghapus ruangan.';
            return redirect()->route('rooms.index')->with('error', $msg);
        } catch (\Exception $e) {
            return redirect()->route('rooms.index')->with('error', 'Koneksi ke backend gagal.');
        }
    }
}

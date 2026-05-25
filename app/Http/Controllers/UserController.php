<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
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
            $response = Http::withHeaders($this->getHeaders())->get("{$this->apiUrl}/users");
            if ($response->successful()) {
                $users = $response->json();
                return view('users.index', compact('users'));
            }
            return redirect()->route('dashboard')->with('error', 'Gagal memuat data user.');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Koneksi ke backend gagal.');
        }
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        try {
            $response = Http::withHeaders($this->getHeaders())->post("{$this->apiUrl}/users", [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'role' => $request->role,
            ]);

            if ($response->successful()) {
                return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
            }

            $msg = $response->json()['message'] ?? 'Gagal menambahkan user.';
            return back()->withErrors(['api_error' => $msg])->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['api_error' => 'Koneksi ke backend gagal.'])->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())->get("{$this->apiUrl}/users/{$id}");
            if ($response->successful()) {
                $user = $response->json();
                return view('users.edit', compact('user'));
            }
            return redirect()->route('users.index')->with('error', 'User tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'Koneksi ke backend gagal.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required'
        ]);

        $payload = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $payload['password'] = $request->password;
        }

        try {
            $response = Http::withHeaders($this->getHeaders())->put("{$this->apiUrl}/users/{$id}", $payload);

            if ($response->successful()) {
                return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
            }

            $msg = $response->json()['message'] ?? 'Gagal memperbarui user.';
            return back()->withErrors(['api_error' => $msg])->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['api_error' => 'Koneksi ke backend gagal.'])->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())->delete("{$this->apiUrl}/users/{$id}");

            if ($response->successful()) {
                return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
            }

            $msg = $response->json()['message'] ?? 'Gagal menghapus user.';
            return redirect()->route('users.index')->with('error', $msg);
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'Koneksi ke backend gagal.');
        }
    }
}

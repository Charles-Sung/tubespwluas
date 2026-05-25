<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_BASE_URL', 'http://localhost:3000/api');
    }

    public function showLogin()
    {
        if (session()->has('jwt_token')) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            $response = Http::post("{$this->apiUrl}/login", [
                'email' => $request->email,
                'password' => $request->password,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Store JWT and user details in session
                session([
                    'jwt_token' => $data['token'],
                    'user' => $data['user']
                ]);

                return redirect()->route('dashboard')->with('success', 'Login berhasil! Selamat datang.');
            }

            $errorMessage = $response->json()['message'] ?? 'Email atau password salah.';
            return back()->withErrors(['login_error' => $errorMessage])->withInput();

        } catch (\Exception $e) {
            return back()->withErrors(['login_error' => 'Koneksi ke server API backend gagal. Pastikan backend berjalan.'])->withInput();
        }
    }

    public function logout(Request $request)
    {
        session()->forget(['jwt_token', 'user']);
        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }
}

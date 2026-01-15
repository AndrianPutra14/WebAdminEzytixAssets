<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('loginpage'); // nama blade login
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required',
        ]);

        // POST ke API login
        $response = Http::post(env('API_URL') . '/auth/login', [
    'username' => $request->username,
    'password' => $request->password,
]);


        if ($response->successful()) {
            $data = $response->json();

            // Pastikan hanya admin yang bisa login
            if ($data['user']['role'] !== 'admin') {
                return back()->withErrors([
                    'username' => 'Hanya admin yang bisa login.',
                ]);
            }

            // Simpan token + user
            session([
                'token' => $data['token'],
                'user'  => $data['user'],
            ]);

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah',
        ]);
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('login.form');
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ApiService
{
    public static function request(string $method, string $endpoint, array $data = [])
    {
        $request = Http::acceptJson();

        // Tambahkan token jika ada
        if (session()->has('token')) {
            $request = $request->withToken(session('token'));
        }

        $url = config('services.api.base_url') . $endpoint;

        return $request->$method($url, $data);
    }
}

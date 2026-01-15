<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = session('token');
        $user  = session('user');

        if (!$token || !$user) {
            return redirect()->route('login.form');
        }

        // Cek role admin
        if ($user['role'] !== 'admin') {
            abort(403, 'Unauthorized: Only admin allowed');
        }

        return $next($request);
    }
}

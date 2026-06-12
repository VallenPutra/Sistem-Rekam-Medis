<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Ubah semua inputan array roles menjadi lowercase
        $lowerRoles = array_map('strtolower', $roles);

        // Cek dengan role user yang sudah di-lowercase juga
        if (!in_array(strtolower(auth()->user()->role), $lowerRoles)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk halaman ini.');
        }

        return $next($request);
    }
}
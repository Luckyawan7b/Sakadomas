<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Jika user belum login, atau rolenya tidak sesuai dengan yang diminta, tolak aksesnya!
        if (!Auth::check() || Auth::user()->role !== $role) {
            // Lempar kembali ke dashboard dengan pesan error (bisa juga pakai abort(403))
            return redirect('/')->with('error', 'Akses Ditolak! Anda tidak memiliki izin untuk membuka halaman tersebut.');
        }

        return $next($request);
    }
}

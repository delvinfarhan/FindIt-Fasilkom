<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! $request->user()) {
            return redirect()->route('login')->with('warning', 'Silakan masuk terlebih dahulu.');
        }

        if ($request->user()->role !== $role) {
            if ($role === 'petugas') {
                return redirect()->route('dashboard')->with('error', 'Akses ditolak: Halaman ini hanya dapat diakses oleh Petugas Security FASILKOM.');
            }

            return redirect()->route('officer.dashboard')->with('info', 'Anda diarahkan ke panel operasional Petugas Security.');
        }

        return $next($request);
    }
}

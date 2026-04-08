<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Jika user_id tidak ada di session, redirect ke login
        if (!session('user_id')) {
            return redirect('/login')
                ->with('error', 'Silakan login terlebih dahulu');
        }

        // Cek apakah role user ada di list role yang diizinkan
        if (!in_array(session('user_role'), $roles)) {
            return redirect('/login')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        return $next($request);
    }
}

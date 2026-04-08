<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user_id tidak ada di session, redirect ke login
        if (!session('user_id')) {
            return redirect('/login')
                ->with('error', 'Silakan login terlebih dahulu');
        }

        return $next($request);
    }
}

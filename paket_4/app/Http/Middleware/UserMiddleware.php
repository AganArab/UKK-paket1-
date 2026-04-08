<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class UserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->session()->get('user');

        if (! $user || $user['role'] !== 'siswa') {
            return Redirect::to('/login');
        }

        return $next($request);
    }
}

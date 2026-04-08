<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->session()->get('user');

        if (! $user || $user['role'] !== 'admin') {
            return Redirect::to('/login');
        }

        return $next($request);
    }
}

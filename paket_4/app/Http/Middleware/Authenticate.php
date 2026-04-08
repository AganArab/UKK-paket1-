<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class Authenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->session()->has('user')) {
            return Redirect::to('/login');
        }

        return $next($request);
    }
}

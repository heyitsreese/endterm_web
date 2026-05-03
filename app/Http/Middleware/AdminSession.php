<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminSession
{
    public function handle($request, Closure $next)
    {
        if (!session('user_id')) {
            return redirect('/login')->with('error', 'Please login first.');
        }

        return $next($request);
    }
}

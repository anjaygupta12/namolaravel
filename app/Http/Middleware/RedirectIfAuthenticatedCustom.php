<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticatedCustom
{
    public function handle($request, Closure $next)
    {
        if (!Auth::guard('tradeuser')->check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        return $next($request);
    }
}

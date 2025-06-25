<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticatedCustom
{
    public function handle($request, Closure $next)
    {
        $isLoggedIn = Auth::check();
        $path = $request->path();
        dd($isLoggedIn);
        if ($isLoggedIn && ($path == '/' || $path == 'login')) {
            return redirect('/dashboard');
        }

        if (!$isLoggedIn && $path !== 'login') {
            return redirect('/login');
        }

        return $next($request);
    }
}

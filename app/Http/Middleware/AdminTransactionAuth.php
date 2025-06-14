<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Admin\AuthController;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminTransactionAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // First ensure admin is logged in
        if (!Session::has('admin_id')) {
            return redirect()->route('admin.login')
                ->with('error', 'Please login to access the admin panel.');
        }
        
        // Check if transaction password is verified and not expired
        if (!AuthController::isTransactionVerified()) {
            // Store the intended URL to redirect back after verification
            Session::put('url.intended', $request->url());
            
            return redirect()->route('admin.transaction.password')
                ->with('error', 'This action requires transaction password verification.');
        }
        
        return $next($request);
    }
}

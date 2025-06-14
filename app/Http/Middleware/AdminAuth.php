<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminAuth
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
        // Check if admin is logged in
        if (!Session::has('admin_id')) {
            return redirect()->route('admin.login')
                ->with('error', 'Please login to access the admin panel.');
        }
        
        // Check for session timeout (30 minutes of inactivity)
        $lastActivity = Session::get('admin_last_activity');
        $timeout = 30 * 60; // 30 minutes in seconds
        
        if ($lastActivity && time() - $lastActivity > $timeout) {
            // Clear admin session
            Session::forget(['admin_id', 'admin_name', 'admin_username', 'admin_type', 'admin_last_activity', 'transaction_verified']);
            
            return redirect()->route('admin.login')
                ->with('error', 'Your session has expired due to inactivity. Please login again.');
        }
        
        // Update last activity time
        Session::put('admin_last_activity', time());
        
        return $next($request);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLogin;
use App\Models\AdminLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show the admin login form
     */
    public function showLoginForm()
    {
        // If admin is already logged in, redirect to dashboard
        if (Session::has('admin_id')) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.auth.login');
    }

    /**
     * Handle admin login request
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        $admin = AdminLogin::where('UserName', $request->username)
                          ->where('Isactive', 1)
                          ->first();

        if (!$admin || !Hash::check($request->password, $admin->Password)) {
            return redirect()->back()
                ->withErrors(['login_error' => 'The provided credentials do not match our records.'])
                ->withInput($request->except('password'));
        }

        // Store admin info in session
        Session::put('admin_id', $admin->PK_ID);
        Session::put('admin_name', $admin->Name);
        Session::put('admin_username', $admin->UserName);
        Session::put('admin_type', $admin->UserType);
        Session::put('admin_last_activity', time());
        
        // Log admin login
        AdminLog::create([
            'admin_id' => $admin->PK_ID,
            'activity' => 'Login successful',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return redirect()->route('admin.dashboard');
    }

    /**
     * Log the admin out
     */
    public function logout(Request $request)
    {
        if (Session::has('admin_id')) {
            // Log admin logout
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Logout successful',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }
        
        // Clear all admin session data
        Session::forget(['admin_id', 'admin_name', 'admin_username', 'admin_type', 'admin_last_activity', 'transaction_verified']);
        
        return redirect()->route('admin.login')->with('success', 'You have been successfully logged out.');
    }
    
    /**
     * Show transaction password form
     */
    public function showTransactionPasswordForm()
    {
        if (!Session::has('admin_id')) {
            return redirect()->route('admin.login');
        }
        
        return view('admin.auth.transaction_password');
    }
    
    /**
     * Verify transaction password
     */
    public function verifyTransactionPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_password' => 'required',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $admin = AdminLogin::find(Session::get('admin_id'));

        if (!$admin || !Hash::check($request->transaction_password, $admin->TransPass)) {
            return redirect()->back()
                ->withErrors(['transaction_password' => 'Invalid transaction password.']);
        }

        // Set transaction verification for 30 minutes
        Session::put('transaction_verified', true);
        Session::put('transaction_verified_at', time());
        
        // Log transaction password verification
        AdminLog::create([
            'admin_id' => Session::get('admin_id'),
            'activity' => 'Transaction password verified',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        // Redirect to intended URL or dashboard
        return redirect()->intended(route('admin.dashboard'));
    }
    
    /**
     * Check if transaction password verification has expired
     * 
     * @return bool
     */
    public static function isTransactionVerified()
    {
        if (!Session::has('transaction_verified') || !Session::has('transaction_verified_at')) {
            return false;
        }
        
        // Check if verification has expired (30 minutes)
        $expiry = Session::get('transaction_verified_at') + (30 * 60);
        
        return time() <= $expiry;
    }
}

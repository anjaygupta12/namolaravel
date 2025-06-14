<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use App\Models\TradeUser;
use App\Models\UserTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * View detailed user information including trades and orders
     *
     * @param int $id User ID
     * @return \Illuminate\View\View
     */
    public function viewUser($id)
    {
        // Get user details
        $user = TradeUser::findOrFail($id);
        
        // Get active trades
        $activeTrades = DB::table('trades')
            ->where('user_id', $id)
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Get closed trades
        $closedTrades = DB::table('trades')
            ->where('user_id', $id)
            ->where('status', 'closed')
            ->orderBy('closed_at', 'desc')
            ->get();
            
        // Get pending orders - MCX
        $mcxPendingOrders = DB::table('pending_orders')
            ->where('user_id', $id)
            ->where('market', 'mcx')
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Get pending orders - Equity
        $equityPendingOrders = DB::table('pending_orders')
            ->where('user_id', $id)
            ->where('market', 'equity')
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Get pending orders - COMEX
        $comexPendingOrders = DB::table('pending_orders')
            ->where('user_id', $id)
            ->where('market', 'comex')
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Log admin activity
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'action' => 'Viewed user details for ' . $user->name . ' (ID: ' . $user->id . ')',
                'ip_address' => request()->ip()
            ]);
        }
        
        return view('admin.users-view', compact(
            'user',
            'activeTrades',
            'closedTrades',
            'mcxPendingOrders',
            'equityPendingOrders',
            'comexPendingOrders'
        ));
    }
    
    /**
     * Display MCX user views page with active trades, closed trades, and pending orders
     *
     * @param int $id User ID
     * @return \Illuminate\View\View
     */
    public function mcxUsersViews($id)
    {
        try {
            // Get user details
            $user = TradeUser::findOrFail($id);
            
            // Initialize variables with empty collections
            $activeTrades = collect([]);
            $closedTrades = collect([]);
            $mcxPendingOrders = collect([]);
            $equityPendingOrders = collect([]);
            $comexPendingOrders = collect([]);
            
            // Check if trades table exists before querying
            if (Schema::hasTable('trades')) {
                // Get active trades
                $activeTrades = DB::table('trades')
                    ->where('user_id', $id)
                    ->where('status', 'active')
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
                // Get closed trades
                $closedTrades = DB::table('trades')
                    ->where('user_id', $id)
                    ->where('status', 'closed')
                    ->orderBy('closed_at', 'desc')
                    ->get();
            }
            
            // Check if pending_orders table exists before querying
            if (Schema::hasTable('pending_orders')) {
                // Get pending orders - MCX
                $mcxPendingOrders = DB::table('pending_orders')
                    ->where('user_id', $id)
                    ->where('market', 'mcx')
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
                // Get pending orders - Equity
                $equityPendingOrders = DB::table('pending_orders')
                    ->where('user_id', $id)
                    ->where('market', 'equity')
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
                // Get pending orders - COMEX
                $comexPendingOrders = DB::table('pending_orders')
                    ->where('user_id', $id)
                    ->where('market', 'comex')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
            
            // Log admin activity
            if (Session::has('admin_id')) {
                AdminLog::create([
                    'admin_id' => Session::get('admin_id'),
                    'action' => 'Viewed MCX details for user ' . $user->name . ' (ID: ' . $user->id . ')',
                    'ip_address' => request()->ip()
                ]);
            }
            
            return view('admin.mcxusers-views', compact(
                'user',
                'activeTrades',
                'closedTrades',
                'mcxPendingOrders',
                'equityPendingOrders',
                'comexPendingOrders'
            ));
            
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error in mcxUsersViews: ' . $e->getMessage());
            
            // Redirect back with error message
            return redirect()->route('admin.users')->with('error', 'Error viewing user details: ' . $e->getMessage());
        }
    }
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = TradeUser::query();
        
        // Apply filters if provided
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('status')) {
            $query->where('is_active', $request->status);
        }
        
        // Order by
        $orderBy = $request->order_by ?? 'created_at';
        $orderDir = $request->order_dir ?? 'desc';
        $query->orderBy($orderBy, $orderDir);
        
        // Paginate results
        $users = $query->paginate(15);
        
        return view('admin.users.index', compact('users'));
    }
    
    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('admin.users.create');
    }
    
    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:trade_users,email',
            'mobile' => 'required|string|max:15|unique:trade_users,mobile',
            'password' => 'required|string|min:8',
            'initial_balance' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }
        
        DB::beginTransaction();
        
        try {
            // Create user
            $user = TradeUser::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => Hash::make($request->password),
                'is_active' => $request->is_active ?? 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Add initial balance if provided
            if ($request->filled('initial_balance') && $request->initial_balance > 0) {
                UserTransaction::create([
                    'user_id' => $user->id,
                    'type' => 'deposit',
                    'amount' => $request->initial_balance,
                    'status' => 'completed',
                    'description' => 'Initial balance added by admin',
                    'created_by' => Session::get('admin_id'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            // Log admin action
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => "Created new user: {$user->name} (ID: {$user->id})",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.users.index')
                ->with('success', 'User created successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create user. ' . $e->getMessage()])
                ->withInput($request->except('password'));
        }
    }
    
    /**
     * Display the specified user
     */
    public function show($id)
    {
        $user = TradeUser::with(['transactions' => function($query) {
            $query->orderBy('created_at', 'desc')->limit(10);
        }])->findOrFail($id);
        
        return view('admin.users.show', compact('user'));
    }
    
    /**
     * Show the form for editing the specified user
     */
    public function edit($id)
    {
        $user = TradeUser::findOrFail($id);
        
        return view('admin.users.edit', compact('user'));
    }
    
    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        $user = TradeUser::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:trade_users,email,' . $id,
            'mobile' => 'required|string|max:15|unique:trade_users,mobile,' . $id,
            'password' => 'nullable|string|min:8',
            'is_active' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }
        
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'is_active' => $request->is_active ?? $user->is_active,
            'updated_at' => now(),
        ];
        
        // Update password if provided
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        
        $user->update($userData);
        
        // Log admin action
        AdminLog::create([
            'admin_id' => Session::get('admin_id'),
            'activity' => "Updated user: {$user->name} (ID: {$user->id})",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }
    
    /**
     * Toggle user active status
     */
    public function toggleStatus($id)
    {
        $user = TradeUser::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();
        
        $status = $user->is_active ? 'activated' : 'deactivated';
        
        // Log admin action
        AdminLog::create([
            'admin_id' => Session::get('admin_id'),
            'activity' => "User {$status}: {$user->name} (ID: {$user->id})",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        
        return redirect()->back()
            ->with('success', "User {$status} successfully.");
    }
    
    /**
     * Show user transaction history
     */
    public function transactions($id)
    {
        $user = TradeUser::findOrFail($id);
        
        $transactions = UserTransaction::where('user_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.users.transactions', compact('user', 'transactions'));
    }
    
    /**
     * Show form to add balance to user
     */
    public function showAddBalance($id)
    {
        $user = TradeUser::findOrFail($id);
        
        return view('admin.users.add_balance', compact('user'));
    }
    
    /**
     * Add balance to user account
     */
    public function addBalance(Request $request, $id)
    {
        $user = TradeUser::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string|max:255',
            'transaction_type' => 'required|in:deposit,bonus',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        DB::beginTransaction();
        
        try {
            // Create transaction
            UserTransaction::create([
                'user_id' => $user->id,
                'type' => $request->transaction_type,
                'amount' => $request->amount,
                'status' => 'completed',
                'description' => $request->description,
                'created_by' => Session::get('admin_id'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Log admin action
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => "Added {$request->amount} as {$request->transaction_type} to user: {$user->name} (ID: {$user->id})",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.users.transactions', $user->id)
                ->with('success', 'Balance added successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withErrors(['error' => 'Failed to add balance. ' . $e->getMessage()])
                ->withInput();
        }
    }
}

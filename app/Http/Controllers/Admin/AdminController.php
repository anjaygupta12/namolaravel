<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLogin;
use App\Models\AdminLog;
use App\Models\SocialLink;
use App\Models\Setting;
use App\Models\Notification;
use App\Models\TradeUser;
use App\Models\UserTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\DepositeMaster;
use App\Models\Transdetail;
use App\Models\Fund;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{    
    /**
     * Display admin dashboard with summary statistics
     */
    public function dashboard()
    {        
        // Get user statistics
        $totalUsers = TradeUser::count();
        $activeUsers = TradeUser::where('is_active', 1)->count();
        $newUsers = TradeUser::whereDate('created_at', Carbon::today())->count();
        
        // Get transaction statistics
        $totalDeposits = UserTransaction::where('type', 'deposit')->where('status', 'completed')->sum('amount');
        $totalWithdrawals = UserTransaction::where('type', 'withdrawal')->where('status', 'completed')->sum('amount');
        $pendingWithdrawals = UserTransaction::where('type', 'withdrawal')->where('status', 'pending')->count();
        $pendingDeposits = UserTransaction::where('type', 'deposit')->where('status', 'pending')->count();
        
        // Get recent activities
        $recentActivities = AdminLog::with('admin')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Get recent users
        $recentUsers = TradeUser::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Log the dashboard view
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed admin dashboard',
                'ip_address' => request()->ip()
            ]);
        }
        
        return view('admin.dashboard', compact(
            'totalUsers', 'activeUsers', 'newUsers',
            'totalDeposits', 'totalWithdrawals', 
            'pendingWithdrawals', 'pendingDeposits',
            'recentActivities', 'recentUsers'
        ));
    }

    /**
     * Display admin profile
     */
    public function profile()
    {
        $admin = AdminLogin::find(Session::get('admin_id'));
        return view('admin.profile', compact('admin'));
    }
    
    /**
     * Show change password form
     */
    public function changePassword()
    {
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed change password page',
                'ip_address' => request()->ip()
            ]);
        }
        return view('admin.change-password');
    }
    
    /**
     * Update admin password
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $admin = AdminLogin::find(Session::get('admin_id'));
        
        // Check if current password is correct
        if (!Hash::check($request->current_password, $admin->Password)) {
            return redirect()->back()
                ->with('error', 'Current password is incorrect.');
        }
        
        // Update password
        $admin->Password = Hash::make($request->new_password);
        $admin->save();
        
        // Log the action
        AdminLog::create([
            'admin_id' => Session::get('admin_id'),
            'activity' => 'Changed admin password',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return redirect()->back()
            ->with('success', 'Password updated successfully.');
    }
    
    /**
     * Show change transaction password form
     */
    public function changeTransactionPassword()
    {
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed change transaction password page',
                'ip_address' => request()->ip()
            ]);
        }
        return view('admin.transaction-password');
    }
    
    /**
     * Update admin transaction password
     */
    public function updateTransactionPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_transaction_password' => 'required',
            'new_transaction_password' => 'required|min:6|confirmed',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $admin = AdminLogin::find(Session::get('admin_id'));
        
        // Check if current transaction password is correct
        if (!Hash::check($request->current_transaction_password, $admin->TransPass)) {
            return redirect()->back()
                ->with('error', 'Current transaction password is incorrect.');
        }
        
        // Update transaction password
        $admin->TransPass = Hash::make($request->new_transaction_password);
        $admin->save();
        
        // Log the action
        AdminLog::create([
            'admin_id' => Session::get('admin_id'),
            'activity' => 'Changed admin transaction password',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return redirect()->back()
            ->with('success', 'Transaction password updated successfully.');
    }
    
    /**
     * Show social links page
     */
    public function socialLinks()
    {
        $socialLinks = SocialLink::first();
        return view('admin.social-links', compact('socialLinks'));
    }
    
    /**
     * Update social links
     */
    public function updateSocialLinks(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'instagram' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'youtube' => 'nullable|url',
            'telegram' => 'nullable|url',
            'whatsapp' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $socialLinks = SocialLink::first();
        
        if (!$socialLinks) {
            $socialLinks = new SocialLink();
        }
        
        $socialLinks->facebook = $request->facebook;
        $socialLinks->twitter = $request->twitter;
        $socialLinks->instagram = $request->instagram;
        $socialLinks->linkedin = $request->linkedin;
        $socialLinks->youtube = $request->youtube;
        $socialLinks->telegram = $request->telegram;
        $socialLinks->whatsapp = $request->whatsapp;
        $socialLinks->save();
        
        // Log the action
        AdminLog::create([
            'admin_id' => Session::get('admin_id'),
            'activity' => 'Updated social links',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return redirect()->back()
            ->with('success', 'Social links updated successfully.');
    }
    
    /**
     * Show notifications page
     */
    public function notifications()
    {
        $notifications = Notification::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.notifications', compact('notifications'));
    }
    
    /**
     * Create a new notification
     */
    public function createNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'message' => 'required|string',
            'type' => 'required|in:info,warning,success,danger',
            'for_all_users' => 'boolean',
            'user_ids' => 'required_if:for_all_users,0|array',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $notification = new Notification();
        $notification->title = $request->title;
        $notification->message = $request->message;
        $notification->type = $request->type;
        
        if ($request->has('for_all_users') && $request->for_all_users) {
            $notification->for_all_users = 1;
            $notification->user_ids = null;
        } else {
            $notification->for_all_users = 0;
            $notification->user_ids = $request->user_ids;
        }
        
        $notification->created_by = Session::get('admin_id');
        $notification->save();
        
        // Log the action
        AdminLog::create([
            'admin_id' => Session::get('admin_id'),
            'activity' => 'Created new notification: ' . $request->title,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return redirect()->back()
            ->with('success', 'Notification created successfully.');
    }
    
    /**
     * Delete a notification
     */
    public function deleteNotification($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();
        
        // Log the action
        AdminLog::create([
            'admin_id' => Session::get('admin_id'),
            'activity' => 'Deleted notification: ' . $notification->title,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        
        return redirect()->back()
            ->with('success', 'Notification deleted successfully.');
    }
    
    /**
     * Show action ledger (admin logs)
     */
    public function actionLedger(Request $request)
    {
        $query = AdminLog::with('admin');
        
        // Apply filters
        if ($request->has('admin_id')) {
            $query->where('admin_id', $request->admin_id);
        }
        
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('activity', 'like', "%{$search}%");
        }
        
        $logs = $query->orderBy('created_at', 'desc')->paginate(20);
        $admins = AdminLogin::all();
        
        return view('admin.action-ledger', compact('logs', 'admins'));
    }
    
    /**
     * Show bank details page
     */
    public function bankDetails()
    {
        // Check if admin is logged in
        if (!Session::has('admin_id')) {
            return redirect('admin/login');
        }
        
        $adminId = Session::get('admin_id');
        
        // Try to get bank details from bank_details table
        $bankDetails = null;
        if (Schema::hasTable('bank_details')) {
            $bankDetails = DB::table('bank_details')
                ->where('admin_id', $adminId)
                ->first();
        } else {
            // Fallback to settings table if bank_details table doesn't exist
            $bankDetailsSetting = Setting::where('key', 'bank_details')->first();
            if ($bankDetailsSetting) {
                $bankDetails = json_decode($bankDetailsSetting->value);
            }
        }
        
        // Log admin activity
        AdminLog::create([
            'admin_id' => $adminId,
            'activity' => 'Viewed bank details',
            'ip_address' => request()->ip()
        ]);
        
        return view('admin.bank-details', compact('bankDetails'));
    }
    
    /**
     * Update bank details
     */
    public function updateBankDetails(Request $request)
    {
        // Check if admin is logged in
        if (!Session::has('admin_id')) {
            return redirect('admin/login');
        }
        
        $adminId = Session::get('admin_id');
        
        // Validate request
        $validator = Validator::make($request->all(), [
            'account_holder' => 'required',
            'account_number' => 'required',
            'bank_name' => 'required',
            'ifsc' => 'required',
            'qr_code' => 'nullable|image|max:1024', // Max 1MB
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Handle QR code upload
        $qrCodeFileName = null;
        if ($request->hasFile('qr_code')) {
            $file = $request->file('qr_code');
            $extension = $file->getClientOriginalExtension();
            $qrCodeFileName = uniqid() . '.' . $extension;
            
            // Create directory if it doesn't exist
            $uploadPath = public_path('uploads/qr_codes');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $file->move($uploadPath, $qrCodeFileName);
        }
        
        // Check if bank_details table exists
        if (Schema::hasTable('bank_details')) {
            // Check if bank details already exist
            $bankDetails = DB::table('bank_details')
                ->where('admin_id', $adminId)
                ->first();
            
            $data = [
                'admin_id' => $adminId,
                'account_holder' => $request->account_holder,
                'account_number' => $request->account_number,
                'bank_name' => $request->bank_name,
                'ifsc' => $request->ifsc,
                'phonepe' => $request->phonepe ?? '',
                'google_pay' => $request->google_pay ?? '',
                'paytm' => $request->paytm ?? '',
                'upi_id' => $request->upi_id ?? '',
                'updated_at' => now()
            ];
            
            // Add QR code to data if uploaded
            if ($qrCodeFileName) {
                $data['qr_code'] = $qrCodeFileName;
            }
            
            if ($bankDetails) {
                // Update existing record
                DB::table('bank_details')
                    ->where('admin_id', $adminId)
                    ->update($data);
                    
                $message = 'Bank details updated successfully';
            } else {
                // Insert new record
                $data['created_at'] = now();
                DB::table('bank_details')->insert($data);
                
                $message = 'Bank details added successfully';
            }
        } else {
            // Fallback to settings table if bank_details table doesn't exist
            Setting::updateOrCreate(
                ['key' => 'bank_details'],
                [
                    'value' => json_encode([
                        'bank_name' => $request->bank_name,
                        'account_holder' => $request->account_holder,
                        'account_number' => $request->account_number,
                        'ifsc' => $request->ifsc,
                        'phonepe' => $request->phonepe ?? '',
                        'google_pay' => $request->google_pay ?? '',
                        'paytm' => $request->paytm ?? '',
                        'upi_id' => $request->upi_id ?? '',
                        'qr_code' => $qrCodeFileName ?? ''
                    ])
                ]
            );
            
            $message = 'Bank details updated successfully';
        }
        
        // Log admin activity
        AdminLog::create([
            'admin_id' => $adminId,
            'activity' => 'Updated bank details',
            'ip_address' => request()->ip()
        ]);
        
        return redirect()->route('admin.bank-details')
            ->with('success', $message);
    }
    
    public function bankDetailsEdit($id)
    {
        // Get bank details from settings
        // Check if admin is logged in
        if (!Session::has('admin_id')) {
            return redirect('admin/login');
        }
        
        $adminId = Session::get('admin_id');
        
        // Try to get bank details from bank_details table
        $bankDetails = null;
        if (Schema::hasTable('bank_details')) {
            $bankDetails = DB::table('bank_details')
                ->where('admin_id', $adminId)
                ->first();
        } else {
            // Fallback to settings table if bank_details table doesn't exist
            $bankDetailsSetting = Setting::where('key', 'bank_details')->first();
            if ($bankDetailsSetting) {
                $bankDetails = json_decode($bankDetailsSetting->value);
            }
        }
        
        // Log admin activity
        AdminLog::create([
            'admin_id' => $adminId,
            'activity' => 'Viewed bank details',
            'ip_address' => request()->ip()
        ]);

        return view('admin.bank-details-edit', compact('bankDetails'));
    }
    /**
     * Show users with negative balance
     */
    public function negativeBalance()
    {
        // Get all users
        $allUsers = TradeUser::all();
        
        // Filter users with negative balance
        $negativeBalanceUsers = $allUsers->filter(function($user) {
            return $user->balance < 0;
        })->sortBy('balance');
        
        // Paginate the filtered results
        $currentPage = request()->get('page', 1);
        $perPage = 20;
        $users = new \Illuminate\Pagination\LengthAwarePaginator(
            $negativeBalanceUsers->forPage($currentPage, $perPage),
            $negativeBalanceUsers->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url()]
        );
        
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed negative balance users',
                'ip_address' => request()->ip()
            ]);
        }
        
        return view('admin.negative-balance', compact('users'));
    }
    
    /**
     * Show market watch page
     */
    public function marketWatch()
    {
        // Get market data
        $marketData = [];
        
        // You may need to replace this with actual market data retrieval logic
        // For example, fetching from an API or database
        
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed market watch',
                'ip_address' => request()->ip()
            ]);
        }
        
        return view('admin.market-watch', compact('marketData'));
    }
    
    /**
     * Show active positions page
     */
    public function activePositions()
    {
        // Get active positions data - typically current open trades or positions
        $positions = []; // Replace with actual data fetching logic
        
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed active positions',
                'ip_address' => request()->ip()
            ]);
        }
        
        return view('admin.active-positions', compact('positions'));
    }
    
    /**
     * Show closed positions page
     */
    public function closedPositions()
    {
        // Get closed positions data - typically historical/completed trades
        $positions = []; // Replace with actual data fetching logic
        
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed closed positions',
                'ip_address' => request()->ip()
            ]);
        }
        
        return view('admin.closed-positions', compact('positions'));
    }
    
    /**
     * Show users page
     */
    public function users(Request $request)
    {
        // Start with a base query
        $query = TradeUser::query();
        
        // Apply filters if provided
        if ($request->has('search_name') && !empty($request->search_name)) {
            $query->where('name', 'like', '%' . $request->search_name . '%');
        }
        
        if ($request->has('search_email') && !empty($request->search_email)) {
            $query->where('email', 'like', '%' . $request->search_email . '%');
        }
        
        if ($request->has('search_status') && $request->search_status !== '') {
            $query->where('is_active', $request->search_status);
        }
        
        // Get users data
        $users = $query->orderBy('created_at', 'desc')->get();
        
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed users list',
                'ip_address' => request()->ip()
            ]);
        }
        
        return view('admin.users', compact('users'));
    }
    public function createUsers(){
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Accessed create user form',
                'ip_address' => request()->ip()
            ]);
        }
        
        return view('admin.users-create');
    }
    
    /**
     * Show form to create a new user
     */
    public function createUser()
    {
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Accessed create user form',
                'ip_address' => request()->ip()
            ]);
        }
        
        return view('admin.users-create');
    }
    
    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeUser(Request $request)
    {
        try {
            // Validate all form fields
            $validator = Validator::make($request->all(), [
                // Personal Details
                'full_name' => 'required|string|max:100',
                'username' => 'required|string|max:50|unique:tradeuser,Username',
                'password' => 'required|string|min:6',
                'email' => 'required|email|max:100|unique:tradeuser,Email',
                'mobile' => 'required|string|max:15|unique:tradeuser,Mobile',
                'address' => 'nullable|string|max:500',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:100',
                'pin_code' => 'nullable|string|max:10',
                'pan' => 'nullable|string|max:10',
                'aadhar' => 'nullable|string|max:12',
                
                // Bank Details
                'bank_name' => 'nullable|string|max:100',
                'account_number' => 'nullable|string|max:50',
                'ifsc_code' => 'nullable|string|max:20',
                'account_holder_name' => 'nullable|string|max:100',
                
                // Trading Configuration
                'is_demo' => 'nullable|boolean',
                'allow_orders_beyond_high_low' => 'nullable|boolean',
                'allow_orders_between_high_low' => 'nullable|boolean',
                'trade_equity_as_units' => 'nullable|boolean',
                'auto_square_off' => 'nullable|boolean',
                'auto_square_off_percentage' => 'nullable|numeric|min:0|max:100',
                'notify_percentage' => 'nullable|numeric|min:0|max:100',
                
                // MCX Configuration
                'mcx_lot_margin_json' => 'nullable|string',
                'mcx_lot_brokerage_json' => 'nullable|string',
                'mcx_bid_gap_json' => 'nullable|string',
                
                // NSE Configuration
                'nse_futures_enabled' => 'nullable|boolean',
                'nse_options_enabled' => 'nullable|boolean',
                'mcx_options_enabled' => 'nullable|boolean',
                'nse_futures_max_lot_per_scrip' => 'nullable|integer|min:1',
                'nse_options_max_lot_per_scrip' => 'nullable|integer|min:1',
                'mcx_options_max_lot_per_scrip' => 'nullable|integer|min:1',
                
                // Brokerage Configuration
                'nse_futures_brokerage' => 'nullable|numeric|min:0',
                'nse_options_brokerage' => 'nullable|numeric|min:0',
                'mcx_options_brokerage' => 'nullable|numeric|min:0',
                
                // Margin Configuration
                'nse_futures_holding_margin' => 'nullable|numeric|min:0',
                'nse_options_holding_margin' => 'nullable|numeric|min:0',
                'mcx_options_holding_margin' => 'nullable|numeric|min:0',
                
                // Short Selling Configuration
                'nse_futures_short_selling_allowed' => 'nullable|boolean',
                'nse_options_short_selling_allowed' => 'nullable|boolean',
                'mcx_options_short_selling_allowed' => 'nullable|boolean',
                
                // Security
                'user_transaction_password' => 'required|string|min:6',
                'admin_transaction_password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // // Validate admin transaction password
            // $admin = AdminLogin::where('PK_ID', Session::get('admin_id'))->first();
            // if (!$admin || !Hash::check($request->admin_transaction_password, $admin->TransPass)) {
            //     return redirect()->back()
            //         ->with('error', 'Invalid Transaction Password')
            //         ->withInput();
            // }

            // Validate JSON fields
            $jsonFields = ['mcx_lot_margin_json', 'mcx_lot_brokerage_json', 'mcx_bid_gap_json'];
            foreach ($jsonFields as $field) {
                if ($request->filled($field)) {
                    $jsonValue = $request->$field;
                    if ($jsonValue !== '{}' && !json_decode($jsonValue)) {
                        return redirect()->back()
                            ->with('error', 'Invalid MCX configuration JSON format. Please check the format and try again.')
                            ->withInput();
                    }
                }
            }

            DB::beginTransaction();

            // Create the trade user using stored procedure approach or direct model save
            $user = new TradeUser();
            
            // Personal Details
            $user->FullName = trim($request->full_name);
            $user->Username = trim($request->username);
            $user->Password = Hash::make($request->password);
            $user->Email = trim($request->email);
            $user->Mobile = trim($request->mobile);
            $user->Address = trim($request->address);
            $user->City = trim($request->city);
            $user->State = trim($request->state);
            $user->PinCode = trim($request->pin_code);
            $user->PAN = trim($request->pan);
            $user->Aadhar = trim($request->aadhar);
            
            // Bank Details
            $user->BankName = trim($request->bank_name);
            $user->AccountNumber = trim($request->account_number);
            $user->IFSCCode = trim($request->ifsc_code);
            $user->AccountHolderName = trim($request->account_holder_name);
            
            // Trading Configuration
            $user->IsDemo = $request->has('is_demo') ? 1 : 0;
            $user->AllowOrdersBeyondHighLow = $request->has('allow_orders_beyond_high_low') ? 1 : 0;
            $user->AllowOrdersBetweenHighLow = $request->has('allow_orders_between_high_low') ? 1 : 0;
            $user->TradeEquityAsUnits = $request->has('trade_equity_as_units') ? 1 : 0;
            $user->AutoSquareOff = $request->has('auto_square_off') ? 1 : 0;
            $user->AutoSquareOffPercentage = $request->auto_square_off_percentage ?? 90.00;
            $user->NotifyPercentage = $request->notify_percentage ?? 80.00;
            
            // MCX Configuration
            $user->MCXLotMarginJSON = trim($request->mcx_lot_margin_json) ?: '{}';
            $user->MCXLotBrokerageJSON = trim($request->mcx_lot_brokerage_json) ?: '{}';
            $user->MCXBidGapJSON = trim($request->mcx_bid_gap_json) ?: '{}';
            
            // NSE Configuration
            $user->NSEFuturesEnabled = $request->has('nse_futures_enabled') ? 1 : 0;
            $user->NSEOptionsEnabled = $request->has('nse_options_enabled') ? 1 : 0;
            $user->MCXOptionsEnabled = $request->has('mcx_options_enabled') ? 1 : 0;
            $user->NSEFuturesMaxLotPerScrip = $request->nse_futures_max_lot_per_scrip ?? 100;
            $user->NSEOptionsMaxLotPerScrip = $request->nse_options_max_lot_per_scrip ?? 50;
            $user->MCXOptionsMaxLotPerScrip = $request->mcx_options_max_lot_per_scrip ?? 50;
            
            // Brokerage Configuration
            $user->NSEFuturesBrokerage = $request->nse_futures_brokerage ?? 20.0000;
            $user->NSEOptionsBrokerage = $request->nse_options_brokerage ?? 20.0000;
            $user->MCXOptionsBrokerage = $request->mcx_options_brokerage ?? 20.0000;
            
            // Margin Configuration
            $user->NSEFuturesHoldingMargin = $request->nse_futures_holding_margin ?? 2.0000;
            $user->NSEOptionsHoldingMargin = $request->nse_options_holding_margin ?? 2.0000;
            $user->MCXOptionsHoldingMargin = $request->mcx_options_holding_margin ?? 2.0000;
            
            // Short Selling Configuration
            $user->NSEFuturesShortSellingAllowed = $request->has('nse_futures_short_selling_allowed') ? 1 : 0;
            $user->NSEOptionsShortSellingAllowed = $request->has('nse_options_short_selling_allowed') ? 1 : 0;
            $user->MCXOptionsShortSellingAllowed = $request->has('mcx_options_short_selling_allowed') ? 1 : 0;
            
            // System fields
            $user->IsActive = 1;
            $user->TransPass = Hash::make($request->user_transaction_password);
            $user->RefferalCode = Str::random(8);
            $user->CreatedBy = Session::get('admin_id');
            $user->CreatedDate = now();
            $user->ModifiedBy = Session::get('admin_id');
            $user->ModifiedDate = now();
            
            $user->save();

            // Create initial fund record
            Fund::create([
                'user_id' => $user->UserId,
                'balance' => 0,
                'equity' => 0,
                'margin' => 0,
                'free_margin' => 0,
                'margin_level' => 0
            ]);

            // Log admin activity
            if (Session::has('admin_id')) {
                AdminLog::create([
                    'admin_id' => Session::get('admin_id'),
                    'activity' => 'Created new user: ' . $user->FullName . ' (Username: ' . $user->Username . ')',
                    'ip_address' => $request->ip()
                ]);
            }

            DB::commit();

            return redirect()->route('admin.users')
                ->with('success', 'User added successfully');

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                if (strpos($e->getMessage(), 'Username') !== false) {
                    return redirect()->back()
                        ->with('error', 'Username already exists. Please choose a different username.')
                        ->withInput();
                } elseif (strpos($e->getMessage(), 'Email') !== false) {
                    return redirect()->back()
                        ->with('error', 'Email already exists. Please choose a different email.')
                        ->withInput();
                } elseif (strpos($e->getMessage(), 'Mobile') !== false) {
                    return redirect()->back()
                        ->with('error', 'Mobile number already exists. Please choose a different mobile number.')
                        ->withInput();
                }
            }
            
            Log::error('Database error creating user: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error saving user: ' . $e->getMessage())
                ->withInput();
                
        } catch (\InvalidArgumentException $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Please enter valid numeric values for brokerage, margin, and lot size fields.')
                ->withInput();
                
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating user: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error saving user: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * View user details
     */
    public function viewUser($id)
    {
        $user = TradeUser::findOrFail($id);
        
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed user details: ' . $user->name,
                'ip_address' => request()->ip()
            ]);
        }
        
        return view('admin.users-view', compact('user'));
    }
    
    /**
     * Show form to edit a user
     */
    public function editUser($id)
    {
        $user = TradeUser::findOrFail($id);
        
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Accessed edit form for user: ' . $user->name,
                'ip_address' => request()->ip()
            ]);
        }
        
        return view('admin.users-edit', compact('user'));
    }
    
    /**
     * Update a user
     */
    public function updateUser(Request $request, $id)
    {
        $user = TradeUser::findOrFail($id);
        
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:tradeuser,email,' . $id,
            'mobile' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:6',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pin_code' => 'nullable|string|max:10',
            'pan' => 'nullable|string|max:10',
            'aadhar' => 'nullable|string|max:12',
            'is_active' => 'boolean',
            'is_demo' => 'boolean',
        ]);
        
        // Only hash the password if it was provided
        if (isset($validated['password']) && !empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        // Update the user
        $user->update($validated);
        
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Updated user: ' . $user->name,
                'ip_address' => request()->ip()
            ]);
        }
        
        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }
    
    /**
     * Copy a user to create a new one with similar details
     */
    public function copyUser($id)
    {
        $sourceUser = TradeUser::findOrFail($id);
        
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Accessed copy user form for: ' . $sourceUser->FullName,
                'ip_address' => request()->ip()
            ]);
        }
        
        return view('admin.users-copy', compact('sourceUser'));
    }

    /**
     * Store the copied user
     */
    public function storeCopyUser(Request $request, $sourceUserId)
    {
        // Validate the request
        $request->validate([
            'full_name' => 'required|string|max:255',
            'mobile' => 'required|string|size:10|regex:/^[0-9]{10}$/',
            'username' => 'required|string|max:50|unique:tradeuser,Username',
            'password' => 'required|string|min:6',
            'city' => 'nullable|string|max:255',
            'transaction_password' => 'required|string|min:4',
            'admin_transaction_password' => 'required|string',
            'is_demo' => 'nullable|boolean'
        ]);

        // Verify admin transaction password
        $admin = AdminLogin::find(Session::get('admin_id'));
        if (!$admin || !Hash::check($request->admin_transaction_password, $admin->TransPass)) {
            return back()->withErrors(['admin_transaction_password' => 'Invalid admin transaction password.'])->withInput();
        }

        try {
            DB::beginTransaction();

            // Get source user for copying configuration
            $sourceUser = TradeUser::where('UserId', $sourceUserId)->firstOrFail();

            // Call stored procedure SP_copyTradeuser
            $adminId = Session::get('admin_id');

            $result = DB::select('EXEC SP_copyTradeuser ?, ?, ?, ?, ?, ?, ?, ?', [
                $adminId,
                $sourceUserId,
                $request->full_name,
                $request->mobile,
                $request->username,
                $request->password,
                $request->city,
                $request->transaction_password
            ]);

            // Check if the stored procedure returned any error message
            if (!empty($result) && isset($result[0]->RESPONSE)) {
                $response = $result[0]->RESPONSE;
                if (strpos(strtolower($response), 'error') !== false) {
                    throw new \Exception($response);
                }
            }

            DB::commit();

            // Log the activity
            if (Session::has('admin_id')) {
                AdminLog::create([
                    'admin_id' => Session::get('admin_id'),
                    'activity' => 'Created user copy: ' . $request->full_name . ' from source: ' . $sourceUser->FullName,
                    'ip_address' => request()->ip()
                ]);
            }

            return redirect()->route('admin.users')->with('success', 'User copied successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log the error
            Log::error('Error copying user: ' . $e->getMessage(), [
                'source_user_id' => $sourceUserId,
                'admin_id' => Session::get('admin_id'),
                'request_data' => $request->except(['password', 'transaction_password', 'admin_transaction_password'])
            ]);

            return back()->withErrors(['error' => 'An error occurred while copying the user. Please try again.'])->withInput();
        }
    }
    
    /**
     * Toggle user status (active/inactive)
     */
    public function toggleUserStatus($UserId)
    {
      
        $user = TradeUser::where('UserId', $UserId)->firstOrFail();
        $user->is_active = !$user->is_active;
        $user->save();
        
        $status = $user->is_active ? 'activated' : 'deactivated';
        
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'User ' . $status . ': ' . $user->name,
                'ip_address' => request()->ip()
            ]);
        }
        
        return redirect()->route('admin.users')->with('success', 'User ' . $status . ' successfully.');
    }
    
    /**
     * Delete a user
     */
    public function deleteUser($id)
    {
        $user = TradeUser::findOrFail($id);
        $userName = $user->name;
        
        // Soft delete the user
        $user->delete();
        
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Deleted user: ' . $userName,
                'ip_address' => request()->ip()
            ]);
        }
        
        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }
    
    /**
     * Show Comex margins for a user
     */
    public function comexMargins($id)
    {
        $user = TradeUser::findOrFail($id);
        
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed comex margins for user: ' . $user->name,
                'ip_address' => request()->ip()
            ]);
        }
        
        return view('admin.comex-margins', compact('user'));
    }
    
    /**
     * Show wallet/funds status for a user
     */
    public function wfStatus($id)
    {
        $user = TradeUser::findOrFail($id);
        
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed wallet status for user: ' . $user->name,
                'ip_address' => request()->ip()
            ]);
        }
        
        return view('admin.wf-status', compact('user'));
    }
    
    /**
     * Show trades page
     */
    public function trades()
    {
        // Get trades data
        $trades = []; // Replace with actual trade data fetching logic
        
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed trades list',
                'ip_address' => request()->ip()
            ]);
        }
        
        return view('admin.trades', compact('trades'));
    }
    
    /**
     * Show trades list page
     */
    public function tradesList()
    {
        // Get trades list data
        $tradesList = []; // Replace with actual trades list data fetching logic
        
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed trades list page',
                'ip_address' => request()->ip()
            ]);
        }
        
        return view('admin.trades-list', compact('tradesList'));
    }
    
    /**
     * Show group trades page
     */
    public function groupTrades()
    {
        // Get group trades data
        $groupTrades = []; // Replace with actual group trades data fetching logic
        
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed group trades',
                'ip_address' => request()->ip()
            ]);
        }
        
        return view('admin.group-trades', compact('groupTrades'));
    }
    
    /**
     * Show closed trades page
     */
    public function closedTrades()
    {
        // Get closed trades data
        $closedTrades = []; // Replace with actual closed trades data fetching logic
        
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed closed trades',
                'ip_address' => request()->ip()
            ]);
        }
        
        return view('admin.closed-trades', compact('closedTrades'));
    }
    
    /**
     * Show deleted trades page
     */
    public function deletedTrades()
    {
        // Get deleted trades data
        $deletedTrades = []; // Replace with actual deleted trades data fetching logic
        
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed deleted trades',
                'ip_address' => request()->ip()
            ]);
        }
        
        return view('admin.deleted-trades', compact('deletedTrades'));
    }
    
    /**
     * Show pending orders page
     */
    public function pendingOrders()
    {
        // Get pending orders data
        $pendingOrders = []; // Replace with actual pending orders data fetching logic
        
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed pending orders',
                'ip_address' => request()->ip()
            ]);
        }
        
        return view('admin.pending-orders', compact('pendingOrders'));
    }
    
    /**
     * Show funds page
     */
    public function funds()
    {
        // Get funds data
        $fundsData = []; // Replace with actual funds data fetching logic
        
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed funds page',
                'ip_address' => request()->ip()
            ]);
        }
        
        return view('admin.funds', compact('fundsData'));
    }
    
    /**
     * Show create funds page
     */
    public function createFunds()
    {
        // Get data needed for creating funds
        $data = []; // Replace with actual data fetching logic
        
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed create funds page',
                'ip_address' => request()->ip()
            ]);
        }
        
        return view('admin.create-funds', compact('data'));
    }
    
    /**
     * Show create funds WD page
     */
    public function createFundsWd()
    {
        // Get data needed for creating funds WD
        $data = []; // Replace with actual data fetching logic
        
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed create funds WD page',
                'ip_address' => request()->ip()
            ]);
        }
        
        return view('admin.create-funds-wd', compact('data'));
    }
    
    /**
     * Show deposit requests page
     */
    public function depositRequests()
    {
        // Get deposit requests data
        $depositRequests = []; 

        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed deposit requests',
                'ip_address' => request()->ip()
            ]);
        }
        $data = DepositeMaster::with('user')->where('UserId',Session::get('admin_id'))
        ->where('Approve_Status','PENDING')->get();

        return view('admin.deposit-requests', compact('data'));
    }
    
    public function handleDeposit(Request $request)
    {

        $request->validate([
            'type' => 'required|in:APPROVED,REJECTED',
        ]);

        DB::beginTransaction();
        try {
            $deposit = DepositeMaster::lockForUpdate()->findOrFail($request->ID);
            $type    = $request->input('type');

            if ($type === 'APPROVED') {
                // Create a wallet transaction
                Transdetail::create([
                    'MemberId'   => $deposit->UserId,
                    'TransType'  => 'Main Wallet',
                    'TransPage'  => 'Deposit approval',
                    'Type'       => '+',
                    'TransDate'  => now(),
                    'Amount'     => $deposit->Amount,
                    'AmountS'    => $deposit->Amount,
                    'Remark'     => 'Wallet Deposit',
                    'LoginId'    => $deposit->UserId,
                    'Pass'       => '',
                    'Expass'     => '',
                    'CounterId'  => 0,
                    'eWalletBit' => 1,
                    'AddRemark'  => 'APPROVED BY ADMIN',
                    'TransId'    => 0,
                    'RefTransId' => 0,
                ]);

                $deposit->update([
                    'Approve_Status' => 'APPROVED',
                    'Approve_Date'   => now(),
                ]);

                $message = 'Deposit approved successfully';
            } else { 
                $deposit->update([
                    'Approve_Status' => 'REJECTED',
                    'Approve_Date'   => now(),
                ]);

                $message = 'Deposit rejected successfully';
            }

            DB::commit();
            return response()->json(['message' => $message], 200);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Show withdrawal requests page
     */
    public function withdrawalRequests()
    {
        // Get withdrawal requests data
        $withdrawalRequests = []; // Replace with actual withdrawal requests data fetching logic
        
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed withdrawal requests',
                'ip_address' => request()->ip()
            ]);
        }
        
        return view('admin.withdrawal-requests', compact('withdrawalRequests'));
    }
    
    /**
     * Show accounts page
     */
    public function accounts()
    {
        // Get accounts data
        $accountsData = []; // Replace with actual accounts data fetching logic
        
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed accounts page',
                'ip_address' => request()->ip()
            ]);
        }
        
        return view('admin.accounts', compact('accountsData'));
    }
    
    /**
     * Show market scripts page
     */
    public function marketScripts()
    {
        // Get market scripts data
        $scriptsData = []; // Replace with actual market scripts data fetching logic
        
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed market scripts',
                'ip_address' => request()->ip()
            ]);
        }
        
        return view('admin.market-scripts', compact('scriptsData'));
    }
    
    /**
     * Show scrip data page
     */
    public function scripData()
    {
        // Get scrip data
        $scripData = []; // Replace with actual scrip data fetching logic
        
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed scrip data',
                'ip_address' => request()->ip()
            ]);
        }
        
        return view('admin.scrip-data', compact('scripData'));
    }
}

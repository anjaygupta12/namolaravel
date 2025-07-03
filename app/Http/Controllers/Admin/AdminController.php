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
use DB;
use App\Models\DepositeMaster;
use App\Models\Transdetail;
use App\Models\MarketMaster;
use App\Exports\FundsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Broker;
use App\Models\Fund;
use App\Models\MarketBidMaster;
use App\Models\WithdrawlMaster;
use App\Models\MarketPlaceMaster;
use Illuminate\Support\Facades\Schema;
use App\Exports\TradeExport;
use App\Models\ForexOption;
use Crabon\Crabon;

use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    /**
     * Display admin dashboard with summary statistics
     */
    public function dashboard()
    {
        // Get user statistics
        $totalUsers = TradeUser::count();
        $activeUsers = TradeUser::where('IsActive', 1)->count();
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
        } else {
            return redirect('admin/login');
        }

        return view('admin.dashboard', compact(
            'totalUsers',
            'activeUsers',
            'newUsers',
            'totalDeposits',
            'totalWithdrawals',
            'pendingWithdrawals',
            'pendingDeposits',
            'recentActivities',
            'recentUsers'
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
        } else {
            return redirect('admin/login');
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
        } else {
            return redirect('admin/login');
        }
        return view('admin.transaction-password');
    }

    /**
     * Update admin transaction password
     */
    public function updateTransactionPassword(Request $request)
    {

        $request->validate([
            'new_password' => 'required',
        ]);

        $user = AdminLogin::find(Session::get('admin_id'));

        if ($request->has('Login')) {

            // if (!Hash::check($request->current_password, $user->Password)) {
            //     return back()->withErrors(['current_password' => 'Current transaction password is incorrect.']);
            // }
            $user->Password = Hash::make($request->new_password);
        } 
        else {
            // if (!Hash::check($request->current_password, $user->TransPass)) {
            //     return back()->withErrors(['current_password' => 'Current transaction password is incorrect.']);
            // }
            $user->TransPass = Hash::make($request->new_password);
        }
        $user->save();
        
        return back()->with('success', 'Transaction password updated successfully.');

        $validator = Validator::make($request->all(), [
            'current_transaction_password' => 'required',
            'new_transaction_password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


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
            $notification->user_ids = json_encode($request->user_ids);
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
        if (\Schema::hasTable('bank_details')) {
            $bankDetails = \DB::table('bank_details')
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
        if (\Schema::hasTable('bank_details')) {
            // Check if bank details already exist
            $bankDetails = \DB::table('bank_details')
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
                \DB::table('bank_details')
                    ->where('admin_id', $adminId)
                    ->update($data);

                $message = 'Bank details updated successfully';
            } else {
                // Insert new record
                $data['created_at'] = now();
                \DB::table('bank_details')->insert($data);

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
        if (\Schema::hasTable('bank_details')) {
            $bankDetails = \DB::table('bank_details')
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
        $negativeBalanceUsers = $allUsers->filter(function ($user) {
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
        } else {
            return redirect('admin/login');
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
        } else {
            return redirect('admin/login');
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

        $marketStats = DB::table('marketbidmaster')
            ->selectRaw('
        Symbol,
        COUNT(*)            AS TotalUser,
        SUM(ToAmount)       AS ToAmount,
        SUM(SalePrice)      AS SalePrice,
        SUM(BuyPrice)       AS BuyPrice,
        SUM(Bid)            AS Bid,
        SUM(Ask)            AS Ask,
        SUM(High)           AS Hig,
        SUM(Low)            AS Low,
        SUM(TradeLast)      AS TradeLast,
        SUM(`Change`)       AS `Change`,
        SUM(TradeOpen)      AS TradeOpen,
        SUM(Volume)         AS Volume,
        SUM(LastTradeQty)   AS LastTradeQty,
        SUM(Atp)            AS Atp,
        SUM(LotSize)        AS LotSize,
        SUM(OpenInterest)   AS OpenInterest,
        SUM(BidQty)         AS BidQty,
        SUM(AskQty)         AS AskQty,
        SUM(PrevClose)      AS PrevClose,
        SUM(UpperCircuit)   AS UpperCircuit,
        SUM(LowerCircuit)   AS LowerCircuit
    ')
            ->groupBy('Symbol')
            ->get();

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


        $positions = DB::table('marketplacemaster')
            ->selectRaw('
        Symbol,
        IFNULL(AVG(LotSize), 0) AS Lots,
        IFNULL(AVG(BUYPRICE), 0) AS BUYPRICE,
        IFNULL(AVG(SELLPRICE), 0) AS SELLPRICE
    ')
            ->where('Status_Exec', 'Close')
            ->groupBy('Symbol')
            ->orderByDesc('Symbol')
            ->get();

        return view('admin.closed-positions', compact('positions'));
    }

    /**
     * Show users page
     */
    public function users(Request $request)
    {
        // Start with a base query
        $query = TradeUser::query()->with('transactions')
            ->select(['id', 'user_id','FullName', 'Username', 'IsActive', 'IsDemo', 'created_at', 'funds']);

        // Apply filters if provided
        if ($request->filled('username') && $request->username != '') {
            $query->where('Username', 'like', $request->username . '%');
        }

        if ($request->filled('user_id') && $request->user_id != '') {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('IsActive', $request->search_status);
        }

        // Get users data
        $users = $query->orderBy('created_at', 'desc')->get();

        // Map to calculate deposit and withdraw
        $users = $users->map(function ($q) {

            $deposit = $q->transactions->where('Type', '+')->where('AdminStatus', 'APPROVED')->sum('Amount');
            $withdraw = $q->transactions->where('Type', '-')->where('AdminStatus', 'APPROVED')->sum('Amount');
            $q->deposit = $deposit;
            $q->withdraw = $withdraw;
           
            $q->funds = $q->funds + $deposit - $withdraw;

            return $q;
        });

       
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed users list',
                'ip_address' => request()->ip()
            ]);
        } else {
            return redirect('admin/login');
        }

        return view('admin.users', compact('users'));
    }
    public function createUsers()
    {
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed users list',
                'ip_address' => request()->ip()
            ]);
        } else {
            return redirect('admin/login');
        }
        $brokers = Broker::all();
        return view('admin/create-user', compact('brokers'));
    }

    public function storeTradeUser(Request $request)
    {

        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed users list',
                'ip_address' => request()->ip()
            ]);
        } else {
            return redirect('admin/login');
        }

        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'mobile' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:255',
            'transaction_password' => 'nullable|string|min:6',
            'auto_square_off_percentage' => 'nullable|numeric',
            'notify_percentage' => 'nullable|numeric',
            'profit_book_interval' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $adminUser = AdminLogin::where('PK_ID', Session::get('admin_id'))->first();

        if (!Hash::check($request->input('transaction_password'), $adminUser->TransPass)) {
            return redirect()->back()->with('error', 'Invalid transaction password.');
        }

        try {
            DB::beginTransaction();

            // Create or update user
            $user = $request->id ? TradeUser::findOrFail($request->id) : new TradeUser();
            // dd($request->status,$request->has('status'),$request->has('Mcxusers.demo'));
            // Basic information
            if(!$request->id){
                $user->user_id= rand(10000, 99999);
                $user->Password = bcrypt($request->input('password'));
                 $user->funds = $request->funds;
            }
            $user->FullName = $request->input('fullname');
            $user->Username = $request->input('username');
          
            $user->Mobile = $request->input('mobile');
            $user->City = $request->input('city');
            $user->TransPass = bcrypt($request->input('transaction_password'));
            $user->broker_id = $request->input('broker_id', 0);
            $user->Notes = $request->input('notes');

            // Account settings
            $user->IsDemo = $request->Mcxusers['demo'] ? 1 : 0;
            $user->IsActive = $request->has('status') ? 1 : 0;
            $user->AutoSquareOff = $request->auto_square_off;
            $user->TradeEquityAsUnits = $request->trade_equity_as_units;
            $user->AllowOrdersBeyondHighLow = $request->allow_orders_beyond_high_low;
            $user->AllowOrdersBetweenHighLow = $request->allow_orders_between_high_low;

            // Risk management
            $user->AutoSquareOffPercentage = $request->input('auto_square_off_percentage', 90);
            $user->NotifyPercentage = $request->input('notify_percentage', 70);
            $user->ProfitBookInterval = $request->input('profit_book_interval', 120);

            // MCX settings
            $user->MCXEnabled = $request->mcx_enabled;
            $user->MCXMinLotPerTrade = $request->input('mcx_min_lot_per_trade', 0);
            $user->MCXMaxLotPerTrade = $request->input('mcx_max_lot_per_trade', 20);
            $user->MCXMaxLotPerScrip = $request->input('mcx_max_lot_per_scrip', 50);
            $user->MaxCommodityLots = $request->input('max_commodity_lots', 100);
            $user->ComexbrokerageType = $request->input('mcx_brokerage_type', 'per_crore');
            $user->MCXBrokerage = $request->input('mcx_brokerage', 800);
            $user->MCXExposureType = $request->input('mcx_exposure_type', 'per_turnover');
            $user->MCXIntradayMargin = $request->input('mcx_intraday_margin', 500);
            $user->MCXHoldingMargin = $request->input('mcx_holding_margin', 100);
            $user->MCXLotMarginJSON = json_encode($request->input('mcx_lot_margin', []));
            $user->MCXLotBrokerageJSON = json_encode($request->input('mcx_lot_brokerage', []));
            $user->MCXBidGapJSON = json_encode($request->input('mcx_bid_gap', []));

            // NSE Futures
            $user->NSEFuturesEnabled = $request->has('nse_enabled') ? 1 : 0;
            $user->NSEFuturesBrokerage = $request->input('nse_brokerage', 800);
            $user->NSEFuturesMinLotPerTrade = $request->input('nse_equity_min_lot_per_trade', 0);
            $user->NSEFuturesMaxLotPerTrade = $request->input('nse_equity_max_lot_per_trade', 50);
            $user->NSEIndexMinLotPerTrade = $request->input('nse_index_min_lot_per_trade', 0);
            $user->NSEIndexMaxLotPerTrade = $request->input('nse_index_max_lot_per_trade', 20);
            $user->NSEFuturesMaxLotPerScrip = $request->input('nse_equity_max_lot_per_scrip', 100);
            $user->NSEIndexMaxLotPerScrip = $request->input('nse_index_max_lot_per_scrip', 100);
            $user->MaxNSEFuturesLots = $request->input('max_nse_equity_lots', 100);
            $user->MaxNSEIndexLots = $request->input('max_nse_index_lots', 100);
            $user->NSEFuturesIntradayMargin = $request->input('nse_intraday_margin', 500);
            $user->NSEFuturesHoldingMargin = $request->input('nse_holding_margin', 100);
            $user->NSEBidGapPercentage = $request->input('nse_bid_gap_percentage', 0);

            // NSE Options
            $user->NSEOptionsEnabled = $request->has('options_enabled') ? 1 : 0;
            $user->EquityOptionsEnabled = $request->has('equity_options_enabled') ? 1 : 0;
            $user->OptionsBrokerageType = $request->input('options_brokerage_type', 'per_lot');
            $user->OptionsBrokerage = $request->input('options_brokerage', 25);
            $user->OptionsEquityBrokerageType = $request->input('options_equity_brokerage_type', 'per_lot');
            $user->OptionsEquityBrokerage = $request->input('options_equity_brokerage', 40);
            $user->OptionsMinimumBid = $request->input('options_minimum_bid', 2);
            $user->OptionsShortSellingAllowed = $request->input('options_short_selling_allowed', 1);
            $user->OptionsEquityShortSellingAllowed = $request->input('options_equity_short_selling_allowed', 0);
            $user->OptionsEquityMinLotPerTrade = $request->input('options_equity_min_lot_per_trade', 1);
            $user->OptionsEquityMaxLotPerTrade = $request->input('options_equity_max_lot_per_trade', 10);
            $user->OptionsIndexMinLotPerTrade = $request->input('options_index_min_lot_per_trade', 1);
            $user->OptionsIndexMaxLotPerTrade = $request->input('options_index_max_lot_per_trade', 20);
            $user->OptionsEquityMaxLotPerScrip = $request->input('options_equity_max_lot_per_scrip', 10);
            $user->OptionsIndexMaxLotPerScrip = $request->input('options_index_max_lot_per_scrip', 20);
            $user->MaxOptionsEquityLots = $request->input('max_options_equity_lots', 10);
            $user->MaxOptionsIndexLots = $request->input('max_options_index_lots', 10);
            $user->OptionsIntradayMargin = $request->input('options_intraday_margin', 7);
            $user->OptionsHoldingMargin = $request->input('options_holding_margin', 3);
            $user->OptionsEquityIntradayMargin = $request->input('options_equity_intraday_margin', 10);
            $user->OptionsEquityHoldingMargin = $request->input('options_equity_holding_margin', 3);
            $user->OptionsBidGapPercentage = $request->input('options_bid_gap_percentage', 0);

            // MCX Options
            $user->MCXOptionsEnabled = $request->has('mcx_options_enabled') ? 1 : 0;
            $user->OptionsMCXBrokerageType = $request->input('options_mcx_brokerage_type', 'per_lot');
            $user->OptionsMCXBrokerage = $request->input('options_mcx_brokerage', 50);
            $user->OptionsMCXShortSellingAllowed = $request->input('options_mcx_short_selling_allowed', 0);
            $user->OptionsMCXMinLotPerTrade = $request->input('options_mcx_min_lot_per_trade', 1);
            $user->OptionsMCXMaxLotPerTrade = $request->input('options_mcx_max_lot_per_trade', 20);
            $user->OptionsMCXMaxLotPerScrip = $request->input('options_mcx_max_lot_per_scrip', 10);
            $user->MaxOptionsMCXLots = $request->input('max_options_mcx_lots', 10);
            $user->OptionsMCXIntradayMargin = $request->input('options_mcx_intraday_margin', 10);
            $user->OptionsMCXHoldingMargin = $request->input('options_mcx_holding_margin', 3);

            // Options Shortselling Config
            $user->OptionsSSBrokerageType = $request->input('options_ss_brokerage_type', 'per_lot');
            $user->OptionsSSBrokerage = $request->input('options_ss_brokerage', 50);
            $user->OptionsSSEquityBrokerageType = $request->input('options_ss_equity_brokerage_type', 'per_lot');
            $user->OptionsSSEquityBrokerage = $request->input('options_ss_equity_brokerage', 20);
            $user->OptionsSSMCXBrokerageType = $request->input('options_ss_mcx_brokerage_type', 'per_lot');
            $user->OptionsSSMCXBrokerage = $request->input('options_ss_mcx_brokerage', 20);
            $user->OptionsSSEquityMinLotPerTrade = $request->input('options_ss_equity_min_lot_per_trade', 0);
            $user->OptionsSSEquityMaxLotPerTrade = $request->input('options_ss_equity_max_lot_per_trade', 2);
            $user->OptionsSSMCXMinLotPerTrade = $request->input('options_ss_mcx_min_lot_per_trade', 1);
            $user->OptionsSSMCXMaxLotPerTrade = $request->input('options_ss_mcx_max_lot_per_trade', 3);
            $user->OptionsSSIndexMinLotPerTrade = $request->input('options_ss_index_min_lot_per_trade', 0);
            $user->OptionsSSIndexMaxLotPerTrade = $request->input('options_ss_index_max_lot_per_trade', 10);
            $user->OptionsSSEquityMaxLotPerScrip = $request->input('options_ss_equity_max_lot_per_scrip', 5);
            $user->OptionsSSIndexMaxLotPerScrip = $request->input('options_ss_index_max_lot_per_scrip', 10);
            $user->OptionsSSMCXMaxLotPerScrip = $request->input('options_ss_mcx_max_lot_per_scrip', 7);
            $user->MaxOptionsSSEquityLots = $request->input('max_options_ss_equity_lots', 10);
            $user->MaxOptionsSSIndexLots = $request->input('max_options_ss_index_lots', 10);
            $user->MaxOptionsSSMCXLots = $request->input('max_options_ss_mcx_lots', 30);
            $user->OptionsSSIntradayMargin = $request->input('options_ss_intraday_margin', 2);
            $user->OptionsSSHoldingMargin = $request->input('options_ss_holding_margin', 1);
            $user->OptionsSSEquityIntradayMargin = $request->input('options_ss_equity_intraday_margin', 3);
            $user->OptionsSSEquityHoldingMargin = $request->input('options_ss_equity_holding_margin', 2);
            $user->OptionsSSMCXIntradayMargin = $request->input('options_ss_mcx_intraday_margin', 5);
            $user->OptionsSSMCXHoldingMargin = $request->input('options_ss_mcx_holding_margin', 3);

           
            // Set created/modified info
            if ($request->id) {
                $user->ModifiedBy = Session::get('admin_id');
                $user->ModifiedDate = now();
            } else {
                $user->CreatedBy = Session::get('admin_id');
                $user->CreatedDate = now();
            }
            $user->save();
            DB::commit();
            return redirect()->route('admin.users')->with('success', 'User ' . ($request->id ? 'updated' : 'created') . ' successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error ' . ($request->id ? 'updating' : 'creating') . ' user: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show form to create a new user
     */
    // public function createUser()
    // {
    //     if (Session::has('admin_id')) {
    //         AdminLog::create([
    //             'admin_id' => Session::get('admin_id'),
    //             'activity' => 'Accessed create user form',
    //             'ip_address' => request()->ip()
    //         ]);
    //     }

    //     return view('admin.users-create');
    // }

    /**
     * Store a new user
     */
    // public function storeUser(Request $request)
    // {
    //     // Validate the request
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:trade_users,email',
    //         'mobile' => 'nullable|string|max:15',
    //         'password' => 'required|string|min:6',
    //         'address' => 'nullable|string',
    //         'city' => 'nullable|string|max:100',
    //         'state' => 'nullable|string|max:100',
    //         'pin_code' => 'nullable|string|max:10',
    //         'pan' => 'nullable|string|max:10',
    //         'aadhar' => 'nullable|string|max:12',
    //         'is_active' => 'boolean',
    //         'is_demo' => 'boolean',
    //     ]);

    //     // Hash the password
    //     $validated['password'] = bcrypt($validated['password']);

    //     // Create the user
    //     $user = TradeUser::create($validated);

    //     if (Session::has('admin_id')) {
    //         AdminLog::create([
    //             'admin_id' => Session::get('admin_id'),
    //             'activity' => 'Created new user: ' . $user->name,
    //             'ip_address' => request()->ip()
    //         ]);
    //     }

    //     return redirect()->route('admin.users')->with('success', 'User created successfully.');
    // }

    /**
     * View user details
     */
    public function resetAccount(Request $request,$id)
    {
        DepositeMaster::where('UserId',$id)->delete();
        Transdetail::where('UserId',$id)->delete();
        TradeUser::where('id',$id)->update('MCXBrokerage',0);

        return redirect()->back()->with('success', 'Reset successfully Account.');
    }
    public function recalculateBrokerage(Request $request)
    {

        return redirect()->back()->with('success', 'Please reset Brockrage change Successfully.');


    }
    public function viewUser($id)
    {
        $user = TradeUser::findOrFail($id);
        $funds = Transdetail::where('MemberId', $id)->paginate(2);

        $trades = MarketBidMaster::where('UserId', $id)
            ->where('Isactive', 1)->get();
        $closedTrade = MarketBidMaster::where('UserId', $id)
            ->where('Isactive', 3)->get();
        $mxcPendingTrade = MarketBidMaster::where('UserId', $id)
            ->where('Isactive', 0)
            ->where('Symbol', 'like', 'MCX%')->get();

        $equityPendingTrade = MarketBidMaster::where('UserId', $id)
            ->where('Isactive', 0)
            ->where('Symbol', 'like', 'MCX%')->get();

        $comexPendingTrade = MarketBidMaster::where('UserId', $id)
            ->where('Isactive', 0)
            ->where('Symbol', 'like', 'MCX%')->get();

        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed user details: ' . $user->name,
                'ip_address' => request()->ip()
            ]);
        } else {
            return redirect('admin/login');
        }
      
        return view('admin.users-view', compact(
            'user',
            'funds',
            'trades',
            'closedTrade',
            'mxcPendingTrade',
            'equityPendingTrade',
            'comexPendingTrade'
        ));
    }

    /**
     * Show form to edit a user
     */
    public function exportExcel(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        $from = $request->input('from_date');
        $to = $request->input('to_date');

        return Excel::download(new TradeExport($from, $to), 'trades_export_' . $from . '_to_' . $to . '.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        $from = $request->input('from_date');
        $to = $request->input('to_date');

        $trades = MarketBidMaster::whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = Pdf::loadView('admin.exports.pdf.trades', compact('trades', 'from', 'to'));

        return $pdf->download('trades_' . $from . '_to_' . $to . '.pdf');
    }

    public function export(Request $request)
    {
        $from = $request->from_date;
        $to = $request->to_date;

        // Export fund data
    }


    public function editUser($id)
    {

        $user = TradeUser::where('id', $id)->first();
        $brokers = Broker::all();

        // Decode JSON fields if needed
        $mcxLotMargin = json_decode($user->MCXLotMarginJSON, true) ?? [];
        $mcxLotBrokerage = json_decode($user->MCXLotBrokerageJSON, true) ?? [];
        $mcxBidGap = json_decode($user->MCXBidGapJSON, true) ?? [];

        return view('admin.users-edit', [
            'user' => $user,
            'brokers' => $brokers,
            'mcxLotMargin' => $mcxLotMargin,
            'mcxLotBrokerage' => $mcxLotBrokerage,
            'mcxBidGap' => $mcxBidGap
        ]);


        // return view('admin.users-edit', compact('user','brokers'));
    }

    /**
     * Update a user
     */
    public function updateUser(Request $request, $id)
    {

        $user = TradeUser::where('id', $id)->first();

        // Update the user
        $user->update([
            'FullName' => $request->input('full_name'),
            'Username' => $request->input('username'),
            'Email' => $request->input('email'),
            'Mobile' => $request->input('mobile'),
            'City' => $request->input('city'),
            'IsDemo' => $request->boolean('is_demo'),
            'AllowOrdersBeyondHighLow' => $request->boolean('allow_orders_beyond_high_low'),
            'AllowOrdersBetweenHighLow' => $request->boolean('allow_orders_between_high_low'),
            'TradeEquityAsUnits' => $request->boolean('trade_equity_as_units'),
            'IsActive' => $request->boolean('account_status'),
            'auto_square_off' => $request->boolean('auto_square_off'),
            'AutoSquareOffPercentage' => $request->input('auto_square_off_percentage', 90),
            'NotifyPercentage' => $request->input('notify_percentage', 70),
            'profit_book_interval' => $request->input('profit_book_interval', 0),
            'MCXOptionsEnabled' => $request->boolean('mcx_enabled'),
            'minimum_lots_single_comex' => $request->input('mcx_min_lot_per_trade', 0),
            'maximum_lots_comex' => $request->input('mcx_max_lot_per_scrip', 0),
            'max_size_all_comex' => $request->input('mcx_max_lot_all_scrips', 0),
            'nse_futures_enabled' => $request->boolean('nse_futures_enabled'),
            'NSEFuturesMaxLotPerScrip' => $request->input('nse_futures_max_lot_per_scrip', 0),
            'broker_id' => $request->input('broker_id'),
            // Update password only if provided
            'Password' => $request->filled('password') ? bcrypt($request->input('password')) : $user->Password,
            'TransPass' => $request->filled('transaction_password') ? bcrypt($request->input('transaction_password')) : $user->TransPass,
        ]);

        return redirect()->route('admin.users')->with('success', 'User updated successfully');
    }

    /**
     * Copy a user to create a new one with similar details
     */
    public function copyUser($id)
    {
        $user = TradeUser::where('id', $id)->first();
        $brokers = Broker::all();

        // Decode JSON fields if needed
        $mcxLotMargin = json_decode($user->MCXLotMarginJSON, true) ?? [];
        $mcxLotBrokerage = json_decode($user->MCXLotBrokerageJSON, true) ?? [];
        $mcxBidGap = json_decode($user->MCXBidGapJSON, true) ?? [];

        return view('admin.users-copy', [
            'user' => $user,
            'brokers' => $brokers,
            'mcxLotMargin' => $mcxLotMargin,
            'mcxLotBrokerage' => $mcxLotBrokerage,
            'mcxBidGap' => $mcxBidGap
        ]);
    }

    /**
     * Toggle user status (active/inactive)
     */
    public function toggleUserStatus($id)
    {

        $user = TradeUser::findOrFail($id);
        $user->IsActive = !$user->IsActive;
        $user->save();

        $status = $user->IsActive ? 'activated' : 'deactivated';

        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'User ' . $status . ': ' . $user->name,
                'ip_address' => request()->ip()
            ]);
        } else {
            return redirect('admin/login');
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
        } else {
            return redirect('admin/login');
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
        } else {
            return redirect('admin/login');
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
        } else {
            return redirect('admin/login');
        }
        $transactions = DB::table('user_transactions')->where('user_id', $user->id)->get();
        $totalDeposit = DepositeMaster::where('type', 1)->where('UserId', $user->id)->sum('Amount');
        $totalWithdrawals = DepositeMaster::where('type', 0)->where('UserId', $user->id)->sum('Amount');
        $netPA = 0;

        return view('admin.wf-status', compact('user', 'transactions', 'totalDeposit', 'totalWithdrawals', 'netPA'));
    }

    /**
     * Show trades page
     */
    public function getScripData(Request $request)
    {

        $symbol = $request->symbol;
        $fromDate = $request->from_date ?? date('Y-m-d', strtotime('-1 day'));
        $toDate = $request->to_date ?? date('Y-m-d');
        $resolution = 1;
        // $minuts = $request->minuts;
        $hours = $request->hours * 60 + $request->minuts;

        $response = fetchFyersHistoricalData($symbol, $fromDate, $toDate, $resolution);
        // Make sure it's an array or collection
        return response()->json($response);
    }
    public function trades(Request $request)
    {

        // Get trades data
        $trades = []; // Replace with actual trade data fetching logic

        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed trades list',
                'ip_address' => request()->ip()
            ]);
        } else {
            return redirect('admin/login');
        }

        // $query = DB::table('sp_getmarketwatch');
        $query = MarketBidMaster::query(); // or new MarketBidMaster if you prefer ->newQuery()

        if ($request->filled('id')) {
            $query->where('Pk_id', $request->id);
        }

        if ($request->filled('scrip_id')) {
            $query->where('Symbol', 'like', '%' . $request->scrip_id . '%');
        }

        if ($request->filled('segment') && $request->segment != 'All') {
            $query->where('segment', $request->segment);
        }

        if ($request->filled('userid')) {
            $query->where('UserId', $request->userid);
        }

        if ($request->filled('buy_rate')) {
            $query->where('BuyPrice', $request->buy_rate);
        }

        if ($request->filled('sell_rate')) {
            $query->where('SalePrice', $request->sell_rate);
        }

        if ($request->filled('lots')) {
            $query->where('LotSize', $request->lots);
        }

        // Now apply order and get results
        $trades = $query->orderByDesc('Pk_id')->get();

        return view('admin.trades', compact('trades'));
    }

    public function tradeCreate()
    {
        $trades = [];

        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed trades list',
                'ip_address' => request()->ip()
            ]);
        } else {
            return redirect('admin/login');
        }
        $tradeUser = TradeUser::all();
        $forexOption = ForexOption::where('Isactive', 1)->get();

        return view('admin.create_trade', compact('trades', 'tradeUser', 'forexOption'));
    }

    public function storeOrUpdate(Request $request)
    {
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed trades list',
                'ip_address' => request()->ip()
            ]);
        } else {
            return redirect('admin/login');
        }

        $validated = $request->validate([
            'scrip_id' => 'required|string',
            'userid' => 'required|integer',
            'lots' => 'required|integer',
            'buy_price' => 'nullable|numeric',
        ]);
        $id = $request->input('id');

        $adminUser = AdminLogin::where('PK_ID', Session::get('admin_id'))->first();

        if (!Hash::check($request->input('transaction_password'), $adminUser->TransPass)) {
            return redirect()->back()->with('error', 'Invalid transaction password.');
        }

        if ($id) {
            $trade = MarketBidMaster::where('Pk_id', $id)->first();
            if (!$trade) {
                return back()->with('error', 'Record not found.');
            }

            MarketBidMaster::where('Pk_id', $id)->update([
                'Symbol' => $validated['scrip_id'],
                'UserId' => $validated['userid'],
                'LotSize' => $validated['lots'],
                'BuyPrice' => $validated['buy_price'],
                'SalePrice' => $validated['sell_price'] ?? null,
                'segment' => $validated['segment'],
            ]);

            return back()->with('success', 'Trade updated successfully.');
        } else {
            // Create new record
            MarketBidMaster::insert([
                'Symbol' => $validated['scrip_id'],
                'UserId' => $validated['userid'],
                'LotSize' => $validated['lots'],
                'BuyPrice' => $validated['buy_price'],
                'SalePrice' => $validated['sell_price'] ?? null,
                'segment' => $validated['segment'],
                'Mode' => ($validated['sell_price']) ? 'SELL' : 'BUY'
            ]);
            return back()->with('success', 'Trade created successfully.');
        }
    }
    public function tradesEdit($id = null)
    {
        $trades = [];

        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed trades list',
                'ip_address' => request()->ip()
            ]);
        } else {
            return redirect('admin/login');
        }
        $trade = null;
        if ($id) {
            $trade = DB::table('sp_getmarketwatch')->where('id', $id)->first();
        }
        $tradeUser = TradeUser::all();
        return view('admin.create_trade', compact('trade', 'tradeUser'));
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
        } else {
            return redirect('admin/login');
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
        } else {
            return redirect('admin/login');
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

        // Get deleted trades data
        $deletedTrades = []; // Replace with actual deleted trades data fetching logic
        $userName = $request->username ?? null;
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed deleted trades',
                'ip_address' => request()->ip()
            ]);
        }

        // MySQL version – no NOLOCK hints
        $closedTrades = DB::table('closeMarketPlaceMaster as CP')
            ->select([
                'CP.Pk_id',
                'CP.Fk_Id',
                'CP.BUYPRICE',
                'CP.SELLPRICE',
                'CP.TransactionId',
                'CP.Mode',
                'CP.ToAmount',
                'CP.TransactionMode',
                'CP.Bid',
                'CP.Ask',
                'CP.High',
                'CP.Low',
                'CP.TradeLast',
                'CP.Change',
                'CP.TradeOpen',
                'CP.Volume',
                'CP.LastTradeQty',
                'CP.Atp',
                'CP.LotSize',
                'CP.OpenInterest',
                'CP.BidQty',
                'CP.AskQty',
                'CP.PrevClose',
                'CP.UpperCircuit',
                'CP.LowerCircuit',
                DB::raw('MP.Timestamp   AS BUYDATE'),
                DB::raw('CP.Timestamp   AS SOLDDATE'),
                'CP.Lastmodify',
                'CP.Isactive',
                DB::raw("CONCAT(LM.USERID, ' ', LM.FULLNAME) AS UserId"),
                'CP.Symbol',
                'CP.IsMin',
                'CP.IsMega',
                'CP.Lots',
                'CP.Price',
                'CP.Status_Exec',
                'CP.Exitrate',
            ])
            ->join('loginmaster  as LM', 'CP.UserId', '=', 'LM.PK_ID')
            ->join('marketplacemaster as MP', 'MP.PK_ID', '=', 'CP.FK_ID')
            ->where('MP.Status_Exec', 'Deleted')          // <- Laravel will bind this and quote it
            ->when($userName, function ($q) use ($userName) {
                $q->where('LM.UserId', $userName);
            })
            ->orderByDesc('CP.Timestamp')
            ->get();


        return view('admin.closed-trades', compact('closedTrades'));
    }

    public function updateComexMargins(Request $request, $id)
    {
        try {
            $user = TradeUser::findOrFail($id);

            // Validate all form fields
            $validator = Validator::make($request->all(), [
                // Comex Configuration
                'comex_brokerage_type' => 'required|string|in:Per Lot,Per Crore',
                'comex_brokerage' => 'required|numeric|min:0',
                'minimum_lots_single_comex' => 'required|numeric|min:0',
                'maximum_lots_comex' => 'required|numeric|min:0',
                'maximum_lots_allowed' => 'required|numeric|min:0',
                'max_size_all_comex' => 'required|numeric|min:0',
                'intraday_exposure_margin_comex' => 'required|numeric|min:0',
                'holding_exposure_margin_comex' => 'required|numeric|min:0',
                'orders_price_comex' => 'required|numeric|min:0',

                // Forex Configuration
                'forex_brokerage_type' => 'required|string|in:Per Lot,Per Crore',
                'forex_brokerage' => 'required|numeric|min:0',
                'minimum_lots_single_forex' => 'required|numeric|min:0',
                'maximum_lots_forex' => 'required|numeric|min:0',
                'maximum_lots_allowed_forex' => 'required|numeric|min:0',
                'max_size_all_forex' => 'required|numeric|min:0',
                'intraday_exposure_margin_forex' => 'required|numeric|min:0',
                'holding_exposure_margin_forex' => 'required|numeric|min:0',
                'orders_price_forex' => 'required|numeric|min:0',

                // Crypto Configuration
                'crypto_brokerage_type' => 'required|string|in:Per Lot,Per Crore',
                'crypto_brokerage' => 'required|numeric|min:0',
                'minimum_lots_single_crypto' => 'required|numeric|min:0',
                'maximum_lots_crypto' => 'required|numeric|min:0',
                'maximum_lots_allowed_crypto' => 'required|numeric|min:0',
                'max_size_all_crypto' => 'required|numeric|min:0',
                'intraday_exposure_margin_crypto' => 'required|numeric|min:0',
                'holding_exposure_margin_crypto' => 'required|numeric|min:0',
                'orders_price_crypto' => 'required|numeric|min:0',

                // Security
                'transaction_password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Validate admin transaction password
            $admin = AdminLogin::where('PK_ID', Session::get('admin_id'))->first();
            if (!$admin || !Hash::check($request->transaction_password, $admin->TransPass)) {
                return redirect()->back()
                    ->with('error', 'Invalid Transaction Password')
                    ->withInput();
            }

            DB::beginTransaction();

            // Update the trade user with all the form data
            $user->update([
                // Comex Configuration
                'comex_trading_enabled' => $request->has('comex_trading_enabled') ? 1 : 0,
                'comex_brokerage_type' => $request->comex_brokerage_type,
                'comex_brokerage' => $request->comex_brokerage,
                'minimum_lots_single_comex' => $request->minimum_lots_single_comex,
                'maximum_lots_comex' => $request->maximum_lots_comex,
                'maximum_lots_allowed' => $request->maximum_lots_allowed,
                'max_size_all_comex' => $request->max_size_all_comex,
                'intraday_exposure_margin_comex' => $request->intraday_exposure_margin_comex,
                'holding_exposure_margin_comex' => $request->holding_exposure_margin_comex,
                'orders_price_comex' => $request->orders_price_comex,

                // Forex Configuration
                'forex_trading_enabled' => $request->has('forex_trading_enabled') ? 1 : 0,
                'forex_brokerage_type' => $request->forex_brokerage_type,
                'forex_brokerage' => $request->forex_brokerage,
                'minimum_lots_single_forex' => $request->minimum_lots_single_forex,
                'maximum_lots_forex' => $request->maximum_lots_forex,
                'maximum_lots_allowed_forex' => $request->maximum_lots_allowed_forex,
                'max_size_all_forex' => $request->max_size_all_forex,
                'intraday_exposure_margin_forex' => $request->intraday_exposure_margin_forex,
                'holding_exposure_margin_forex' => $request->holding_exposure_margin_forex,
                'orders_price_forex' => $request->orders_price_forex,

                // Crypto Configuration
                'crypto_trading_enabled' => $request->has('crypto_trading_enabled') ? 1 : 0,
                'crypto_brokerage_type' => $request->crypto_brokerage_type,
                'crypto_brokerage' => $request->crypto_brokerage,
                'minimum_lots_single_crypto' => $request->minimum_lots_single_crypto,
                'maximum_lots_crypto' => $request->maximum_lots_crypto,
                'maximum_lots_allowed_crypto' => $request->maximum_lots_allowed_crypto,
                'max_size_all_crypto' => $request->max_size_all_crypto,
                'intraday_exposure_margin_crypto' => $request->intraday_exposure_margin_crypto,
                'holding_exposure_margin_crypto' => $request->holding_exposure_margin_crypto,
                'orders_price_crypto' => $request->orders_price_crypto,
            ]);

            DB::commit();

            // Log the activity
            if (Session::has('admin_id')) {
                AdminLog::create([
                    'admin_id' => Session::get('admin_id'),
                    'activity' => 'Updated comex margins for user: ' . $user->FullName,
                    'ip_address' => request()->ip()
                ]);
            }

            return redirect()->back()->with('success', 'Comex margins updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();

            // Log the error
            if (Session::has('admin_id')) {
                AdminLog::create([
                    'admin_id' => Session::get('admin_id'),
                    'activity' => 'Failed to update comex margins for user ID: ' . $id . ' - Error: ' . $e->getMessage(),
                    'ip_address' => request()->ip()
                ]);
            }

            return redirect()->back()
                ->with('error', 'An error occurred while updating comex margins. Please try again.')
                ->withInput();
        }
    }

    /**
     * Show deleted trades page
     */
    public function deletedTrades()
    {
        // Get deleted trades data
        $deletedTrades = []; // Replace with actual deleted trades data fetching logic
        $userName = $request->username ?? null;
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed deleted trades',
                'ip_address' => request()->ip()
            ]);
        }

        // MySQL version – no NOLOCK hints
        $query = DB::table('closeMarketPlaceMaster as CP')
            ->select([
                'CP.Pk_id',
                'CP.Fk_Id',
                'CP.BUYPRICE',
                'CP.SELLPRICE',
                'CP.TransactionId',
                'CP.Mode',
                'CP.ToAmount',
                'CP.TransactionMode',
                'CP.Bid',
                'CP.Ask',
                'CP.High',
                'CP.Low',
                'CP.TradeLast',
                'CP.Change',
                'CP.TradeOpen',
                'CP.Volume',
                'CP.LastTradeQty',
                'CP.Atp',
                'CP.LotSize',
                'CP.OpenInterest',
                'CP.BidQty',
                'CP.AskQty',
                'CP.PrevClose',
                'CP.UpperCircuit',
                'CP.LowerCircuit',
                DB::raw('MP.Timestamp   AS BUYDATE'),
                DB::raw('CP.Timestamp   AS SOLDDATE'),
                'CP.Lastmodify',
                'CP.Isactive',
                DB::raw("CONCAT(LM.USERID, ' ', LM.FULLNAME) AS UserId"),
                'CP.Symbol',
                'CP.IsMin',
                'CP.IsMega',
                'CP.Lots',
                'CP.Price',
                'CP.Status_Exec',
                'CP.Exitrate',
            ])
            ->join('loginmaster  as LM', 'CP.UserId', '=', 'LM.PK_ID')
            ->join('marketplacemaster as MP', 'MP.PK_ID', '=', 'CP.FK_ID')
            ->where('MP.Status_Exec', 'Deleted')          // <- Laravel will bind this and quote it
            ->when($userName, function ($q) use ($userName) {
                $q->where('LM.UserId', $userName);
            })
            ->orderByDesc('CP.Timestamp')
            ->get();

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
        } else {
            return redirect('admin/login');
        }

        return view('admin.pending-orders', compact('pendingOrders'));
    }

    /**
     * Show funds page
     */
    public function funds(Request $request)
    {
        // Get funds data
        $fundsData = [];

        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed funds page',
                'ip_address' => request()->ip()
            ]);
        } else {
            return redirect('admin/login');
        }

        $depositQ = DB::table('transdetail as td')
            ->join('tradeuser as tu', 'td.MemberId', '=', 'tu.id')
            ->when($request->filled('user_id'), function ($q) use ($request) {
                $uid = $request->user_id;
                $q->where(function ($sub) use ($uid) {
                    $sub->where('tu.id', $uid)
                        ->orWhere('tu.UserName', 'like', "%{$uid}%");
                });
            })
            ->when($request->filled('amount'), function ($q) use ($request) {
                $q->where('td.AmountS', $request->amount);
            })
            ->selectRaw("td.type,td.TransPage,tu.FullName,tu.UserName as UserName,td.PK_ID,td.AmountS,
                        td.Remark,'Deposit' as Mode,td.adminstatus,td.transdate as Timestamp")
            ->orderBy('Timestamp', 'desc')
            ->get();

        // dd($depositQ);
        return view('admin.funds', compact('depositQ'));
    }

    public function fundsReport(Request $request)
    {
        $fundsData = [];

        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed funds page',
                'ip_address' => request()->ip()
            ]);
        } else {
            return redirect('admin/login');
        }

        $depositQ = DB::table('transdetail as td')
            ->join('tradeuser as tu', 'td.MemberId', '=', 'tu.id')
            ->where('td.TransPage', 'Deposit approval')
            ->selectRaw("td.TransPage,tu.FullName,tu.UserName as UserName,td.PK_ID,td.AmountS,
                        td.Remark,'Deposit' as Mode,td.adminstatus,td.transdate as Timestamp")
            ->orderBy('Timestamp', 'desc')
            ->get();

        // Second half – withdrawals
        $withdrawQ = DB::table('withdrawlmaster as wm')
            ->join('tradeuser as tu', 'wm.UserId', '=', 'tu.id')
            ->selectRaw("tu.FullName,tu.UserName as UserName,wm.PK_ID,wm.Amount,wm.PaymentMethod,
            'Withdrawal' as Mode,wm.Status as adminstatus,wm.Timestamp as Timestamp")
            ->orderBy('Timestamp', 'desc')
            ->get();

        $merged = collect();

        foreach ($depositQ as $item) {
            $merged->push([
                $item->PK_ID,
                $item->UserName,
                $item->FullName,
                $item->AmountS,
                $item->Mode,
                $item->Remark ?? '',
                $item->Mode,
                $item->Timestamp,
            ]);
        }

        foreach ($withdrawQ as $item) {
            $merged->push([
                $item->PK_ID,
                $item->UserName,
                $item->FullName,
                $item->Amount,
                $item->Mode,
                $item->PaymentMethod ?? '',
                $item->Mode,
                $item->Timestamp,
            ]);
        }

        if ($request->has('export')) {
            return Excel::download(new FundsExport($merged), 'trader_funds.xlsx');
        }
    }
    /**
     * Show create funds page
     */
    public function createFunds($id)
    {

        // Get data needed for creating funds
        $data = [];

        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed create funds page',
                'ip_address' => request()->ip()
            ]);
        } else {
            return redirect('admin/login');
        }
        $data = TradeUser::where('id', $id)->first();
        return view('admin.create-funds', compact('data'));
    }

    /**
     * Show create funds WD page
     */
    public function createFundsWd($id)
    {
        // Get data needed for creating funds WD
        $data = []; // Replace with actual data fetching logic

        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed create funds WD page',
                'ip_address' => request()->ip()
            ]);
        } else {
            return redirect('admin/login');
        }
        $data = TradeUser::where('id', $id)->first();
        return view('admin.create-funds-wd', compact('data'));
    }
    public function fundsStore(Request $request)
    {
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed create funds WD page',
                'ip_address' => request()->ip()
            ]);
        } else {
            return redirect('admin/login');
        }

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'notes' => 'nullable|string|max:255',
            'transaction_password' => 'required|string',
        ]);

        $user = TradeUser::where('id', $request->userid)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }
        $adminUser = AdminLogin::where('PK_ID', Session::get('admin_id'))->first();

        if (!Hash::check($request->input('transaction_password'), $adminUser->TransPass)) {
            return redirect()->back()->with('error', 'Invalid transaction password.');
        }

        $fund = new DepositeMaster();
        $fund->UserId = $user->id;
        $fund->Amount = $request->amount;
        $fund->Approve_Status = 'APPROVED';
        $fund->notes = $request->notes;
        $fund->LastModify = now();
        $fund->type = 1;
        $fund->save();

        $transPage = 'Deposit approval';
        $depType = '+';
        $remarks = 'Deposit';

        $this->approvedStatus($fund, $transPage, $depType, $remarks);

        return redirect()->back()->with('success', 'Funds Deposite Successfully.');
    }

    public function fundWithdrawal(Request $request)
    {

        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed create funds WD page',
                'ip_address' => request()->ip()
            ]);
        }

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'notes' => 'nullable|string|max:255',
            'transaction_password' => 'required|string',
        ]);

        $user = TradeUser::where('id', $request->userid)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }
        $adminUser = AdminLogin::where('PK_ID', Session::get('admin_id'))->first();

        if (!Hash::check($request->input('transaction_password'), $adminUser->TransPass)) {
            return redirect()->back()->with('error', 'Invalid transaction password.');
        }

        $fund = new DepositeMaster();
        $fund->UserId = $user->id;
        $fund->Amount = $request->amount;
        $fund->Approve_Status = 'APPROVED';
        $fund->notes = $request->notes;
        $fund->type = 0;
        $fund->LastModify = now();
        $fund->save();
        $transPage = 'withdraw  approval';
        $depType = '-';
        $remarks = 'withdraw';
        
        $this->approvedStatus($fund, $transPage, $depType, $remarks);

        return redirect()->back()->with('success', 'Fund Withdrawal successfully.');
    }

    public function approvedStatus($data, $transPage, $depType, $remarks)
    {

        Transdetail::create([
            'MemberId'   => $data->UserId,
            'TransType'  => 'Main Wallet',
            'TransPage'  => $transPage,
            'Type'       => $depType,
            'TransDate'  => now(),
            'Amount'     => $data->Amount,
            'AmountS'    => $data->Amount,
            'Remark'     => $remarks,
            'LoginId'    => $data->UserId,
            'Pass'       => '',
            'Expass'     => '',
            'CounterId'  => 0,
            'eWalletBit' => 1,
            'AddRemark'  => 'APPROVED BY ADMIN',
            'TransId'    => 0,
            'RefTransId' => 0,
            'AdminStatus'=>'APPROVED'
        ]);
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
        } else {
            return redirect('admin/login');
        }
        $data = DepositeMaster::with('user')
            ->where('Approve_Status', 'Pending')
            ->get();

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
            $transPage = 'Deposit approval';
            $depType = '+';
            $remarks = 'Deposit';

            if ($deposit->type == 0) {
                $transPage = 'withdraw  approval';
                $depType = '-';
                $remarks = 'withdraw';
            }

            if ($type === 'APPROVED') {
                // Create a wallet transaction
                Transdetail::create([
                    'MemberId'   => $deposit->UserId,
                    'TransType'  => 'Main Wallet',
                    'TransPage'  => $transPage,
                    'Type'       => $depType,
                    'TransDate'  => now(),
                    'Amount'     => $deposit->Amount,
                    'AmountS'    => $deposit->Amount,
                    'Remark'     => $remarks,
                    'LoginId'    => $deposit->UserId,
                    'Pass'       => '',
                    'Expass'     => '',
                    'CounterId'  => 0,
                    'eWalletBit' => 1,
                    'AddRemark'  => 'APPROVED BY ADMIN',
                    'TransId'    => 0,
                    'RefTransId' => 0,
                    'AdminStatus'=>'APPROVED'
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
        } else {
            return redirect('admin/login');
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
        } else {
            return redirect('admin/login');
        }

        return view('admin.accounts', compact('accountsData'));
    }

    /**
     * Show market scripts page
     */
    public function marketScripts()
    {
        // Get market scripts data
        // $scriptsData =  MarketMaster::all();

        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed market scripts',
                'ip_address' => request()->ip()
            ]);
        } else {
            return redirect('admin/login');
        }
        $scriptsData =  MarketMaster::where('CreatedBy', Session::get('admin_id'))->get();

        return view('admin.market-scripts', compact('scriptsData'));
    }
    public function scriptStatus(Request $request, $id)
    {
        $scriptsData =  MarketMaster::where('ScriptId', $id)->first();
        if ($scriptsData->Isactive == 1) {
            $status = 0;
        } else {
            $status = 1;
        }
        MarketMaster::where('ScriptId', $id)
            ->update(['Isactive' => $status]);
        return back()->with('success', 'Script Status updated successfully.');
    }
    public function editScript(Request $request, $id)
    {
        $scriptsData =  MarketMaster::where('ScriptId', $id)->first();

        return response()->json($scriptsData);
    }
    public function deleteScript(Request $request, $id)
    {

        MarketMaster::where('ScriptId', $id)->delete();
        return back()->with('success', 'Script Status Deleted successfully.');
    }
    public function updateScript(request $request)
    {
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed market scripts',
                'ip_address' => request()->ip()
            ]);
        }

        if ($request->id !== null) {
            // Update existing record
            MarketMaster::where('ScriptId', $request->id)->update([
                'ScriptName' => $request->script_name,
                'MarketType' => $request->market_type,
                'LotSize' => $request->lot_size,
                'TickSize' => $request->tick_size,
                'Isactive' => $request->is_active
            ]);
            return back()->with('success', 'Script Updated successfully.');
        } else {
            MarketMaster::create([
                'ScriptName' => $request->script_name,
                'MarketType' => $request->market_type,
                'LotSize' => $request->lot_size,
                'TickSize' => $request->tick_size,
                'Isactive' => $request->is_active,
                'CreatedBy' => Session::get('admin_id')
            ]);
            return back()->with('success', 'Script Create successfully.');
        }
    }



    /**
     * Show scrip data page
     */
    public function scripData()
    {
        // Get scrip data

        $scripdata = ForexOption::get();
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed scrip data',
                'ip_address' => request()->ip()
            ]);
        } else {
            return redirect('admin/login');
        }

        return view('admin.scrip-data', compact('scripdata'));
    }
}

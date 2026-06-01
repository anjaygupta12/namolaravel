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
use App\Models\LotSize;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\User\HomeController;
use Illuminate\Support\Facades\Http;
use Log;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\MarketCalendar;



class AdminController extends Controller
{
    /**
     * Display admin dashboard with summary statistics
     */
    public function dashboard()
    {
        //   updateFyersTokens();
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed admin dashboard',
                'ip_address' => request()->ip()
            ]);
        } else {
            return redirect('admin/login');
        }
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek   = Carbon::now()->endOfWeek(Carbon::SUNDAY);


        $adminId = Session::get('admin_id');
        $admin = AdminLogin::with('role')->where('PK_ID', $adminId)->first();
        $isAdmin = $admin->role->name === 'Admin';

        $transactionModes = ['MCX', 'NSE', 'OPTIONS', 'COMEX'];
        $modes = ['BUY', 'SELL'];

        $turnovers = [];

        foreach ($modes as $mode) {
            foreach ($transactionModes as $transactionMode) {
                $key = strtolower($transactionMode) . ucfirst(strtolower($mode)) . 'TurnOver';

                $query = MarketBidMaster::where('marketbidmaster.Mode', $mode)
                    ->join('tradeuser as tu', 'tu.id', '=', 'marketbidmaster.UserId') // ✅ alias 'tu' defined here
                    ->where('marketbidmaster.TransactionMode', $transactionMode)
                    // ->where('marketbidmaster.Isactive', 1)
                    ->whereBetween('marketbidmaster.updated_at', [$startOfWeek, $endOfWeek])
                    ->select(DB::raw('SUM(marketbidmaster.BuyPrice * marketbidmaster.Lots * marketbidmaster.LotSize) as total'));

                if (!$isAdmin) {
                    $query->where('tu.broker_id', $adminId);
                }

                $total = $query->value('total');
                $turnovers[$key] = $total ? number_format((float)$total / 100000, 2, '.', '') : '0';
            }
        }

        // All Turnover (Buy + Sell combined) for each TransactionMode

        foreach ($transactionModes as $transactionMode) {
            $key = strtolower($transactionMode) . 'AllTurnOver';

            $query = MarketBidMaster::join('tradeuser as tu', 'tu.id', '=', 'marketbidmaster.UserId') // ✅ alias 'tu' defined here
                ->where('marketbidmaster.TransactionMode', $transactionMode)
                // ->where('marketbidmaster.Mode', $mode)
                ->where('marketbidmaster.Isactive', 1)
                ->select(DB::raw('SUM(marketbidmaster.BuyPrice * marketbidmaster.Lots * marketbidmaster.LotSize) as total'));

            if (!$isAdmin) {
                $query->where('tu.broker_id', $adminId);
            }

            $total = $query->value('total');
            $turnovers[$key] = $total ? number_format((float)$total / 100000, 2, '.', '') : '0';
        }
        // dd($turnovers);
        $activeUsers = [];
        foreach ($transactionModes as $transactionMode) {
            $key = strtolower($transactionMode) . 'ActiveUser';
            $activeUsers[$key] = MarketBidMaster::join('tradeuser as tu', 'tu.id', '=', 'marketbidmaster.UserId')
                ->where('marketbidmaster.TransactionMode', $transactionMode)
                ->where('marketbidmaster.Isactive', 1)
                ->distinct('marketbidmaster.UserId')
                ->count('marketbidmaster.UserId');
        }

        $ProfitLoss = [];

        foreach ($transactionModes as $transactionMode) {
            $key = strtolower($transactionMode) . 'ProfitLoss';

            $subQuery = MarketBidMaster::join('tradeuser as tu', 'tu.id', '=', 'marketbidmaster.UserId')
                ->where('marketbidmaster.TransactionMode', $transactionMode)
                ->where('marketbidmaster.Isactive', 2)
                ->select(
                    DB::raw("
            CASE 
                WHEN marketbidmaster.Mode = 'SELL' 
                THEN (marketbidmaster.SalePrice * marketbidmaster.Lots * marketbidmaster.LotSize) 
                     - (marketbidmaster.BuyPrice * marketbidmaster.Lots * marketbidmaster.LotSize)
                ELSE 
                     (marketbidmaster.BuyPrice * marketbidmaster.Lots * marketbidmaster.LotSize) 
                     - (marketbidmaster.SalePrice * marketbidmaster.Lots * marketbidmaster.LotSize)
            END as pric
        ")
                );
            if (!$isAdmin) {
                $query->where('marketbidmaster.broker_id', $adminId);
            }

            $total = DB::table(DB::raw("({$subQuery->toSql()}) as t"))
                ->mergeBindings($subQuery->getQuery())
                ->sum('pric');

            $ProfitLoss[$key] = $total ? number_format((float)$total, 2, '.', '') : '0';
        }

        $brokerage = [];
        foreach ($transactionModes as $transactionMode) {
            $key = strtolower($transactionMode) . 'brokerage';

            $query = MarketBidMaster::join('tradeuser as tu', 'tu.id', '=', 'marketbidmaster.UserId') // ✅ alias 'tu' defined here
                ->where('marketbidmaster.TransactionMode', $transactionMode)
                ->where('marketbidmaster.Isactive', 1);
            if (!$isAdmin) {
                $query->where('tu.broker_id', $adminId);
            }

            $total = $query->value('marketbidmaster.brokrage'); // ✅ fetch first
            $brokerage[$key] = $total;
        }

        $activeBuyUsers = [];
        foreach ($transactionModes as $transactionMode) {
            $key = strtolower($transactionMode) . 'activeBuyUsers';
            $query = MarketBidMaster::join('tradeuser as tu', 'tu.id', '=', 'marketbidmaster.UserId')
                ->where('marketbidmaster.TransactionMode', $transactionMode)
                ->where('marketbidmaster.Mode', 'BUY')
                ->where('marketbidmaster.Isactive', 1);

            if (!$isAdmin) {
                $query->where('tu.broker_id', $adminId);
            }
            $activeBuyUsers[$key] =  $query->count();
        }

        $activeSellUsers = [];
        foreach ($transactionModes as $transactionMode) {
            $key = strtolower($transactionMode) . 'activeSellUsers';

            $query = MarketBidMaster::join('tradeuser as tu', 'tu.id', '=', 'marketbidmaster.UserId')
                ->where('marketbidmaster.TransactionMode', $transactionMode)
                ->where('marketbidmaster.Mode', 'SELL')
                ->where('marketbidmaster.Isactive', 1);
            if (!$isAdmin) {
                $query->where('tu.broker_id', $adminId);
            }
            $activeSellUsers[$key] =  $query->count();
        }

        $timing = DB::table('market_calendar')->where('market', 'MCX')->where('type', 'TIME')->first();
        $currentTime = Carbon::now()->format('H:i:s');

        if ($currentTime >= $timing->start_time && $currentTime <= $timing->end_time) {
            $marginColumn = 'marketbidmaster.used_margin_req';
        } else {
            $marginColumn = 'marketbidmaster.holding_margin_req';
        }

        // $now = Carbon::now();

        $startTime = Carbon::today()->setTime(6, 30);   // 6:30 AM
        $endTime   = Carbon::today()->setTime(18, 30);  // 6:30 PM
        $startOfWeek = Carbon::now()->startOfWeek(); // Monday
        $endOfWeek = Carbon::now()->endOfWeek();     // Sunday

        // $marginColumn = $now->between($startTime, $endTime)
        //     ? 'marketbidmaster.used_margin_req'
        //     : 'marketbidmaster.holding_margin_req';

        $query = DB::table('marketbidmaster')
            ->join('tradeuser', 'tradeuser.id', '=', 'marketbidmaster.UserId')
            ->join('adminlogin', 'tradeuser.broker_id', '=', 'adminlogin.PK_ID')
            ->select(
                'adminlogin.user_id as user_id',
                'adminlogin.PK_ID as id',
                'adminlogin.UserName as username',
                DB::raw('SUM(marketbidmaster.Lots) as total_trades'),
                DB::raw('GROUP_CONCAT(marketbidmaster.Pk_id) as trade_ids'),
                DB::raw("SUM(marketbidmaster.used_margin_req) as total_margin"),
                DB::raw("SUM(marketbidmaster.holding_margin_req) as holding_margin")
            )
            //   ->whereBetween('marketbidmaster.created_at', [$startOfWeek, $endOfWeek])
            ->where('marketbidmaster.Isactive', 1)
            ->whereNull('marketbidmaster.deleted_at')
            ->groupBy('adminlogin.PK_ID', 'adminlogin.UserName');

        if (!$isAdmin) {

             $childAdmins = AdminLogin::where('parent_id', $adminId)->pluck('PK_ID');

            $allAdminIds = collect([$adminId])
                ->merge($childAdmins)
                ->unique()
                ->toArray();
            
            $query->whereIn('tradeuser.broker_id', $allAdminIds);
            // $query->where('tradeuser.broker_id', $adminId); // ✅ 'tu' alias use karo, 'tradeuser' nahi
        }
        $totalTrade = $query->get();

        $query1 = TradeUser::query();

        if (!$isAdmin) {
            $query1->where('broker_id', $adminId);
        }

        $users = $query1->orderBy('created_at', 'desc')->count();

        return view('admin.dashboard', compact(
            'users',
            'totalTrade',
            'turnovers',
            'activeUsers',
            'ProfitLoss',
            'brokerage',
            'activeBuyUsers',
            'activeSellUsers'
        ));
    }

    public function brokerM2m($any, $id)
    {

    
        $adminId = Session::get('admin_id');

        $admin = AdminLogin::with('role')->where('PK_ID', $adminId)->first();

        $isAdmin = $admin->role->name === 'Admin';


        $transactionModes = ['MCX', 'NSE', 'OPTIONS', 'COMEX'];
        $modes = ['BUY', 'SELL'];
        $turnovers = $activeUsers = $ProfitLoss = $brokerage = $activeBuyUsers = $activeSellUsers = [];

        $isBroker = $any === 'broker';

        $baseQuery = MarketBidMaster::query();
        if ($isBroker) {
            $baseQuery->join('tradeuser', 'tradeuser.id', '=', 'marketbidmaster.UserId')
                ->join('adminlogin', 'tradeuser.broker_id', '=', 'adminlogin.PK_ID')
                ->where('tradeuser.broker_id', $id)->where('tradeuser.Isactive', 1)
                ->where('tradeuser.IsDemo', 0);
        } else {
            $baseQuery->where('marketbidmaster.UserId', $id)->where('marketbidmaster.Isactive', 1)
                ->join('tradeuser', 'tradeuser.id', '=', 'marketbidmaster.UserId')
                ->where('tradeuser.IsDemo', 0);
        }

        foreach ($modes as $mode) {
            foreach ($transactionModes as $transactionMode) {
                $key = strtolower($transactionMode) . ucfirst(strtolower($mode)) . 'TurnOver';
                $turnovers[$key] = (clone $baseQuery)
                    ->where('marketbidmaster.Mode', $mode)
                    ->where('marketbidmaster.TransactionMode', $transactionMode)
                    ->select(DB::raw('SUM(BuyPrice * Lots * LotSize) as total'))
                    ->value('total') / 100000 ?? 0;
            }
        }

        foreach ($transactionModes as $transactionMode) {
            $lowerKey = strtolower($transactionMode);

            // All TurnOver
            $turnovers[$lowerKey . 'AllTurnOver'] = (clone $baseQuery)
                ->where('marketbidmaster.TransactionMode', $transactionMode)
                ->select(DB::raw('SUM(SalePrice * Lots * LotSize) as total'))
                ->value('total') / 100000 ?? 0;

            // Active Users
            $activeUsers[$lowerKey . 'ActiveUser'] = (clone $baseQuery)
                ->where('marketbidmaster.TransactionMode', $transactionMode)
                ->where('marketbidmaster.Isactive', 1)
                ->count();

            // Profit Loss
            $sell = (clone $baseQuery)
                ->where('marketbidmaster.TransactionMode', $transactionMode)
                ->where('marketbidmaster.Mode', 'SELL')
                ->select(DB::raw('SUM(SalePrice * Lots * LotSize) as sell_total'))
                ->value('sell_total') ?? 0;

            $buy = (clone $baseQuery)
                ->where('marketbidmaster.TransactionMode', $transactionMode)
                ->where('marketbidmaster.Mode', 'BUY')
                ->select(DB::raw('SUM(SalePrice * Lots * LotSize) as buy_total'))
                ->value('buy_total') ?? 0;

            $ProfitLoss[$lowerKey . 'ProfitLoss'] = $sell - $buy;

            // Brokerage
            $brokerage[$lowerKey . 'brokerage'] = (clone $baseQuery)
                ->where('marketbidmaster.TransactionMode', $transactionMode)
                ->sum('marketbidmaster.brokrage');

            // Active BUY Users
            $activeBuyUsers[$lowerKey . 'activeBuyUsers'] = (clone $baseQuery)
                ->where('marketbidmaster.TransactionMode', $transactionMode)
                ->where('marketbidmaster.Mode', 'BUY')
                ->where('marketbidmaster.Isactive', 1)
                ->count();

            // Active SELL Users
            $activeSellUsers[$lowerKey . 'activeSellUsers'] = (clone $baseQuery)
                ->where('marketbidmaster.TransactionMode', $transactionMode)
                ->where('marketbidmaster.Mode', 'SELL')
                ->where('marketbidmaster.Isactive', 1)
                ->count();
        }

        // Total Trades and Margin Calculation
        $timing = DB::table('market_calendar')->where('market', 'MCX')->where('type', 'TIME')->first();
        $currentTime = Carbon::now()->format('H:i:s');

        if ($currentTime >= $timing->start_time && $currentTime <= $timing->end_time) {
            $marginColumn = 'marketbidmaster.used_margin_req';
        } else {
            $marginColumn = 'marketbidmaster.holding_margin_req';
        }

        // $now = Carbon::now();
        // $startTime = Carbon::today()->setTime(6, 30);
        // $endTime = Carbon::today()->setTime(18, 30);

        // $marginColumn = $now->between($startTime, $endTime)
        //     ? 'marketbidmaster.used_margin_req'
        //     : 'marketbidmaster.holding_margin_req';

        $tradeQuery = DB::table('marketbidmaster')
            ->join('tradeuser', 'tradeuser.id', '=', 'marketbidmaster.UserId')
            ->where('marketbidmaster.Isactive', 1);

        // ==============================
        // TOTAL TRADE SUMMARY
        // ==============================

        if ($isBroker) {
          $admin = AdminLogin::where('parent_id', $id)->pluck('PK_ID');

$totalTrade = MarketBidMaster::join(
        'tradeuser',
        'tradeuser.id',
        '=',
        'marketbidmaster.UserId'
    )

    ->where(function ($q) use ($id, $admin) {
        $q->where('tradeuser.broker_id', $id)
          ->orWhereIn('tradeuser.broker_id', $admin);
    })

    ->where('tradeuser.IsDemo', 0)
    ->where('marketbidmaster.Isactive', 1)

    ->select(
        'tradeuser.id',
        'tradeuser.user_id',
        'tradeuser.FullName as username',
        'tradeuser.balance',
        'tradeuser.broker_id',

        DB::raw('SUM(marketbidmaster.Lots) as total_trades'),
        DB::raw('GROUP_CONCAT(marketbidmaster.Pk_id) as trade_ids'),
        DB::raw("SUM($marginColumn) as total_margin")
    )

    ->groupBy(
        'tradeuser.id',
        'tradeuser.user_id',
        'tradeuser.FullName',
        'tradeuser.balance',
        'tradeuser.broker_id'
    )

    ->get();
        } else {

            $totalTrade = MarketBidMaster::join(
                'tradeuser',
                'tradeuser.id',
                '=',
                'marketbidmaster.UserId'
            )
                ->where('marketbidmaster.Isactive', 1)
                ->where('tradeuser.IsDemo', 0)
                ->where('marketbidmaster.UserId', $id)
                ->select(
                    'tradeuser.balance',
                    'marketbidmaster.Mode',
                    'tradeuser.Username as username',
                    'tradeuser.Username as admin',
                    DB::raw('SUM(marketbidmaster.Lots) as total_trades'),
                    DB::raw("SUM($marginColumn) as total_margin")
                )
                ->groupBy(
                    'marketbidmaster.Mode',
                    'tradeuser.Username',
                    'tradeuser.balance'
                )
                ->get();
        }

        $query = MarketBidMaster::join(
            'tradeuser',
            'tradeuser.id',
            '=',
            'marketbidmaster.UserId'
        )
            ->selectRaw('
            is_qty,
             MAX(LotSize) as LotSize,
            SUM(quentity) as quentity,
                        SUM(marketbidmaster.SalePrice) as SalePrice,
                        SUM(marketbidmaster.BuyPrice) as BuyPrice,
                        COUNT(*) as active,
                       ROUND(SUM(marketbidmaster.BuyPrice * marketbidmaster.Lots) / SUM(marketbidmaster.Lots), 2) as avgBuy,
                       ROUND(SUM(marketbidmaster.SalePrice * marketbidmaster.Lots) / SUM(marketbidmaster.Lots), 2) as avgSell,
                        SUM(marketbidmaster.Lots) as total,
                        COUNT(DISTINCT marketbidmaster.UserId) as users,
                        MAX(marketbidmaster.created_at) as timestamp,
                        marketbidmaster.Symbol,
                        MAX(marketbidmaster.Pk_id) as Pk_id,
                        MAX(tradeuser.broker_id) as broker_id,
                        marketbidmaster.Mode,
                        SUM(' . $marginColumn . ') as total_margin
                    ')->where('marketbidmaster.Isactive', 1);

        // Broker filter
        if ($isBroker) {
            $query->where('tradeuser.broker_id', $id);
        } else {
            $query->where('marketbidmaster.UserId', $id);
        }


        // Final positions result
        $positions = $query
            ->groupBy('marketbidmaster.Symbol', 'marketbidmaster.Mode', 'is_qty')
            ->orderByRaw("SUBSTRING_INDEX(marketbidmaster.Symbol, ':', -1) ASC")
            ->get();


        // $totalTrade = $tradeQuery->get();

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


        $query = TradeUser::with('broker');
        
        if (!$isAdmin || $isBroker) {
            $query->where('broker_id', $adminId);
        }

        $users = $query->orderBy('created_at', 'desc')->count();

        return view('admin.dashboard', compact(
            'users',
            'positions',
            'totalTrade',
            'turnovers',
            'activeUsers',
            'ProfitLoss',
            'brokerage',
            'activeBuyUsers',
            'activeSellUsers'
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
    public function changeAuthCode()
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
        $data = DB::table('fyers')->first();
        return view('admin.change-authcode', compact('data'));
    }
    public function updateAuthCode(request $request)
    {
        //  $output = file_put_contents('/tmp/restart_namo', '1');

        // dd($output);

        DB::table('fyers')->where('id', 1)->update(['FYERS_AUTH_CODE' => $request->code]);
        updateFyersTokens();
        return redirect()->back()->with('success', 'AUTH CODE Updated Successfully!');
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
        } else {
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
    public function updateUserPassword(Request $request)
    {
        $segment2 = $request->segment(2);

        $request->validate([
            'new_password' => 'required',
        ]);

        $admin = AdminLogin::find(Session::get('admin_id'));

        if ($request->user == null) {
            // $admin->Password = Hash::make($request->new_password);
        } else {

            TradeUser::where('id', $request->user)->update(['Password' => Hash::make($request->new_password), 'device_id' => null]);
        }

        return back()->with('success', 'password updated successfully.');
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
    public function __actionLedger(Request $request)
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
    public function actionLedger(Request $request)
    {
        $adminId = Session::get('admin_id');

        $admin = AdminLogin::with('role')->where('PK_ID', $adminId)->first();

        if (!$admin) {
            return redirect('admin/login');
        }

        $isAdmin = $admin->role->name === 'Admin';

        $query = DB::table('transdetail as td')
            ->join('tradeuser as tu', 'td.MemberId', '=', 'tu.id')
            ->selectRaw("td.Pk_id,tu.broker_id, td.TransType, td.transaction_id, td.type, td.TransPage, 
                    tu.FullName, tu.UserName as UserName, td.PK_ID, td.AmountS,
                    td.Remark, 'Deposit' as Mode, td.adminstatus, td.transdate as Timestamp")
            ->orderBy('td.TransDate', 'desc');
        if (!$isAdmin) {
            $query->where('tu.broker_id', $adminId);
        }

        // ✅ Search filter
        if ($request->filled('message')) {
            $search = $request->input('message');
            $query->where('td.Remark', 'like', '%' . $search . '%');
        }

        $logs = $query->get();
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
        $activeTrades = MarketBidMaster::where('Isactive', 1)
            ->where('UserId', Auth::guard('tradeuser')->user()->id)
            ->orderBy('timestamp', 'desc')
            ->get();

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
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed market watch',
                'ip_address' => request()->ip()
            ]);
        } else {
            return redirect('admin/login');
        }

        $admin_id = Session::get('admin_id');

        $parentBloacked = collect();
        $selfBloacked = collect();

        if ($admin_id != 1) {

            $broker_id = AdminLogin::where('PK_ID', $admin_id)
                ->pluck('parent_id')->push(1);

            $parentBloacked = DB::table('userbannedsymbols')
                ->whereIn('brokerid', $broker_id)
                ->where('banned', 1)
                ->pluck('symbol');

            $selfBloacked = DB::table('userbannedsymbols')
                ->where('brokerid', $admin_id)
                ->where('banned', 1)
                ->pluck('symbol');
        } else {

            $selfBloacked = DB::table('userbannedsymbols')
                ->where('brokerid', $admin_id)
                ->where('banned', 1)
                ->pluck('symbol');
        }
        // dd($adminBlocked,$parentBloacked,$selfBloacked);
        /* Merge admin + parent blocked */


        return view('admin.market-watch', compact(
            'parentBloacked',
            'selfBloacked'
        ));
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
        $homeControl = new HomeController();
        $adminId = Session::get('admin_id');

        $admin = AdminLogin::with('role')->where('PK_ID', $adminId)->first();

        if (!$admin) {
            return redirect('admin/login');
        }

        $isAdmin = $admin->role->name === 'Admin';
        // $positions = MarketBidMaster::with('user')
        //     ->whereHas('user', function ($q) use ($isAdmin, $adminId) {
        //         $q->where('IsDemo', 0);

        //         // Restrict to broker's users if not Admin
        //         if (!$isAdmin) {
        //             $q->where('broker_id', $adminId);
        //         }
        //     })->where('Isactive', 1)
        //     ->select(
        //         'Symbol',
        //         DB::raw('MAX(Pk_id) as Pk_id'),
        //         DB::raw('SUM(Lots) as Lots'),
        //         DB::raw('SUM(used_margin_req) as used_margin_req'),
        //         DB::raw('SUM(holding_margin_req) as holding_margin_req'),
        //         DB::raw('SUM(brokrage) as brokrage'),
        //         DB::raw("
        //             ROUND(
        //                 SUM(CASE WHEN Mode = 'BUY' THEN BuyPrice * Lots END) 
        //                 / NULLIF(SUM(CASE WHEN Mode = 'BUY' THEN Lots END), 0)
        //             , 2) AS BuyPrice
        //         "),

        //         DB::raw("
        //             ROUND(
        //                 SUM(CASE WHEN Mode = 'SELL' THEN BuyPrice * Lots END) 
        //                 / NULLIF(SUM(CASE WHEN Mode = 'SELL' THEN Lots END), 0)
        //             , 2) AS SellPrice
        //         "),
        //         DB::raw("
        //             SUM(CASE WHEN Mode = 'BUY' THEN Lots END) AS BuyCount
        //         "),

        //         DB::raw("
        //             SUM(CASE WHEN Mode = 'SELL' THEN Lots END) AS SellCount
        //         "),
        //     )
        //     ->groupBy('Symbol')
        //     ->get();

        // $positions = $positions->map(function ($row) use ($homeControl) {
        //     $row->setRawAttributes(
        //         array_merge($row->getAttributes(), [
        //             'lotSize' => $homeControl->getLotValume($row->Symbol),
        //         ]),
        //         true  // 👈 'true' syncs original attributes, keeps model intact
        //     );
        //     return $row;
        // });

        // $positions = $positions->map(function ($row) use ($homeControl) {
        //     $row->setRawAttributes(
        //         array_merge($row->getAttributes(), [
        //             'm2m'  => $this->getLiveData($row->Symbol),
        //         ]),
        //         true  // 👈 'true' syncs original attributes, keeps model intact
        //     );
        //     return $row;
        // });




        // $positions = MarketBidMaster::with('user')
        //     ->whereHas('user', function ($q) use ($isAdmin, $adminId) {
        //         $q->where('IsDemo', 0);

        //         // Restrict to broker's users if not Admin
        //         if (!$isAdmin) {
        //             $q->where('broker_id', $adminId);
        //         }
        //     })
        //     ->select(
        //         'Mode',
        //         'Symbol',
        //         DB::raw('MAX(Pk_id) as Pk_id'),
        //         DB::raw('SUM(Lots) as Lots'),
        //         DB::raw('SUM(used_margin_req) as used_margin_req'),
        //         DB::raw('SUM(holding_margin_req) as holding_margin_req'),
        //         DB::raw('SUM(brokrage) as brokrage'),
        //         DB::raw('MAX(created_at) as created_at'),

        //         // CONDITIONAL AVERAGE
        //         DB::raw("
        //     ROUND(
        //         SUM(CASE WHEN Mode = 'BUY' THEN BuyPrice * Lots END) 
        //         / NULLIF(SUM(CASE WHEN Mode = 'BUY' THEN Lots END), 0)
        //     , 2) AS BuyPrice
        // "),

        //         DB::raw("
        //     ROUND(
        //         SUM(CASE WHEN Mode = 'SELL' THEN BuyPrice * Lots END) 
        //         / NULLIF(SUM(CASE WHEN Mode = 'SELL' THEN Lots END), 0)
        //     , 2) AS SellPrice
        // "),
        //         DB::raw("
        //     COUNT(CASE WHEN Mode = 'BUY' THEN 1 END) AS BuyCount
        // "),

        //         DB::raw("
        //     COUNT(CASE WHEN Mode = 'SELL' THEN 1 END) AS SellCount
        // "),

        //         DB::raw('MAX(Timestamp) as Timestamp')
        //     )
        //     ->where('Isactive', 1)

        //     ->groupBy('Symbol', 'Mode')
        //     ->orderBy('created_at', 'desc')
        //     ->get();
        // dd($positions);
        // $positions = $positions->map(function ($row) use ($homeControl) {
        //     $row->lotSize = $homeControl->getLotValume($row->Symbol);
        //     return $row;
        // });
        $positions = MarketBidMaster::join(
            'tradeuser',
            'tradeuser.id',
            '=',
            'marketbidmaster.UserId'
        )
            ->selectRaw('
        marketbidmaster.is_qty,

        CASE 
            WHEN marketbidmaster.is_qty = 1 
            THEN SUM(marketbidmaster.quentity) 
            ELSE MAX(marketbidmaster.LotSize) 
        END as LotSize,

        SUM(marketbidmaster.SalePrice) as SalePrice,
        SUM(marketbidmaster.BuyPrice) as BuyPrice,

        COUNT(*) as active,

        ROUND(
            SUM(marketbidmaster.BuyPrice * marketbidmaster.Lots) 
            / NULLIF(SUM(marketbidmaster.Lots),0), 
        2) as avgBuy,

        ROUND(
            SUM(marketbidmaster.SalePrice * marketbidmaster.Lots) 
            / NULLIF(SUM(marketbidmaster.Lots),0), 
        2) as avgSell,

        SUM(marketbidmaster.Lots) as total,

        COUNT(DISTINCT marketbidmaster.UserId) as users,

        MAX(marketbidmaster.created_at) as timestamp,

        marketbidmaster.Symbol,
        marketbidmaster.Mode,

        MAX(marketbidmaster.Pk_id) as Pk_id,
        MAX(tradeuser.broker_id) as broker_id
    ')
            ->where('marketbidmaster.Isactive', 1)

            ->when(!$isAdmin, function ($query) use ($adminId) {
                // Level 1 children
                $childAdmins = AdminLogin::where('parent_id', $adminId)->pluck('PK_ID');

                $subChildAdmins = AdminLogin::whereIn('parent_id', $childAdmins)->pluck('PK_ID');

                // Merge all IDs
                $allAdminIds = collect([$adminId])
                    ->merge($childAdmins)
                    ->merge($subChildAdmins)
                    ->unique()
                    ->toArray();

                $query->whereIn('tradeuser.broker_id', $allAdminIds);
                // $query->where('tradeuser.broker_id', $adminId);
            })

            ->whereNull('marketbidmaster.deleted_at')

            ->groupBy(
                'marketbidmaster.Symbol',
                'marketbidmaster.Mode',
                'marketbidmaster.is_qty'
            )

            ->orderByDesc('timestamp')

            ->get();
        $positions = $positions->map(function ($row) use ($homeControl) {
            $row->setRawAttributes(
                array_merge($row->getAttributes(), [
                    'm2m'  => $this->getLiveData($row->Symbol) ?? 0,
                ]),
                true  // 👈 'true' syncs original attributes, keeps model intact
            );
            return $row;
        });
        $positions = $positions->map(function ($row) use ($homeControl) {

            $responseApi = $this->getMarketData($row->Symbol);

            $cmp = 0;

            if (!empty($responseApi['data'])) {
                $cmp = $row->Mode === 'BUY'
                    ? ($responseApi['data']['bid_price'] ?? 0)
                    : ($responseApi['data']['ask_price'] ?? 0);
            }

            $row->setRawAttributes(
                array_merge($row->getAttributes(), [
                    'TradeLast' => $cmp
                ]),
                true
            );

            return $row;
        });

        // dd($positions);
        return view('admin.active-positions', compact('positions'));
    }

    public function getLiveData($symbols)
    {

        $fyers = DB::table('fyers')->first();
        $token = $fyers->FYERS_CLIENT_ID . ':' . $fyers->FYERS_ACCESS_TOKEN;

        $response = Http::withHeaders([
            'Authorization' => $token
        ])->get('https://api-t1.fyers.in/data/quotes', [
            'symbols' => $symbols,
        ]);

        if (!$response->successful()) {
            return [];
        }

        $apiData = collect($response->json()['d'])->keyBy('n');

        $sellPriceTotal = 0;
        $totalPL = 0;
        $trades = MarketBidMaster::where('Symbol', $symbols)
            ->where('Isactive', 1)
            ->get();
       
        foreach ($trades as $trade) {
            $symbol = $trade->Symbol;

            // if (!isset($apiData[$symbol])) continue;

            // $data = $apiData[$symbol];

            // $bid = $data['v']['bid'] ?? 0;
            // $ask = $data['v']['ask'] ?? 0;"
             if (!empty($symbol) && strpos($symbol, '&') !== false) {
                        $symbol = str_replace('&', '%26', $symbol);
                    }

                        $data = $this->getMarketData($symbol);

            //  $testing[] = $symbol;
            if (isset($data['data']['bid_price'])) {
                $bid = $data['data']['bid_price'];
                $ask = $data['data']['ask_price'];
            } else {
                $lastData = DB::table('forexoptions')->where('Symbol', $trade->Symbol)->first();

                $bid = $lastData->bid;
                $ask = $lastData->ask;
            }
            

            $sellPrice = $trade->Mode === 'BUY' ? $bid : $ask;
            if ($trade->Mode == 'SELL') {
                $lotSize = $trade->Lots * $trade->LotSize;
                $pl = ($trade->BuyPrice - $sellPrice) * $lotSize;
            } else {
                $lotSize = $trade->Lots * $trade->LotSize;
                $pl = ($sellPrice - $trade->BuyPrice) * $lotSize;
            }

            $sellPriceTotal += $sellPrice;
            $totalPL += $pl;
        }

        return $totalPL;
    }

    /**
     * Show closed positions page
     */
    public function activeUsers(Request $request, $id)
    {

        $symbol = MarketBidMaster::find($id)->Symbol;

        $users = MarketBidMaster::selectRaw("marketbidmaster.UserId,marketbidmaster.UserId,tradeuser.user_id,tradeuser.id,tradeuser.Username,
                    SUM(CASE 
                        WHEN marketbidmaster.Mode = 'SELL' 
                        THEN marketbidmaster.Lots 
                        ELSE 0 
                    END) AS total_sell,
                         SUM(CASE 
                        WHEN marketbidmaster.Mode = 'BUY' 
                        THEN marketbidmaster.Lots 
                        ELSE 0 
                    END) AS total_buy,
                    SUM(marketbidmaster.Lots) AS total_trades,

                    MAX(marketbidmaster.created_at) AS timestamp
                ")
            ->join('tradeuser', 'tradeuser.id', '=', 'marketbidmaster.UserId')
            ->where('marketbidmaster.Symbol', $symbol)
            ->where('marketbidmaster.Isactive', 1)
            ->where('tradeuser.IsDemo', 0)
            ->groupBy('marketbidmaster.UserId', 'tradeuser.user_id', 'tradeuser.Username')
            ->orderBy('timestamp', 'desc')
            ->get();
        // $users = MarketBidMaster::where('marketbidmaster.Symbol', $symbol)
        // ->where('marketbidmaster.Isactive', 1)->get();

        // dd($users);
        return view('admin.active-user', compact('symbol', 'users'));
    }
    public function closedUsers(Request $request, $id)
    {

        $symbol = MarketBidMaster::find($id)->Symbol;

        $closedTrade = MarketBidMaster::selectRaw("
                marketbidmaster.UserId,
                tradeuser.user_id,
                tradeuser.id,
                tradeuser.Username,

                COUNT(CASE WHEN marketbidmaster.Mode = 'SELL' THEN 1 END) AS total_sell,
                COUNT(CASE WHEN marketbidmaster.Mode = 'BUY' THEN 1 END) AS total_buy,

                (
                    COUNT(CASE WHEN marketbidmaster.Mode = 'SELL' THEN 1 END) +
                    COUNT(CASE WHEN marketbidmaster.Mode = 'BUY' THEN 1 END)
                ) AS total_trades,

                AVG(marketbidmaster.SalePrice) AS avg_sale_price,
                AVG(marketbidmaster.BuyPrice) AS avg_buy_price,
                SUM(marketbidmaster.Lots) AS total_lots,
                SUM(marketbidmaster.brokrage) AS total_brokerage,
                AVG(marketbidmaster.LotSize) AS avg_lotsize,

                MAX(marketbidmaster.created_at) AS timestamp,
                MAX(marketbidmaster.updated_at) AS timestamp_update
            ")
            ->join('tradeuser', 'tradeuser.id', '=', 'marketbidmaster.UserId')
            ->where('marketbidmaster.Symbol', $symbol)
            ->groupBy(
                'marketbidmaster.UserId',
                'tradeuser.user_id',
                'tradeuser.id',
                'tradeuser.Username'
            )
            ->orderBy('timestamp', 'desc')
            ->get();


        return view('admin.delete-user', compact('symbol', 'closedTrade'));
    }

    public function closedPositions()
    {

        $positions = [];

        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed closed positions',
                'ip_address' => request()->ip()
            ]);
        }
        $adminId = Session::get('admin_id');

        $admin = AdminLogin::with('role')->where('PK_ID', $adminId)->first();

        if (!$admin) {
            return redirect('admin/login');
        }

        $isAdmin = $admin->role->name === 'Admin';

        $positions = MarketBidMaster::with('user')
            ->whereHas('user', function ($q) use ($isAdmin, $adminId) {
                $q->where('IsDemo', 0);

                // Restrict to broker's users if not Admin
                if (!$isAdmin) {
                    // Level 1 children
                    $childAdmins = AdminLogin::where('parent_id', $adminId)->pluck('PK_ID');

                    // Level 2 children (optional)
                    $subChildAdmins = AdminLogin::whereIn('parent_id', $childAdmins)->pluck('PK_ID');

                    // Merge all IDs
                    $allAdminIds = collect([$adminId])
                        ->merge($childAdmins)
                        ->merge($subChildAdmins)
                        ->unique()
                        ->toArray();

                    $q->whereIn('broker_id', $allAdminIds);
                    // $q->where('broker_id', $adminId);
                }
            })
            ->selectRaw('
                IFNULL(AVG(Lots), 1) AS Lots,
                IFNULL(AVG(BuyPrice), 0) AS BUYPRICE,
                COUNT(Isactive) AS active,
                IFNULL(AVG(SalePrice), 0) AS SELLPRICE,
                SUM(
                    CASE 
                        WHEN Mode = "BUY" THEN 
                            (BuyPrice * LotSize * Lots - SalePrice * LotSize * Lots)
                        ELSE 
                            (SalePrice * LotSize * Lots - BuyPrice * LotSize * Lots)
                    END
                ) AS netpl,
                SUM(brokrage) AS brokrage,
                Symbol,
                MAX(Pk_id) AS Pk_id,
                MAX(created_at) AS latest_time
            ')
            ->where('Isactive', 2)
            ->groupBy('Symbol')
            ->orderByDesc('latest_time')
            ->get();


        return view('admin.closed-positions', compact('positions'));
    }

    /**
     * Show users page
     */
    public function users(Request $request)
    {

        // Auth check first - before any queries
        if (!Session::has('admin_id')) {
            return redirect('admin/login');
        }

        $adminId = Session::get('admin_id');

        $admin = AdminLogin::with('role')->where('PK_ID', $adminId)->first();

        if (!$admin) {
            return redirect('admin/login');
        }

        $isAdmin = $admin->role->name === 'Admin';

        // Base query

        $query = TradeUser::with(['transactions', 'broker', 'bidamount'])
            ->select([
                'id',
                'broker_id',
                'user_id',
                'FullName',
                'Username',
                'IsActive',
                'IsDemo',
                'created_at',
                'funds',
                'balance',
                'deposits',
                'withdrawals',
                'net_p_l',
                'MCXBrokerage',
            ])
            ->withSum(['bidamount as weekly_profit_loss' => function ($q) {
                $q->whereBetween('updated_at', [
                    now()->startOfWeek(Carbon::MONDAY),
                    now()->endOfWeek(Carbon::FRIDAY)
                ])
                    ->where('Isactive', 2);
            }], DB::raw("
                    CASE 
                        WHEN Mode = 'SELL' THEN
                            (BuyPrice * (Lots * LotSize)) -
                            (SalePrice * (Lots * LotSize))
                    ELSE
                        (SalePrice * (Lots * LotSize)) -
                        (BuyPrice * (Lots * LotSize))
                    END
                "));

        // Restrict non-admins to their own broker users only
        // if (!$isAdmin) {
        //     $admin = AdminLogin::where('parent_id', $adminId)->pluck('PK_ID');

        //     $query->where(function ($q) use ($adminId, $admin) {
        //         $q->where('broker_id', $adminId)
        //             ->orWhereIn('broker_id', $admin);
        //     });
        // }

        if (!$isAdmin) {

            // Level 1 children
            $childAdmins = AdminLogin::where('parent_id', $adminId)->pluck('PK_ID');

            // Level 2 children (optional)
            $subChildAdmins = AdminLogin::whereIn('parent_id', $childAdmins)->pluck('PK_ID');

            // Merge all IDs
            $allAdminIds = collect([$adminId])
                ->merge($childAdmins)
                ->merge($subChildAdmins)
                ->unique()
                ->toArray();

            $query->whereIn('broker_id', $allAdminIds);
        }

        // dd($isAdmin,$query->first());
        // Apply filters
        if ($request->filled('username')) {
            $query->where('Username', 'like', $request->username . '%');
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Fixed: was using wrong variable $request->search_status instead of $request->status
        if ($request->filled('status')) {
            $query->where('IsActive', $request->status);
        }

        $users = $query->orderBy('created_at', 'desc')->get();
        // dd($users);
        // Log activity
        AdminLog::create([
            'admin_id'   => $adminId,
            'activity'   => 'Viewed users list',
            'ip_address' => $request->ip()
        ]);

        return view('admin.users', compact('users'));
    }

    public function subBrokerUsers(Request $request, $id)
    {
      
        // Auth check first - before any queries
        if (!Session::has('admin_id')) {
            return redirect('admin/login');
        }

        $adminId = Session::get('admin_id');

        $admin = AdminLogin::with('role')->where('PK_ID', $id)->first();

        if (!$admin) {
            return redirect('admin/login');
        }

        $isAdmin = $admin->role->name === 'Admin';

        // Base query

        $query = TradeUser::with(['transactions', 'broker', 'bidamount'])
            ->select([
                'id',
                'broker_id',
                'user_id',
                'FullName',
                'Username',
                'IsActive',
                'IsDemo',
                'created_at',
                'funds',
                'balance',
                'deposits',
                'withdrawals',
                'net_p_l',
                'MCXBrokerage',
            ])
            ->withSum(['bidamount as weekly_profit_loss' => function ($q) {
                $q->whereBetween('updated_at', [
                    now()->startOfWeek(Carbon::MONDAY),
                    now()->endOfWeek(Carbon::FRIDAY)
                ])
                    ->where('Isactive', 2);
            }], DB::raw("
                    CASE 
                        WHEN Mode = 'SELL' THEN
                            (BuyPrice * (Lots * LotSize)) -
                            (SalePrice * (Lots * LotSize))
                    ELSE
                        (SalePrice * (Lots * LotSize)) -
                        (BuyPrice * (Lots * LotSize))
                    END
                "));

        // Restrict non-admins to their own broker users only
        if (!$isAdmin) {
            $admin = AdminLogin::where('parent_id', $id)->pluck('PK_ID');

            $query->where(function ($q) use ($adminId, $id, $admin) {
                $q->where('broker_id', $id)
                    ->orWhereIn('broker_id', $admin);
            });
        }
    
        // Apply filters
        if ($request->filled('username')) {
            $query->where('Username', 'like', $request->username . '%');
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Fixed: was using wrong variable $request->search_status instead of $request->status
        if ($request->filled('status')) {
            $query->where('IsActive', $request->status);
        }

        $users = $query->orderBy('created_at', 'desc')->get();
        // dd($users);
        // Log activity
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
        $adminId = Session::get('admin_id');

        $brokers = AdminLogin::where('PK_ID', '!=', 1)->get();

        if ($adminId != 1) {
            $brokers = AdminLogin::where('PK_ID', '!=', 1)
                ->where('PK_ID', $adminId)
                ->OrWhere('parent_id', $adminId)
                ->get();
        }

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
            'fullname' => 'required',
            'username' => 'required',
            'password' => 'nullable',
        ]);
        if (!$request->id || $request->has('copy')) {
            $validator = Validator::make($request->all(), [
                'fullname' => 'required',
                'username' => 'required|unique:tradeuser,Username',
                'password' => 'required',
            ]);
        }


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $adminUser = AdminLogin::where('PK_ID', Session::get('admin_id'))->first();

        if (!Hash::check($request->input('transaction_password'), $adminUser->TransPass)) {
            return redirect()->back()->withInput()->with('error', 'Invalid transaction password.');
        }

        if ($request->input('Username') == $adminUser->TransPass) {
            return redirect()->back()->withInput()->with('error', 'You are entering a duplicate User Name.');
        }

        $bidmarket = MarketBidMaster::where('UserId', $request->id)->where('IsActive', 1);
        $user = $request->id ? TradeUser::findOrFail($request->id) : new TradeUser();

        if ($request->mcx_enabled == 0) {
            $exist = (clone $bidmarket)->where('TransactionMode', 'MCX')->exists();
            if ($exist) {
                return redirect()->back()->withInput()->with('error', "You can't disable MCX Tab because there are active MCX trades. Please close them first.");
            }
        }

        if ($request->nse_enabled != $user->NSEFuturesEnabled) {
            $exist = (clone $bidmarket)->where('TransactionMode', 'NSE')->exists();
            if ($exist) {
                return redirect()->back()->withInput()->with('error', "You can't disable NSE Tab because there are active NSE trades. Please close them first.");
            }
        }

        if ($request->id) {


            // $bidmarket = MarketBidMaster::where('UserId', $request->id)->get();

            // foreach ($bidmarket as $val) {

            //     $SymbolShortName = ForexOption::where('Symbol', $val->Symbol)->value('SymbolShortName');
            //     $mcxLotMargin = $request->mcx_lot_margin ?? [];
            //     // dd($SymbolShortName);
            //     // $usedmargin = $mcxLotMargin[$SymbolShortName]['INTRADAY'];
            //     // $holdingmargin = $mcxLotMargin[$SymbolShortName]['HOLDING'];

            //     $val->used_margin_req =  $mcxLotMargin[$SymbolShortName]['INTRADAY'];
            //     $val->holding_margin_req =  $mcxLotMargin[$SymbolShortName]['HOLDING'];
            //     $val->save();
            // }
        }

        try {
            DB::beginTransaction();

            // update bid market margin 
            // mcx_lot_margin

            // Create or update user


            // dd($request->status,$request->has('status'),$request->has('Mcxusers.demo'));
            // Basic information
            if (!$request->id) {
                $user->user_id = rand(10000, 99999);
                // $user->Password = bcrypt($request->input('password'));
                // $user->balance =  $request->funds;
                // $user->funds = $request->funds;
            }

            if ($request->filled('password')) {
                $user->Password = bcrypt($request->password);
            }

            $user->FullName = $request->input('fullname');
            $user->Username = $request->input('username');

            $user->Mobile = $request->input('mobile');
            $user->City = $request->input('city');
            $user->TransPass = bcrypt($request->input('transaction_password'));
            $user->broker_id = $request->input('broker_id', 1);
            $user->Notes = $request->input('notes');
            // Account settings 
            $user->IsDemo = $request->Mcxusers['demo'] ? 1 : 0;
            $user->StopTrade =  $request->input('stop_trade', 0);
            $user->IsActive = $request->has('status') ? 1 : 0;
            $user->AutoSquareOff = (int) $request->auto_square_off;
            $user->TradeEquityAsUnits = $request->trade_equity_as_units;
            $user->AllowOrdersBeyondHighLow = $request->boolean('allow_orders_beyond_high_low');
            $user->AllowOrdersBetweenHighLow = $request->boolean('allow_orders_between_high_low');

            // Risk management
            $user->AutoSquareOffPercentage = $request->input('auto_square_off_percentage', 90);
            $user->NotifyPercentage = $request->input('notify_percentage', 70);
            $user->ProfitBookInterval = $request->input('profit_book_interval', 120);

            // MCX settings
            $user->MCXEnabled = $request->input('mcx_enabled', 0);
            $user->MCXMinLotPerTrade = $request->input('mcx_min_lot_per_trade', 0);
            $user->MCXMaxLotPerTrade = $request->input('mcx_max_lot_per_trade', 20);
            $user->MCXMaxLotPerScrip = $request->input('mcx_max_lot_per_scrip', 50);
            $user->MaxCommodityLots = $request->input('max_commodity_lots', 100);
            $user->mcx_brokerage_type = $request->mcx_brokerage_type;
            $user->MCXBrokerage = $request->input('mcx_brokerage', 800);
            $user->MCXExposureType = $request->input('mcx_exposure_type', 'per_turnover');
            $user->MCXIntradayMargin = $request->input('mcx_intraday_margin', 500);
            $user->MCXHoldingMargin = $request->input('mcx_holding_margin', 100);
            $user->MCXLotMarginJSON = json_encode($request->input('mcx_lot_margin', []));
            $user->MCXLotBrokerageJSON = json_encode($request->input('mcx_lot_brokerage', []));
            $user->MCXBidGapJSON = json_encode($request->input('mcx_bid_gap', []));

            // NSE Futures
            $user->NSEFuturesEnabled = $request->input('nse_enabled', 0);
            $user->NSEFuturesBrokerage = $request->input('nse_brokerage', 800);
            $user->NSEFuturesMinLotPerTrade = $request->input('nse_equity_min_lot_per_trade', 0);
            $user->NSEFuturesMaxLotPerTrade = $request->input('nse_equity_max_lot_per_trade', 1);
            $user->NSEIndexMinLotPerTrade = $request->input('nse_index_min_lot_per_trade', 0);
            $user->NSEIndexMaxLotPerTrade = $request->input('nse_index_max_lot_per_trade', 20);
            $user->NSEFuturesMaxLotPerScrip = $request->input('nse_equity_max_lot_per_scrip', 100);
            $user->NSEIndexMaxLotPerScrip = $request->input('nse_index_max_lot_per_scrip', 100);
            $user->MaxNSEFuturesLots = $request->input('max_nse_equity_lots', 100);
            $user->MaxNSEIndexLots = $request->input('max_nse_index_lots', 100);
            $user->NSEFuturesIntradayMargin = $request->input('nse_intraday_margin', 24);
            $user->NSEFuturesHoldingMargin = $request->input('nse_holding_margin', 23);
            $user->NSEBidGapPercentage = $request->input('nse_bid_gap_percentage', 0);

            // NSE Options
            $user->NSEOptionsEnabled = $request->input('options_enabled', 0);
            $user->EquityOptionsEnabled = $request->input('equity_options_enabled');
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
            $user->MCXOptionsEnabled = $request->mcx_options_enabled;
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
            $user->OptionsSSBrokerageType = $request->options_ss_brokerage_type;
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
            if (! $request->has('status') && $user->device_id != 0) {
                $user->device_id = null;
            }

            // Set created/modified info

            if ($request->id) {
                $user->ModifiedBy = Session::get('admin_id');
                $user->ModifiedDate = now();
            } else {
                $user->CreatedBy = Session::get('admin_id');
                $user->CreatedDate = now();
            }

            if ($request->filled('funds') || !$request->id) {
                $user->balance = $request->funds;
                $user->funds = $request->funds;
            }

            $user->save();
            DB::commit();
            $homeControl = new HomeController();
            $homeControl->updateTrades($user);
            $user->makeHidden(['password']);


            if ($request->filled('funds') || !$request->id) {

                $fund = new DepositeMaster();
                $fund->UserId = $user->id;
                $fund->Amount = $request->funds;
                $fund->Approve_Status = 'APPROVED';
                $fund->notes = 'Deposit Initial Fund added in ' . $user->Username;
                $fund->LastModify = now();
                $fund->type = 1;
                $fund->save();
                $homeControl->trnsectionDeatil($user->id, $user, '', '', 1);
            }
            if (!$request->id || $request->has('copy')) {
                return redirect()->route('admin.comex-margins', $user->id);
            }
            return redirect()->route('admin.users')->with('success', 'User ' . ($request->id ? 'updated' : 'created') . ' successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
            //return redirect()->back()->with('error', $e->getMessage())
            // ->withInput(); 
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
    public function resetAccount(Request $request, $id)
    {

        $adminUser = AdminLogin::where('PK_ID', Session::get('admin_id'))->first();

        if (!Hash::check($request->input('transaction_password'), $adminUser->TransPass)) {
            return redirect('/admin/reset-account-confirm/' . $id)
                ->with('error', 'Invalid transaction password.');
        }

        DepositeMaster::where('UserId', $id)->delete();
        Transdetail::where('UserId', $id)->delete();
        MarketBidMaster::where('UserId', $id)->delete();
        $user = TradeUser::find($id);
        if ($user) {
            $user->reset()->save();
        }
        return redirect('/admin/users/view/' . $id)
            ->with('success', 'Reset successfully Account.');

        // return redirect()->back()->with('success', 'Reset successfully Account.');
    }
    public function recalculateBrokerage(Request $request)
    {

        return redirect()->back()->with('success', 'Please reset Brockrage change Successfully.');
    }
    public function viewUser($id)
    {

        $user = TradeUser::with(['transactions', 'broker', 'bidamount'])
            ->withSum([
                'bidamount as weekly_profit_loss' => function ($q) {
                    $q->whereBetween('updated_at', [
                        now()->startOfWeek(Carbon::MONDAY),
                        now()->endOfWeek(Carbon::FRIDAY)
                    ])
                        ->where('Isactive', 2);
                }
            ], DB::raw("
        CASE 
            WHEN Mode = 'SELL' THEN
                (BuyPrice * (Lots * LotSize)) -
                (SalePrice * (Lots * LotSize))
        ELSE
                (SalePrice * (Lots * LotSize)) -
                (BuyPrice * (Lots * LotSize))
        END
    "))
            ->findOrFail($id);

        // $funds = Transdetail::where('MemberId', $id)->paginate(2);
        $startOfWeek = Carbon::now()->startOfWeek(); // Monday
        $endOfWeek = Carbon::now()->endOfWeek();     // Sunday
        $funds = DepositeMaster::where('UserId', $id)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->orderByDesc('created_at')->get();

        $trades = MarketBidMaster::with('user')
            ->where('UserId', $id)
            ->where('Isactive', 1)
            ->get()
            ->map(function ($item) {
                $item->clean_symbol = explode(':', $item->Symbol)[1] ?? $item->Symbol;
                return $item;
            })
            ->sortBy('clean_symbol')
            ->values();

        $closedTrade = MarketBidMaster::with('user')->where('UserId', $id)
            ->orderBy('updated_at', 'desc')
            ->whereBetween('updated_at', [$startOfWeek, $endOfWeek])
            ->where('Isactive', 2)->get();

        $mxcPendingTrade = MarketBidMaster::with('user')->where('UserId', $id)
            ->where('Isactive', 0)
            ->orderBy('updated_at', 'desc')
            ->whereBetween('updated_at', [$startOfWeek, $endOfWeek])
            ->where('TransactionMode', 'MCX')->get();

        $equityPendingTrade = MarketBidMaster::with('user')->where('UserId', $id)
            ->where('Isactive', 0)
            ->orderBy('updated_at', 'desc')
            ->whereBetween('updated_at', [$startOfWeek, $endOfWeek])
            ->where('TransactionMode', 'NSE')->get();

        $comexPendingTrade = MarketBidMaster::with('user')->where('UserId', $id)
            ->where('Isactive', 0)
            ->orderBy('updated_at', 'desc')
            ->whereBetween('updated_at', [$startOfWeek, $endOfWeek])
            ->where('TransactionMode', 'COMEX')->get();

        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed user details: ' . $user->name,
                'ip_address' => request()->ip()
            ]);
        } else {
            return redirect('admin/login');
        }
        // dd($funds);
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

    public function tradeView(Request $request, $id)
    {
        $trades = MarketBidMaster::with('user')->where('Pk_id', $id)->first();

        return view('admin.trade-view', compact('trades'));
    }
    public function EditTrade(Request $request, $id)
    { 
        $trades = MarketBidMaster::join('forexoptions', 'marketbidmaster.Symbol', '=', 'forexoptions.Symbol')
            ->select('marketbidmaster.*', 'forexoptions.SymbolShortName')
            ->where('marketbidmaster.Pk_id', $id)
            ->first();

        $tradeUser = TradeUser::all();
        $forexOption = ForexOption::where('Isactive', 1)->get();

        return view('admin.update-trade', compact('trades', 'tradeUser', 'forexOption'));
    }
    public function updateTrade(Request $request)
    {
       
        $trade = MarketBidMaster::join('forexoptions', 'marketbidmaster.Symbol', '=', 'forexoptions.Symbol')
        ->select('marketbidmaster.*', 'forexoptions.SymbolShortName')
        ->where('marketbidmaster.Pk_id', $request->id)
        ->first();

        $homeControl = new HomeController();

        $lotSize = $homeControl->getLotValume($request->input('scrip_id'));

        if ($trade->is_qty) {
            $lots = $request->lots / $lotSize;
        } else {
            $lots = $request->lots;
        }
            if($request->input('min_mega')==1){
            if($trade->SymbolShortName=='GOLD' || $trade->SymbolShortName=='COPPER' || $trade->SymbolShortName=='CRUDEOIL'){
                $lot = $request->lots;
                $lots = 10*$lot / $lotSize;
                $lotSize = 10;
                }  

                if($trade->SymbolShortName=='SILVER'){

                $lot = $request->lots;
                $lots = 5*$lot / $lotSize;
                $lotSize = 5;
                }
            }

        // Get Symbol Short Name (Safe Check)
        $symbolData = ForexOption::where('Symbol', $request->input('scrip_id'))->first();

        $SymbolShortName = $symbolData->SymbolShortName;
        $transactionmode = $request->transactionmode;
        $user = Auth::guard('tradeuser')->user() ?? TradeUser::findOrFail($request->userid);


        $marginAvilabe = $homeControl->getMarginCheckAvilable($user, $SymbolShortName, $lotSize, $transactionmode, $lots);
        $m2mbalance = $this->dashboardLivePl('admin', $user->id);
        $data = json_decode($m2mbalance->getContent(), true);
        $pl = $data[0]['pl'] ?? 0;
        $userBalance = $user->balance + ($pl);

        // if ($userBalance < $marginAvilabe['need_balance']) {
        //     return redirect('admin/users/view/' . $request->userid)->with('error', 'Your available balance is: ' . $user->balance . ' Fund Needed: ' . $marginAvilabe['need_balance'] . ' used margin: ' . $marginAvilabe['used_margin']);
        // }

        $adminUser = AdminLogin::where('PK_ID', Session::get('admin_id'))->first();

        if (!Hash::check($request->input('transaction_password'), $adminUser->TransPass)) {
            return redirect('admin/users/view/' . $request->userid)->with('error', 'Invalid transaction password.');
        }
        $active = 1;
        $ipAddrss = null;
        if (!empty($request->input('buy_price')) && !empty($request->input('sell_price'))) {
            $active = 2;
            $ipAddrss = '127.0.0.1';
            if ($request->input('Mode') == 'BUY') {

                $buyPrice  = $request->input('buy_price');
                $sellPrice = $request->input('sell_price');

            } else {

                $buyPrice  = $request->input('sell_price');
                $sellPrice = $request->input('buy_price');
            }

        } else {

            $buyPrice  = $request->input('buy_price') ?? $request->input('sell_price');
            $sellPrice = null;
        }

        $data = [
            'Symbol' => $request->input('scrip_id'),
            'BuyPrice' => $buyPrice,
            'SalePrice' => $sellPrice,
            'UserId' => $request->userid,
            'Lots' => $lots,
            'holding_margin_req' => $marginAvilabe['intraday'] ?? 0 * $request->lots,
            'used_margin_req' => $marginAvilabe['holding'] ?? 0 * $request->lots,
            'quentity' => ($trade->is_qty == 1) ? $request->lots : $lotSize,
            'Isactive'=>$active,
            'updated_at' => now()
        ];
            if ($ipAddrss != null) {
                $data['IpAddress'] = $ipAddrss;
                $data['sell_ip_address'] = $ipAddrss;
            }

        // dd($data);
        MarketBidMaster::where('Pk_id', $request->id)->update($data);

        return redirect('admin/users/view/' . $request->userid)->with('success', 'Updated Successfully..');
    }

    public function DeleteTradeConfirm($id)
    {
        $trade = MarketBidMaster::withTrashed()->find($id);

        return view('admin.delete-trade-confirm', compact('trade'));
    }

    public function closedTradesConfirm($id)
    {
        $trade = MarketBidMaster::with('user')->withTrashed()->find($id);

        $responseApi = $this->getMarketData($trade->Symbol);
       
        $sellPrice = $trade->Mode === 'BUY' ? $responseApi['data']['bid_price'] : $responseApi['data']['ask_price'];

        return view('admin.close-trade-confirm', compact('trade', 'sellPrice'));
    }


    public function deleteUserConfirm($id)
    {
        $user = $user = TradeUser::findOrFail($id);
        return view('admin.delete-user-confirm', compact('user'));
    }

    public function resetAcountConfirm($id)
    {
        $user = $user = TradeUser::findOrFail($id);
        return view('admin.reset-account-confirm', compact('user'));
    }

    public function closedTradeAdmin(Request $request, $id, $userid)
    {
        $adminUser = AdminLogin::where('PK_ID', Session::get('admin_id'))->first();

        if (!Hash::check($request->input('transaction_password'), $adminUser->TransPass)) {
            return redirect('/admin/close-trade-confirm/' . $id . '/' . $userid)
                ->with('error', 'Invalid transaction password.');
        }
        $trade = MarketBidMaster::where('Pk_id', $id)->first();
        $homeControl = new HomeController();
        $responseApi = $homeControl->getMarketData($trade->Symbol);

        $sellPrice = $trade->Mode === 'BUY' ? $responseApi['data']['bid_price'] : $responseApi['data']['ask_price'];
        $confirm = $homeControl->closeBulkTradesExist($trade, $sellPrice, true);
       
        return redirect('admin/users/view/' . $userid)
            ->with('success', 'Trade Closeed successfully.');
    }

    public function DeleteTrade(Request $request, $id, $userid)
    {
        $adminUser = AdminLogin::where('PK_ID', Session::get('admin_id'))->first();

        if (!Hash::check($request->input('transaction_password'), $adminUser->TransPass)) {
            return redirect('/admin/delete-trade-confirm/' . $id . '/' . $userid)
                ->with('error', 'Invalid transaction password.');
        }

        $trade = MarketBidMaster::find($id);
        if ($trade->Isactive != 1) {
            $profitLoss = 0;
            $lotSize = (int) $trade->Lots * $trade->LotSize;
            $buyRate = (float) $trade->BuyPrice;
            $sellRate = (float) $trade->SalePrice;
            $lots = (float) $trade->Lots;
            $buyTurnover = $buyRate * $lotSize;
            $sellTurnover = $sellRate * $lotSize;
            if ($trade->Mode == 'SELL') {
                $profitLoss =  $buyTurnover - $sellTurnover;
            } else {
                $profitLoss = $sellTurnover - $buyTurnover;
            }

            $brokerage = $trade->brokrage;

            $user = TradeUser::findOrFail($request->userid);
            $user->decrement('balance', $profitLoss);
            $user->decrement('net_p_l', $profitLoss);

            $user->increment('balance', $brokerage);
            $user->increment('net_p_l', $brokerage);
        }

        if ($trade) {
            $trade->delete();
            return redirect('admin/users/view/' . $userid)
                ->with('success', 'Trade Deleted successfully.');
        }

        return redirect('admin/users/view/' . $userid)
            ->with('success', 'Trade Deleted successfully.');
    }


    public function RestoreTrade($id)
    {
        $trade = MarketBidMaster::withTrashed()->find($id);
        return view('admin.restore-trade', compact('trade'));
    }


    public function RestoreTradeUpdate(Request $request)
    {
        $adminUser = AdminLogin::where('PK_ID', Session::get('admin_id'))->first();

        if (!Hash::check($request->input('transaction_password'), $adminUser->TransPass)) {
            return redirect('/admin/restore-trade/' . $request->id . '/' . $request->userid)
                ->with('error', 'Invalid transaction password.');
        }


        $trade = MarketBidMaster::withTrashed()->find($request->id);
        $profitLoss = 0;
        $lotSize = (int) $trade->Lots * $trade->LotSize;
        $buyRate = (float) $trade->BuyPrice;
        $sellRate = (float) $trade->SalePrice;
        $lots = (float) $trade->Lots;
        $buyTurnover = $buyRate * $lotSize;
        $sellTurnover = $sellRate * $lotSize;
        if ($trade->Mode == 'SELL') {
            $profitLoss =  $buyTurnover - $sellTurnover;
        } else {
            $profitLoss = $sellTurnover - $buyTurnover;
        }
        $brokerage = $trade->brokrage;

        $user = TradeUser::findOrFail($request->userid);
        //  dd($profitLoss,$brokerage);
        //   if ($profitLoss >= 0) {
        $user->decrement('balance', $profitLoss);
        $user->decrement('net_p_l', $profitLoss);

        $user->increment('balance', $brokerage);
        $user->increment('net_p_l', $brokerage);
        // } else {
        //     $user->increment('balance', $profitLoss);
        //     $user->increment('net_p_l', $profitLoss);
        // }

        $symbol = $trade->Symbol;
        $homeControl = new HomeController();

        $lotSize = $homeControl->getLotValume($symbol);
        $SymbolShortName = ForexOption::where('Symbol', $symbol)->value('SymbolShortName');
        $marginAvilabe = $homeControl->getMarginCheckAvilable($user, $SymbolShortName, $lotSize, $trade->TransactionMode, $trade->Lots, $trade->BuyPrice);

        $m2mbalance = $this->dashboardLivePl('admin-restore', $user->id);
        $data = json_decode($m2mbalance->getContent(), true);
        $pl = $data[0]['pl'] ?? 0;
        $userBalance = $user->balance + ($pl);

        //     if ($userBalance < $marginAvilabe['need_balance']) {
        //     return redirect('admin/users/view/'.$request->userid)->with('error', 'Your available balance is: ' . $user->balance . ' Fund Needed: ' . $marginAvilabe['need_balance'] . ' used margin: ' . $marginAvilabe['used_margin']);
        // }


        $trade = MarketBidMaster::withTrashed()->find($request->id);

        if ($trade) {
            $trade->Isactive = 1;
            $trade->holding_margin_req = $marginAvilabe['intraday'] ?? 0 * $trade->lots;
            $trade->used_margin_req = $marginAvilabe['holding'] ?? 0 * $trade->lots;
            $trade->restore();

            return redirect('admin/users/view/' . $request->userid)->with('success', 'Trade restored successfully!');
        }

        return redirect('admin/users/view/' . $request->userid)->with('error', 'Trade not found!');
    }

    public function dashboardLivePl($segment, $user_id)
    {

        // return false;
        // Base query for active trades
        $query = MarketBidMaster::with('user')->where('Isactive', 1);
        // Handle User Segment
        if ($segment === 'user' && $user_id) {

            $trades = $query->where('UserId', $user_id)->get();
        }
        // Handle Broker Segment
        elseif ($segment === 'broker') {
             $admin = AdminLogin::where('parent_id', $user_id)->pluck('PK_ID');

            $brokersTrades = DB::table('marketbidmaster')
                ->join('tradeuser', 'tradeuser.id', '=', 'marketbidmaster.UserId')
                ->join('adminlogin', 'tradeuser.broker_id', '=', 'adminlogin.PK_ID')
                ->where('marketbidmaster.Isactive', 1)
                ->where(function ($q) use ($user_id, $admin) {
                        $q->where('tradeuser.broker_id', $user_id)
                        ->orWhereIn('tradeuser.broker_id', $admin);
                    })
                // ->where('tradeuser.broker_id', $user_id)
                // ->where('tradeuser.IsDemo', 0)
                ->select(
                    'adminlogin.UserName as admin',
                    'tradeuser.id as id',
                    'tradeuser.user_id as user_id',
                    'tradeuser.Username as username',
                    DB::raw('GROUP_CONCAT(marketbidmaster.Pk_id) as trade_ids')
                )
                ->groupBy('tradeuser.id')
                ->get();

            $result = [];

            foreach ($brokersTrades as $brokerTrade) {
                $tradeIds = explode(',', $brokerTrade->trade_ids);
                $trades = MarketBidMaster::whereIn('Pk_id', $tradeIds)->get();

                $symbols = $trades->pluck('Symbol')->unique()->toArray();
                if (empty($symbols)) continue;

                // $fyers = DB::table('fyers')->first();
                // $token = $fyers->FYERS_CLIENT_ID . ':' . $fyers->FYERS_ACCESS_TOKEN;

                // $response = Http::withHeaders([
                //     'Authorization' => $token
                // ])->get('https://api-t1.fyers.in/data/quotes', [
                //     'symbols' => implode(',', $symbols),
                // ]);

                // if (!$response->successful()) continue;

                $symbolsString = implode(',', $symbols);
                if (strpos($symbolsString, '&') !== false) {
                    $symbolsString = str_replace('&', '%26', $symbolsString);
                }
                $apiResponse = $this->getMarketData($symbolsString);
                $normalizedMarketData = [];
                if (isset($apiResponse['success']) && $apiResponse['success']) {
                    if (isset($apiResponse['count'])) {
                        $normalizedMarketData = $apiResponse['data'] ?? [];
                    } else if (isset($apiResponse['symbol'])) {
                        $normalizedMarketData[$apiResponse['symbol']] = $apiResponse['data'];
                    }
                }

                $sellPriceTotal = 0;
                $totalPL = 0;

                foreach ($trades as $trade) {
                    $symbol = $trade->Symbol;
                    $symbolKey = $symbol;
                    if (!empty($symbolKey) && strpos($symbolKey, '&') !== false) {
                        $symbolKey = str_replace('&', '%26', $symbolKey);
                    }

                    if (isset($normalizedMarketData[$symbolKey]) && $normalizedMarketData[$symbolKey] !== null && isset($normalizedMarketData[$symbolKey]['bid_price'])) {
                        $bid = $normalizedMarketData[$symbolKey]['bid_price'];
                        $ask = $normalizedMarketData[$symbolKey]['ask_price'];
                    } else {
                        $lastData = DB::table('forexoptions')->where('Symbol', $trade->Symbol)->first();
                        $bid = $lastData->bid ?? 0;
                        $ask = $lastData->ask ?? 0;
                    }

                    $sellPrice = $trade->Mode === 'BUY' ? $bid : $ask;
                    if ($trade->Mode == 'SELL') {
                        $lotSize = (int)$trade->Lots * $trade->LotSize;
                        $pl = ($trade->BuyPrice - $sellPrice) * $lotSize;
                    } else {
                        $lotSize = (int)$trade->Lots * $trade->LotSize;
                        $pl = ($sellPrice - $trade->BuyPrice) * $lotSize;
                    }

                    $sellPriceTotal += $sellPrice;
                    $totalPL += $pl;
                }

                $result[] = [
                    'trade_id'   => $brokerTrade->trade_ids,
                    'Pk_id'      => $brokerTrade->id,
                    'Symbol'     => '',
                    'TradeLast'  => $sellPriceTotal,
                    'pl'         => $totalPL
                ];
            }

            return response()->json($result);
        }
        // Handle Admin Segment

        elseif ($segment === 'main-admin') {
            $brokersTrades = DB::table('marketbidmaster')
                ->join('tradeuser', 'tradeuser.id', '=', 'marketbidmaster.UserId')
                ->join('adminlogin', 'tradeuser.broker_id', '=', 'adminlogin.PK_ID')
                ->where('marketbidmaster.Isactive', 1)
                ->select(
                    'adminlogin.user_id as user_id',
                    'adminlogin.PK_ID as id',
                    'adminlogin.UserName',
                    DB::raw('GROUP_CONCAT(marketbidmaster.Pk_id) as trade_ids'),
                    DB::raw('MAX(adminlogin.Pk_id) as bid_id'),
                )
                ->groupBy('adminlogin.PK_ID', 'adminlogin.UserName')
                ->get();
            $result = [];
            foreach ($brokersTrades as $brokerTrade) {
                $tradeIds = explode(',', $brokerTrade->trade_ids);
                $trades = MarketBidMaster::with('user')->whereIn('Pk_id', $tradeIds)->get();

                $symbols = $trades->pluck('Symbol')->unique()->toArray();
                if (empty($symbols)) continue;

                // $fyers = DB::table('fyers')->first();
                // $token = $fyers->FYERS_CLIENT_ID . ':' . $fyers->FYERS_ACCESS_TOKEN;

                // $response = Http::withHeaders([
                //     'Authorization' => $token
                // ])->get('https://api-t1.fyers.in/data/quotes', [
                //     'symbols' => implode(',', $symbols),
                // ]);

                // if (!$response->successful()) continue;

                $symbolsString = implode(',', $symbols);
                if (strpos($symbolsString, '&') !== false) {
                    $symbolsString = str_replace('&', '%26', $symbolsString);
                }
                $apiResponse = $this->getMarketData($symbolsString);
                $normalizedMarketData = [];
                if (isset($apiResponse['success']) && $apiResponse['success']) {
                    if (isset($apiResponse['count'])) {
                        $normalizedMarketData = $apiResponse['data'] ?? [];
                    } else if (isset($apiResponse['symbol'])) {
                        $normalizedMarketData[$apiResponse['symbol']] = $apiResponse['data'];
                    }
                }

                $sellPriceTotal = 0;
                $totalPL = 0;

                $userCache = [];
                foreach ($trades as $trade) {
                    $symbol = $trade->Symbol;
                    $symbolKey = $symbol;
                    if (!empty($symbolKey) && strpos($symbolKey, '&') !== false) {
                        $symbolKey = str_replace('&', '%26', $symbolKey);
                    }

                    if (isset($normalizedMarketData[$symbolKey]) && $normalizedMarketData[$symbolKey] !== null && isset($normalizedMarketData[$symbolKey]['bid_price'])) {
                        $bid = $normalizedMarketData[$symbolKey]['bid_price'];
                        $ask = $normalizedMarketData[$symbolKey]['ask_price'];
                    } else {
                        $lastData = DB::table('forexoptions')->where('Symbol', $trade->Symbol)->first();
                        $bid = $lastData->bid ?? 0;
                        $ask = $lastData->ask ?? 0;
                    }

                    $sellPrice = $trade->Mode === 'BUY' ? $bid : $ask;
                    $lotSize = (int)$trade->Lots * $trade->LotSize;

                    // $pl = ($sellPrice - $trade->BuyPrice) * $lotSize;

                    if ($trade->Mode == 'SELL') {
                        $pl = ($trade->BuyPrice - $sellPrice) * $lotSize;
                    } else {
                        $pl = ($sellPrice - $trade->BuyPrice) * $lotSize;
                    }

                    $sellPriceTotal += $sellPrice;
                    $totalPL += $pl;
                    // dd();
                    if (!isset($userCache[$trade->user->id])) {
                        $userCache[$trade->user->id] = [
                            'cradits' => DB::table('transdetail')->where('MemberId', $trade->user->id)->where('Remark', 'Deposit')->sum('Amount'),
                            'widrows' => DB::table('withdrawlmaster')->where('UserId', $trade->user->id)->sum('Amount'),
                            'balance' => $trade->user->balance
                        ];
                    }
                    $cradits = $userCache[$trade->user->id]['cradits'];
                    $widrows = $userCache[$trade->user->id]['widrows'];
                    $balance = $userCache[$trade->user->id]['balance'];
                }
                // dd($testing);
                $result[] = [
                    'bid_id'   => $brokerTrade->bid_id,
                    'Pk_id'      => $brokerTrade->id,
                    'Symbol'     => '',
                    'TradeLast'  => $sellPriceTotal,
                    'pl'         => $totalPL,
                    'cradit'     => $cradits,
                    'widrows'    => $widrows,
                    'balance'    => $balance
                ];
            }

            return response()->json($result);
        } elseif ($segment === 'admin' && $user_id) {
            $result = [];

            $brokersTrades = DB::table('marketbidmaster as mb')
                ->join('tradeuser as tu', 'tu.id', '=', 'mb.UserId')
                ->where('mb.Isactive', 1)
                ->where('mb.UserId', $user_id)
                ->select(
                    'tu.id as user_id',
                    'tu.broker_id as id',
                    'mb.Symbol',
                    DB::raw('GROUP_CONCAT(mb.Pk_id) as trade_ids'),
                    DB::raw('MAX(mb.Pk_id) as bid_id'),
                    DB::raw('DATE(MAX(mb.created_at)) as date')
                )
                ->groupBy(
                    'mb.Symbol',
                    'tu.id',
                )->get();

            // PRE-FETCH MARKET DATA FOR ALL SYMBOLS ONCE
            $allSymbols = $brokersTrades->pluck('Symbol')->unique()->toArray();
            $allSymbolsString = implode(',', $allSymbols);
            if (strpos($allSymbolsString, '&') !== false) {
                $allSymbolsString = str_replace('&', '%26', $allSymbolsString);
            }
            $apiResponse = [];
            if (!empty($allSymbolsString)) {
                $apiResponse = $this->getMarketData($allSymbolsString);
            }
            $normalizedMarketData = [];
            if (isset($apiResponse['success']) && $apiResponse['success']) {
                if (isset($apiResponse['count'])) {
                    $normalizedMarketData = $apiResponse['data'] ?? [];
                } else if (isset($apiResponse['symbol'])) {
                    $normalizedMarketData[$apiResponse['symbol']] = $apiResponse['data'];
                }
            }

            foreach ($brokersTrades as $brokerTrade) {
                $tradeIds = explode(',', $brokerTrade->trade_ids);
                $trades = MarketBidMaster::with('user')->whereIn('Pk_id', $tradeIds)->get();

                $sellPriceTotal = 0;
                $totalPL = 0;
                $cradits = 0;
                $widrows = 0;
                $balance = 0;
                $userCache = [];
                foreach ($trades as $trade) {
                    $symbol = $trade->Symbol;
                    $symbolKey = $symbol;
                    if (!empty($symbolKey) && strpos($symbolKey, '&') !== false) {
                        $symbolKey = str_replace('&', '%26', $symbolKey);
                    }

                    if (isset($normalizedMarketData[$symbolKey]) && $normalizedMarketData[$symbolKey] !== null && isset($normalizedMarketData[$symbolKey]['bid_price'])) {
                        $bid = $normalizedMarketData[$symbolKey]['bid_price'];
                        $ask = $normalizedMarketData[$symbolKey]['ask_price'];
                    } else {
                        $lastData = DB::table('forexoptions')->where('Symbol', $trade->Symbol)->first();
                        $bid = $lastData->bid ?? 0;
                        $ask = $lastData->ask ?? 0;
                    }

                    $sellPrice = $trade->Mode === 'BUY' ? $bid : $ask;

                    // $sellPrice = $trade->Mode == 'BUY' ? $data['v']['bid'] : $data['v']['ask'];
                    $lotSize = $trade->Lots * $trade->LotSize;

                    // $pl = ($sellPrice - $trade->BuyPrice) * $lotSize;
                    if ($trade->Mode == 'SELL') {
                        $pl = ($trade->BuyPrice - $sellPrice) * $lotSize;
                    } else {
                        $pl = ($sellPrice - $trade->BuyPrice) * $lotSize;
                    }

                    $sellPriceTotal += $sellPrice;
                    $totalPL += $pl;
                    // dd($trade);
                    if (!isset($userCache[$trade->user->id])) {
                        $userCache[$trade->user->id] = [
                            'cradits' => DB::table('transdetail')->where('MemberId', $trade->user->id)->where('Remark', 'Deposit')->sum('Amount'),
                            'widrows' => DB::table('withdrawlmaster')->where('UserId', $trade->user->id)->sum('Amount'),
                            'balance' => $trade->user->balance
                        ];
                    }
                    $cradits = $userCache[$trade->user->id]['cradits'];
                    $widrows = $userCache[$trade->user->id]['widrows'];
                    $balance = $userCache[$trade->user->id]['balance'];
                }
                // dd('');
                $result[] = [
                    'bid_id'   => $brokerTrade->bid_id,
                    'Pk_id'      => $brokerTrade->id,
                    'Symbol'     => '',
                    'TradeLast'  => $sellPriceTotal,
                    'pl'         => $totalPL,
                    'cradit'     => $cradits,
                    'widrows'    => $widrows,
                    'balance'    => $balance,
                    'date' => $brokerTrade->date ?? ''
                ];
            }

            return response()->json($result);
        } elseif ($segment === 'admin-restore' && $user_id) {
            $result = [];

            $brokersTrades = DB::table('marketbidmaster as mb')
                ->join('tradeuser as tu', 'tu.id', '=', 'mb.UserId')

                ->where('mb.UserId', $user_id)
                ->select(
                    'tu.id as user_id',
                    'tu.broker_id as id',
                    'mb.Symbol',
                    DB::raw('GROUP_CONCAT(mb.Pk_id) as trade_ids'),
                    DB::raw('MAX(mb.Pk_id) as bid_id'),
                    DB::raw('DATE(MAX(mb.created_at)) as date')
                )
                ->groupBy(
                    'mb.Symbol',
                    'tu.id',
                )->get();

            foreach ($brokersTrades as $brokerTrade) {
                $tradeIds = explode(',', $brokerTrade->trade_ids);
                $trades = MarketBidMaster::with('user')->whereIn('Pk_id', $tradeIds)->get();

                $symbols = $trades->pluck('Symbol')->unique()->toArray();
                if (empty($symbols)) continue;

                // $fyers = DB::table('fyers')->first();
                // $token = $fyers->FYERS_CLIENT_ID . ':' . $fyers->FYERS_ACCESS_TOKEN;

                // $response = Http::withHeaders([
                //     'Authorization' => $token
                // ])->get('https://api-t1.fyers.in/data/quotes', [
                //     'symbols' => implode(',', $symbols),
                // ]);

                // if (!$response->successful()) continue;

                $symbolsString = implode(',', $symbols);
                if (strpos($symbolsString, '&') !== false) {
                    $symbolsString = str_replace('&', '%26', $symbolsString);
                }
                $apiResponse = $this->getMarketData($symbolsString);
                $normalizedMarketData = [];
                if (isset($apiResponse['success']) && $apiResponse['success']) {
                    if (isset($apiResponse['count'])) {
                        $normalizedMarketData = $apiResponse['data'] ?? [];
                    } else if (isset($apiResponse['symbol'])) {
                        $normalizedMarketData[$apiResponse['symbol']] = $apiResponse['data'];
                    }
                }

                $sellPriceTotal = 0;
                $totalPL = 0;
                 $cradits = 0;
                 $widrows = 0;
                 $balance = 0;
                $userCache = [];
                foreach ($trades as $trade) {
                    $symbol = $trade->Symbol;
                    $symbolKey = $symbol;
                    if (!empty($symbolKey) && strpos($symbolKey, '&') !== false) {
                        $symbolKey = str_replace('&', '%26', $symbolKey);
                    }

                    if (isset($normalizedMarketData[$symbolKey]) && $normalizedMarketData[$symbolKey] !== null && isset($normalizedMarketData[$symbolKey]['bid_price'])) {
                        $bid = $normalizedMarketData[$symbolKey]['bid_price'];
                        $ask = $normalizedMarketData[$symbolKey]['ask_price'];
                    } else {
                        $lastData = DB::table('forexoptions')->where('Symbol', $trade->Symbol)->first();
                        $bid = $lastData->bid ?? 0;
                        $ask = $lastData->ask ?? 0;
                    }

                    $sellPrice = $trade->Mode === 'BUY' ? $bid : $ask;
                    // $sellPrice = $trade->Mode == 'BUY' ? $data['v']['bid'] : $data['v']['ask'];
                    $lotSize = $trade->Lots * $trade->LotSize;

                    // $pl = ($sellPrice - $trade->BuyPrice) * $lotSize;
                    if ($trade->Mode == 'SELL') {
                        $pl = ($trade->BuyPrice - $sellPrice) * $lotSize;
                    } else {
                        $pl = ($sellPrice - $trade->BuyPrice) * $lotSize;
                    }
                    $sellPriceTotal += $sellPrice;
                    $totalPL += $pl;
                    // dd();
                    if (!isset($userCache[$trade->user->id])) {
                        $userCache[$trade->user->id] = [
                            'cradits' => DB::table('transdetail')->where('MemberId', $trade->user->id)->where('Remark', 'Deposit')->sum('Amount'),
                            'widrows' => DB::table('withdrawlmaster')->where('UserId', $trade->user->id)->sum('Amount'),
                            'balance' => $trade->user->balance
                        ];
                    }
                    $cradits = $userCache[$trade->user->id]['cradits'];
                    $widrows = $userCache[$trade->user->id]['widrows'];
                    $balance = $userCache[$trade->user->id]['balance'];
                }

                $result[] = [
                    'bid_id'   => $brokerTrade->bid_id,
                    'Pk_id'      => $brokerTrade->id,
                    'Symbol'     => '',
                    'TradeLast'  => $sellPriceTotal,
                    'pl'         => $totalPL,
                    'cradit'     => $cradits,
                    'widrows'    => $widrows,
                    'balance'    => $balance,
                    'date' => $brokerTrade->date ?? ''
                ];
            }

            return response()->json($result);
        }

        // Handle No-Segment or Default
        else {
            $trades = $query->get();
        }

        // --- Handle direct user or global calculation ---
        $symbols = $trades->pluck('Symbol')->unique()->toArray();
        if (empty($symbols)) {
            return response()->json([]);
        }

        // $fyers = DB::table('fyers')->first();
        // $token = $fyers->FYERS_CLIENT_ID . ':' . $fyers->FYERS_ACCESS_TOKEN;

        // $response = Http::withHeaders([
        //     'Authorization' => $token
        // ])->get('https://api-t1.fyers.in/data/quotes', [
        //     'symbols' => implode(',', $symbols),
        // ]);

        // if (!$response->successful()) {
        //     return response()->json(['error' => 'Unable to fetch prices'], 500);
        // }
        Log::info('heating from Admin 2 ');

        $symbolsString = implode(',', $symbols);
        if (strpos($symbolsString, '&') !== false) {
            $symbolsString = str_replace('&', '%26', $symbolsString);
        }
        $apiResponse = $this->getMarketData($symbolsString);
        $normalizedMarketData = [];
        if (isset($apiResponse['success']) && $apiResponse['success']) {
            if (isset($apiResponse['count'])) {
                $normalizedMarketData = $apiResponse['data'] ?? [];
            } else if (isset($apiResponse['symbol'])) {
                $normalizedMarketData[$apiResponse['symbol']] = $apiResponse['data'];
            }
        }

        $userCache = [];
        $result = [];

        foreach ($trades as $trade) {
           
            $symbol = $trade->Symbol;
            $symbolKey = $symbol;
            // if (!empty($symbolKey) && strpos($symbolKey, '&') !== false) {
            //     $symbolKey = str_replace('&', '%26', $symbolKey);
            // }
            // dd($normalizedMarketData[$symbolKey]);
            if (isset($normalizedMarketData[$symbolKey]) && $normalizedMarketData[$symbolKey] !== null && isset($normalizedMarketData[$symbolKey]['bid_price'])) {
                $bid = $normalizedMarketData[$symbolKey]['bid_price'];
                $ask = $normalizedMarketData[$symbolKey]['ask_price'];
            } else {
                $lastData = DB::table('forexoptions')->where('Symbol',$symbol)->first();
              
                $bid = $lastData->bid ?? 0;
                $ask = $lastData->ask ?? 0;
            }

            $sellPrice = $trade->Mode === 'BUY' ? $bid : $ask;

            // $sellPrice = $trade->Mode == 'BUY' ? $data['v']['bid'] : $data['v']['ask'];
            $lotSize = $trade->Lots * $trade->LotSize;
            // $pl = ($sellPrice - $trade->BuyPrice) * $lotSize;
            if ($trade->Mode == 'SELL') {
                $pl = ($trade->BuyPrice - $sellPrice) * $lotSize;
            } else {
                $pl = ($sellPrice - $trade->BuyPrice) * $lotSize;
            }

            if (!isset($userCache[$trade->user->id])) {
                $userCache[$trade->user->id] = [
                    'cradits' => DB::table('transdetail')->where('MemberId', $trade->user->id)->where('Remark', 'Deposit')->sum('Amount'),
                    'widrows' => DB::table('withdrawlmaster')->where('UserId', $trade->user->id)->sum('Amount'),
                    'balance' => $trade->user->balance
                ];
            }
            $cradits = $userCache[$trade->user->id]['cradits'];
            $widrows = $userCache[$trade->user->id]['widrows'];
            $balance = $userCache[$trade->user->id]['balance'];

            $result[] = [
                'Pk_id'     => $trade->Pk_id,
                'Symbol'    => $symbol,
                'TradeLast' => $sellPrice,
                'pl'        => $pl,
                'cradit'    => $cradits,
                'widrows'   => $widrows,
                'balance'   => $balance
            ];
        }

        return response()->json($result);
    }

    public function dashboardLivePl__($segment, $user_id)
    {
        // ─────────────────────────────────────────────
        // 1. Shared Fyers price fetcher
        // ─────────────────────────────────────────────

        $adminId = Session::get('admin_id') ?? Auth::guard('tradeuser')->user()->broker_id;
        $admin = AdminLogin::with('role')->where('PK_ID', $adminId)->first();
        $isAdmin = $admin->role->name === 'Admin';

        // $fetchPrices = function (array $symbols): ?\Illuminate\Support\Collection {

        //     if (empty($symbols)) return null;

        //     $response = $this->getMarketData(implode(',', $symbols));

        //     if (!isset($response['success']) || !$response['success']) {
        //         return collect();
        //     }

        //     // Normalize response
        //     $data = (count($symbols) === 1 && isset($response['symbol']))
        //         ? [$response['symbol'] => $response['data']]
        //         : ($response['data'] ?? []);

        //     return collect($data)->mapWithKeys(function ($item, $symbol) {
        //         return [
        //             $symbol => $this->formatMarketItem($item, $symbol)
        //         ];
        //     });
        // };
        $fetchPrices = function (array $symbols): ?\Illuminate\Support\Collection {
            if (empty($symbols)) return null;

            $fyers = DB::table('fyers')->first();
            $token = $fyers->FYERS_CLIENT_ID . ':' . $fyers->FYERS_ACCESS_TOKEN;

            $response = Http::withHeaders(['Authorization' => $token])
                ->get('https://api-t1.fyers.in/data/quotes', [
                    'symbols' => implode(',', $symbols),
                ]);

            if (!$response->successful()) return null;

            return collect($response->json()['d'])->keyBy('n');
        };

        // ─────────────────────────────────────────────
        // 2. Shared P&L calculator for a trade collection
        // ─────────────────────────────────────────────
        $calcPL = function (\Illuminate\Support\Collection $trades, \Illuminate\Support\Collection $apiData): array {

            $sellPriceTotal = 0;
            $totalPL        = 0;
            $transModeWise  = [];

            foreach ($trades as $trade) {

                $symbol = $trade->Symbol;

                if (!isset($apiData[$symbol])) {
                    continue;
                }

                $v   = $apiData[$symbol]['v'];
                $bid = $v['bid'] ?? 0;
                $ask = $v['ask'] ?? 0;

                $sellPrice = $trade->Mode === 'BUY' ? $bid : $ask;

                $lotSize = $trade->Lots * $trade->LotSize;

                $pl = $trade->Mode === 'SELL'
                    ? ($trade->BuyPrice - $sellPrice) * $lotSize
                    : ($sellPrice - $trade->BuyPrice) * $lotSize;

                $sellPriceTotal += $sellPrice;
                $totalPL += $pl;

                // TransactionMode wise PL
                $mode = $trade->TransactionMode;

                if (!isset($transModeWise[$mode])) {
                    $transModeWise[$mode] = 0;
                }

                $transModeWise[$mode] += $pl;
            }

            return [$sellPriceTotal, $totalPL, $transModeWise];
        };

        // ─────────────────────────────────────────────
        // 3. Shared user financial summary
        // ─────────────────────────────────────────────
        $getUserFinancials = function ($userId): array {
            return [
                'cradit'  => DB::table('transdetail')
                    ->where('MemberId', $userId)
                    ->where('Remark', 'Deposit')
                    ->sum('Amount'),
                'widrows' => DB::table('withdrawlmaster')
                    ->where('UserId', $userId)
                    ->sum('Amount'),
            ];
        };

        // ─────────────────────────────────────────────────────────────────────────
        // SEGMENT: user  — single user's active trades
        // ─────────────────────────────────────────────────────────────────────────
        if ($segment === 'user' && $user_id) {
            $trades = MarketBidMaster::where('Isactive', 1)
                ->where('UserId', $user_id)
                ->get();

            // falls through to the generic result builder below

            // ─────────────────────────────────────────────────────────────────────────
            // SEGMENT: broker  — all traders under a broker
            // ─────────────────────────────────────────────────────────────────────────
        } elseif ($segment === 'broker' && $user_id) {

            $query = DB::table('marketbidmaster')
                ->join('tradeuser',  'tradeuser.id',        '=', 'marketbidmaster.UserId')
                ->join('adminlogin', 'tradeuser.broker_id',  '=', 'adminlogin.PK_ID')
                ->where('marketbidmaster.Isactive', 1)
                ->where('tradeuser.broker_id', $user_id)
                ->select(
                    'adminlogin.UserName as admin',
                    'tradeuser.id        as id',
                    'tradeuser.user_id   as user_id',
                    'tradeuser.Username  as username',
                    DB::raw('GROUP_CONCAT(marketbidmaster.Pk_id) as trade_ids')
                )
                ->groupBy('tradeuser.id');
            if (!$isAdmin) {
                $query->where('tradeuser.broker_id', $adminId); // ✅ 'tu' alias use karo, 'tradeuser' nahi
            }
            $brokersTrades = $query->get();

            $result = [];
            $transModeWise = [];
            foreach ($brokersTrades as $row) {
                $trades  = MarketBidMaster::whereIn('Pk_id', explode(',', $row->trade_ids))->get();
                $symbols = $trades->pluck('Symbol')->unique()->toArray();
                if (empty($symbols)) continue;

                $apiData = $fetchPrices($symbols);
                if (!$apiData) continue;

                [$sellPriceTotal, $totalPL] = $calcPL($trades, $apiData);
                //     dd($row);
                //     if (!isset($transModeWise[$row->mode])) {
                //     $transModeWise[$row->mode] = 0;
                // }

                // $transModeWise[$row->mode] += $totalPL;

                $result[] = [
                    'trade_id'  => $row->trade_ids,
                    'Pk_id'     => $row->id,
                    'Symbol'    => '',
                    'TradeLast' => $sellPriceTotal,
                    'pl'        => $totalPL,
                    'transModeWise' => []
                ];
            }

            return response()->json($result);

            // ─────────────────────────────────────────────────────────────────────────
            // SEGMENT: main-admin  — aggregated per broker
            // ─────────────────────────────────────────────────────────────────────────
        } elseif ($segment === 'main-admin') {

            // Replace these with your actual auth values

            $query = DB::table('marketbidmaster')
                ->join('tradeuser',  'tradeuser.id',        '=', 'marketbidmaster.UserId')
                ->join('adminlogin', 'tradeuser.broker_id',  '=', 'adminlogin.PK_ID')
                ->where('marketbidmaster.Isactive', 1)
                ->select(
                    'adminlogin.user_id  as user_id',
                    'adminlogin.PK_ID    as id',
                    'adminlogin.UserName',
                    DB::raw('GROUP_CONCAT(marketbidmaster.Pk_id) as trade_ids'),
                    DB::raw('MAX(adminlogin.Pk_id) as bid_id')
                )
                ->groupBy('adminlogin.PK_ID', 'adminlogin.UserName');

            if (!$isAdmin && $adminId) {
                $query->where('tu.broker_id', $adminId);
            }

            $brokersTrades = $query->get();
            $result = [];

            foreach ($brokersTrades as $row) {
                $trades  = MarketBidMaster::with('user')->whereIn('Pk_id', explode(',', $row->trade_ids))->get();
                $symbols = $trades->pluck('Symbol')->unique()->toArray();
                if (empty($symbols)) continue;

                $apiData = $fetchPrices($symbols);
                if (!$apiData) continue;

                [$sellPriceTotal, $totalPL, $transModeWise] = $calcPL($trades, $apiData);
                // dd($sellPriceTotal, $totalPL,$transModeWise);
                $lastUser   = $trades->last()->user;
                $financials = $getUserFinancials($lastUser->id);

                $result[] = array_merge([
                    'bid_id'    => $row->bid_id,
                    'Pk_id'     => $row->id,
                    'Symbol'    => '',
                    'TradeLast' => $sellPriceTotal,
                    'pl'        => $totalPL,
                    'balance'   => $lastUser->balance,
                    'transModeWise' => $transModeWise
                ], $financials);
            }

            return response()->json($result);

            // ─────────────────────────────────────────────────────────────────────────
            // SEGMENT: admin / admin-restore  — per-symbol breakdown for one user
            // ─────────────────────────────────────────────────────────────────────────
        } elseif (in_array($segment, ['admin', 'admin-restore']) && $user_id) {

            $baseQuery = DB::table('marketbidmaster as mb')
                ->join('tradeuser as tu', 'tu.id', '=', 'mb.UserId')
                ->where('mb.UserId', $user_id)
                ->select(
                    'tu.id         as user_id',
                    'tu.broker_id  as id',
                    'mb.Symbol',
                    DB::raw('GROUP_CONCAT(mb.Pk_id) as trade_ids'),
                    DB::raw('MAX(mb.Pk_id)           as bid_id'),
                    DB::raw('DATE(MAX(mb.created_at)) as date')
                )->groupBy('mb.Symbol', 'tu.id');

            if (!$isAdmin) {
                $baseQuery->where('tu.broker_id', $adminId);
            }

            // Only filter active trades for the normal 'admin' segment
            if ($segment === 'admin') {
                $baseQuery->where('mb.Isactive', 1);
            }

            $brokersTrades = $baseQuery->get();
            $result = [];

            foreach ($brokersTrades as $row) {
                $trades  = MarketBidMaster::with('user')->whereIn('Pk_id', explode(',', $row->trade_ids))->get();
                $symbols = $trades->pluck('Symbol')->unique()->toArray();
                if (empty($symbols)) continue;

                $apiData = $fetchPrices($symbols);
                if (!$apiData) continue;

                [$sellPriceTotal, $totalPL] = $calcPL($trades, $apiData);

                $lastUser   = $trades->last()->user;
                $financials = $getUserFinancials($lastUser->id);

                $result[] = array_merge([
                    'bid_id'    => $row->bid_id,
                    'Pk_id'     => $row->id,
                    'Symbol'    => '',
                    'TradeLast' => $sellPriceTotal,
                    'pl'        => $totalPL,
                    'balance'   => $lastUser->balance,
                    'date'      => $row->date ?? '',
                ], $financials);
            }

            return response()->json($result);

            // ─────────────────────────────────────────────────────────────────────────
            // DEFAULT  — all active trades (no segment or unrecognised)
            // ─────────────────────────────────────────────────────────────────────────
        } else {
            $baseQuery = MarketBidMaster::where('Isactive', 1);
            if (!$isAdmin) {
                $baseQuery->where('broker_id', $adminId);
            }
            $trades = $baseQuery->get();
        }

        // ─────────────────────────────────────────────────────────────────────────
        // Generic result builder used by 'user' and default segments
        // ─────────────────────────────────────────────────────────────────────────
        $symbols = $trades->pluck('Symbol')->unique()->toArray();
        if (empty($symbols)) {
            return response()->json([]);
        }

        $apiData = $fetchPrices($symbols);
        if (!$apiData) {
            return response()->json(['error' => 'Unable to fetch prices'], 500);
        }

        $result = [];
        foreach ($trades as $trade) {
            $symbol = $trade->Symbol;
            if (!isset($apiData[$symbol])) continue;

            $v         = $apiData[$symbol]['v'];
            $sellPrice = $trade->Mode === 'BUY' ? ($v['bid'] ?? 0) : ($v['ask'] ?? 0);
            $lotSize   = (int) $trade->Lots * $trade->LotSize;

            $pl = $trade->Mode === 'SELL'
                ? ($trade->BuyPrice - $sellPrice) * $lotSize
                : ($sellPrice - $trade->BuyPrice) * $lotSize;

            $result[] = [
                'Pk_id'     => $trade->Pk_id,
                'Symbol'    => $symbol,
                'TradeLast' => $sellPrice,
                'pl'        => $pl,
                'balance'   => '1234567',
            ];
        }

        return response()->json($result);
    }


    public function getLivePrices($user_id)
    {

        $adminId = Session::get('admin_id') ?? Auth::guard('tradeuser')->user()->broker_id;
        // dd(Session::get('admin_id') , Auth::guard('tradeuser')->user());
        $admin = AdminLogin::with('role')->where('PK_ID', $adminId)->first();
        $isAdmin = $admin->role->name === 'Admin';


        if ($user_id == null) {
            $query = MarketBidMaster::where('Isactive', 1);
            if (!$isAdmin) {
                $query->where('broker_id', $adminId);
            }
            $trades = $query->get();
        }

        $query = MarketBidMaster::where('Isactive', 1)
            ->where('UserId', $user_id);
        if (!$isAdmin) {
            // $query->where('broker_id', $adminId);
        }
        $trades = $query->get();

        // $symbols = $trades->pluck('Symbol')->unique()->toArray();

        // $fyers = DB::table('fyers')->first();
        // $token = $fyers->FYERS_CLIENT_ID . ':' . $fyers->FYERS_ACCESS_TOKEN;

        // $url = 'https://api-t1.fyers.in/data/quotes';
        // $queryParams = [
        //     'symbols' => implode(',', $symbols),
        // ];


        // $response = Http::withHeaders([
        //     'Authorization' => $token
        // ])->get($url, $queryParams);

        // if (!$response->successful()) {
        //     return response()->json(['error' => 'Unable to fetch prices'], 500);
        // }

        // $apiData = collect($response->json()['d'])->keyBy('n');
        $result = [];

        foreach ($trades as $trade) {
            $symbol = $trade->Symbol;

            // $this->getMarketData($symbol);

            if (!empty($symbol) && strpos($symbol, '&') !== false) {
                $symbol = str_replace('&', '%26', $symbol);
            }
            $data = $this->getMarketData($symbol);

            //  $testing[] = $symbol;
            if (isset($data['data']['bid_price'])) {
                $bid = $data['data']['bid_price'];
                $ask = $data['data']['ask_price'];
            } else {
                $lastData = DB::table('forexoptions')->where('Symbol', $trade->Symbol)->first();

                $bid = $lastData->bid;
                $ask = $lastData->ask;
            }

            $sellPrice = $trade->Mode === 'BUY' ? $bid : $ask;

            if ($trade->Mode == 'SELL') {
                $lotSize = $trade->Lots * $trade->LotSize;
                $pl = ($trade->BuyPrice - $sellPrice) * $lotSize;
            } else {
                $lotSize = $trade->Lots * $trade->LotSize;
                $pl = ($sellPrice - $trade->BuyPrice) * $lotSize;
            }

            $result[] = [
                'Pk_id' => $trade->Pk_id,
                'Symbol' => $trade->Symbol,
                'TradeLast' => $sellPrice,
                'pl' => $pl,
                'lotsize' => $trade->LotSize,
                'lots' => $trade->Lots,
                'buy' => $trade->BuyPrice,
                'sell' => $sellPrice

            ];
        }
        // dd($result);
        return response()->json($result);
    }

    /**
     * Show form to edit a user
     */
    public function exportExcel(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        $from = $request->input('from_date');
        $to = $request->input('to_date');
        $userID = $request->input('user_id');
        return Excel::download(new TradeExport($from, $to, $userID), 'trades_export_' . $from . '_to_' . $to . '.xlsx');
    }

    public function exportPdf(Request $request, $id)
    {
        $user = TradeUser::where('id', $id)->first();

        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        $from = $request->input('from_date');
        $to = $request->input('to_date');

        $trades = MarketBidMaster::whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->orderBy('created_at', 'desc')
            ->where('UserId', $id)
            ->get();

        $funds = DepositeMaster::where('UserId', $id)
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->orderByDesc('created_at')->paginate(3);

        $mxcPendingTrade = MarketBidMaster::whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->orderBy('created_at', 'desc')
            ->where('TransactionMode', 'MCX')
            ->where('Isactive', 2)
            ->where('UserId', $id)
            ->get();

        $equityPendingTrade = MarketBidMaster::whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->orderBy('created_at', 'desc')
            ->where('TransactionMode', 'NSE')
            ->where('UserId', $id)
            ->where('Isactive', 2)
            ->get();


        $comexPendingTrade = MarketBidMaster::whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->orderBy('created_at', 'desc')
            ->where('TransactionMode', 'COMEX')
            ->where('UserId', $id)
            ->where('Isactive', 2)
            ->get();

        $carryForwordTrade = MarketBidMaster::whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->orderBy('created_at', 'desc')
            ->where('UserId', $id)
            ->where('Isactive', 1)
            ->get();

        $scriptWise = MarketBidMaster::with('user')
            ->selectRaw('
                    IFNULL(AVG(Lots), 1) AS Lots,
                    IFNULL(AVG(BuyPrice), 0) AS BUYPRICE,
                    COUNT(Isactive) AS active,
                    IFNULL(AVG(SalePrice), 0) AS SELLPRICE,
                    SUM(
                        CASE 
                            WHEN Mode = "BUY" THEN 
                                (BuyPrice * LotSize * Lots - SalePrice * LotSize * Lots)
                            ELSE 
                                (SalePrice * LotSize * Lots - BuyPrice * LotSize * Lots)
                        END
                    ) AS netpl,
                    SUM(brokrage) AS brokrage,
                    Symbol,
                    MAX(Pk_id) AS Pk_id,
                    MAX(created_at) AS latest_time,
                    MAX(updated_at) AS latest_updated_at
                ')
            ->where('UserId', $id)
            ->where('Isactive', 2)
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->groupBy('Symbol')
            ->orderByRaw('MAX(updated_at) DESC')
            ->get();
        // dd($scriptWise);

        $pdf = Pdf::loadView('admin.exports.pdf.trades', compact(
            'user',
            'trades',
            'from',
            'to',
            'funds',
            'trades',
            'mxcPendingTrade',
            'equityPendingTrade',
            'comexPendingTrade',
            'carryForwordTrade'
        ));

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


        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed trades list',
                'ip_address' => request()->ip()
            ]);
        } else {
            return redirect('admin/login');
        }


        $user = TradeUser::where('id', $id)->first();

        $adminId = Session::get('admin_id');
        $brokers = AdminLogin::where('PK_ID', '!=', 1)->get();

        if ($adminId != 1) {
            $brokers = AdminLogin::where('PK_ID', '!=', 1)
                ->where('PK_ID', $adminId)
                ->OrWhere('parent_id', $adminId)
                ->get();
        }

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

        $adminId = Session::get('admin_id');
        $brokers = AdminLogin::where('PK_ID', '!=', 1)->get();

        if ($adminId != 1) {
            $brokers = AdminLogin::where('PK_ID', '!=', 1)
                ->where('parent_id', $adminId)->where('parent_id', $adminId)->get();
        }

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
    public function deleteUser(Request $request, $id)
    {

        $adminUser = AdminLogin::where('PK_ID', Session::get('admin_id'))->first();

        if (!Hash::check($request->input('transaction_password'), $adminUser->TransPass)) {
            return redirect('/admin/delete-user-confirm/' . $id)
                ->with('error', 'Invalid transaction password.');
        }

        $user = TradeUser::findOrFail($id);
        $userName = $user->name;

        // Soft delete the user
        $user->forceDelete();

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
        // Auth check first
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        if (!Session::has('admin_id')) {
            return redirect('admin/login');
        }

        $adminId = Session::get('admin_id');

        $admin = AdminLogin::with('role')->where('PK_ID', $adminId)->first();

        if (!$admin) {
            return redirect('admin/login');
        }

        $isAdmin = $admin->role->name === 'Admin';

        // Base query with user relationship
        $query = MarketBidMaster::with('user')->where('Isactive', '!=', 0)
            ->whereBetween('updated_at', [$startOfWeek, $endOfWeek]);

        // Restrict non-admins to their broker's users only
        if (!$isAdmin) {
            $query->whereHas('user', function ($q) use ($adminId) {
                $q->where('broker_id', $adminId);
            });
        }

        // Apply filters
        if ($request->filled('id')) {
            $query->where('Pk_id', $request->id);
        }

        if ($request->filled('scrip_id')) {
            $query->where('Symbol', 'like', '%' . $request->scrip_id . '%');
        }

        if ($request->filled('segment') && $request->segment !== 'All') {
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

        $trades = $query->orderByDesc('updated_at')->get();

        // Log activity
        AdminLog::create([
            'admin_id'   => $adminId,
            'activity'   => 'Viewed trades list',
            'ip_address' => $request->ip()
        ]);
        // dd($trades->first());
        return view('admin.trades', compact('trades'));
    }

    public function tradeCreate()
    {
        $trades = [];


        $adminId = Session::get('admin_id');

        $admin = AdminLogin::with('role')->where('PK_ID', $adminId)->first();


        $isAdmin = $admin->role->name === 'Admin';

        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed trades list',
                'ip_address' => request()->ip()
            ]);
        } else {
            return redirect('admin/login');
        }
        $tradeUser = TradeUser::query();

        if (!$isAdmin) {

            $childAdmins = AdminLogin::where('parent_id', $adminId)->pluck('PK_ID');

            // Level 2 children (optional)
            $subChildAdmins = AdminLogin::whereIn('parent_id', $childAdmins)->pluck('PK_ID');

            // Merge all IDs
            $allAdminIds = collect([$adminId])
                ->merge($childAdmins)
                ->merge($subChildAdmins)
                ->unique()
                ->toArray();

            $tradeUser->whereIn('broker_id', $allAdminIds);
        }

        $tradeUser = $tradeUser->get();

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

        $homeCtrl = new HomeController();
        $user = TradeUser::with('bidamount')->where('id', $request->userid)->first();

        $transactionMode = explode(':', $request->Symbol)[0];
        $mode = $request->buy_price ? 'BUY' : 'SELL';

          $active = 1;
        $ipAddrss = null;
        if (!empty($request->buy_price) && !empty($request->sell_price) ) {
            $active = 2;
            $ipAddrss = '127.0.0.1';
            if ($request->input('Mode') == 'BUY') {

                $buyPrice  = $request->buy_price;
                $sellPrice = $request->sell_price;

            } else {

                $buyPrice  = $request->sell_price;
                $sellPrice = $request->buy_price;
            }

        } else {

            // $buyPrice  = $request->input('buy_price') ?? $request->input('sell_price');
            $buyPrice = !empty($request->buy_price) ? $request->buy_price : $request->sell_price;
            $sellPrice = null;
        }

        // $data = [
        //     'Symbol' => $request->input('scrip_id'),
        //     'BuyPrice' => $buyPrice,
        //     'SalePrice' => $sellPrice,
        //     'UserId' => $request->userid,
        //     'Lots' => $lots,
        //     'holding_margin_req' => $marginAvilabe['intraday'] ?? 0 * $request->lots,
        //     'used_margin_req' => $marginAvilabe['holding'] ?? 0 * $request->lots,
        //     'quentity' => ($trade->is_qty == 1) ? $request->lots : $lotSize,
        //     'Isactive'=>$active,
        //     'updated_at' => now()
        // ];
        //     if ($ipAddrss != null) {
        //         $data['IpAddress'] = $ipAddrss;
        //         $data['sell_ip_address'] = $ipAddrss;
        //     }

        

        $request->request->remove('_token');

        $data = [
            'Mode' => $mode,
            'TransactionMode' => $transactionMode,
            'segment' => 'BUY',
            'tblfcbuyprice' => $buyPrice,
            'tblfcsellprice' => $sellPrice,
            'Lots' => $request->lots,
            'Min' => $request->min_mega,
            'IsActive'=> $active
        ];
        // dd($data);
        if ($transactionMode == 'NSE' && $user->TradeEquityAsUnits) {
            $data['qty'] = $request->lots; // or $request->qty if needed
        }
       
        $request->merge($data);
        // $request->merge([
        //     'Mode' => $mode,
        //     'TransactionMode' => $transactionMode,
        //     'segment' => 'BUY',
        //     'tblfcbuyprice' => $buyPrice,
        //     'Lots' => $request->lots
        // ]);

        $data = $request;

        $response = $homeCtrl->saveTransactionAdmin($data);
        $data = json_decode($response->getContent(), true);

        // If response is JsonResponse
        // Check error
        if (isset($data['error'])) {
            return back()->with('error', $data['error']);
        }
        if (isset($data['success'])) {
            return back()->with('success', $data['success']);
        }



        // Success case
        return back()->with('success', 'Trade created successfully.');


        return back()->with('success', 'Trade created successfully.');


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

            // MarketBidMaster::where('Pk_id', $id)->update([
            //     'Symbol' => $validated['scrip_id'],
            //     'UserId' => $validated['userid'],
            //     'LotSize' => $validated['lots'],
            //     'BuyPrice' => $validated['buy_price'],
            //     'SalePrice' => $validated['sell_price'] ?? null,
            //     'segment' => $validated['segment'],
            // ]);

            return back()->with('success', 'Trade updated successfully.');
        } else {
            // Create new record
            // MarketBidMaster::insert([
            //     'Symbol' => $validated['scrip_id'],
            //     'UserId' => $validated['userid'],
            //     'LotSize' => $validated['lots'],
            //     'BuyPrice' => $validated['buy_price'],
            //     'SalePrice' => $validated['sell_price'] ?? null,
            //     'segment' => $validated['segment'],
            //     'Mode' => ($validated['sell_price']) ? 'SELL' : 'BUY'
            // ]);
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

        $closedTrades = MarketBidMaster::with('user')->where('Isactive', 2)
            ->orderBy('updated_at', 'desc')->get();
        // dd($closedTrades);
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
    public function __deletedTrades()
    {

        // Get deleted trades data
        $deletedTrades = [];
        $userName = $request->username ?? null;
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed deleted trades',
                'ip_address' => request()->ip()
            ]);
        }

        $deletedTrades = MarketBidMaster::onlyTrashed()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();


        return view('admin.deleted-trades', compact('deletedTrades'));
    }

    public function deletedTrades(Request $request)
    {
        // Auth check first
        if (!Session::has('admin_id')) {
            return redirect('admin/login');
        }

        $adminId = Session::get('admin_id');

        $admin = AdminLogin::with('role')->where('PK_ID', $adminId)->first();

        if (!$admin) {
            return redirect('admin/login');
        }

        $isAdmin = $admin->role->name === 'Admin';

        // Base query
        $query = MarketBidMaster::onlyTrashed()->with('user');

        // Restrict non-admins to their broker's users only
        if (!$isAdmin) {
            $query->whereHas('user', function ($q) use ($adminId) {
                $q->where('broker_id', $adminId);
            });
        }

        $deletedTrades = $query->orderByDesc('updated_at', 'desc')->get();

        // Log activity
        AdminLog::create([
            'admin_id'   => $adminId,
            'activity'   => 'Viewed deleted trades',
            'ip_address' => $request->ip()
        ]);

        return view('admin.deleted-trades', compact('deletedTrades'));
    }

    /**
     * Show pending orders page
     */
    public function __pendingOrders()
    {

        $pendingOrders = MarketBidMaster::with('user')->where('Isactive', 0)
            ->orderBy('timestamp', 'desc')
            ->get();

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

    public function pendingOrders(Request $request)
    {
        // dd('hello');
        // Auth check first
        if (!Session::has('admin_id')) {
            return redirect('admin/login');
        }

        $adminId = Session::get('admin_id');

        $admin = AdminLogin::with('role')->where('PK_ID', $adminId)->first();

        if (!$admin) {
            return redirect('admin/login');
        }

        $isAdmin = $admin->role->name === 'Admin';

        // Base query
        $query = MarketBidMaster::with('user')->where('Isactive', 0);

        // Restrict non-admins to their broker's users only
        if (!$isAdmin) {
            $query->whereHas('user', function ($q) use ($adminId) {
                $q->where('broker_id', $adminId);
            });
        }

        $pendingOrders = $query->orderByDesc('timestamp')->get();

        // Log activity
        AdminLog::create([
            'admin_id'   => $adminId,
            'activity'   => 'Viewed pending orders',
            'ip_address' => $request->ip()
        ]);

        return view('admin.pending-orders', compact('pendingOrders'));
    }

    public function createPendingOrder(Request $request)
    {
        $trades = [];
        $tradeUser = TradeUser::all();
        $forexOption = ForexOption::where('Isactive', 1)->get();

        return view('admin.create-pending-orders', compact('trades', 'tradeUser', 'forexOption'));
    }
    public function createPendingTrade(Request $request)
    {

        $adminUser = AdminLogin::where('PK_ID', Session::get('admin_id'))->first();


        if (!Hash::check($request->input('transaction_password'), $adminUser->TransPass)) {
            return redirect()->back()->with('error', 'Invalid transaction password.');
        }
        $exchange = explode(":", $request->Symbol)[0];

        $request->merge([
            'TransactionMode' => $exchange
        ]);

        $homeCtrl = new HomeController();
        $request->request->remove('_token');

        $data = new \Illuminate\Http\Request($request->all());
        $homeCtrl->saveTransactionNew($data);
        return back()->with('success', 'Transaction saved successfully');
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
            ->selectRaw("td.TransType,td.transaction_id,td.type,td.TransPage,tu.FullName,tu.UserName as UserName,td.PK_ID,td.AmountS,
                        td.Remark,'Deposit' as Mode,td.adminstatus,td.transdate as Timestamp")
            ->orderBy('td.TransDate', 'desc')
            ->get();


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
    public function __fundsStore(Request $request)
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
        $user->increment('deposits', (float) $request->amount);
        $user->increment('balance', (float) $request->amount);
        $user->save();

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

    public function fundsStore(Request $request)
    {
        try {

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

            if (!$adminUser || !Hash::check($request->input('transaction_password'), $adminUser->TransPass)) {
                return redirect()->back()->with('error', 'Invalid transaction password.');
            }

            // Start Transaction (Important for funds)
            DB::beginTransaction();

            $user->increment('deposits', (float) $request->amount);
            $user->increment('balance', (float) $request->amount);
            $user->save();

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

            DB::commit();
            return redirect()->route('admin.payment-confirm', ['id' => $fund->PK_Id]);

        // return redirect()->back()->with('success', 'Funds Deposit Successfully.');
        } catch (\Exception $e) {

            DB::rollback();
            
            \Log::error('Fund Deposit Error: ' . $e->getMessage(), [
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
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
        $usedMargin = 0;
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }
        $adminUser = AdminLogin::where('PK_ID', Session::get('admin_id'))->first();

        if (!Hash::check($request->input('transaction_password'), $adminUser->TransPass)) {
            return redirect()->back()->with('error', 'Invalid transaction password.');
        }
        $currentTime = Carbon::now()->format('H:i:s');
        $timing = DB::table('market_calendar')->where('market', 'MCX')->where('type', 'TIME')->first();

        if ($currentTime >= $timing->start_time && $currentTime <= $timing->end_time) {
            $usedMargin = MarketBidMaster::where('UserId',$user->id)->where('Isactive', '=', 1)->sum('used_margin_req');
        } else {
            $usedMargin = $user->bidamount()->where('Isactive', '=', 1)->sum('holding_margin_req');
        }
        $m2mbalance = $this->dashboardLivePl('admin-restore', $user->id);
        $data = json_decode($m2mbalance->getContent(), true);
        $pl = $data[0]['pl'] ?? 0;
        // $netBalance = $user->balance - ($usedMargin + $pl);
        $netBalance = $user->balance - $usedMargin;
       
        if ($netBalance < $request->amount) {
            return redirect()->back()->with('error', 'insufficient Funds...');
        }


        $user->increment('withdrawals', $request->amount);
        $user->decrement('balance', $request->amount);

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
        return redirect()->route('admin.payment-confirm', ['id' => $fund->PK_Id]);

        // return redirect()->back()->with('success', 'Fund Withdrawal successfully.');
    }

    public function approvedStatus($data, $transPage, $depType, $remarks)
    {

        Transdetail::create([
            'transaction_id' => date('ymdhis'),
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
            'AdminStatus' => 'APPROVED'
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
            ->where('type', 1)
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
            $TradeUser = TradeUser::find($deposit->UserId);

            $type    = $request->input('type');
            $transPage = 'Deposit approval';
            $depType = '+';
            $remarks = 'Deposit';

            if ($deposit->type == 0) {
                $transPage = 'withdraw approval';
                $depType = '-';
                $remarks = 'withdraw';
                $TradeUser->decrement('balance', $deposit->Amount);
                $TradeUser->increment('withdrawals', $deposit->Amount);
            } else {
                $TradeUser->increment('balance', $deposit->Amount);
                $TradeUser->increment('deposits', $deposit->Amount);
            }

            if ($type === 'APPROVED') {
                // Create a wallet transaction
                Transdetail::create([
                    'transaction_id' => date('ymdhis'),
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
                    'AdminStatus' => 'APPROVED'
                ]);

                $deposit->update([
                    'Approve_Status' => 'APPROVED',
                    'Approve_Date'   => now(),
                ]);
                $TradeUser->save();
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
        $withdrawalRequests = DepositeMaster::with('user')
            ->where('Approve_Status', 'Pending')
            ->where('type', 0)
            ->get();

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
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed market scripts',
                'ip_address' => request()->ip()
            ]);
        } else {
            return redirect('admin/login');
        }

        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek   = Carbon::now()->endOfWeek(Carbon::FRIDAY);
        $adminId = Session::get('admin_id');


        $accounts = AdminLogin::leftJoin('tradeuser as tu', 'tu.broker_id', '=', 'adminlogin.PK_ID')
            ->leftJoin('marketbidmaster', function ($join) {
                $join->on('marketbidmaster.UserId', '=', 'tu.id')
                    ->where('marketbidmaster.Isactive', 2)
                    ->where('marketbidmaster.deleted_at', null)
                    ->whereBetween('marketbidmaster.updated_at', [
                        now()->startOfWeek(Carbon::MONDAY),
                        now()->endOfWeek(Carbon::FRIDAY)
                    ]);
            })
            ->select(
                'adminlogin.PK_ID as broker_id',
                'adminlogin.UserName',

                DB::raw("
            COALESCE(SUM(
                CASE 
                    WHEN marketbidmaster.Mode = 'BUY' 
                    THEN (marketbidmaster.SalePrice * marketbidmaster.Lots * marketbidmaster.LotSize) 
                        - (marketbidmaster.BuyPrice * marketbidmaster.Lots * marketbidmaster.LotSize)
                    ELSE 
                        (marketbidmaster.BuyPrice * marketbidmaster.Lots * marketbidmaster.LotSize) 
                        - (marketbidmaster.SalePrice * marketbidmaster.Lots * marketbidmaster.LotSize)
                END
            ),0) as total_profit
        "),

                DB::raw("COALESCE(SUM(marketbidmaster.brokrage),0) as total_brokrage"),

                DB::raw("
            ROUND(
                (
                    COALESCE(SUM(
                        CASE 
                            WHEN marketbidmaster.Mode = 'SELL' 
                            THEN (marketbidmaster.SalePrice * marketbidmaster.Lots * marketbidmaster.LotSize) 
                                - (marketbidmaster.BuyPrice * marketbidmaster.Lots * marketbidmaster.LotSize)
                            ELSE 
                                (marketbidmaster.BuyPrice * marketbidmaster.Lots * marketbidmaster.LotSize) 
                                - (marketbidmaster.SalePrice * marketbidmaster.Lots * marketbidmaster.LotSize)
                        END
                    ),0) 
                    + COALESCE(SUM(marketbidmaster.brokrage),0)
                ) * (adminlogin.profit_share / 100)
            , 2) as net_pl_share
        "),

                DB::raw("
            ROUND(
                COALESCE(SUM(marketbidmaster.brokrage),0) * (adminlogin.brokerage_share / 100)
            , 2) as payable
        "),

                DB::raw("
            ROUND(
                COALESCE(SUM(
                    CASE 
                        WHEN marketbidmaster.Mode = 'SELL' 
                        THEN (marketbidmaster.SalePrice * marketbidmaster.Lots * marketbidmaster.LotSize) 
                            - (marketbidmaster.BuyPrice * marketbidmaster.Lots * marketbidmaster.LotSize)
                        ELSE 
                            (marketbidmaster.BuyPrice * marketbidmaster.Lots * marketbidmaster.LotSize) 
                            - (marketbidmaster.SalePrice * marketbidmaster.Lots * marketbidmaster.LotSize)
                    END
                ),0) * (adminlogin.profit_share / 100)
            , 2) as pl_share
        ")
            );


        // ✅ Admin hierarchy filter (FIXED)
        if ($adminId != 1) {

            $childAdmins = AdminLogin::where('parent_id', $adminId)->pluck('PK_ID');

            $subChildAdmins = AdminLogin::whereIn('parent_id', $childAdmins)->pluck('PK_ID');

            $allAdminIds = collect([$adminId])
                ->merge($childAdmins)
                ->merge($subChildAdmins)
                ->unique()
                ->toArray();

            $accounts->whereIn('adminlogin.PK_ID', $allAdminIds);
        }


        // ✅ Final result
        $accounts = $accounts
            ->groupBy('adminlogin.PK_ID', 'adminlogin.UserName')
                // ->havingRaw('payable > 0')
            ->get();

        //  $accounts = MarketBidMaster::join('tradeuser as tu', 'tu.id', '=', 'marketbidmaster.UserId')
        //         ->join('adminlogin', 'adminlogin.PK_ID', '=', 'tu.broker_id')
        //         ->select(
        //             'tu.broker_id',
        //             'adminlogin.UserName',
        //             DB::raw("
        //                 SUM(
        //                     CASE 
        //                         WHEN marketbidmaster.Mode = 'BUY' 
        //                         THEN (marketbidmaster.SalePrice * marketbidmaster.Lots * marketbidmaster.LotSize) 
        //                             - (marketbidmaster.BuyPrice * marketbidmaster.Lots * marketbidmaster.LotSize)
        //                         ELSE 
        //                             (marketbidmaster.BuyPrice * marketbidmaster.Lots * marketbidmaster.LotSize) 
        //                             - (marketbidmaster.SalePrice * marketbidmaster.Lots * marketbidmaster.LotSize)
        //                     END
        //                 ) as total_profit
        //             "),
        //             DB::raw("SUM(marketbidmaster.brokrage) as total_brokrage"),
        //             DB::raw("
        //             ROUND(
        //                 (
        //                     SUM(
        //                         CASE 
        //                             WHEN marketbidmaster.Mode = 'SELL' 
        //                             THEN (marketbidmaster.SalePrice * marketbidmaster.Lots * marketbidmaster.LotSize) 
        //                                 - (marketbidmaster.BuyPrice * marketbidmaster.Lots * marketbidmaster.LotSize)
        //                             ELSE 
        //                                 (marketbidmaster.BuyPrice * marketbidmaster.Lots * marketbidmaster.LotSize) 
        //                                 - (marketbidmaster.SalePrice * marketbidmaster.Lots * marketbidmaster.LotSize)
        //                         END
        //                     ) 
        //                     + SUM(marketbidmaster.brokrage)
        //                 ) * (adminlogin.profit_share / 100)
        //             , 2) as net_pl_share
        //         "),
        //             DB::raw("
        //                     ROUND(SUM(marketbidmaster.brokrage) * (adminlogin.brokerage_share / 100), 2) as payable
        //                 "),
        //             DB::raw("
        //         ROUND(
        //             SUM(
        //                 CASE 
        //                     WHEN marketbidmaster.Mode = 'SELL' 
        //                     THEN (marketbidmaster.SalePrice * marketbidmaster.Lots * marketbidmaster.LotSize) 
        //                         - (marketbidmaster.BuyPrice * marketbidmaster.Lots * marketbidmaster.LotSize)
        //                     ELSE 
        //                         (marketbidmaster.BuyPrice * marketbidmaster.Lots * marketbidmaster.LotSize) 
        //                         - (marketbidmaster.SalePrice * marketbidmaster.Lots * marketbidmaster.LotSize)
        //                 END
        //             ) * (adminlogin.profit_share / 100)
        //         , 2) as pl_share
        //     ")

        //         );

        //     if ($adminId != 1) {
        //     $childAdmins = AdminLogin::where('parent_id', $adminId)->pluck('PK_ID');
        //      $subChildAdmins = AdminLogin::whereIn('parent_id', $childAdmins)->pluck('PK_ID');
        //        $allAdminIds = collect([$adminId])
        //             ->merge($childAdmins)
        //             ->merge($subChildAdmins)
        //             ->unique()
        //             ->toArray();
        //         $accounts->where('tu.broker_id', $adminId);
        //         // $accounts->where('adminlogin.parent_id', $adminId);
        //     }

        //     $accounts = $accounts
        //         ->whereBetween('marketbidmaster.updated_at', [
        //             now()->startOfWeek(Carbon::MONDAY),
        //             now()->endOfWeek(Carbon::FRIDAY)
        //         ])
        //         ->groupBy('tu.broker_id', 'adminlogin.UserName')
        //         ->where('marketbidmaster.Isactive', 2)
        //         ->get();

        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed accounts page',
                'ip_address' => request()->ip()
            ]);
        } else {
            return redirect('admin/login');
        }

        return view('admin.accounts', compact('accounts'));
    }
    public function subAaccounts($id)
    {

        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek   = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        $accounts = MarketBidMaster::join('tradeuser as tu', 'tu.id', '=', 'marketbidmaster.UserId')
            ->join('adminlogin', 'adminlogin.PK_ID', '=', 'tu.broker_id')
            ->select(
                'tu.broker_id',
                'adminlogin.UserName',
                DB::raw("
                    SUM(
                        CASE 
                            WHEN marketbidmaster.Mode = 'BUY' 
                            THEN (marketbidmaster.SalePrice * marketbidmaster.Lots * marketbidmaster.LotSize) 
                                - (marketbidmaster.BuyPrice * marketbidmaster.Lots * marketbidmaster.LotSize)
                            ELSE 
                                (marketbidmaster.BuyPrice * marketbidmaster.Lots * marketbidmaster.LotSize) 
                                - (marketbidmaster.SalePrice * marketbidmaster.Lots * marketbidmaster.LotSize)
                        END
                    ) as total_profit
                "),
                DB::raw("SUM(marketbidmaster.brokrage) as total_brokrage"),
                DB::raw("
                ROUND(
                    (
                        SUM(
                            CASE 
                                WHEN marketbidmaster.Mode = 'SELL' 
                                THEN (marketbidmaster.SalePrice * marketbidmaster.Lots * marketbidmaster.LotSize) 
                                    - (marketbidmaster.BuyPrice * marketbidmaster.Lots * marketbidmaster.LotSize)
                                ELSE 
                                    (marketbidmaster.BuyPrice * marketbidmaster.Lots * marketbidmaster.LotSize) 
                                    - (marketbidmaster.SalePrice * marketbidmaster.Lots * marketbidmaster.LotSize)
                            END
                        ) 
                        + SUM(marketbidmaster.brokrage)
                    ) * (adminlogin.profit_share / 100)
                , 2) as net_pl_share
            "),
                DB::raw("
                        ROUND(SUM(marketbidmaster.brokrage) * (adminlogin.brokerage_share / 100), 2) as payable
                    "),
                DB::raw("
            ROUND(
                SUM(
                    CASE 
                        WHEN marketbidmaster.Mode = 'SELL' 
                        THEN (marketbidmaster.SalePrice * marketbidmaster.Lots * marketbidmaster.LotSize) 
                            - (marketbidmaster.BuyPrice * marketbidmaster.Lots * marketbidmaster.LotSize)
                        ELSE 
                            (marketbidmaster.BuyPrice * marketbidmaster.Lots * marketbidmaster.LotSize) 
                            - (marketbidmaster.SalePrice * marketbidmaster.Lots * marketbidmaster.LotSize)
                    END
                ) * (adminlogin.profit_share / 100)
            , 2) as pl_share
        ")

            )
            ->where('adminlogin.parent_id', $id)
            ->where('marketbidmaster.deleted_at', null)
            ->whereBetween('marketbidmaster.updated_at', [$startOfWeek, $endOfWeek])
            ->groupBy('tu.broker_id')
            ->get();

        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed accounts page',
                'ip_address' => request()->ip()
            ]);
        } else {
            return redirect('admin/login');
        }

        return view('admin.accounts', compact('accounts'));
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
    public function forexOptions(Request $request)
    {
        $today = Carbon::today()->toDateString();

        // Disable expired options automatically
        // ForexOption::whereDate('ExpiryDate', '<', $today)
        //     ->update(['Isactive' => 0]);

        // Start query
        // FUT CE,PE
        $query = ForexOption::query();

        // Date range filter
        if ($request->filled('from_date')) {
            $query->whereDate('ExpiryDate', '>=', $request->from_date);
        }
        // if ($request->exchange=='MCX' || $request->exchange=='NSE' ) {
        //    $query->where('Symbol', 'LIKE', '%FUT');
        // }
        if ($request->exchange == 'MCX' || $request->exchange == 'NSE') {
            $query->where(function ($q) use ($request) {
                $q->where('Symbol', 'LIKE', $request->exchange . ':%')
                    ->where('Symbol', 'LIKE', '%FUT');
            });
        }
        // else{

        //    $query->where('Symbol', 'LIKE', '%CE')->orWhere('Symbol', 'LIKE', '%PE');
        // }

        // if ($request->filled('to_date')) {
        //     $query->whereDate('ExpiryDate', '<=', $request->to_date);
        // }

        // Exchange filter
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where('Symbol', 'LIKE', "%{$search}%");
        }

        // Search input filter
        // if ($request->filled('search')) {
        //     $search = strtolower($request->search);

        //     $query->where(function ($q) use ($search) {
        //         $q->whereRaw('LOWER(Symbol) LIKE ?', ["%{$search}%"])
        //             ->orWhereRaw('LOWER(instrument) LIKE ?', ["%{$search}%"])
        //             ->orWhereRaw('LOWER(SymbolShortName) LIKE ?', ["%{$search}%"]);
        //     });
        // }


        $forexOptions = $query->orderBy('ExpiryDate', 'ASC')->paginate(100)->withQueryString();

        // Log admin activity
        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed scrip data',
                'ip_address' => request()->ip()
            ]);
        } else {
            return redirect('admin/login');
        }

        return view('admin.forexoptions', compact('forexOptions'));
    }
    public function forexOptionUpdate(Request $request)
    {

        ForexOption::where('Id', $request->id)->update(['Isactive' => $request->status]);

        return response()->json(['message' => 'successfully Updated..'], 200);
    }
    public function scripData()
    {
        // Get scrip data

        $today = Carbon::today()->toDateString();

        $forexOptions = ForexOption::whereDate('ExpiryDate', '>=', $today)->get();

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
    public function lotSize(Request $request)
    {
        $query = LotSize::query();

        if ($request->has('status') && $request->status != '') {
            $query->where('market', $request->status);
        }
        if ($request->has('symbol') && $request->symbol != '') {
            $query->where('name', 'like', '%' . $request->symbol . '%');
        }

        $lotSizes = $query->get();

        return view('admin.lot-size', compact('lotSizes'));
    }

    public function storeLotSize(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'qty' => 'required|numeric',
            'market' => 'required|string',
        ]);

        LotSize::create($request->only(['name', 'qty', 'market']));

        return redirect()->route('admin.lot.size')->with('success', 'Lot Size created successfully');
    }
    public function editLotSize($id)
    {
        $lotSize = LotSize::findOrFail($id);
        return view('admin.lot-size.edit', compact('lotSize'));
    }
    public function updateLotSize(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'qty' => 'required|numeric',
            'market' => 'required|string',
        ]);

        $lotSize = LotSize::findOrFail($id);
        $lotSize->update($request->only(['name', 'qty', 'market']));

        $lotSizeSybmol = DB::table('forexoptions')
            ->join(
                'lot_size',
                DB::raw("CONVERT(lot_size.name USING utf8mb4) COLLATE utf8mb4_unicode_ci"),
                '=',
                DB::raw("CONVERT(forexoptions.SymbolShortName USING utf8mb4) COLLATE utf8mb4_unicode_ci")
            )
            ->join('marketbidmaster', 'marketbidmaster.Symbol', 'forexoptions.Symbol')
            ->where('lot_size.id', $id)
            ->where('marketbidmaster.Isactive', 1)
            ->pluck('marketbidmaster.Pk_id')->toArray();

        MarketBidMaster::whereIn('Pk_id', $lotSizeSybmol)
            ->update(['LotSize' => $request->qty]);

        return redirect()->route('admin.lot.size')->with('success', 'Lot Size updated successfully');
    }
    public function bannedStatus(Request $request)
    {

        $admin_id = Session::get('admin_id');
        $status = ($request->status == 1) ? 0 : 1;

        //     if ($admin_id == 1) {

        //         // dd(ForexOption::where('Symbol', $request->sybmol)->first(),$status);
        //         // dd($request->sybmol,$status);
        //         dd(ForexOption::where('Symbol', trim($request->sybmol))
        // ->update(['banned' => $status]));

        //         if ($status == 0) {
        //             $message = 'Symbol added to banned list.';
        //         } else {
        //             $message = 'Symbol removed from banned list.';
        //         }
        //     } else {

        $exists = DB::table('userbannedsymbols')
            ->where('brokerid', $admin_id)
            ->where('symbol', $request->sybmol)
            ->where('banned', 1)
            ->exists();

        if ($exists) {
            // Remove (unban)
            DB::table('userbannedsymbols')
                ->where('brokerid', $admin_id)
                ->where('symbol', $request->sybmol)
                ->delete();

            $message = 'Symbol removed from banned list.';
        } else {
            // Add (ban)
            DB::table('userbannedsymbols')->insert([
                'brokerid' => $admin_id,
                'symbol' => $request->sybmol,
                'banned' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $message = 'Symbol added to banned list.';
        }
        // }
        return redirect()->back()
            ->with('success', $message);
    }

    public function setCalender()
    {

        $nseTime    = MarketCalendar::where('market', 'NSE')->where('type', 'TIME')->first();
        $mcxTime    = MarketCalendar::where('market', 'MCX')->where('type', 'TIME')->first();
        $optionsTime = MarketCalendar::where('market', 'OPTIONS')->where('type', 'TIME')->first();
        $comixTime  = MarketCalendar::where('market', 'COMIX')->where('type', 'TIME')->first();
        $forexTime  = MarketCalendar::where('market', 'FOREX')->where('type', 'TIME')->first();
        $cryptoTime = MarketCalendar::where('market', 'CRYPTO')->where('type', 'TIME')->first();

        $holidays = MarketCalendar::where('type', 'HOLIDAY')
            ->orderBy('holiday_date', 'asc')
            ->get();

        return view('admin.calender', compact(
            'nseTime',
            'mcxTime',
            'optionsTime',
            'comixTime',
            'forexTime',
            'cryptoTime',
            'holidays'
        ));
    }

    public function storeCalender(Request $request)
    {
        try {
            $markets = [
                'NSE'     => ['start' => $request->nse_start_time,     'end' => $request->nse_end_time],
                'MCX'     => ['start' => $request->mcx_start_time,     'end' => $request->mcx_end_time],
                'OPTIONS' => ['start' => $request->options_start_time, 'end' => $request->options_end_time],
                'COMIX'   => ['start' => $request->comix_start_time,   'end' => $request->comix_end_time],
                'FOREX'   => ['start' => $request->forex_start_time,   'end' => $request->forex_end_time],
                'CRYPTO'  => ['start' => $request->crypto_start_time,  'end' => $request->crypto_end_time],
            ];

            foreach ($markets as $market => $times) {
                MarketCalendar::updateOrCreate(
                    ['market' => $market, 'type' => 'TIME'],
                    ['start_time' => $times['start'], 'end_time' => $times['end']]
                );
            }

            // ================= SAVE HOLIDAYS =================

            if (!empty($request->holidays)) {
                foreach ($request->holidays as $k => $date) {

                    if (!$date || empty($request->holiday_market[$k])) {
                        continue;
                    }

                    MarketCalendar::updateOrCreate(
                        [
                            'market'       => $request->holiday_market[$k],
                            'type'         => 'HOLIDAY',
                            'holiday_date' => $date,
                        ],
                        [
                            'title' => $request->holiday_titles[$k] ?? null,
                        ]
                    );
                }
            }

            return redirect()->back()->with('success', 'Market calendar saved successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function deleteHoliday($id)
    {

        MarketCalendar::where('id', $id)->where('type', 'HOLIDAY')->delete();

        return response()->json(['status' => true]);
    }


    public function getMarketData($symbol)
    {
        $url = "https://namotraders.in:5005/api/getdata?symbol=" . $symbol;

        $response = Http::get($url);

        if ($response->successful()) {
            $data = $response->json();
            return $data;
        } else {
            return [];
        }
    }

    private function formatMarketItem(array $item, string $symbol): array
    {

        $exchange = explode(':', $symbol)[0] ?? '';
        $shortName = $item['symbolShortName'];

        $ask = (float) ($item['ask_price'] ?? 0);
        $bid = (float) ($item['bid_price'] ?? 0);
        // dd($bid,$ask,$shortName,$exchange,$item);
        return [
            'n' => $symbol,
            's' => 'ok',
            'v' => [
                'ask' => $ask,
                'bid' => $bid,
                'chp' => (float) ($item['chp'] ?? 0),
                'ch' => (float) ($item['ch'] ?? 0),
                'description' => $symbol,
                'exchange' => $exchange,
                'fyToken' => $item['fyToken'] ?? '',
                'high_price' => (float) ($item['high_price'] ?? 0),
                'low_price' => (float) ($item['low_price'] ?? 0),
                'lp' => (float) ($item['ltp'] ?? 0),
                'open_price' => (float) ($item['open_price'] ?? 0),
                'original_name' => $symbol,
                'prev_close_price' => (float) ($item['prev_close_price'] ?? 0),
                'short_name' => $shortName,
                'spread' => $ask - $bid,
                'symbol' => $symbol,
                'tt' => (string) ($item['last_traded_time'] ?? ''),
                'volume' => (int) ($item['vol_traded_today'] ?? 0),
                'atp' => (float) ($item['avg_trade_price'] ?? 0),
            ]
        ];
    }
    public function paymentConfirm($id){
            $data = DepositeMaster::join('tradeuser','tradeuser.id','depositemaster.UserId')
            ->where('depositemaster.PK_Id',$id)->first();
            // dd($data);
            return view('admin.payment-confirm',compact('data'));


    }
}

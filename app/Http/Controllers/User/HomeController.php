<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\MarketPlaceMaster;
use App\Models\WithdrawlMaster;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use App\Models\DepositeMaster;
use App\Models\MarketBidMaster;
use App\Models\ClientSubscription;
use App\Models\ForexOption;
use App\Models\TradeUser;
use App\Models\Transdetail;
use Illuminate\Support\Facades\Http;
use DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\AdminLogin;
use App\Models\Notification;
use App\Models\LotSize;

class HomeController extends Controller
{


    public function index()
    {
        if (!Auth::guard('tradeuser')->check()) {
            return redirect('/login');
        }

        return View::make('user.index');
    }

    public function trades()
    {
        if (!Auth::guard('tradeuser')->check()) {
            return redirect('/login');
        }
        return View::make('user.trades');
    }

    public function portfolio()
    {

        $usedMargin = MarketBidMaster::where('UserId', Auth::guard('tradeuser')->user()->id)
            ->where('Isactive', 1)->sum('used_margin_req');

        $marginAvilabe = Auth::guard('tradeuser')->user()->balance;

        return View::make('user.portfolio', compact('usedMargin', 'marginAvilabe'));
    }

    public function watchlist()
    {
        return View::make('user.watchlist');
    }

    public function myAccount()
    {
        if (!Auth::guard('tradeuser')->check()) {
            return redirect('/login');
        }
        $data = DepositeMaster::with('user')
            ->orderBy('created_at', 'desc')
            // ->where('type',0)
            ->get();
        $notifaction = Notification::where('is_read', 0)->get();
        $invest = MarketBidMaster::where('UserId', Auth::guard('tradeuser')->user()->id)
            ->where('Isactive', 1)->sum('BuyPrice');

        return View::make('user.my_account', compact('data', 'invest', 'notifaction'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::guard('tradeuser')->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password updated successfully.');

        return back()->with('success', 'Password updated successfully');
    }

    public function depositWithdraw()
    {
        if (!Auth::guard('tradeuser')->check()) {
            return redirect('/login');
        }

        return View::make('user.deposit_withdraw');
    }
    public function getData(Request $request)
    {
        $query = $request->input('query');
        $type = $request->input('type');
        $clientId = Auth::guard('tradeuser')->user()->id;


        $dataQuery = ForexOption::leftJoin('clientsubscription', function ($join) use ($clientId) {
            $join->on('forexoptions.Symbol', '=', 'clientsubscription.Symbol')
                ->where('clientsubscription.UserId', $clientId);
        })
            ->select('forexoptions.*', 'clientsubscription.Isactive as ckecked')
            ->where('forexoptions.Isactive', 1);
        // ->where

        if ($type == 'MCX') {
            if (Auth::guard('tradeuser')->user()->MCXEnabled == 1) {
                $dataQuery->where('instrument', 'FUT')
                    ->where('forexoptions.Symbol', 'like', 'MCX%');
            }
        } else if ($type == 'NSE') {
            if (Auth::guard('tradeuser')->user()->NSEFuturesEnabled == 1) {
                $dataQuery->where('instrument', 'FUT')
                    ->where('forexoptions.Symbol', 'like', 'NSE%');
            }
        } else if ($type == 'OPTIONS') {
            //  $user->NSEOptionsEnabled
            $dataQuery->where(function ($q) {
                $q->where('forexoptions.instrument', 'PE')
                    ->orWhere('forexoptions.instrument', 'CE');
            });
        }

        if ($query != null) {
            $dataQuery->where('forexoptions.Symbol', 'like', '%' . $query . '%');
        }

        // You can add more filters like clientId if needed
        // if ($clientId) {
        //     $dataQuery->where('client_id', $clientId);
        // }

        $data = $dataQuery->get();

        return response()->json($data);
    }


    public function getSymbol($symbol = '')
    {
        $segment = DB::table('ForexOptions')
            ->where('symbol', $symbol)
            ->value('segment');

        return $segment ?? 0;
    }

    public function UpdateWatchList(Request $request)
    {

        if ($request->action == 'add') {
            ClientSubscription::insert([
                'Symbol' => $request->symbol,
                'UserId' => Auth::guard('tradeuser')->user()->id,
                'Isactive' => 1
            ]);
        } else {
            ClientSubscription::where('Symbol', $request->symbol)
                ->where('UserId', Auth::guard('tradeuser')->user()->id,)->delete();
        }

        return response()->json(['success' => true, 'message' => ucfirst($request->action) . 'ed successfully']);
    }

    public function brokarageCharge($user, $symbol)
    {
        $exchange = explode(":", $symbol)[0];
        $brokCharge = 0;
        if (substr($symbol, -2) === "CE" || substr($symbol, -2) === "PE") {

          if (preg_match('/NSE:(NIFTY|BANKNIFTY)/', $symbol, $matches) && $user->OptionsSSBrokerageType == 'per_lot') {
            $brokrageChangeOneTime = $user->options_brokerage;
        }
        if ($exchange == 'MCX' && $user->options_mcx_brokerage_type == 'per_lot') {
            $brokrageChangeOneTime = $user->options_mcx_brokerage;
        }
        if ($user->options_equity_brokerage_type == 'per_lot') {
            $brokrageChangeOneTime = $user->options_equity_brokerage;
        }
        $brokCharge = $brokrageChangeOneTime * 2;
        
        } else if ($exchange == 'MCX') {
            if ($user->mcx_brokerage_type == 'per_crore') {
                $brokCharge = $user->MCXBrokerage;
            }
        } else if ($exchange == 'NSE') {
            $brokCharge = $user->NSEFuturesBrokerage;
        }
        return $brokCharge;
    }

    public function saveTransaction(Request $request)
    {
        if (!Auth::guard('tradeuser')->check()) {
            return response()->json(['error' => 'Session has been expaired...'], 500);
            return redirect('/login');
        }


        $user = Auth::guard('tradeuser')->user();
        $exchange = explode(':', $request->input('Symbol'))[0];



        $mcx_maxLot_size = $user->MCXMaxLotPerScrip;
        $mcx_minLot_size = $user->MCXMaxLotPerTrade;

        $authOff = $user->AutoSquareOff;

        if ($user->IsActive != 1) {
            return response()->json(['error' => 'You Account is Blocked'], 500);
        }

        //  $this->tradingTime($request->input('Symbol'), $user);

        //     if($exchange=='MCX'){
        //     if($mcx_maxLot_size <  $request->input('lblLotSize')){
        //          return response()->json(['error' => 'You can not take Lot Size gratter then'], 500);
        //     }
        //     if($mcx_minLot_size >  $request->input('lblLotSize')){
        //          return response()->json(['error' => 'You can not take Lot Size Less then '], 500);
        //     }
        // }
           $exchange = explode(":", $request->input('Symbol'))[0];
       
      $symbol = $request->input('Symbol');

        $lotSize =(int)DB::table('forexoptions')
            ->join('lot_size', DB::raw("CONVERT(lot_size.name USING utf8mb4) COLLATE utf8mb4_unicode_ci"), '=', 
                                DB::raw("CONVERT(forexoptions.SymbolShortName USING utf8mb4) COLLATE utf8mb4_unicode_ci"))
            ->where('forexoptions.Symbol', $symbol)
            ->pluck('lot_size.qty')
            ->first() ?? 1;
        

        if ($user->balance < $request->tblfcbuyprice * ($request->Lots*$lotSize)) {
            return response()->json(['error' => 'Your available balance is insufficient. Trade amount: ' . $user->balance], 500);
        }

        $tradeUser = TradeUser::find($user->id);

        $tradeUser->decrement('balance', $request->tblfcbuyprice * ($request->Lots*$lotSize));

        try {
            $user = Auth::guard('tradeuser')->user();

            $ip = $request->header('X_FORWARDED_FOR');
            if ($ip) {
                $ip = explode(',', $ip)[0];
            } else {
                $ip = $request->ip();
            }
            // $buyPrice = $request->input('tblfcbuyprice', 0) * $request->input('lblBidQty', 1);

            $perratRate = $request->input('tblfcbuyprice', 0);
            $lots = ($request->Lots*$lotSize) * 100;

            $holdingmargin = ($perratRate * $lots) / $tradeUser->MCXHoldingMargin;
            $usedmargin = ($perratRate * $lots) / $tradeUser->MCXIntradayMargin;
            $brokrage = $this->brokarageCharge($user, $request->input('Symbol'));
           
            $data = [
                'Mode' => $request->input('Mode'),
                'ToAmount' => $request->input('textfclot', 0),
                'TransactionMode' => $request->input('TransactionMode'),
                // 'SalePrice' => $request->input('tblfcsellprice', 0),
                'BuyPrice' => $request->input('tblfcbuyprice', 0),
                'Bid' => $request->input('lblBid', 0),
                'Ask' => $request->input('lblAsk', 0),
                'High' => $request->input('lblHigh', 0),
                'Low' => $request->input('lblLow', 0),
                'TradeLast' => $request->input('lblLast', 0),
                'Change' => $request->input('lblChange', 0),
                'TradeOpen' => $request->input('lblOpen', 0),
                'Volume' => $request->input('lblVolume', 0),
                'LastTradeQty' => $request->input('lblLastTradedQty', 0),
                'Atp' => $request->input('lblAtp', 0),
                'LotSize' => $lotSize,
                'OpenInterest' => $request->input('lblOpenInterest', 0),
                'BidQty' => $request->input('lblBidQty', 0),
                'AskQty' => $request->input('lblAskQty', 0),
                'PrevClose' => $request->input('lblPrevClose', 0),
                'UpperCircuit' => $request->input('lblUpperCircuit', 0),
                'LowerCircuit' => $request->input('lblLowerCircuit', 0),
                'OPTION' => 'I',
                'UserId' => $user->id,
                'Symbol' => $request->input('Symbol'),
                'Min' => $request->input('Min') == 'True' ? 'Y' : 'N',
                'Mega' => $request->input('Mega') == 'True' ? 'Y' : 'N',
                'IsMinMega' => $request->input('Mega') == 'True' ? 'Y' : 'N',
                'Lots' => $request->input('Lots', 0),
                'Price' => $request->input('Price', 0),
                'IpAddress' => $ip,
                'Isactive' => $request->has('isOrder') ? 0 : 1,
                'IsOrder' => $request->has('isOrder') ? true : false,
                'holding_margin_req' => $holdingmargin,
                'used_margin_req' => $usedmargin,
                'brokrage'=>$brokrage,
            ];
            MarketBidMaster::insert($data);

            Transdetail::create([
                'transaction_id' => date('ymdhis'),
                'MemberId'   => $user->id,
                'TransType'  => 'BUY',
                'TransPage'  => 'withdraw  approval',
                'Type'       => '-',
                'TransDate'  => now(),
                'Amount'     => $request->tblfcbuyprice * ($request->Lots*$lotSize),
                'AmountS'    => $request->tblfcbuyprice * ($request->Lots*$lotSize),
                'Remark'     => 'Buy Trade',
                'LoginId'    => $user->id,
                'AddRemark'  => 'APPROVED BY ADMIN',
                'AdminStatus' => 'APPROVED'
            ]);

            return response()->json(['message' => 'Transaction saved successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save transaction: ' . $e->getMessage()], 500);
        }
    }

    public function depositRequestForm()
    {
        if (!Auth::guard('tradeuser')->check()) {
            return redirect('/login');
        }

        return View::make('user.deposit_request_form');
    }
    public function depositRequestSubmit(Request $request)
    {
        if (!Auth::guard('tradeuser')->check()) {
            return redirect('/login');
        }

        $request->validate([
            'amount'     => 'required|numeric|min:1',
            'screenshot' => 'required|image|mimes:jpeg,jpg,png|max:1024',
        ]);

        $path = $request->file('screenshot')->store('deposits', 'public');

        DepositeMaster::create([
            'UserId'        => Auth::guard('tradeuser')->user()->id,
            'Amount'        => $request->amount,
            'ScreenShot'    => $path,
            'Approve_Status' => 'Pending',
            'Approve_date'  => null,
            'Timestamp'     => Carbon::now(),
            'LastModify'    => Carbon::now(),
            'Isactive'      => true,
            'type'          => 1
        ]);

        return redirect()->back()->with('success', 'Deposit request submitted successfully.');
    }

    public function withdrawalRequestsForm()
    {
        if (!Auth::guard('tradeuser')->check()) {
            return redirect('/login');
        }
        return View::make('user.withdrawal_requests_form');
    }
    public function withdrawalRequests(Request $request)
    {

        if ($request->ajax()) {

            $data = DepositeMaster::with('user')
                ->orderBy('created_at', 'desc')
                ->where('type', 0)
                ->get();
            return response()->json($data);
        }

        return View::make('user.withdrawal_requests');
    }
    public function withdrawalRequestsSubmit(Request $request)
    {

        $request->validate([
            'payment_method' => 'required|string|max:50',
            'amount'         => 'required|numeric|min:1',
            'mobile'         => 'required|string|max:15',
            'holder_name'    => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'ifsc'           => 'required|string|max:20',
        ]);

        DepositeMaster::create([
            'UserId'        => Auth::guard('tradeuser')->user()->id,
            'PaymentMethod' => $request->payment_method,
            'Amount'        => $request->amount,
            'Mobile'        => $request->mobile,
            'AccountHolder' => $request->holder_name,
            'AccountNo'     => $request->account_number,
            'IFSC'          => $request->ifsc,
            'Approve_Status'        => 'Pending',
            'Approve_date'  => null,
            'Timestamp'     => Carbon::now(),
            'LastModify'    => Carbon::now(),
            'Isactive'      => true,
            'type'          => 0
        ]);


        return redirect()->back()->with('success', 'Withdrawal request submitted successfully!');
    }

    public function login()
    {

        if (Auth::guard('tradeuser')->check()) {
            return redirect('/dashboard');
        }
        return View::make('user.login');
    }

    public function register()
    {
        return View::make('user.register');
    }
    public function userRegister(Request $request)
    {
        User::insert([
            'name' => $request->full_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'referral' => $request->referral,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'User registered successfully');
    }
    public function __userLogin(Request $request)
    {

        $request->validate([
            'login' => 'required',
            'password' => 'required|string',
        ]);

        $credentials = [
            'email' => $request->login,
            'password' => $request->password
        ];
        // dd($credentials,Auth::attempt($credentials));
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return redirect()->back()->with('error', 'Invalid credentials');
    }

    public function userLogin(Request $request)
    {
        try {
            $request->validate([
                'login' => 'required',
                'password' => 'required|string',
            ]);

            $user = TradeUser::where('Username', $request->login)
                ->orWhere('Mobile', $request->login)
                ->first();

            if ($user && Hash::check($request->password, $user->Password)) {
                if ($user->IsActive == 0) {
                    return back()->with('error', 'You are blocked....');
                }
                Auth::guard('tradeuser')->login($user);
                $request->session()->regenerate();

                return redirect()->intended('/dashboard');
            }

            return back()->with('error', 'Invalid login credentials');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }

        // $request->validate([
        //     'email' => 'required|email',
        //     'password' => 'required|string',
        // ]);

        // $credentials = [
        //     'email' => $request->email,
        //     'password' => $request->password
        // ];
        // // dd($credentials,Auth::attempt($credentials));
        // if (Auth::attempt($credentials)) {
        //     $request->session()->regenerate();
        //     dd(Auth::user());
        //     return redirect()->intended('/dashboard');
        // }
        // return redirect()->back()->with('error', 'Invalid credentials');
    }



    public function getPendingTrades()
    {
        try {
            $pendingTrades = MarketBidMaster::where('Isactive', 0)
                ->orderBy('timestamp', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $pendingTrades
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching pending trades' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get active trades data
     */
    public function getActiveTrades()
    {
        try {
            $activeTrades = MarketBidMaster::where('Isactive', 1)
                ->where('UserId', Auth::guard('tradeuser')->user()->id)
                ->orderBy('timestamp', 'desc')
                ->get()
                ->map(function ($item) {
                    $item->timestamp = Carbon::parse($item->Timestamp)->format('n/j/Y g:i:s A');
                    return $item;
                });


            return response()->json([
                'success' => true,
                'data' => $activeTrades
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching pending trades' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get closed trades data
     */
    public function getClosedTrades()
    {
        try {
            $closedTrades = MarketBidMaster::where('Isactive', 2)
                ->where('UserId', Auth::guard('tradeuser')->user()->id)
                ->orderBy('timestamp', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $closedTrades
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching pending trades' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get trade details by ID
     */
    public function getTradeDetails(Request $request)
    {
        try {
            $tradeId = $request->input('ID');

            $tradeDetails = TradeDetail::where('trade_id', $tradeId)
                ->select([
                    'id as Pk_id',
                    'symbol as Symbol',
                    'bid as Bid',
                    'ask as Ask',
                    'trade_last as TradeLast',
                    'change_value as Change',
                    'high as High',
                    'low as Low',
                    'trade_open as TradeOpen',
                    'bid_qty as BidQty',
                    'prev_close as PrevClose',
                    'ask_qty as AskQty',
                    'volume as Volume',
                    'last_trade_qty as LastTradeQty',
                    'upper_circuit as UpperCircuit',
                    'open_interest as OpenInterest',
                    'atp as Atp',
                    'lower_circuit as LowerCircuit',
                    'lot_size as LotSize'
                ])
                ->get();

            return response()->json([
                'success' => true,
                'data' => $tradeDetails
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching trade details'
            ], 500);
        }
    }

    /**
     * Close/Exit a trade
     */
    public function exitTrade(Request $request)
    {
        try {
            $tradeId = $request->input('ID');

            $trade = MarketPlaceMaster::find($tradeId);
            if (!$trade) {
                return response()->json([
                    'success' => false,
                    'message' => 'Trade not found'
                ], 404);
            }

            // Update trade status to closed
            $trade->update([
                'status' => 'CLOSED',
                'close_timestamp' => now(),
                'closed_by' => auth()->id() ?? 'system'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Trade closed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error closing trade'
            ], 500);
        }
    }

    /**
     * Close bulk trades by exchange type
     */


    public function closeBulkTrades(Request $request)
    {

        try {
            $request->validate([
                'exchange_type' => 'required|string',
            ]);

            $exchangeType = $request->input('exchange_type');

            $allowedTypes = ['MCX', 'NSE', 'COMEX'];
            $sybmols = [];
            $user = Auth::guard('tradeuser')->user();

            if ($user->IsActive == 0) {
                return response()->json(['error' => 'You Account is Blocked'], 500);
            }


            if (is_numeric($exchangeType)) {
                $sybmols[] = MarketBidMaster::where('Pk_id', $exchangeType)->value('Symbol');
            } else {

                $closedCount = MarketBidMaster::where('Isactive', 1)
                    ->orWhere('Isactive', 0)
                    ->where('UserId', Auth::guard('tradeuser')->user()->id)
                    ->where('Symbol', 'like', $exchangeType . '%')->get();

                foreach ($closedCount as $val) {
                    $sybmols[] = $val->Symbol;
                }
            }

            $correntData = $this->fetchCorrentData($sybmols)['d'];

            foreach ($correntData as $data) {
                $symbol = $data['n'];

                $trade = MarketBidMaster::where('Isactive', 1)
                    ->orWhere('Isactive', 0)
                    ->where('Symbol', $data['n'])
                    ->where('UserId', Auth::guard('tradeuser')->user()->id)
                    ->first();

                // tarading timinng ......

                $this->tradingTime($symbol, $user);

                $createdDate = Carbon::parse($trade->created_at);
                $interval = (int) $user->ProfitBookInterval;
                $targetTime = $createdDate->copy()->addMinutes($interval);

                if (!Carbon::now()->greaterThanOrEqualTo($targetTime)) {
                    $errors[] = "Can't Close: $symbol before {$user->ProfitBookInterval} minutes";
                    continue;
                }

                if ($trade->Mode == 'BUY') {
                    $sellPrice =  $data['v']['ask'];
                } else {
                    $sellPrice =  $data['v']['bid'];
                }
                if ($trade) {

                    MarketBidMaster::where('Pk_id', $trade->Pk_id)
                        ->update([
                            'Isactive' => 2,
                            'SalePrice' => $sellPrice
                        ]);

                    $exchange = explode(":", $symbol)[0];
                    $TradeUser = TradeUser::find($user->id);
                    // options config
                    if (substr($symbol, -2) === "CE" || substr($symbol, -2) === "PE") {
                        $this->optionConfig($symbol, $user);
                    } else if ($exchange == 'MCX') {
                        if ($TradeUser->mcx_brokerage_type == 'per_crore') {
                            // $TradeUser->decrement('balance', $TradeUser->MCXBrokerage);
                            $this->trnsectionDeatil($user->id, 'SELL brokerage charge', 'SELL brokerage charge', $TradeUser->MCXBrokerage, 0);
                        }
                    } else if ($exchange == 'NSE') {
                        $TradeUser->decrement('balance', $TradeUser->NSEFuturesBrokerage);
                        $this->trnsectionDeatil($user->id, 'SELL brokerage charge', 'SELL brokerage charge', $TradeUser->NSEFuturesBrokerage, 0);
                    }
                   
                    $TradeUser->save();
                    $lotSize =(int)DB::table('forexoptions')
                        ->join('lot_size', DB::raw("CONVERT(lot_size.name USING utf8mb4) COLLATE utf8mb4_unicode_ci"), '=', 
                                            DB::raw("CONVERT(forexoptions.SymbolShortName USING utf8mb4) COLLATE utf8mb4_unicode_ci"))
                        ->where('forexoptions.Symbol', $symbol)
                        ->pluck('lot_size.qty')
                        ->first() ?? 1;

                    $salePrice = $sellPrice * $trade->lotSize;
                    $buyPrice = $trade->BuyPrice * $trade->lotSize;
                    $holdingMargin = $trade->holding_margin_req;
                    $usedMargin = $trade->used_margin_req;
                    $netBalance = $salePrice+$holdingMargin+$usedMargin;

                    $trade->used_margin_req = 0;
                    $trade->used_margin_req = 0;

                    $TradeUser->increment('balance', $netBalance);
                    $TradeUser->increment('net_p_l', $salePrice - $buyPrice);
                    $TradeUser->brokrage = $this->brokarageCharge($user, $symbol);
                    $TradeUser->save();
                    $trade->save();
                    $this->trnsectionDeatil($user->id, 'SELL Deposit', 'Sale Price', $salePrice, 1);
                }
            }
            if (!empty($errors)) {
                return response()->json(['success' => false, 'message' => $errors], 500);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => "Successfully closed trades",
                    'closed_count' => ''
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function tradingTime($symbol, $user)
    {
        $now = Carbon::now();
        $dayOfWeek = $now->dayOfWeek;

        if ($dayOfWeek === 0 || $dayOfWeek === 6) {
            return response()->json(['error' => 'Trading is not allowed on weekends.'], 500);
        }
        $time = $now->format('H:i');

        if ($symbol === 'MCX') {
            $start = '09:30';
            $end   = '23:30';
        } elseif ($symbol === 'NSE') {
            $start = '09:15';
            $end   = '15:30';
        } elseif ($symbol === 'COMEX') {
            $start = '04:30';
            $end   = '02:30';
            if ($time <= $end || $time >= $start) {
                return true;
            } else {
                return response()->json(['error' => 'Trading not allowed at this time for COMEX'], 500);
            }
        } else {
            return response()->json(['message' => 'Invalid exchange symbol.'], 500);
        }
        if ($time < $start || $time > $end) {
            return response()->json(['message' => 'You cannot close trade after trading hours.'], 500);
        }

        return true;
    }

    public function optionConfig($symbol, $user)
    {

        $exchange = explode(":", $symbol)[0];
        // preg_match('/NSE:(NIFTY|BANKNIFTY)/', $symbol, $matches);

        //     $indexName = $matches[1];
        //     dd($indexName);

        if (preg_match('/NSE:(NIFTY|BANKNIFTY)/', $symbol, $matches) && $user->OptionsSSBrokerageType == 'per_lot') {
            $brokrageChangeOneTime = $user->options_brokerage;
        }
        if ($exchange == 'MCX' && $user->options_mcx_brokerage_type == 'per_lot') {
            $brokrageChangeOneTime = $user->options_mcx_brokerage;
        }
        if ($user->options_equity_brokerage_type == 'per_lot') {
            $brokrageChangeOneTime = $user->options_equity_brokerage;
        }

        $TradeUser = TradeUser::find($user->id);

        $this->trnsectionDeatil($user->id, 'BUY brokerage charge', 'BUY brokerage charge', $$brokrageChangeOneTime, 0);
        $TradeUser->decrement('balance', $brokrageChangeOneTime * 2);
        $this->trnsectionDeatil($user->id, 'BUY brokerage charge', 'SELL brokerage charge', $brokrageChangeOneTime, 0);
        $TradeUser->save();
    }

    public function trnsectionDeatil($user_id, $remark, $transpage, $amount, $type)
    {

        Transdetail::create([
            'transaction_id' => date('ymdhis'),
            'MemberId'   => $user_id,
            'TransType'  => $remark,
            'TransPage'  => $transpage,
            // 'withdraw  approval',
            'Type'       => ($type == 0) ? '-' : '+',
            'TransDate'  => now(),
            'Amount'     => $amount,
            'AmountS'    => $amount,
            'Remark'     => $remark,
            'LoginId'    => $user_id,
            'AddRemark'  => 'APPROVED BY ADMIN',
            'AdminStatus' => 'APPROVED'
        ]);
    }

    public function fetchCorrentData(array $symbols)
    {
        $url = "https://api-t1.fyers.in/data/quotes";
        $data = \DB::table('fyers')->first();

        $queryParams = [
            'symbols' => implode(',', $symbols),
        ];

        $token = $data->FYERS_CLIENT_ID . ':' . $data->FYERS_ACCESS_TOKEN;
        // dd($token);
        try {
            $response = Http::withHeaders([
                'Authorization' => $token
            ])->get($url, $queryParams);

            if ($response->successful()) {
                return $response->json();
            } else {
                return [
                    'error' => true,
                    'status' => $response->status(),
                    'message' => $response->body()
                ];
            }
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Verify user password (implement your own logic)
     */
    private function verifyUserPassword(string $password): bool
    {
        // Implement your password verification logic here
        // This could check against the user's stored password hash
        $user = auth()->user();
        return $user && \Hash::check($password, $user->password);
    }
}

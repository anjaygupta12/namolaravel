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
use DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        return View::make('user.index');
    }

    public function trades()
    {
        return View::make('user.trades');
    }

    public function portfolio()
    {
        $trades = [];
        return View::make('user.portfolio', compact('trades'));
    }

    public function watchlist()
    {
        return View::make('user.watchlist');
    }

    public function myAccount()
    {
        return View::make('user.my_account');
    }

    public function depositWithdraw()
    {
        return View::make('user.deposit_withdraw');
    }
    public function getData(Request $request)
    {
        $query = $request->input('query');
        $type = $request->input('type');
        $clientId = $request->input('clientid');

        $dataQuery = ForexOption::leftjoin('clientsubscription','forexoptions.Symbol','=','clientsubscription.Symbol')
        ->select('forexoptions.*','clientsubscription.Isactive as ckecked')
        ->where('forexoptions.Isactive', 1);
        
        if ($type == 'MCX') {
            $dataQuery->where('instrument', 'FUT')
            ->where('forexoptions.Symbol', 'like', 'MCX%');
        }else if($type == 'NSE'){
             $dataQuery->where('instrument', 'FUT')
            ->where('forexoptions.Symbol', 'like', 'NSE%');
        }
        else if ($type == 'OPTIONS') {
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
        if($request->action=='add'){
            ClientSubscription::insert([ 
           'Symbol'=>$request->symbol,
           'UserId'=>Auth::user()->id,
           'Isactive'=>1
       ]);

        }else{
            ClientSubscription::where('Symbol',$request->symbol)
            ->where('UserId',Auth::user()->id,)->delete();
        }

            return response()->json(['success' => true, 'message' => ucfirst($request->action) . 'ed successfully']);

    }


    public function saveTransaction(Request $request)
    {

        try {
            $userId = session('LoginId') ?? 1;

            $ip = $request->header('X_FORWARDED_FOR');
            if ($ip) {
                $ip = explode(',', $ip)[0];
            } else {
                $ip = $request->ip();
            }

            $data = [
                'Mode' => $request->input('Mode'),
                'ToAmount' => $request->input('textfclot', 0),
                'TransactionMode' => $request->input('TransactionMode'),
                'SalePrice' => $request->input('tblfcsellprice', 0),
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
                'LotSize' => $request->input('lblLotSize', 0),
                'OpenInterest' => $request->input('lblOpenInterest', 0),
                'BidQty' => $request->input('lblBidQty', 0),
                'AskQty' => $request->input('lblAskQty', 0),
                'PrevClose' => $request->input('lblPrevClose', 0),
                'UpperCircuit' => $request->input('lblUpperCircuit', 0),
                'LowerCircuit' => $request->input('lblLowerCircuit', 0),
                'OPTION' => 'I',
                'UserId' => $userId,
                'Symbol' => $request->input('Symbol'),
                'Min' => $request->input('Min') == 'True' ? 'Y' : 'N',
                'Mega' => $request->input('Mega') == 'True' ? 'Y' : 'N',
                'Lots' => $request->input('Lots', 0),
                'Price' => $request->input('Price', 0),
                'IpAddress' => $ip,
                'created_at' => now(),
                'updated_at' => now()
            ];

            MarketBidMaster::insert($data);

            return response()->json(['message' => 'Transaction saved successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save transaction: ' . $e->getMessage()], 500);
        }
    }

    public function depositRequestForm()
    {
        return View::make('user.deposit_request_form');
    }
    public function depositRequestSubmit(Request $request)
    {
        $request->validate([
            'amount'     => 'required|numeric|min:1',
            'screenshot' => 'required|image|mimes:jpeg,jpg,png|max:1024',
        ]);

        $path = $request->file('screenshot')->store('deposits', 'public');

        DepositeMaster::create([
            'UserId'        => 1,
            'Amount'        => $request->amount,
            'ScreenShot'    => $path,
            'Approve_Status' => 'Pending',
            'Approve_date'  => null,
            'Timestamp'     => Carbon::now(),
            'LastModify'    => Carbon::now(),
            'Isactive'      => true
        ]);

        return redirect()->back()->with('success', 'Deposit request submitted successfully.');
    }

    public function withdrawalRequestsForm()
    {
        return View::make('user.withdrawal_requests_form');
    }
    public function withdrawalRequests(Request $request)
    {

        if ($request->ajax()) {
            $data = WithdrawlMaster::where('UserId', 1)
                ->orderByDesc('Timestamp')
                ->get()
                ->map(function ($item) {
                    $item->FormattedTimestamp = \Carbon\Carbon::parse($item->Timestamp)->format('n/j/Y g:i:s A');
                    return $item;
                });

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

        WithdrawlMaster::create([
            'UserId'        => 1,
            'PaymentMethod' => $request->payment_method,
            'Amount'        => $request->amount,
            'Mobile'        => $request->mobile,
            'AccountHolder' => $request->holder_name,
            'AccountNo'     => $request->account_number,
            'IFSC'          => $request->ifsc,
            'Status'        => 'Pending',
            'Timestamp'     => Carbon::now(),
            'LastModify'    => Carbon::now(),
            'Isactive'      => true
        ]);

        return redirect()->back()->with('success', 'Withdrawal request submitted successfully!');
    }

    public function login()
    {
        if (Auth::check()) {
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
    public function userLogin(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];
        // dd($credentials,Auth::attempt($credentials));
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return redirect()->back()->with('error', 'Invalid credentials');
    }



    public function getPendingTrades()
    {
        try {
            $pendingTrades = MarketBidMaster::where('Status_Exec', 'Pending')
                ->select([
                    'Pk_id as Pk_id',
                    'symbol as Symbol',
                    'mode as Mode',
                    'status_exec as Status_Exec',
                    'timestamp as Timestamp',
                ])
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
            $activeTrades = MarketBidMaster::where('Isactive', '1')
                ->select([
                    'Pk_id as Pk_id',
                    'symbol as Symbol',
                    'timestamp as Timestamp',
                    'LastTradeQty',
                ])
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
            $closedTrades = MarketBidMaster::where('Isactive', '3')
                ->select([
                    'Pk_id as Pk_id',
                    'symbol as Symbol',
                    'timestamp as Timestamp',
                    'LastTradeQty',
                ])
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
                'exchange_type' => 'required|string|in:MCX,NSE,COMEX',
                'password' => 'required|string'
            ]);

            $exchangeType = $request->input('exchange_type');
            $password = $request->input('password');

            // Verify password (implement your own password verification logic)
            if (!$this->verifyUserPassword($password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid password'
                ], 401);
            }

            // Close all active trades for the specified exchange
            $closedCount = MarketPlaceMaster::where('status', 'ACTIVE')
                ->where('exchange_type', $exchangeType)
                ->update([
                    'status' => 'CLOSED',
                    'close_timestamp' => now(),
                    'closed_by' => auth()->id() ?? 'system',
                    'close_reason' => 'Bulk close by user'
                ]);

            return response()->json([
                'success' => true,
                'message' => "Successfully closed {$closedCount} trades",
                'closed_count' => $closedCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error closing bulk trades'
            ], 500);
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

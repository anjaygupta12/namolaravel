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
use App\Models\MarketCalendar;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\TryCatch;
use App\Http\Controllers\Admin\AdminController;

class HomeController extends Controller
{

    public function index()
    {
        if (!Auth::guard('tradeuser')->check()) {
            return redirect('/login');
        }
        $user = DB::table('tradeuser')->find(Auth::guard('tradeuser')->id());
        //    dd($user->MCXEnabled);
        //  dd($user->NSEFuturesEnabled);
        //  dd($user->NSEOptionsEnabled);
        $this->getBlockedSymbols();
        return View::make('user.index', compact('user'));
    }

    public function trades()
    {
        if (!Auth::guard('tradeuser')->check()) {
            return redirect('/login');
        }
        return View::make('user.trades');
    }

    public function clearTraderAccount()
    {
        dd(DB::table('tradeuser')->update([
            'net_p_l' => 0.00,
            'brokrage' => 0.00
        ]));
        Log::info('clear trade user account');
    }

    // public function __lowBalance()
    // {

    //     try {
    //         // ✅ Fetch all active trades where AutoSquareOff is enabled
    //         $trades = DB::table('marketbidmaster')
    //             ->join('tradeuser', 'tradeuser.id', '=', 'marketbidmaster.UserId')
    //             ->where('marketbidmaster.Isactive', 1)
    //             ->where('tradeuser.AutoSquareOff', 1)
    //             ->select(
    //                 'marketbidmaster.Pk_id',
    //                 'marketbidmaster.Symbol',
    //                 'marketbidmaster.BuyPrice',
    //                 'marketbidmaster.Mode',
    //                 'marketbidmaster.Lots',
    //                 'marketbidmaster.LotSize',
    //                 'tradeuser.id as user_id',
    //                 'tradeuser.balance'
    //             )
    //             ->get();

    //         if ($trades->isEmpty()) {
    //             Log::info('LowBalance: No active trades with AutoSquareOff enabled.');
    //             return;
    //         }

    //         // ✅ Group trades by user and calculate total P&L per user
    //         $userData = [];

    //         foreach ($trades as $trade) {
    //             $symbol  = $trade->Symbol;
    //             $user_id = $trade->user_id;

    //             $data = $this->getMarketData($trade->Symbol);

    //             if (!$data) {
    //                 Log::warning('LowBalance: Symbol not found in forexoptions', [
    //                     'symbol'  => $symbol,
    //                     'user_id' => $user_id,
    //                 ]);
    //                 continue;
    //             }

    //             // ✅ Correct sell price based on mode
    //             $sellPrice = $trade->Mode === 'BUY' ? $data['data']['bid_price'] : $data['data']['ask_price'];

    //             $lotSize = (int) $trade->Lots * $trade->LotSize;

    //             // ✅ P&L calculation based on mode
    //             $pl = $trade->Mode === 'BUY'
    //                 ? ($sellPrice - $trade->BuyPrice) * $lotSize
    //                 : ($trade->BuyPrice - $sellPrice) * $lotSize;

    //             // ✅ Initialize user data
    //             if (!isset($userData[$user_id])) {
    //                 $userData[$user_id] = [
    //                     'user_id' => $user_id,
    //                     'balance' => (float) $trade->balance,
    //                     'totalPL' => 0,
    //                 ];
    //             }

    //             $userData[$user_id]['totalPL'] += $pl;
    //         }

    //         // ✅ Check each user's balance against their total P&L loss
    //         foreach ($userData as $userInfo) {
    //             $user_id    = $userInfo['user_id'];
    //             $balance    = $userInfo['balance'];
    //             $totalPL    = $userInfo['totalPL'];

    //             // ✅ Net balance = current balance + running P&L
    //             $netBalance = $balance + $totalPL;

    //             if ($netBalance <= 0) {
    //                 Log::warning('LowBalance: User has insufficient balance, closing trades', [
    //                     'user_id'     => $user_id,
    //                     'net_balance' => $netBalance,
    //                 ]);

    //                 // ✅ Fetch fresh active trades for this user
    //                 $userTrades = DB::table('marketbidmaster')
    //                     ->where('UserId', $user_id)
    //                     ->where('Isactive', 1)
    //                     ->get();

    //                 if ($userTrades->isEmpty()) {
    //                     Log::info('LowBalance: No active trades to close for user', ['user_id' => $user_id]);
    //                     continue;
    //                 }

    //                 foreach ($userTrades as $userTrade) {

    //                     $closeResult = $this->closeSingle($userTrade, false, $sellPrice);

    //                     if (!empty($closeResult['error']) && $closeResult['error'] === true) {
    //                         Log::error('LowBalance: Failed to close trade', [
    //                             'trade_id' => $userTrade->Pk_id,
    //                         ]);
    //                         continue;
    //                     }
    //                     $symbolShort  = explode(':', $userTrade->Symbol)[1] ?? $userTrade->Symbol;
    //                     // ✅ Log transaction
    //                     $this->trnsectionDeatil(
    //                         $user_id,
    //                         "Trade closed due to insufficient balance. Symbol: {$symbolShort}",
    //                         'Auto Enable for Admin',
    //                         0,
    //                         1
    //                     );

    //                     Log::info('LowBalance: Trade closed successfully', [
    //                         'trade_id' => $userTrade->Pk_id,
    //                         'symbol'   => $userTrade->Symbol,
    //                         'user_id'  => $user_id,
    //                     ]);
    //                 }

    //                 // ✅ Re-check balance after closing all trades
    //                 $updatedUser = DB::table('tradeuser')->where('id', $user_id)->first();

    //                 if ($updatedUser && $updatedUser->balance <= 0) {
    //                     Log::warning('LowBalance: User balance still low after closing all trades', [
    //                         'user_id'         => $user_id,
    //                         'updated_balance' => $updatedUser->balance,
    //                     ]);

    //                     // ✅ Check if any trades are still active (edge case)
    //                     $remainingTrades = DB::table('marketbidmaster')
    //                         ->where('UserId', $user_id)
    //                         ->where('Isactive', 1)
    //                         ->get();

    //                     if ($remainingTrades->isNotEmpty()) {
    //                         Log::warning('LowBalance: Remaining active trades found, closing again', [
    //                             'user_id' => $user_id,
    //                             'count'   => $remainingTrades->count(),
    //                         ]);

    //                         foreach ($remainingTrades as $remainingTrade) {
    //                             $closeResult = $this->closeSingle($remainingTrade, false, 234567);

    //                             if (!empty($closeResult['error']) && $closeResult['error'] === true) {
    //                                 Log::error('LowBalance: Failed to close remaining trade', [
    //                                     'trade_id' => $remainingTrade->Pk_id,
    //                                     'symbol'   => $remainingTrade->Symbol,
    //                                     'user_id'  => $user_id,
    //                                     'reason'   => $closeResult['message'],
    //                                 ]);
    //                                 continue;
    //                             }
    //                             $symbolShort  = explode(':', $remainingTrade->Symbol)[1] ?? $remainingTrade->Symbol;
    //                             $this->trnsectionDeatil(
    //                                 $user_id,
    //                                 "Trade closed again — balance still insufficient. Symbol: {$symbolShort}",
    //                                 'Auto Square Off',
    //                                 0,
    //                                 1
    //                             );

    //                             Log::info('LowBalance: Remaining trade closed', [
    //                                 'trade_id' => $remainingTrade->Pk_id,
    //                                 'symbol'   => $remainingTrade->Symbol,
    //                                 'user_id'  => $user_id,
    //                             ]);
    //                         }
    //                     } else {
    //                         Log::info('LowBalance: No remaining trades for user', ['user_id' => $user_id]);
    //                     }
    //                 } else {
    //                     Log::info('LowBalance: User balance restored after closing trades', [
    //                         'user_id'         => $user_id,
    //                         'updated_balance' => $updatedUser->balance ?? 'N/A',
    //                     ]);
    //                 }
    //             }
    //         }

    //         Log::info('LowBalance: Check completed successfully.');
    //     } catch (\Exception $e) {
    //         Log::error('LowBalance: Unexpected exception', [
    //             'message' => $e->getMessage(),
    //             'file'    => $e->getFile(),
    //             'line'    => $e->getLine(),
    //             'trace'   => $e->getTraceAsString(),
    //         ]);
    //     }
    // }

    public function lowBalance()
    {
        Log::info('clear trade user account');
        try {
            // ✅ Fetch all active trades where AutoSquareOff is enabled
            $trades = DB::table('marketbidmaster')
                ->join('tradeuser', 'tradeuser.id', '=', 'marketbidmaster.UserId')
                ->where('marketbidmaster.Isactive', 1)
                ->where('tradeuser.AutoSquareOff', 1)
                ->select(
                    'marketbidmaster.Pk_id',
                    'marketbidmaster.Symbol',
                    'marketbidmaster.BuyPrice',
                    'marketbidmaster.Mode',
                    'marketbidmaster.Lots',
                    'marketbidmaster.LotSize',
                    'tradeuser.id as user_id',
                    'tradeuser.balance'
                )
                ->get();

            if ($trades->isEmpty()) {
                Log::info('LowBalance: No active trades with AutoSquareOff enabled.');
                return;
            }

            // ✅ Group trades by user with per-trade P&L
            $userData = [];

            foreach ($trades as $trade) {
                $user_id = $trade->user_id;
                $data    = $this->getMarketData($trade->Symbol);

                if (!$data) {
                    Log::warning('LowBalance: Symbol not found', [
                        'symbol'  => $trade->Symbol,
                        'user_id' => $user_id,
                    ]);
                    continue;
                }

                $sellPrice = $trade->Mode === 'BUY'
                    ? $data['data']['bid_price']
                    : $data['data']['ask_price'];

                $lotSize = (int) $trade->Lots * $trade->LotSize;

                $pl = $trade->Mode === 'BUY'
                    ? ($sellPrice - $trade->BuyPrice) * $lotSize
                    : ($trade->BuyPrice - $sellPrice) * $lotSize;

                if (!isset($userData[$user_id])) {
                    $userData[$user_id] = [
                        'balance' => (float) $trade->balance,
                        'totalPL' => 0,
                        'trades'  => [],
                    ];
                }

                $userData[$user_id]['totalPL'] += $pl;

                // ✅ Store each trade with its P&L and sell price
                $userData[$user_id]['trades'][] = [
                    'trade'     => $trade,
                    'pl'        => $pl,
                    'sellPrice' => $sellPrice,
                ];
            }

            // ✅ Process each user
            foreach ($userData as $user_id => $userInfo) {
                $netBalance = $userInfo['balance'] + $userInfo['totalPL'];

                // ✅ Balance is healthy — skip this user
                if ($netBalance > 0) {
                    continue;
                }

                Log::warning('LowBalance: Net balance negative, starting one-by-one closure', [
                    'user_id'     => $user_id,
                    'balance'     => $userInfo['balance'],
                    'totalPL'     => $userInfo['totalPL'],
                    'net_balance' => $netBalance,
                ]);

                // ✅ Sort: biggest loss first (most negative PL first)
                $tradesToClose = collect($userInfo['trades'])
                    ->sortBy('pl') // ascending = most negative first
                    ->values();

                foreach ($tradesToClose as $tradeItem) {

                    // ✅ Re-fetch fresh balance before each trade closure
                    $freshUser = DB::table('tradeuser')->where('id', $user_id)->first();

                    if (!$freshUser) {
                        Log::error('LowBalance: User not found', ['user_id' => $user_id]);
                        break;
                    }

                    // ✅ Recalculate live P&L from all still-active trades
                    $activeTrades = DB::table('marketbidmaster')
                        ->where('UserId', $user_id)
                        ->where('Isactive', 1)
                        ->get();

                    if ($activeTrades->isEmpty()) {
                        Log::info('LowBalance: No more active trades for user', ['user_id' => $user_id]);
                        break;
                    }

                    $currentTotalPL = 0;

                    foreach ($activeTrades as $activeTrade) {
                        $liveData = $this->getMarketData($activeTrade->Symbol);
                        if (!$liveData) continue;

                        $liveSellPrice = $activeTrade->Mode === 'BUY'
                            ? $liveData['data']['bid_price']
                            : $liveData['data']['ask_price'];

                        $lotSize = (int) $activeTrade->Lots * $activeTrade->LotSize;

                        $currentTotalPL += $activeTrade->Mode === 'BUY'
                            ? ($liveSellPrice - $activeTrade->BuyPrice) * $lotSize
                            : ($activeTrade->BuyPrice - $liveSellPrice) * $lotSize;
                    }

                    $currentNetBalance = (float) $freshUser->balance + $currentTotalPL;

                    // ✅ Balance recovered — stop closing trades
                    if ($currentNetBalance > 0) {
                        Log::info('LowBalance: Balance recovered, stopping closure', [
                            'user_id'     => $user_id,
                            'net_balance' => $currentNetBalance,
                        ]);
                        break;
                    }

                    // ✅ Confirm this trade is still active (race condition guard)
                    $tradeRecord = DB::table('marketbidmaster')
                        ->where('Pk_id', $tradeItem['trade']->Pk_id)
                        ->where('Isactive', 1)
                        ->first();

                    if (!$tradeRecord) {
                        Log::info('LowBalance: Trade already closed, skipping', [
                            'trade_id' => $tradeItem['trade']->Pk_id,
                        ]);
                        continue;
                    }

                    // ✅ Close this single trade
                    $closeResult = $this->closeSingle($tradeRecord, true, $tradeItem['sellPrice']);

                    if (!empty($closeResult['error']) && $closeResult['error'] === true) {
                        Log::error('LowBalance: Failed to close trade', [
                            'trade_id' => $tradeRecord->Pk_id,
                            'symbol'   => $tradeRecord->Symbol,
                            'reason'   => $closeResult['message'] ?? 'Unknown',
                        ]);
                        continue;
                    }
                    $symbolShort  = explode(':', $tradeRecord->Symbol)[1] ?? $tradeRecord->Symbol;
                    // ✅ Log transaction
                    $this->trnsectionDeatil(
                        $user_id,
                        "Trade closed due to insufficient balance. Symbol: {$symbolShort}",
                        'Auto Square Off',
                        0,
                        1
                    );

                    Log::info('LowBalance: Trade closed, rechecking balance', [
                        'trade_id'        => $tradeRecord->Pk_id,
                        'symbol'          => $tradeRecord->Symbol,
                        'user_id'         => $user_id,
                        'net_balance_was' => $currentNetBalance,
                    ]);
                }

                // ✅ Final status log after loop ends for this user
                $finalUser = DB::table('tradeuser')->where('id', $user_id)->first();
                Log::info('LowBalance: Closure loop finished for user', [
                    'user_id'       => $user_id,
                    'final_balance' => $finalUser->balance ?? 'N/A',
                ]);
            }

            Log::info('LowBalance: Check completed successfully.');
        } catch (\Exception $e) {
            Log::error('LowBalance: Unexpected exception', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
            ]);
        }
    }




    public function carryFarwad(Request $request)
    {
        try {

            // ✅ Check Day & Time (Asia/Kolkata)
            $now = Carbon::now('Asia/Kolkata');

            // ✅ Fetch active trades
            $trades = DB::table('marketbidmaster')
                ->join('tradeuser', 'tradeuser.id', '=', 'marketbidmaster.UserId')
                ->where('marketbidmaster.Isactive', 1)
                // ->where('tradeuser.id', '=', 67)
                ->where('marketbidmaster.deleted_at', null)
                ->select('marketbidmaster.*', 'tradeuser.user_id', 'tradeuser.Username')
                ->get();

            if ($trades->isEmpty()) {
                Log::info('CarryForward: No active trades found.');
                return;
            }

            Log::info('CarryForward: Starting carry forward', [
                'total_trades' => $trades->count()
            ]);

            foreach ($trades as $trade) {

                DB::beginTransaction();

                try {
                    // dd($trade);
                    // ✅ Get market price
                    $data = DB::table('forexoptions')
                        ->where('Symbol', $trade->Symbol)
                        ->first();

                    if (!$data) {

                        Log::warning('CarryForward: Symbol not found in forexoptions');
                        DB::rollBack();
                        continue;
                    }
                    $symbol = $trade->Symbol;
                    if (!empty($symbol) && strpos($symbol, '&') !== false) {
                        $symbol = str_replace('&', '%26', $symbol);
                    }

                    $responseApi = $this->getMarketData($symbol);

                    // if (!empty($responseApi['error']) && $responseApi['error'] === true) {
                    //     Log::error('closeSingle: API fetch failed', [
                    //         'symbol'  => $trade->Symbol,
                    //         'message' => $responseApi['message'],
                    //         'user_id' => $user->id ?? null,
                    //     ]);
                    //     return ['error' => true, 'message' => $responseApi['message']];
                    // }

                    // foreach ($responseApi['d'] as $data) 

                    if (isset($responseApi['data'])) {

                        $closePrice = $trade->Mode === 'BUY'
                            ? ($responseApi['data']['bid_price'] ?? 0)
                            : ($responseApi['data']['ask_price'] ?? 0);
                    } else {

                        //    dd($trade);
                        continue;
                    }

                    // ✅ New trade mode
                    $newMode = $trade->Mode === 'BUY'
                        ? 'SELL'
                        : 'BUY';

                    $this->closeSingle($trade, false, $closePrice);

                    // if (!empty($closeResult['error']) && $closeResult['error'] === true) {

                    //     Log::error('CarryForward: Failed to close old trade', [
                    //         'trade_id' => $trade->Pk_id,
                    //         'symbol'   => $trade->Symbol,
                    //         'user_id'  => $trade->UserId,
                    //         'reason'   => $closeResult['message'],
                    //     ]);

                    //     DB::rollBack();
                    //     continue;
                    // }

                    $newTradeArray = (array) $trade;
                    // unset($newTradeArray['Pk_id']);

                    unset(
                        $newTradeArray['Pk_id'],
                        $newTradeArray['user_id'],
                        $newTradeArray['Username']
                    );

                    $newTradeArray['trade_id']   = rand(10000000, 99999999);
                    $newTradeArray['Bought_by'] = 'Admin';
                    $newTradeArray['BuyPrice']   = $closePrice;
                    $newTradeArray['SalePrice']  = null;
                    $newTradeArray['Isactive']   = 1;
                    $newTradeArray['IpAddress']  = '127.0.0.1';
                    $newTradeArray['created_at'] = now();
                    $newTradeArray['updated_at'] = now();

                    DB::table('marketbidmaster')->insert($newTradeArray);

                    Log::info('CarryForward: New trade inserted', [
                        'symbol'   => $trade->Symbol,
                        'new_mode' => $newMode,
                        'price'    => $closePrice,
                    ]);

                    // --------------------------------
                    // STEP 5 : Transaction log
                    // --------------------------------
                    $user = TradeUser::find($trade->UserId);

                    $symbolShort  = explode(':', $trade->Symbol)[1] ?? $trade->Symbol;
                    $msg = "{$user->Username} ({$user->user_id}) {$trade->Lots} lots of {$symbolShort} squared off at {$closePrice} due to weekly settlement.";

                    $this->trnsectionDeatil($trade->UserId, $msg, 'Carry Forward', 0, 1);

                    DB::commit();

                    Log::info('CarryForward: Trade carried forward successfully', [
                        'old_trade_id' => $trade->Pk_id,
                        'symbol'       => $trade->Symbol,
                        'close_price'  => $closePrice,
                        'new_mode'     => $newMode,
                    ]);
                } catch (\Exception $innerException) {
                    DB::rollBack();
                    // dd($innerException->getMessage());
                    Log::error('CarryForward: Trade processing failed', [
                        'trade_id' => $trade->Pk_id,
                        'symbol'   => $trade->Symbol,
                        'error'    => $innerException->getMessage(),
                    ]);
                }
            }

            Log::info('CarryForward: All trades processed.');
        } catch (\Exception $e) {

            Log::error('CarryForward: Unexpected exception', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
            ]);
        }
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


    public function manageCloseonExpairy()
    {
        $today = Carbon::today()->toDateString();

        $homeCont = new HomeController();

        try {
            // Bug Fix 1: Renamed $data to $expiryRecords to avoid variable overwrite inside foreach
            // Bug Fix 2: Added 'forexoptions.Symbol' to the select so $val->Symbol is available
            $expiryRecords = DB::table('forexoptions')
                ->join('marketbidmaster', 'forexoptions.Symbol', '=', 'marketbidmaster.Symbol')
                ->where('marketbidmaster.Isactive', 1)
                 ->where('marketbidmaster.deleted_at', null)
                ->whereDate('forexoptions.ExpiryDate', '<=', $today)
                ->get();

  $watchlist = DB::table('forexoptions')
    ->join('clientsubscription', 'forexoptions.Symbol', '=', 'clientsubscription.Symbol')
    ->where('clientsubscription.Isactive', 1)
    ->whereDate('forexoptions.ExpiryDate', '<=', $today)
   ->pluck('clientsubscription.PK_ID');
                DB::table('clientsubscription')
    ->whereIn('PK_ID', $watchlist)
    ->delete();
            

            foreach ($expiryRecords as $val) {

                $responseApi = $this->getMarketData($val->Symbol);
                
                if (!empty($responseApi)) {
                    $closePrice = $val->Mode === 'BUY' ? $responseApi['data']['bid_price'] : $responseApi['data']['ask_price'];
                } else {
                    $data = DB::table('forexoptions')->where('Symbol', $val->Symbol)->first();

                    $closePrice = $val->Mode === 'BUY' ? $data->bid : $data->ask;
                }
                $user = TradeUser::where('id', $val->UserId)->first();
                if (empty($user)) {
                    MarketBidMaster::where('UserId', $val->UserId)->update(['isActive' => 2]);
                }

                $this->closeSingle($val, true, $closePrice);
                $symbolShort = str_replace("MCX:", "", $val->Symbol);
                // $msg = "{$user->Username} ({$user->user_id}) {$val->Lots} lots of {$symbolShort} squared off at {$closePrice} due to weekly settlement.";
                $msg = "{$user->Username} ({$user->user_id}) {$val->Lots} lots of {$symbolShort} Trade closed successfully due to weekly expiry.";
                $this->trnsectionDeatil($val->id, $msg, null, $symbolShort, 1);

                Log::info('Successfully closed trades on expiry for symbol: ' . $val->Symbol);
            }


            // Close today's active records and activate the next upcoming expiry
            set_time_limit(0);
ini_set('memory_limit', '-1');

// $records = DB::table('forexoptions')
//     ->where('Isactive', 1)
//     ->whereDate('ExpiryDate', '<=', $today)
//     ->orderBy('Id')
//     ->cursor();
// dd($records);
// foreach ($records as $row) {

//     // deactivate current expired record
//     DB::table('forexoptions')
//         ->where('Id', $row->Id)
//         ->update([
//             'Isactive' => 0
//         ]);

//     // activate next upcoming record
//     $nextId = DB::table('forexoptions')
//         ->where('SymbolShortName', $row->SymbolShortName)
//         ->where('Isactive', 0)
//         ->whereDate('ExpiryDate', '>', $today)
//         ->orderBy('ExpiryDate', 'asc')
//         ->value('Id');

//     if ($nextId) {
//         DB::table('forexoptions')
//             ->where('Id', $nextId)
//             ->update([
//                 'Isactive' => 1
//             ]);
//     }
// }
            $records = DB::table('forexoptions')
                ->where('Isactive', 1)
                ->whereDate('ExpiryDate', '<=', $today)
                ->get();
            // dd($records);
            foreach ($records as $row) {

                // Step 1: Get the next upcoming expiry record (second latest by ExpiryDate)
                $nextRecord = DB::table('forexoptions')
                    ->where('SymbolShortName', $row->SymbolShortName)
                    ->where('Isactive', 0)                // Only inactive records
                    ->whereDate('ExpiryDate', '>', $today) // Future expiry only
                    ->orderBy('ExpiryDate', 'asc')        // Get the nearest upcoming
                    ->first();                            // Get the next one

                // Step 2: Activate the next record (if exists)
                if ($nextRecord) {
                    DB::table('forexoptions')
                        ->where('Id', $nextRecord->Id)
                        ->update(['Isactive' => 1]);
                }

                // Step 3: Deactivate the expired record
                DB::table('forexoptions')
                    ->where('Id', $row->Id)
                    ->update(['Isactive' => 0]);
            }

        } catch (\Exception $e) {

            Log::error('manageCloseonExpairy error: ' . $e->getMessage());
        }
    }

    public function portfolio()
    {
        if (!Auth::guard('tradeuser')->check()) {
            return redirect('/login');
        }

        $startOfWeek = Carbon::now()->startOfWeek(); // Monday
        $endOfWeek = Carbon::now()->endOfWeek();     // Sunday

        $usedMargin = MarketBidMaster::where('UserId', Auth::guard('tradeuser')->user()->id)
            ->where('Isactive', 1)->sum('used_margin_req');

        $marginAvilabe = Auth::guard('tradeuser')->user()->balance;
        $holdingMargin =  MarketBidMaster::where('UserId', Auth::guard('tradeuser')->user()->id)
            ->where('Isactive', 1)->sum('holding_margin_req');

        $deposit =DepositeMaster::where('UserId', Auth::guard('tradeuser')->user()->id)
        ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
        ->where('type', 1)
        ->where(function ($q) {
            $q->where('notes', '!=', 'Opening Balance.')
              ->orWhereNull('notes');
        })
        ->sum('Amount');


        $widrow = DepositeMaster::where('UserId', Auth::guard('tradeuser')->user()->id)
        ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
        ->where('type', 0)
        ->where(function ($q) {
            $q->where('notes', '!=', 'Opening Balance.')
              ->orWhereNull('notes');
        })
        ->sum('Amount');

        $totalPLSatteled = MarketBidMaster::where('UserId', Auth::guard('tradeuser')->user()->id)
            ->where('Isactive', 2)
            ->select(DB::raw("
                            SUM(
                                CASE 
                                    WHEN Mode = 'SELL' 
                                    THEN (BuyPrice - SalePrice) * (Lots * LotSize)
                                    ELSE (SalePrice - BuyPrice) * (Lots * LotSize)
                                END
                            ) - SUM(brokrage) as total_pl
                        "))
            ->whereBetween('updated_at', [$startOfWeek, $endOfWeek])
            ->value('total_pl');



        return View::make('user.portfolio', compact('totalPLSatteled', 'usedMargin', 'marginAvilabe', 'holdingMargin', 'deposit', 'widrow'));
    }

    public function watchlist()
    {
        return View::make('user.watchlist');
    }

    public function myAccount()
    {
          $startOfWeek = Carbon::now()->startOfWeek(); // Monday
          $endOfWeek   = Carbon::now()->endOfWeek();   // Sunday


        if (!Auth::guard('tradeuser')->check()) {
            return redirect('/login');
        }
        $data = DepositeMaster::where('UserId', Auth::guard('tradeuser')->user()->id)
        ->where('notes', 'Opening Balance.')
        ->whereBetween('created_at', [$startOfWeek, $endOfWeek])->get();

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

    public function getBlockedSymbols()
    {
        $clientId = Auth::guard('tradeuser')->user()->id;
        $brokerId = Auth::guard('tradeuser')->user()->broker_id;

        $broker_ids = collect([$brokerId, 1])
            ->merge(AdminLogin::where('PK_ID', $brokerId)->pluck('parent_id'))
            ->unique()
            ->filter()
            ->values();

        $blockedSymbols = DB::table('userbannedsymbols')
            ->whereIn('brokerid', $broker_ids)
            ->where('banned', 1)
            ->pluck('symbol')->toArray();

        ClientSubscription::whereIn('Symbol', $blockedSymbols)
            ->where('UserId', $clientId)->delete();

        return $blockedSymbols;
    }
    public function getData(Request $request)
    {
        $query = $request->input('query');
        $type = $request->input('type');
        $clientId = Auth::guard('tradeuser')->user()->id;

        $blockedSymbols = $this->getBlockedSymbols();

        $dataQuery = ForexOption::leftJoin('clientsubscription', function ($join) use ($clientId) {
            $join->on('forexoptions.Symbol', '=', 'clientsubscription.Symbol')
                ->where('clientsubscription.UserId', $clientId);
        })
            ->whereNotIn('forexoptions.Symbol', $blockedSymbols)
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
            $dataQuery->whereRaw("forexoptions.Symbol REGEXP 'PE$|CE$'");
        }

        if ($query != null) {
            $dataQuery->where('forexoptions.Symbol', 'like', '%' . $query . '%');
        }

        // You can add more filters like clientId if needed
        // if ($clientId) {
        //     $dataQuery->where('client_id', $clientId);
        // }

        $data = $dataQuery->get();
        // dd($data);
        return response()->json($data);
    }


    public function getSymbol(Request $request)
    {

        // $segment = DB::table('forexoptions')
        //     ->where('symbol', $request->Symbol)
        //     ->value('segment');
        // $response = $this->getMarketDepth($request->Symbol);
        $response = $this->getMarketData($request->Symbol);

        $lowerCkt = $response['data']['lower_circuit'] ?? 0;
        $upperCkt = $response['data']['upper_circuit'] ?? 0;

        $lotSize = DB::table('forexoptions')
            ->join(
                'lot_size',
                DB::raw("CONVERT(lot_size.name USING utf8mb4) COLLATE utf8mb4_unicode_ci"),
                '=',
                DB::raw("CONVERT(forexoptions.SymbolShortName USING utf8mb4) COLLATE utf8mb4_unicode_ci")
            )
            ->where('forexoptions.Symbol', $request->Symbol)
            ->pluck('lot_size.qty')
            ->first();
        return response()->json(['success' => true, 'lotSize' => $lotSize, 'lower_ckt' => $lowerCkt, 'upper_ckt' => $upperCkt]);
    }


    public function getMarketDepth($symbol)
    {
        $data = \DB::table('fyers')->first();

        // $queryParams = [
        //     'symbols' => implode(',', $symbols),
        // ];

        $accessToken = $data->FYERS_CLIENT_ID . ':' . $data->FYERS_ACCESS_TOKEN;
        // dd($token);
        // $accessToken = $data->FYERS_ACCESS_TOKEN;
        // $appId = 'app_id';

        $url = "https://api-t1.fyers.in/data/depth";

        $response = Http::withHeaders([
            'Authorization' => $accessToken,
        ])->get($url, [
            'symbol' => $symbol,
            'ohlcv_flag' => 1
        ]);

        return $response->json();
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

    public function calculateCharge($amount, $baseCharge = 800)
    {
        $ratePerRupee = $baseCharge / 10000000;
        $charge = $amount * $ratePerRupee;
        return round($charge, 2);
    }

    public function brokarageCharge($buyAmount, $amount, $user, $symbol)
    {
        $exchange = explode(":", $symbol)[0];
        $brokCharge = 0;

        // if (substr($symbol, -2) === "CE" || substr($symbol, -2) === "PE") {

        //     if (preg_match('/NSE:(NIFTY|BANKNIFTY|FINNIFTY)/', $symbol, $matches) && $user->OptionsSSBrokerageType == 'per_lot') {
        //         $brokrageChangeOneTime = $user->options_brokerage;
        //     }

        //     // if ($exchange == 'MCX' && $user->options_mcx_brokerage_type == 'per_lot') {
        //     //     $brokrageChangeOneTime = $this->calculateCharge($buyAmount+$amount, $user->options_mcx_brokerage);

        //     //     // $brokrageChangeOneTime = $user->options_mcx_brokerage;
        //     // }
        //     if ($user->options_equity_brokerage_type == 'per_lot') {
        //         $brokrageChangeOneTime = $user->options_equity_brokerage;
        //     }
        //     $brokCharge = $brokrageChangeOneTime * 2;
        // }
        if ($exchange == 'MCX') {
            //  dd($buyAmount + $amount, $user->MCXBrokerage);
            $brokCharge = $this->calculateCharge($buyAmount + $amount, $user->MCXBrokerage);
            // $brokrageChangeOneTime = $user->options_mcx_brokerage;

        } else if ($exchange == 'NSE') {
            $brokCharge = $this->calculateCharge($buyAmount + $amount, $user->MCXBrokerage);
        }
        return $brokCharge;
    }

    public function saveTransaction(Request $request)
    {

        if ($request->tblfcbuyprice == 0) {
            return response()->json(['error' => '❌ You Can not trade becouse hit the curcit.'], 500);
        }
        if ($request->TransactionMode === 'OPTIONS') {
            $request->merge(['TransactionMode' => 'NSE']);
        }

        if (!Auth::guard('tradeuser')->check()) {
            return response()->json(['error' => '❌ Session has been expaired...'], 500);
            return redirect('/login');
        }

        $user = Auth::guard('tradeuser')->user();
        $exchange = explode(':', $request->input('Symbol'))[0];

        // account blocked
        if ($user->IsActive != 1) {
            return response()->json(['error' => '❌ You Account is Blocked '], 500);
        }
        // store trade from admin
        if ($user->StopTrade == 1) {
            return response()->json(['error' => '❌ You Trader has been Stoped from admin..'], 500);
        }

        $symbol = $request->input('Symbol');

        // if (preg_match('/(\d+)(CE|PE)$/', $symbol, $m)) {
        //     $strike = (float) $m[1];
        //     $price  = (float) $request->input('Price');

        //     if ($price > $strike * 1.05 || $price < $strike * 0.95) {
        //         dd('Price is outside ±5% of strike: ' . $strike);
        //        return response()->json(['error' => '❌ Price is outside ±5% of strike: ' . $strike], 500);

        //     }
        // }

        if ($request->has('isOrder') == true) {
            $amount = $request->input('tblfcbuyprice', 0);
            $heightPrice = $request->input('lblHigh', 0);
            $lowPrice = $request->input('lblLow', 0);

            if ($user->AllowOrdersBetweenHighLow == 1 && $user->AllowOrdersBeyondHighLow == 0) {

                //   dd($amount,$heightPrice,$lowPrice, $amount < $lowPrice || $amount > $heightPrice);
                if ($amount < $lowPrice || $amount > $heightPrice) {
                    return response()->json(['error' => 'Order price must be between Height and Low...'], 500);
                }
            }
            if ($user->AllowOrdersBeyondHighLow == 1 && $user->AllowOrdersBetweenHighLow == 0) {
                // dd($amount > $lowPrice && $amount < $heightPrice,$amount , $lowPrice ,$heightPrice);
                if ($amount > $lowPrice && $amount < $heightPrice) {
                    return response()->json(['error' => 'Order price must be beyond Height and Low...'], 500);
                }
            }
            if ($user->AllowOrdersBeyondHighLow == 0 && $user->AllowOrdersBetweenHighLow == 0) {

                return response()->json(['error' => 'you can not Order...'], 500);
            }
        }

        // check trading timing
        $tradingTime = $this->getTradeTime($request->input('TransactionMode'),$symbol);

        if ($tradingTime) {
            return response()->json(['error' => 'You can not trade becouse market has been closed'], 500);
        }


        $exchange = explode(":", $request->input('Symbol'))[0];

        $symbol = $request->input('Symbol');

        // lot wise valume 
        $lotSize = $this->getLotValume($symbol);
        
        // dd($request->has('qty'),$request->input('Lots', 1));
        if ($request->has('qty')) {
            $lots = $request->input('qty', 1) / $lotSize;
        } else {
            $lots = $request->input('Lots', 1);
        }

        //  check margin avilable 
        $SymbolShortName = ForexOption::where('Symbol', $symbol)->value('SymbolShortName');
        $transctionMode = $request->input('TransactionMode');
        // $lotMargin = $this->checkMarginAvilabel($request, $symbol, $user);
        if ($request->input('Min') == 1) {
            
            if ($SymbolShortName == 'GOLD' || $SymbolShortName == 'COPPER' || $SymbolShortName == 'CRUDEOIL') {
                $lot = $request->input('Lots', 1);
                $partTen = $lotSize/10;
                $lots = $partTen * $lot / $lotSize;
                $lotSize = $partTen;
            }

            if ($SymbolShortName == 'SILVER') {

                $lot = $request->input('Lots', 1);
                $lots = 5 * $lot / $lotSize;
                $lotSize = 5;
            }
        }


        $marginAvilabe = $this->getMarginCheckAvilable($user, $SymbolShortName, $lotSize, $transctionMode, $lots, $request->input('tblfcbuyprice', 0), $symbol);

        $mode = $request->input('Mode') == 'BUY' ? 'SELL' : 'BUY';
        $symbol = $request->input('Symbol');

        // margin intraday
        // $marginAvilabe = $this->getMarginCheckAvilable($user, $SymbolShortName, $lotSize, $transctionMode,$request->Lots);

        $usedmargin = $marginAvilabe['intraday'];
        $holdingmargin = $marginAvilabe['holding'];
        // brokrage changes
        // dd($request->has('qty'),$request->has('isOrder'));
        if ($request->has('qty') == true && $request->has('isOrder') == false) {

            $existTrade = MarketBidMaster::where('Mode', $mode)->where('Symbol', $symbol)
                ->where('UserId', $user->id)
                ->where('is_qty', 1)
                ->where('Isactive', 1)->first();
            if ($existTrade) {

                $checked =  $this->checkQtyExist($request, $existTrade, $marginAvilabe, $lots);
                if ($checked['error'] == false) {
                    return response()->json(['message' => $request->input('Mode') . 'Already exists and then closed successfully']);
                } else {
                    return response()->json(['message' => $checked['message']]);
                }
            }
        }

        $existTrade = MarketBidMaster::where('Mode', $mode)->where('Symbol', $symbol)
            ->where('UserId', $user->id)
            ->where('Isactive', 1)->first();
        //   dd($existTrade,$request->has('isOrder'),$mode);
        if ($request->has('isOrder') == false) {
            $checked = '';

            if ($existTrade) {
                $inputLots =  $lots;

                if ($existTrade->Lots > $inputLots) {
                    $existTrade->Lots = $existTrade->Lots - $lots;
                    $existTrade->save();

                    $newData1 = $existTrade->toArray();

                    unset($newData1['Pk_id']);

                    $newData1['Lots'] = $lots;
                    $newData1['trade_id'] = rand(10000000, 99999999);

                    $newData1['holding_margin_req'] = $holdingmargin * $lots;
                    $newData1['used_margin_req'] = $usedmargin * $lots;
                    $newData1['Timestamp'] = Carbon::parse($newData1['Timestamp'])
                        ->format('Y-m-d H:i:s');
                    $newData1['created_at'] = Carbon::parse($newData1['created_at'])
                        ->format('Y-m-d H:i:s');
                    $newData1['updated_at'] = now()->format('Y-m-d H:i:s');

                    $insertId = DB::table('marketbidmaster')->insertGetId($newData1);

                    // get inserted data
                    $existTrade1 = DB::table('marketbidmaster')
                        ->where('Pk_id', $insertId)
                        ->first();

                    $sellPrice = $request->input('tblfcbuyprice', 0);
                    $checked = $this->closeBulkTradesExist($existTrade1, $sellPrice);
                } else if ($existTrade->Lots < $inputLots) {

                    $existLost = $inputLots - $existTrade->Lots;
                    $newData = $existTrade->toArray();
                    unset($newData['Pk_id']);
                    $newData['Lots'] = $existLost;
                    $newData['SalePrice'] = null;
                    $newData['holding_margin_req'] = $holdingmargin * $existLost;
                    $newData['used_margin_req'] = $usedmargin * $existLost;
                    $newData['BuyPrice'] = $request->input('tblfcbuyprice', 0);
                    $newData['trade_id'] = rand(10000000, 99999999);
                    $newData['Mode'] = $request->input('Mode');
                    $newData['updated_at'] = now();
                    MarketBidMaster::create($newData);
                    $sellPrice = $request->input('tblfcbuyprice', 0);
                    $checked = $this->closeBulkTradesExist($existTrade, $sellPrice);
                } else {
                    $sellPrice = $request->input('tblfcbuyprice', 0);
                    $checked = $this->closeBulkTradesExist($existTrade, $sellPrice);
                }

                if ($checked['error'] == false) {
                    return response()->json(['message' => $request->input('Mode') . 'Already exists and then closed successfully']);
                } else {
                    return response()->json(['message' => $checked['message']]);
                }
                // now closed tradder coder here............. 
            }
        }

        $adminCtrl = new AdminController();
        $m2mbalance = $adminCtrl->dashboardLivePl('admin', $user->id);
        $data = json_decode($m2mbalance->getContent(), true);
        $pl = array_sum(array_column($data, 'pl')) ?? 0;
        $clearBalance = $user->balance + $pl;

        if (!$request->has('isOrder') && !$existTrade) {
            if ($clearBalance < $marginAvilabe['need_balance']) {
                return response()->json(['error' => 'Margin not Available...'], 500);
            }
            // check lot size

            $allowLotSize = $this->checkLotSize($request, $exchange, $user);

            if ($allowLotSize != '') {
                return response()->json(['error' => $allowLotSize], 500);
            }
        }


        try {
            $user = Auth::guard('tradeuser')->user();

            $ip = $request->header('X_FORWARDED_FOR');
            if ($ip) {
                $ip = explode(',', $ip)[0];
            } else {
                $ip = $request->ip();
            }
            // $buyPrice = $request->input('tblfcbuyprice', 0) * $request->input('lblBidQty', 1);


            // its per turnhove basic for teature
            $perratRate = $request->input('tblfcbuyprice', 0);
            // $lots = ($request->Lots) * 100;

            // $perratRate = $request->input('tblfcbuyprice', 0);
            // $lots = ($request->Lots) * 100;

            // $holdingmargin = ($perratRate * $lots) / $tradeUser->MCXHoldingMargin;
            // $usedmargin = ($perratRate * $lots) / $tradeUser->MCXIntradayMargin;

            // per lot basic only for GOLD


            $saleAmount = ($lotSize * $lots) *  $request->input('tblfcsellprice', 0);
            $amount = ($lotSize * $lots) * $perratRate;
            $brokrage = $this->brokarageCharge($amount, $saleAmount, $user, $request->input('Symbol'));

            // $newbalance = $request->tblfcbuyprice*$request->Lots;
            // $tradeUser->decrement('balance',  $newbalance);

            $statusTrade = $request->has('isOrder') ? 0 : 1;

            // if ($user->AllowOrdersBeyondHighLow == 0 && $statusTrade == 0) {
            //     $existTrade = MarketBidMaster::where('UserId', $user->id)->exist();
            //     if (!$existTrade) {
            //         $interPrice = $request->input('tblfcbuyprice');
            //         $highPrice = $request->input('lblHigh');
            //         $lowPrice = $request->input('lblLow');

            //         if ($interPrice > $lowPrice && $interPrice < $highPrice) {
            //             return response()->json(['error' => 'Allow Fresh Entry Order above high & below low']);
            //         }
            //     }
            // }
            //     if ($user->AllowOrdersBetweenHighLow == 0 && $statusTrade == 0) {
            //     $existTrade = MarketBidMaster::where('UserId', $user->id)->exist();
            //     if (!$existTrade) {
            //         $interPrice = $request->input('tblfcbuyprice');
            //         $highPrice = $request->input('lblHigh');
            //         $lowPrice = $request->input('lblLow');

            //         if ($interPrice < $lowPrice && $interPrice > $highPrice) {
            //             return response()->json(['error' => 'Allow Fresh Entry Order above high & below low']);
            //         }
            //     }
            // }
            $lotSize = $this->getLotValume($symbol);
            $data = [
                'trade_id' => rand(10000000, 99999999),
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
                'quentity' => $request->has('qty') ? $request->qty : $lotSize,
                'OpenInterest' => $request->input('lblOpenInterest', 0),
                'BidQty' => $request->input('lblBidQty', 0),
                'AskQty' => $request->input('lblAskQty', 0),
                'PrevClose' => $request->input('lblPrevClose', 0),
                'UpperCircuit' => $request->input('lblUpperCircuit', 0),
                'LowerCircuit' => $request->input('lblLowerCircuit', 0),
                'OPTION' => 'I',
                'UserId' => $user->id,
                'Symbol' => $request->input('Symbol'),
                'Min' => $request->input('Min'),
                'Mega' => $request->input('Mega'),
                'IsMin' => $request->input('Min') == 'True' ? 'Y' : 'N',
                'Lots' => $lots,
                'Price' => $request->input('Price', 0),
                'IpAddress' => $ip,
                'Isactive' => $request->has('isOrder') ? 0 : 1,
                'IsOrder' => $request->has('isOrder') ? true : false,
                'holding_margin_req' => $holdingmargin * $lots,
                'used_margin_req' => $usedmargin * $lots,
                'brokrage' => $brokrage,
                'activeConditions' => $request->activeConditions ?? null,
                'nse_type' => (stripos($request->input('Symbol'), 'NIFTY') !== false) ? 'INDEX' : null,
                'is_qty' => $request->has('qty') ? 1 : 0,
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s')

            ];
            // dd('hello',$data);
            MarketBidMaster::insert($data);

            $buyPrice = $lots *  $request->input('tblfcbuyprice', 0);
            $broker = DB::table('adminlogin')->where('PK_ID', $user->broker_id)->first();

            $brokerName = $broker ? $broker->Name : null;
            if ($request->has('isOrder')) {
                $msg = $user->Username . " (" . $user->user_id . ") " . $request->input('Mode')
                    . " Order of " . $lots
                    . " lots of " . $SymbolShortName
                    . " order placed successfully at " . $request->input('tblfcbuyprice', 0);
            } else {
                $msg = $user->Username . " (" . $user->user_id . ") " . $request->input('Mode')
                    . " Order of " . $lots
                    . " lots of " . $SymbolShortName
                    . " executed successfully at " . $request->input('tblfcbuyprice', 0);
            }

            $charge =  $request->input('tblfcbuyprice', 0);
            $this->trnsectionDeatil($user->id, $msg, 'SELL brokerage charge Rs.', $charge, 0);

            // $this->trnsectionDeatil($user->id, $msg, $request->input('Mode') . ' Executed Successfully', $buyPrice, 0);

            return response()->json(['message' => 'Transaction saved successfully']);
        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getLine(), $e->getFile());
            return response()->json(['error' => 'Failed to save transaction: ' . $e->getMessage()], 500);
        }
    }

    public function __new_saveTransaction(Request $request)
    {
        if (!Auth::guard('tradeuser')->check()) {
            return response()->json(['error' => '❌ Session has been expaired...'], 500);
            return redirect('/login');
        }

        $user = Auth::guard('tradeuser')->user();
        $exchange = explode(':', $request->input('Symbol'))[0];

        // account blocked
        if ($user->IsActive != 1) {
            return response()->json(['error' => '❌ You Account is Blocked '], 500);
        }
        // store trade from admin
        if ($user->StopTrade == 1) {
            return response()->json(['error' => '❌ You Trader has been Stoped from admin..'], 500);
        }


        $trade = $this->saveTransactionNew($request);
        if ($trade) {
            return response()->json(['message' => 'Transaction saved successfully']);
        } else {
            return response()->json(['error' => 'Failed to save transaction:' . $trade]);
        }
    }

    public function saveTransactionNew($request)
    {


        $user = $request->has('userid') ? TradeUser::with('bidamount')->where('id', $request->userid)->first() : Auth::guard('tradeuser')->user();

        // $user = TradeUser::with('bidamount')->where('id',$request->userid)->first();
        $exchange = explode(':', $request->input('Symbol'))[0];
        // dd($user);
        // account blocked
        // check intdra timing

        // if ($tradingTime != '') {
        //     return response()->json(['error' => $tradingTime], 500);
        // }
        // check lot size

        // $allowLotSize = $this->checkLotSize($request, $exchange, $user);
        // if ($allowLotSize != '') {
        //     return response()->json(['error' => $allowLotSize], 500);
        // }


        $symbol = $request->input('Symbol');

        // lot wise valume
        if ($request->has('qty') == true) {
            $lotSize = $request->qty;
        } else {
            $lotSize = $this->getLotValume($symbol);
        }

        $mode = $request->input('Mode') == 'BUY' ? 'SELL' : 'BUY';
        $symbol = $request->input('Symbol');
        $sellPrice = $request->input('tblfcbuyprice', 0);
        $existTrade = MarketBidMaster::where('Mode', $mode)->where('Symbol', $symbol)
            ->where('UserId', $user->id)
            ->where('Isactive', 1)->first();
        //    dd($existTrade);

        if ($existTrade) {
            if (!$request->has('isOrder')) {
                $checked = $this->closeBulkTradesExist($existTrade, $sellPrice);
                //  if ($checked) {
                return response()->json(['message' => $request->input('Mode') . ' All Ready Exist and then Closed Successfully']);
                //  }
                // now closed tradder coder here.............
            }
        }


        //  check margin avilable
        $lotMargin = $this->checkMarginAvilabel($request, $symbol, $user);

        if ($user->balance < $lotMargin['need_balance']) {
            return response()->json(['error' => 'Your available balance is: ' . $user->balance . ' Fund Needed: ' . $lotMargin['need_balance'] . ' used margin: ' . $lotMargin['used_margin']], 500);
        }

        try {

            // $user = $request->has('userid') ? DB::table('tradeuser')->where('id', $request->userid)->first() : Auth::guard('tradeuser')->user();

            $ip = $request->header('X_FORWARDED_FOR');
            if ($ip) {
                $ip = explode(',', $ip)[0];
            } else {
                $ip = $request->ip();
            }
            // $buyPrice = $request->input('tblfcbuyprice', 0) * $request->input('lblBidQty', 1);

            // its per turnhove basic for teature
            $perratRate = $request->input('tblfcbuyprice', 0);
            // $lots = ($request->Lots) * 100;

            // $perratRate = $request->input('tblfcbuyprice', 0);
            // $lots = ($request->Lots) * 100;

            // $holdingmargin = ($perratRate * $lots) / $tradeUser->MCXHoldingMargin;
            // $usedmargin = ($perratRate * $lots) / $tradeUser->MCXIntradayMargin;
            $SymbolShortName = ForexOption::where('Symbol', $symbol)->value('SymbolShortName');
            $transctionMode = $request->input('TransactionMode');
            if ($request->has('qty') == true) {
                $calLots = $lotSize / $request->qty;
            } else {
                $calLots = $request->Lots;
            }
            // dd('hello',$lotSize, $transctionMode, $request->Lots, $request->input('tblfcbuyprice', 0));
            $marginAvilabe = $this->getMarginCheckAvilable($user, $SymbolShortName, $lotSize, $transctionMode, $calLots, $request->input('tblfcbuyprice', 0), $symbol);

            $adminCtrl = new AdminController();
            $m2mbalance = $adminCtrl->dashboardLivePl('admin', $user->id);

            $userBalance = $user->balance;

            if ($userBalance < $marginAvilabe['need_balance']) {
                return response()->json(['error' => 'Your available balance is: ' . $user->balance . ' Fund Needed: ' . $marginAvilabe['need_balance'] . ' used margin: ' . $marginAvilabe['used_margin']], 500);
            }

            $mode = $request->input('Mode') == 'BUY' ? 'SELL' : 'BUY';
            $symbol = $request->input('Symbol');

            // margin intraday
            // $marginAvilabe = $this->getMarginCheckAvilable($user, $SymbolShortName, $lotSize, $transctionMode,$request->Lots);

            $usedmargin = $marginAvilabe['intraday'];
            $holdingmargin = $marginAvilabe['holding'];

            // brokrage changes

            $saleAmount = ($lotSize * $request->Lots) *  $request->input('tblfcsellprice', 0);
            $amount = ($lotSize * $request->Lots) * $perratRate;
            $brokrage = $this->brokarageCharge($amount, $saleAmount, $user, $request->input('Symbol'));

            // $newbalance = $request->tblfcbuyprice*$request->Lots;
            // $tradeUser->decrement('balance',  $newbalance);

            $data = [
                'trade_id' => rand(10000000, 99999999),
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
                'Lots' => $request->input('Lots', 1),
                'Price' => $request->input('Price', 0),
                'IpAddress' => '127.0.0.1',
                'Isactive' => $request->has('isOrder') ? 0 : 1,
                'IsOrder' => $request->has('isOrder') ? true : false,
                'holding_margin_req' => $holdingmargin * $request->input('Lots', 1),
                'used_margin_req' => $usedmargin * $request->input('Lots', 1),
                'brokrage' => $brokrage,
                'Bought_by' => 'Admin',
                'created_at' => now(),
                'updated_at' => now()

            ];

            MarketBidMaster::insert($data);
            // $buyPrice = ($lotSize * $request->Lots) *  $request->input('tblfcbuyprice', 0);
            $buyPrice = $request->Lots *  $request->input('tblfcbuyprice', 0);
            $broker = DB::table('adminlogin')->where('PK_ID', $user->broker_id)->first();
            $msg = $user->Username . "(" . $user->user_id . ") " . $request->input('Mode') . " Order of ." . $request->input('Lots', 1) . " lots of " . $SymbolShortName . " executed successfully at " . $request->input('tblfcbuyprice', 0);
            $this->trnsectionDeatil($user->id, $msg, $request->input('Mode') . ' Executed Successfully', '', 0);
            return response()->json(['success' => 'Trade created Successfully']);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return $e->getMessage();
        }
    }

    public function saveTransactionAdmin___old($request)
    {
        $user = TradeUser::with('bidamount')->where('id', $request->userid)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 500);
        }

        // if ($request->tblfcbuyprice == 0) {
        //     return response()->json(['error' => '❌ You Can not trade becouse hit the curcit.'], 500);
        // }

        if ($request->TransactionMode === 'OPTIONS') {
            $request->merge(['TransactionMode' => 'NSE']);
        }

        // if ($user->IsActive != 1) {
        //     return response()->json(['error' => '❌ You Account is Blocked '], 500);
        // }

        // if ($user->StopTrade == 1) {
        //     return response()->json(['error' => '❌ You Trader has been Stoped from admin..'], 500);
        // }

        $symbol = $request->input('Symbol');
        $exchange = explode(':', $symbol)[0];

        // ORDER VALIDATION
        $amount = $request->input('tblfcbuyprice', 0);
        // if ($request->has('isOrder')) {
        // $heightPrice = $request->input('lblHigh', 0);
        // $lowPrice = $request->input('lblLow', 0);

        // if ($user->AllowOrdersBetweenHighLow == 1 && $user->AllowOrdersBeyondHighLow == 0) {
        //     if ($amount < $lowPrice || $amount > $heightPrice) {
        //         return response()->json(['error' => 'Order price must be between Height and Low...'], 500);
        //     }
        // }

        // if ($user->AllowOrdersBeyondHighLow == 1 && $user->AllowOrdersBetweenHighLow == 0) {
        //     if ($amount > $lowPrice && $amount < $heightPrice) {
        //         return response()->json(['error' => 'Order price must be beyond Height and Low...'], 500);
        //     }
        // }

        // if ($user->AllowOrdersBeyondHighLow == 0 && $user->AllowOrdersBetweenHighLow == 0) {
        //     return response()->json(['error' => 'you can not Order...'], 500);
        // }
        // }

        // TRADING TIME CHECK
        // $tradingTime = $this->getTradeTime($request->input('TransactionMode'));
        // if ($tradingTime) {
        //     return response()->json(['error' => 'You can not trade becouse market has been closed'], 500);
        // }

        // LOT CALCULATION
        $lotSize = $this->getLotValume($symbol);

        if ($request->has('qty')) {
            $lots = $request->input('qty', 1) / $lotSize;
        } else {
            $lots = $request->input('Lots', 1);
        }

        $SymbolShortName = ForexOption::where('Symbol', $symbol)->value('SymbolShortName');
        $transctionMode = $request->input('TransactionMode');


        if ($request->input('Min') == 1) {
            if ($SymbolShortName == 'GOLD' || $SymbolShortName == 'COPPER' || $SymbolShortName == 'CRUDEOIL') {
                $lot = $request->input('Lots', 1);
                $partTen = $lotSize/10;
                $lots = $partTen * $lot / $lotSize;
                $lotSize = $partTen;
            }

            if ($SymbolShortName == 'SILVER') {

                $lot = $request->input('Lots', 1);
                $lots = 5 * $lot / $lotSize;
                $lotSize = 5;
            }
        }

        $marginAvilabe = $this->getMarginCheckAvilable(
            $user,
            $SymbolShortName,
            $lotSize,
            $transctionMode,
            $lots,
            $request->input('tblfcbuyprice', 0),
            $symbol
        );

        $mode = $request->input('Mode') == 'BUY' ? 'SELL' : 'BUY';

        $usedmargin = $marginAvilabe['intraday'];
        $holdingmargin = $marginAvilabe['holding'];

        // EXISTING TRADE (QTY)
        if ($request->has('qty') && !$request->has('isOrder')) {

            $existTrade = MarketBidMaster::where('Mode', $mode)
                ->where('Symbol', $symbol)
                ->where('UserId', $user->id)
                ->where('is_qty', 1)
                ->where('Isactive', 1)
                ->first();

            if ($existTrade) {
                $checked = $this->checkQtyExist($request, $existTrade, $marginAvilabe, $lots);

                if ($checked['error'] == false) {
                    return response()->json(['message' => $request->input('Mode') . ' Already exists and closed']);
                } else {
                    return response()->json(['message' => $checked['message']]);
                }
            }
        }

        // EXISTING TRADE (NORMAL)
        $existTrade = MarketBidMaster::where('Mode', $mode)
            ->where('Symbol', $symbol)
            ->where('UserId', $user->id)
            ->where('Isactive', 1)
            ->first();

        if (!$request->has('isOrder') && $existTrade) {

            $sellPrice = $request->input('tblfcbuyprice', 0);
            $checked = $this->closeBulkTradesExist($existTrade, $sellPrice);

            if ($checked['error'] == false) {
                return response()->json(['message' => $request->input('Mode') . ' Already exists and closed']);
            } else {
                return response()->json(['message' => $checked['message']]);
            }
        }

        // BALANCE CHECK WITH PL
        $adminCtrl = new AdminController();
        $m2mbalance = $adminCtrl->dashboardLivePl('admin', $user->id);
        $data = json_decode($m2mbalance->getContent(), true);
        $pl = array_sum(array_column($data, 'pl')) ?? 0;
        $clearBalance = $user->balance + $pl;

        // if ($clearBalance < $marginAvilabe['need_balance']) {
        //     return response()->json(['error' => 'Margin not Available...'], 500);
        // }

        // LOT SIZE VALIDATION
        // $allowLotSize = $this->checkLotSize($request, $exchange, $user);
        // if ($allowLotSize != '') {
        //     return response()->json(['error' => $allowLotSize], 500);
        // }

        try {

            $ip = $request->header('X_FORWARDED_FOR');
            $ip = $ip ? explode(',', $ip)[0] : $request->ip();

            $perratRate = $request->input('tblfcbuyprice', 0);

            $saleAmount = ($lotSize * $lots) * $request->input('tblfcsellprice', 0);
            $amount = ($lotSize * $lots) * $perratRate;

            $brokrage = $this->brokarageCharge($amount, $saleAmount, $user, $symbol);
            $lotSize = $this->getLotValume($symbol);
            $data = [
                'trade_id' => rand(10000000, 99999999),
                'Mode' => $request->input('Mode'),
                'ToAmount' => $request->input('textfclot', 0),
                'TransactionMode' => $request->input('TransactionMode'),
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
                'quentity' => $request->has('qty') ? $request->qty : $lotSize,
                'OpenInterest' => $request->input('lblOpenInterest', 0),
                'BidQty' => $request->input('lblBidQty', 0),
                'AskQty' => $request->input('lblAskQty', 0),
                'PrevClose' => $request->input('lblPrevClose', 0),
                'UpperCircuit' => $request->input('lblUpperCircuit', 0),
                'LowerCircuit' => $request->input('lblLowerCircuit', 0),
                'OPTION' => 'I',
                'UserId' => $user->id,
                'Symbol' => $symbol,
                'Min' => $request->input('min_mega') == '1' ? 1 : 0,
                'Mega' => $request->input('min_mega') == '1' ? 0 : 1,
                'IsMin' => $request->input('min_mega') == '1' ? 'Y' : 'N',
                'Lots' => $lots,
                'Price' => $request->input('Price', 0),
                'IpAddress' => '127.0.0.1',
                'Isactive' => $request->has('isOrder') ? 0 : 1,
                'IsOrder' => $request->has('isOrder'),
                'holding_margin_req' => $holdingmargin * $lots,
                'used_margin_req' => $usedmargin * $lots,
                'brokrage' => $brokrage,
                'activeConditions' => $request->activeConditions ?? null,
                'nse_type' => (stripos($symbol, 'NIFTY') !== false) ? 'INDEX' : null,
                'is_qty' => $request->has('qty') ? 1 : 0,
                'Bought_by' => 'Admin',
                'created_at' => now(),
                'updated_at' => now()
            ];

            MarketBidMaster::insert($data);

            $msg = $user->Username . " (" . $user->user_id . ") " . $request->input('Mode')
                . " Order of " . $lots . " lots of " . $SymbolShortName
                . " executed successfully at " . $request->input('tblfcbuyprice', 0);

            $charge = $request->input('tblfcbuyprice', 0);
            $this->trnsectionDeatil($user->id, $msg, 'SELL brokerage charge Rs.', $charge, 0);

            return response()->json(['message' => 'Transaction saved successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed: ' . $e->getMessage()], 500);
        }
    }

      public function saveTransactionAdmin($request)
    {
        $user = TradeUser::with('bidamount')->where('id', $request->userid)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 500);
        }

        // if ($request->tblfcbuyprice == 0) {
        //     return response()->json(['error' => '❌ You Can not trade becouse hit the curcit.'], 500);
        // }

        if ($request->TransactionMode === 'OPTIONS') {
            $request->merge(['TransactionMode' => 'NSE']);
        }

        // if ($user->IsActive != 1) {
        //     return response()->json(['error' => '❌ You Account is Blocked '], 500);
        // }

        // if ($user->StopTrade == 1) {
        //     return response()->json(['error' => '❌ You Trader has been Stoped from admin..'], 500);
        // }

        $symbol = $request->input('Symbol');
        $exchange = explode(':', $symbol)[0];

        // ORDER VALIDATION
        $amount = $request->input('tblfcbuyprice', 0);
        // if ($request->has('isOrder')) {
        // $heightPrice = $request->input('lblHigh', 0);
        // $lowPrice = $request->input('lblLow', 0);

        // if ($user->AllowOrdersBetweenHighLow == 1 && $user->AllowOrdersBeyondHighLow == 0) {
        //     if ($amount < $lowPrice || $amount > $heightPrice) {
        //         return response()->json(['error' => 'Order price must be between Height and Low...'], 500);
        //     }
        // }

        // if ($user->AllowOrdersBeyondHighLow == 1 && $user->AllowOrdersBetweenHighLow == 0) {
        //     if ($amount > $lowPrice && $amount < $heightPrice) {
        //         return response()->json(['error' => 'Order price must be beyond Height and Low...'], 500);
        //     }
        // }

        // if ($user->AllowOrdersBeyondHighLow == 0 && $user->AllowOrdersBetweenHighLow == 0) {
        //     return response()->json(['error' => 'you can not Order...'], 500);
        // }
        // }

        // TRADING TIME CHECK
        // $tradingTime = $this->getTradeTime($request->input('TransactionMode'));
        // if ($tradingTime) {
        //     return response()->json(['error' => 'You can not trade becouse market has been closed'], 500);
        // }

        // LOT CALCULATION
        $lotSize = $this->getLotValume($symbol);

        if ($request->has('qty')) {
            $lots = $request->input('qty', 1) / $lotSize;
        } else {
            $lots = $request->input('Lots', 1);
        }

        $SymbolShortName = ForexOption::where('Symbol', $symbol)->value('SymbolShortName');
        $transctionMode = $request->input('TransactionMode');


        if ($request->input('Min') == 1) {
                if ($SymbolShortName == 'GOLD' || $SymbolShortName == 'COPPER' || $SymbolShortName == 'CRUDEOIL') {
                $lot = $request->input('Lots', 1);
                $partTen = $lotSize/10;
                $lots = $partTen * $lot / $lotSize;
                $lotSize = $partTen;
            }

            if ($SymbolShortName == 'SILVER') {

                $lot = $request->input('Lots', 1);
                $lots = 5 * $lot / $lotSize;
                $lotSize = 5;
            }
        }

        $marginAvilabe = $this->getMarginCheckAvilable(
            $user,
            $SymbolShortName,
            $lotSize,
            $transctionMode,
            $lots,
            $request->input('tblfcbuyprice', 0),
            $symbol
        );

        $mode = $request->input('Mode') == 'BUY' ? 'SELL' : 'BUY';

        $usedmargin = $marginAvilabe['intraday'];
        $holdingmargin = $marginAvilabe['holding'];
        // dd($request->all(), $request->has('qty') == true && $request->has('isOrder') == false);

        if ($request->has('qty') == true && $request->has('isOrder') == false && $request->IsActive==1 ) {

            $existTrade = MarketBidMaster::where('Mode', $mode)->where('Symbol', $symbol)
                ->where('UserId', $user->id)
                ->where('is_qty', 1)
                ->where('Isactive', 1)->first();
            if ($existTrade) {

                $checked =  $this->checkQtyExist($request, $existTrade, $marginAvilabe, $lots);
                if ($checked['error'] == false) {
                    return response()->json(['message' => $request->input('Mode') . 'Already exists and then closed successfully']);
                } else {
                    return response()->json(['message' => $checked['message']]);
                }
            }
        }

        $existTrade = MarketBidMaster::where('Mode', $mode)->where('Symbol', $symbol)
            ->where('UserId', $user->id)
            ->where('Isactive', 1)->first();

         
        if ($request->has('isOrder') == false && $request->IsActive==1) {
            $checked = '';
            
            if ($existTrade) {
                $inputLots =  $lots;

                if ($existTrade->Lots > $inputLots) {
                    
                    $existTrade->Lots = $existTrade->Lots - $lots;
                    $existTrade->save();

                    $newData1 = $existTrade->toArray();
                    // dd($newData1);
                    unset($newData1['Pk_id']);

                    $newData1['Lots'] = $lots;
                    $newData1['trade_id'] = rand(10000000, 99999999);

                    $newData1['holding_margin_req'] = $holdingmargin * $lots;
                    $newData1['used_margin_req'] = $usedmargin * $lots;
                    $newData1['Timestamp'] = Carbon::parse($newData1['Timestamp'])
                        ->format('Y-m-d H:i:s');
                    $newData1['created_at'] = Carbon::parse($newData1['created_at'])
                        ->format('Y-m-d H:i:s');
                    $newData1['updated_at'] = now()->format('Y-m-d H:i:s');

                    $insertId = DB::table('marketbidmaster')->insertGetId($newData1);

                    // get inserted data
                    $existTrade1 = DB::table('marketbidmaster')
                        ->where('Pk_id', $insertId)
                        ->first();

                    $sellPrice = $request->input('tblfcbuyprice', 0);
                    $checked = $this->closeBulkTradesExist($existTrade1, $sellPrice);
                } else if ($existTrade->Lots < $inputLots) {

                    $existLost = $inputLots - $existTrade->Lots;
                   $newData = $existTrade->toArray();

                unset($newData['Pk_id']);

                $newData['Lots'] = $existLost;
                $newData['SalePrice'] = null;
                $newData['holding_margin_req'] = $holdingmargin * $existLost;
                $newData['used_margin_req'] = $usedmargin * $existLost;
                $newData['BuyPrice'] = $request->input('tblfcbuyprice', 0);
                $newData['trade_id'] = rand(10000000, 99999999);
                $newData['Mode'] = $request->input('Mode');

                $newData['Timestamp'] = now()->format('Y-m-d H:i:s');

                $newData['created_at'] = now()->format('Y-m-d H:i:s');
                $newData['updated_at'] = now()->format('Y-m-d H:i:s');

                $insertId = DB::table('marketbidmaster')->insertGetId($newData);
                    // MarketBidMaster::create($newData);
                    $sellPrice = $request->input('tblfcbuyprice', 0);
                    $checked = $this->closeBulkTradesExist($existTrade, $sellPrice);
                } else {
                    $sellPrice = $request->input('tblfcbuyprice', 0);
                    $checked = $this->closeBulkTradesExist($existTrade, $sellPrice);
                }

                if ($checked['error'] == false) {
                    return response()->json(['message' => $request->input('Mode') . 'Already exists and then closed successfully']);
                } else {
                    return response()->json(['message' => $checked['message']]);
                }
                // now closed tradder coder here.............
            }
        }

        // BALANCE CHECK WITH PL
        $adminCtrl = new AdminController();
        $m2mbalance = $adminCtrl->dashboardLivePl('admin', $user->id);
        $data = json_decode($m2mbalance->getContent(), true);
        $pl = array_sum(array_column($data, 'pl')) ?? 0;
        $clearBalance = $user->balance + $pl;

        // if ($clearBalance < $marginAvilabe['need_balance']) {
        //     return response()->json(['error' => 'Margin not Available...'], 500);
        // }

        // LOT SIZE VALIDATION
        // $allowLotSize = $this->checkLotSize($request, $exchange, $user);
        // if ($allowLotSize != '') {
        //     return response()->json(['error' => $allowLotSize], 500);
        // }

        try {

            $ip = $request->header('X_FORWARDED_FOR');
            $ip = $ip ? explode(',', $ip)[0] : $request->ip();

            $perratRate = $request->input('tblfcbuyprice', 0);

            $saleAmount = ($lotSize * $lots) * $request->input('tblfcsellprice', 0);
            $amount = ($lotSize * $lots) * $perratRate;

            $brokrage = $this->brokarageCharge($amount, $saleAmount, $user, $symbol);
            $lotSize = $this->getLotValume($symbol);

            $data = [
                'trade_id' => rand(10000000, 99999999),
                'Mode' => $request->input('Mode'),
                'ToAmount' => $request->input('textfclot', 0),
                'TransactionMode' => $request->input('TransactionMode'),
                'BuyPrice' => $request->input('tblfcbuyprice', 0),
                'SalePrice' => $request->input('tblfcsellprice', 0),
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
                'quentity' => $request->has('qty') ? $request->qty : $lotSize,
                'OpenInterest' => $request->input('lblOpenInterest', 0),
                'BidQty' => $request->input('lblBidQty', 0),
                'AskQty' => $request->input('lblAskQty', 0),
                'PrevClose' => $request->input('lblPrevClose', 0),
                'UpperCircuit' => $request->input('lblUpperCircuit', 0),
                'LowerCircuit' => $request->input('lblLowerCircuit', 0),
                'OPTION' => 'I',
                'UserId' => $user->id,
                'Symbol' => $symbol,
                'Min' => $request->input('min_mega') == '1' ? 1 : 0,
                'Mega' => $request->input('min_mega') == '1' ? 0 : 1,
                'IsMin' => $request->input('min_mega') == '1' ? 'Y' : 'N',
                'Lots' => $lots,
                'Price' => $request->input('Price', 0),
                'IpAddress' => '127.0.0.1',
                'Isactive' => $request->IsActive,
                'IsOrder' => $request->has('isOrder'),
                'holding_margin_req' => $holdingmargin * $lots,
                'used_margin_req' => $usedmargin * $lots,
                'brokrage' => $brokrage,
                'activeConditions' => $request->activeConditions ?? null,
                'nse_type' => (stripos($symbol, 'NIFTY') !== false) ? 'INDEX' : null,
                'is_qty' => $request->has('qty') ? 1 : 0,
                'Bought_by' => 'Admin',
                'created_at' => now(),
                'updated_at' => now()
            ];
            if ($request->IsActiv == 2) {
            $data['IpAddress'] = $ipAddrss;
            $data['sell_ip_address'] = $ipAddrss;
            }


            MarketBidMaster::insert($data);

            $msg = $user->Username . " (" . $user->user_id . ") " . $request->input('Mode')
                . " Order of " . $lots . " lots of " . $SymbolShortName
                . " executed successfully at " . $request->input('tblfcbuyprice', 0);

            $charge = $request->input('tblfcbuyprice', 0);
            $this->trnsectionDeatil($user->id, $msg, 'SELL brokerage charge Rs.', $charge, 0);

            return response()->json(['message' => 'Transaction saved successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed: ' . $e->getMessage()], 500);
        }
    }


    public function getLotValume($symbol)
    {
        $lotSize = DB::table('forexoptions')
            ->join(
                'lot_size',
                DB::raw("CONVERT(lot_size.name USING utf8mb4) COLLATE utf8mb4_unicode_ci"),
                '=',
                DB::raw("CONVERT(forexoptions.SymbolShortName USING utf8mb4) COLLATE utf8mb4_unicode_ci")
            )
            ->where('forexoptions.Symbol', $symbol)
            ->pluck('lot_size.qty')
            ->first();

        if (!$lotSize) {
            $lotSize = DB::table('forexoptions')
                ->where('Symbol', $symbol)
                ->value('LotSize');
        }

        return (int) $lotSize;
    }

    // check lot size
    public function checkLotSize($request, $exchange, $user)
    {
        $isQty = $request->has('qty') ? $request->qty : false;

        $lots = $request->input('Lots', 1);
        $symbol = $request->input('Symbol');
        $mcx_min_lot  = $user->MCXMinLotPerTrade;
        $mcx_max_lot_at_time = $user->MCXMaxLotPerTrade;
        $mcx_max_lot_size = $user->MCXMaxLotPerScrip;
        $mcx_all = $user->MaxCommodityLots;
        // dd($mcx_min_lot,$mcx_max_lot_at_time,$mcx_max_lot_size);

        $nse_min_lot  = $user->NSEFuturesMinLotPerTrade;
        $nse_max_lot_at_time = $user->NSEFuturesMaxLotPerTrade;
        $nse_max_lot_size = $user->NSEFuturesMaxLotPerScrip;
        $nse_all = $user->MaxNSEFuturesLots;
        //   dd($user->NSEFuturesMaxLotPerTrade);

        $nse_min_lot_index  = $user->NSEIndexMinLotPerTrade;
        $nse_max_lot_at_time_index = $user->NSEIndexMaxLotPerTrade;
        $nse_max_lot_size_index = $user->NSEIndexMaxLotPerScrip;
        $nse_all_index = $user->MaxNSEIndexLots;


        $bid_lots = DB::table('marketbidmaster')->whereIn('Isactive', [0, 1])
            ->where('deleted_at', null)
            ->where('Symbol', 'like', $request->input('Symbol') . '%')
            ->whereIn('Isactive', [0, 1])
            ->where('UserId', $user->id)->sum('Lots');

        $message = '';

        if ($exchange == 'MCX') {
            $bid_lots_all = DB::table('marketbidmaster')->whereIn('Isactive', [0, 1])
                ->where('deleted_at', null)
                ->where('TransactionMode', "MCX")
                ->whereIn('Isactive', [0, 1])
                ->where('UserId', $user->id)->sum('Lots');

            if ($mcx_min_lot >  $lots) {
                $message = "❌ You can't take Lot Size less then " . $mcx_min_lot . ': ' . $symbol;
            } else if ($lots  > $mcx_max_lot_at_time) {
                $message =  "❌ Maximum lot size allowed per single trade of MCX " . $mcx_max_lot_at_time . ": " . $symbol;
            } else if ($mcx_max_lot_size  < $bid_lots + $lots) {
                $message =  "❌ Maximum lot size allowed per script of MCX to be actively open at a time: " . $mcx_max_lot_size . ': ' . $symbol;
            } else if ($mcx_all < $bid_lots_all + $lots) {
                $message =  "❌ Max Size All Commodity " . $mcx_all . ' ' . $symbol;
            }
        }

        if ($exchange == 'NSE') {


            if (stripos($request->input('Symbol'), 'NIFTY') !== false) {


                if ($isQty != false) {

                    $lotSize = $this->getLotValume($symbol);

                    $qtySize = $request->input('qty', 1);
                    $bid_lots_allNseIndex = DB::table('marketbidmaster')->whereIn('Isactive', [0, 1])
                        ->where('deleted_at', null)
                        ->where('TransactionMode', "NSE")
                        ->whereIn('Isactive', [0, 1])
                        ->where('nse_type', 'INDEX')
                        ->where('UserId', $user->id)->sum('quentity');


                    $nse_min_lot_index = $nse_min_lot_index * $lotSize;
                    $nse_max_lot_at_time_index = $nse_max_lot_at_time_index * $lotSize;
                    $nse_max_lot_size_index = $nse_max_lot_size_index * $lotSize;
                    $nse_all_index = $nse_all_index * $lotSize;

                    if ($nse_min_lot_index >  $qtySize) {
                        $message = "❌ Minimum Quantity size required per single trade of Equity " . $nse_min_lot_index . ': ' . $symbol;
                    } else if ($qtySize > $nse_max_lot_at_time_index) {
                        $message =  "❌ Maximum Quantity size allowed per single trade of Equity " . $nse_max_lot_at_time_index . ': ' . $symbol;
                    } else if ($nse_max_lot_size_index  < $bid_lots + $qtySize) {
                        $message =  "❌ Maximum Quantity size allowed per single trade of Equity INDEX " . $nse_max_lot_size_index . ': ' . $symbol;
                    } else if ($nse_all_index < $bid_lots_allNseIndex + $qtySize) {
                        $message =  "❌ Max Size All Equity " . $nse_all . ': ' . $symbol;
                    }
                    return $message;
                }

                $bid_lots_allNseIndex = DB::table('marketbidmaster')->whereIn('Isactive', [0, 1])
                    ->where('deleted_at', null)
                    ->where('TransactionMode', "NSE")
                    ->whereIn('Isactive', [0, 1])
                    ->where('nse_type', 'INDEX')
                    ->where('UserId', $user->id)->sum('Lots');

                if ($nse_min_lot_index >  $lots) {
                    $message = "❌ Minimum lots size required per single trade of Equity INDEX " . $nse_min_lot_index . ': ' . $symbol;
                } else if ($lots  > $nse_max_lot_at_time_index) {
                    $message =  "❌ Maximum lots size allowed per single trade of Equity INDEX " . $nse_max_lot_at_time_index . ': ' . $symbol;
                } else if ($nse_max_lot_size_index  < $bid_lots + $lots) {
                    $message =  "❌ Maximum lots size allowed per scrip of Equity INDEX to be actively open at a time " . $nse_max_lot_size_index . ': ' . $symbol;
                } else if ($nse_all_index < $bid_lots_allNseIndex + $lots) {
                    $message =  "❌ Max Size All Index " . $nse_all_index . ' ' . $symbol;
                }
            } else {


                if ($isQty != false) {

                    $lotSize = $this->getLotValume($symbol);

                    $qtySize = $request->input('qty', 1);
                    $bid_lots_allNse = DB::table('marketbidmaster')->whereIn('Isactive', [0, 1])
                        ->where('deleted_at', null)
                        ->where('TransactionMode', "NSE")
                        ->whereIn('Isactive', [0, 1])
                        ->where('UserId', $user->id)->sum('quentity');

                    $nse_min_lot = $nse_min_lot * $lotSize;
                    $nse_max_lot_at_time = $nse_max_lot_at_time * $lotSize;
                    $nse_max_lot_size = $nse_max_lot_size * $lotSize;
                    $nse_all = $nse_all * $lotSize;


                    // dd($nse_all,$lotSize,$bid_lots_allNse,$qtySize);

                    if ($nse_min_lot >  $qtySize) {
                        $message = "❌ Minimum Quantity size required per single trade of Equity " . $nse_min_lot . ': ' . $symbol;
                    } else if ($qtySize > $nse_max_lot_at_time) {
                        $message =  "❌ Maximum Quantity size allowed per single trade of Equity " . $nse_max_lot_at_time . ': ' . $symbol;
                    } else if ($nse_max_lot_size  < $bid_lots + $qtySize) {
                        $message =  "❌ Maximum Quantity size allowed per single trade of Equity INDEX " . $nse_max_lot_size . ': ' . $symbol;
                    } else if ($nse_all < $bid_lots_allNse + $qtySize) {
                        $message =  "❌ Max Size All Equity " . $nse_all . ': ' . $symbol;
                    }
                    return $message;
                }

                $bid_lots_allNse = DB::table('marketbidmaster')->whereIn('Isactive', [0, 1])
                    ->where('deleted_at', null)
                    ->where('TransactionMode', "NSE")
                    ->whereIn('Isactive', [0, 1])
                    ->where('nse_type', null)
                    ->where('UserId', $user->id)->sum('Lots');

                if ($nse_min_lot >  $lots) {
                    $message = "❌ Minimum lots size required per single trade of Equity " . $nse_min_lot . ': ' . $symbol;
                } else if ($lots > $nse_max_lot_at_time) {
                    $message =  "❌ Maximum lots size allowed per single trade of Equity " . $nse_max_lot_at_time . ': ' . $symbol;
                } else if ($nse_max_lot_size  < $bid_lots + $lots) {
                    $message =  "❌ Maximum lots size allowed per single trade of Equity INDEX " . $nse_max_lot_size . ': ' . $symbol;
                } else if ($nse_all < $bid_lots_allNse + $lots) {

                    $message =  "❌ Max Size All Equity " . $nse_all . ': ' . $symbol;
                }
            }
        }
        return $message;
    }


    public function checkMarginAvilabel($request, $symbol, $user)
    {
        $SymbolShortName = ForexOption::where('Symbol', $symbol)->value('SymbolShortName');
        $mcxLotMargin = json_decode($user->MCXLotMarginJSON, true) ?? [];


        $now = Carbon::now();
        $startIntraday = Carbon::today()->setTime(9, 30);
        $endIntraday = Carbon::today()->setTime(18, 30);
        $lotMargin = 0;
        $usedMargin = 0;
        // dd($request);
        if ($request->TransactionMode == 'MCX') {

            if ($now->between($startIntraday, $endIntraday)) {

                $lotMargin = $mcxLotMargin[$SymbolShortName]['INTRADAY'] ?? 0;

                $usedMargin = $user->bidamount()->where('Isactive', 1)->sum('used_margin_req');
            } else {
                $lotMargin += $mcxLotMargin[$SymbolShortName]['HOLDING'] ?? 0;
                $usedMargin += $user->bidamount()->where('Isactive', 1)->sum('holding_margin_req');
            }
        }

        return ['need_balance' => ($lotMargin * $request->Lots) + $usedMargin, 'used_margin' => $usedMargin];
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
    
//     public function setDeviceId(Request $request)
// {
//     $deviceId = $request->query('device_id');
//     $loggedIn = $request->query('logiedIn');

//     if (!$deviceId) {

//         return response()->json([
//             'status' => false,
//             'message' => 'Device ID missing'
//         ], 400);
//     }

//     /*
//     |--------------------------------------------------------------------------
//     | AUTO LOGIN CASE
//     |--------------------------------------------------------------------------
//     | When app opens first time
//     | logiedIn parameter NOT passed
//     */

//     if (!$loggedIn) {

//         $user = TradeUser::where('device_id', $deviceId)->first();

//         if ($user) {

//             Auth::guard('tradeuser')->login($user);

//             return response()->json([
//                 'status' => true,
//                 'auto_login' => true,
//                 'redirect' => url('/dashboard')
//             ]);
//         }

//         return response()->json([
//             'status' => false
//         ]);
//     }

//     /*
//     |--------------------------------------------------------------------------
//     | CHECK ACTIVE SESSION CASE
//     |--------------------------------------------------------------------------
//     | Runs on every page every 10 sec
//     */

//     $user = Auth::guard('tradeuser')->user();

//     // session expired
//     if (!$user) {

//         return response()->json([
//             'force_logout' => true
//         ]);
//     }

//     // another device login detected
//     if ($user->device_id != $deviceId) {

//         Auth::guard('tradeuser')->logout();

//         $request->session()->invalidate();
//         $request->session()->regenerateToken();

//         return response()->json([
//             'force_logout' => true
//         ]);
//     }

//     // current device valid
//     return response()->json([
//         'status' => true
//     ]);
// }
    public function setDeviceId(Request $request)
    {
        $deviceId = $request->query('device_id');
        $segment2 = $request->query('logiedIn');

        if (!$deviceId) {
            return response()->json(['error' => 'Device ID missing'], 400);
        }

        $user = TradeUser::where('device_id', $deviceId)->first();

        if ($user) {
            // Restore authentication if session has expired or is logged in as a different user
            if (!Auth::guard('tradeuser')->check() || Auth::guard('tradeuser')->user()->id !== $user->id) {
                Auth::guard('tradeuser')->login($user);
            }

            if (!$segment2) {
                return response()->json([
                    'device_id' => $deviceId,
                    'redirect' => url('/dashboard'),
                    'csrf_token' => csrf_token(),
                ]);
            } else {
                return response()->json([
                    'device_id' => $deviceId,
                    'redirect' => null,
                    'csrf_token' => csrf_token(),
                ]);
            }
        } else {

            if ($segment2 == 1) {

                // ✅ Force logout
                Auth::guard('tradeuser')->logout();

                // ✅ Clear session
                // $request->session()->invalidate();
                // $request->session()->regenerateToken();

                return response()->json([
                    'device_id' => $deviceId,
                    'redirect'  => url('/login')
                ]);
            }
        }
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
                $user->device_id = $request->device_id;
                $user->save();
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


    public function Deletetrade($id)
    {
        MarketBidMaster::where('Pk_id', $id)->delete();
        return back()->with('success', 'Trade Cancelled successfully!');
    }
    public function __Updatetrade(Request $request)
    {

        $request->validate([
            'trade_id' => 'required',
            'buy_price' => 'required',
            'lots' => 'required',
            'high_price' => 'required|numeric',
            'low_price'  => 'required|numeric',
            'buy_price'  => 'required|numeric',
        ]);


        $high = $request->high_price;
        $low  = $request->low_price;
        $buy  = $request->buy_price;

        if (!($buy > $high || $buy < $low)) {
            return back()->withErrors([
                'buy_price' => 'Buy price must be greater than High price OR lower than Low price.'
            ]);
        }

        $lotSize = $this->getLotValume($request->symbol);

        $symbolData = ForexOption::where('Symbol', $request->symbol)->first();


        $SymbolShortName = $symbolData->SymbolShortName;
        $transactionmode = $request->transactionmode;
        $user = Auth::guard('tradeuser')->user();

        $marginAvilabe = $this->getMarginCheckAvilable($user, $SymbolShortName, $lotSize, $transactionmode, $request->Lots, $buy, $request->symbol);
        $trade = MarketBidMaster::withTrashed()->find($request->trade_id);

        if ($trade) {
            $trade->BuyPrice = $request->buy_price;
            $trade->Lots =  $request->lots;
            $trade->holding_margin_req = $marginAvilabe['intraday'] ?? 0 * $trade->lots;
            $trade->used_margin_req = $marginAvilabe['holding'] ?? 0 * $trade->lots;
            $trade->restore();
        }

        return back()->with('success', 'Trade updated successfully!');
    }
    public function Updatetrade(Request $request)
    {
        $request->validate([
            'trade_id'   => 'required',
            'lots'       => 'required',
            'high_price' => 'required|numeric',
            'low_price'  => 'required|numeric',
            'buy_price'  => 'required|numeric',
        ]);
        $user = Auth::guard('tradeuser')->user();

        $heightPrice = $request->high_price;
        $lowPrice  = $request->low_price;
        $amount  = $request->buy_price;
        $buy =  $request->buy_price;
        // if (!($buy > $high || $buy < $low)) {
        //     return back()->withErrors([
        //         'buy_price' => 'Buy price must be greater than High price OR lower than Low price.'
        //     ]);
        // }

        if ($user->AllowOrdersBetweenHighLow == 1 && $user->AllowOrdersBeyondHighLow == 0) {

            //   dd($amount,$heightPrice,$lowPrice, $amount < $lowPrice || $amount > $heightPrice);
            if ($amount < $lowPrice || $amount > $heightPrice) {
                return response()->json(['error' => 'Order price must be between Height and Low...'], 500);
            }
        }
        if ($user->AllowOrdersBeyondHighLow == 1 && $user->AllowOrdersBetweenHighLow == 0) {
            // dd($amount > $lowPrice && $amount < $heightPrice,$amount , $lowPrice ,$heightPrice);
            if ($amount > $lowPrice && $amount < $heightPrice) {
                return response()->json(['error' => 'Order price must be beyond Height and Low...'], 500);
            }
        }


        // if ($user->AllowOrdersBetweenHighLow == 1) {
        //     //   dd($amount,$heightPrice,$lowPrice, $amount < $lowPrice || $amount > $heightPrice);
        //     if ($amount < $lowPrice || $amount > $heightPrice) {
        //         return back()->withErrors([
        //             'buy_price' => 'Order price must be between Height and Low...'
        //         ]);
        //     }
        // } else {
        //     if ($amount > $lowPrice || $amount < $heightPrice) {
        //         return back()->withErrors([
        //             'buy_price' => 'Order price must be beyond Height and Low...'
        //         ]);
        //     }
        // }

        $lotSize    = $this->getLotValume($request->symbol);
        $symbolData = ForexOption::where('Symbol', $request->symbol)->first();

        $SymbolShortName = $symbolData->SymbolShortName;
        $transactionmode = $request->transactionmode;

        $userBalance = $user->balance;

        $marginAvilabe = $this->getMarginCheckAvilable(
            $user,
            $SymbolShortName,
            $lotSize,
            $transactionmode,
            $request->lots,
            $buy,
            $request->symbol
        );

        if ($userBalance < $marginAvilabe['need_balance']) {
            return back()->with('error', 'Your available balance is: ' . $user->balance . ' Fund Needed: ' . $marginAvilabe['need_balance'] . ' used margin: ' . $marginAvilabe['used_margin']);
        }

        $trade = MarketBidMaster::withTrashed()->find($request->trade_id);

        if ($trade) {
            $trade->BuyPrice          = $buy;
            $trade->Lots              = $request->lots;
            $trade->holding_margin_req = ($marginAvilabe['holding']  ?? 0) * $request->lots;
            $trade->used_margin_req    = ($marginAvilabe['intraday'] ?? 0) * $request->lots;
            $trade->restore();
            $trade->save();
        }

        return back()->with('success', 'Trade updated successfully!');
    }
    public function getPendingTrades()
    {
        try {
            $startOfWeek = Carbon::now()->startOfWeek(); // Monday
            $endOfWeek   = Carbon::now()->endOfWeek();   // Sunday

            $pendingTrades = MarketBidMaster::withTrashed()->select(
                'Pk_id as id',
                'Isactive',
                'Mode',
                'Symbol',
                'Lots',
                'used_margin_req',
                'holding_margin_req',
                'BuyPrice',
                'SalePrice',
                'TransactionMode',
                'quentity',
                'is_qty',
                'LotSize',
                'is_execute',
                'created_at',
                'updated_at',
                'deleted_at'
            )
                // ->where('Isactive', 0)
                ->where('IsOrder', 1)
                ->where('UserId', Auth::guard('tradeuser')->user()->id)
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])

                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($item) {
                    $item->isTimeUp = $this->getTradeTime($item->TransactionMode,$item->Symbol);
                    $item->created_at = Carbon::parse($item->created_at)->format('n/j/Y g:i:s A');
                    return $item;
                });

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

    // public function getTradeTime($mode,$symbol=null)
    // {

    //     $currentDate = Carbon::now();
    //     $formattedDate = $currentDate->format('Y-m-d');

    //     // Check Saturday (6) or Sunday (0)
    //     if ($currentDate->dayOfWeek == Carbon::SATURDAY || $currentDate->dayOfWeek == Carbon::SUNDAY) {
    //         return true;
    //     }

    //     // Check DB Holiday
    //       if (substr($symbol, -2) === "CE" || substr($symbol, -2) === "PE") {
    //         $isHoliday = MarketCalendar::where('type', 'HOLIDAY')
    //         ->where('market', 'OPTOPNS')
    //         ->whereDate('holiday_date', $formattedDate)
    //         ->exists();
    //       }

    //     $isHoliday = MarketCalendar::where('type', 'HOLIDAY')
    //         ->where('market', $mode)
    //         ->whereDate('holiday_date', $formattedDate)
    //         ->exists();

    //     if ($isHoliday) {
    //         return true;
    //     }

    //  if (substr($symbol, -2) === "CE" || substr($symbol, -2) === "PE") {
    //         $times = MarketCalendar::where('type', 'TIME')
    //         ->where('market', "OPTIONS")
    //         ->select('start_time', 'end_time')
    //         ->get();
    //       }else{
    //           $times = MarketCalendar::where('type', 'TIME')
    //               ->where('market', $mode)
    //               ->select('start_time', 'end_time')
    //               ->get();
    //       }



    //     $currentTime = Carbon::now()->format('H:i:s');

    //     $isBetween = true;
    //     foreach ($times as $time) {
    //         if ($currentTime >= $time->start_time && $currentTime <= $time->end_time) {
    //             $isBetween = false;
    //             break;
    //         }
    //     }

    //     return $isBetween;
    // }
    public function getTradeTime($mode, $symbol = null)
    {
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->format('Y-m-d');
        $currentTime = $currentDate->format('H:i:s');

        // Check Saturday or Sunday
        if (
            $currentDate->dayOfWeek == Carbon::SATURDAY ||
            $currentDate->dayOfWeek == Carbon::SUNDAY
        ) {
            return true;
        }

        // Check if symbol is OPTION (CE / PE)
        $isOption = false;

        if ($symbol) {
            $suffix = substr($symbol, -2);

            if ($suffix === 'CE' || $suffix === 'PE') {
                $isOption = true;
            }
        }

        // Set market name
        $market = $isOption ? 'OPTIONS' : $mode;

        // Check Holiday
        $isHoliday = MarketCalendar::where('type', 'HOLIDAY')
            ->where('market', $market)
            ->whereDate('holiday_date', $formattedDate)
            ->exists();
       
        if ($isHoliday) {
            return true;
        }

        // Get Trading Times
        $times = MarketCalendar::where('type', 'TIME')
            ->where('market', $market)
            ->select('start_time', 'end_time')
            ->get();
        
        // Check current time between market timing
        foreach ($times as $time) {

            if (
                $currentTime >= $time->start_time &&
                $currentTime <= $time->end_time
            ) {
                return false; // Market Open
            }
        }

        return true; // Market Closed
    }

    /**
     * Get active trades data
     */
    public function getActiveTrades()
    {
        try {

            $activeTrades = MarketBidMaster::select(
                'Mode',
                'Symbol',
                'Lots',
                'used_margin_req',
                'holding_margin_req',
                'BuyPrice',
                'SalePrice',
                'sold_by',
                'Bought_by',
                'is_qty',
                'quentity',
                'LotSize',
                'created_at',
                'updated_at'
            )
                ->where('Isactive', 1)
                ->where('UserId', Auth::guard('tradeuser')->user()->id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($item) {
                    $item->created_at = Carbon::parse($item->created_at)->format('n/j/Y g:i:s A');
                    $item->updated_at = Carbon::parse($item->updated_at)->format('n/j/Y g:i:s A');
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
    public function getActiveTradesPortfolio()
    {
        try {
            // $activeTrades = MarketBidMaster::where('Isactive', 1)
            //     ->where('UserId', Auth::guard('tradeuser')->user()->id)
            //     ->orderBy('timestamp', 'desc')
            //     ->get()
            //     ->map(function ($item) {
            //         $item->timestamp = Carbon::parse($item->Timestamp)->format('n/j/Y g:i:s A');
            //         return $item;
            //     });

            $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
            $endOfWeek   = Carbon::now()->endOfWeek(Carbon::SUNDAY);

            $timing = DB::table('market_calendar')->where('market', 'MCX')->where('type', 'TIME')->first();
            $currentTime = Carbon::now()->format('H:i:s');

            if ($currentTime >= $timing->start_time && $currentTime <= $timing->end_time) {
                $marginColumn = 'used_margin_req';
            } else {
                $marginColumn = 'holding_margin_req';
            }



            $activeTrades = MarketBidMaster::select(
                'Mode',
                'Symbol',
                'is_qty',
                DB::raw('SUM(CASE WHEN is_qty = 1 THEN quentity ELSE Lots END) as Lots'),
                DB::raw('SUM(' . $marginColumn . ') as used_margin_req'),
                DB::raw('SUM(holding_margin_req) as holding_margin_req'),
                DB::raw('MAX(created_at) as created_at'),
                DB::raw('ROUND(SUM(BuyPrice * Lots) / SUM(Lots), 2) as BuyPrice'),
                DB::raw('MAX(Timestamp) as Timestamp'),
                DB::raw('MAX(Pk_id) as Pk_id'),
                DB::raw('MAX(updated_at) as updated_at')  // Add this

            )
                ->where('Isactive', 1)
                ->where('UserId', Auth::guard('tradeuser')->user()->id)
                // ->whereBetween('updated_at', [$startOfWeek, $endOfWeek]) // ✅ current week filter

                ->groupBy('Symbol', 'Mode', 'is_qty')
                ->orderBy('updated_at', 'desc')
                ->get()
                ->map(function ($item) {
                    $item->created_at = Carbon::parse($item->created_at)->format('n/j/Y g:i:s A');
                    return $item;
                });

            // dd($activeTrades ,MarketBidMaster::where('UserId', Auth::guard('tradeuser')->user()->id)->where('Isactive', 1)->get());

            //      $activeTrades = MarketBidMaster::select(
            //         'Mode',
            //         'Symbol',
            //         'Lots',
            //         'used_margin_req',
            //         'holding_margin_req',
            //         'BuyPrice',
            //         'SalePrice',
            //         'created_at',
            //         'updated_at'
            //     )
            //     ->where('Isactive', 1)
            //     ->where('UserId', Auth::guard('tradeuser')->user()->id)
            //     ->orderBy('created_at', 'desc')
            //     ->get()
            // ->map(function ($item) {
            //     $item->created_at = Carbon::parse($item->created_at)->format('n/j/Y g:i:s A');
            //     $item->updated_at = Carbon::parse($item->updated_at)->format('n/j/Y g:i:s A');
            //     return $item;
            // });



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

            $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
            $endOfWeek   = Carbon::now()->endOfWeek(Carbon::SUNDAY);

            $closedTrades = MarketBidMaster::select(
                'Mode',
                'Symbol',
                'Lots',
                'used_margin_req',
                'holding_margin_req',
                'LotSize',
                'BuyPrice',
                'SalePrice',
                'brokrage',
                'sold_by',
                'quentity',
                'is_qty',
                'LotSize',
                'Bought_by',
                'created_at',
                'updated_at'
            )
                ->where('Isactive', 2)
                ->where('UserId', Auth::guard('tradeuser')->user()->id)
                ->whereBetween('updated_at', [$startOfWeek, $endOfWeek]) // ✅ current week filter
                ->orderBy('updated_at', 'desc')
                ->get()
                ->map(function ($item) {
                    $item->created_at = Carbon::parse($item->created_at)->format('n/j/Y g:i:s A');
                    $item->updated_at = Carbon::parse($item->updated_at)->format('n/j/Y g:i:s A');
                    return $item;
                });

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
            if ($user->StopTrade == 1) {
                $errors[] = 'Your Trade has been Stoped from Admin..';
            }

            if (!$request->has('bulk')) {


                $sybmols[] = MarketBidMaster::where('Isactive', 1)->where('Symbol', $exchangeType)->value('Symbol');
            } else {

                $closedCount = MarketBidMaster::where('Isactive', 1)
                    // ->orWhere('Isactive', 0)
                    ->where('UserId', Auth::guard('tradeuser')->user()->id)
                    ->where('Symbol', 'like', $exchangeType . '%')->get();

                foreach ($closedCount as $val) {
                    $sybmols[] = $val->Symbol;
                }
            }

            $apiResponse = $this->fetchCorrentData($sybmols);

            if (isset($apiResponse['error']) && $apiResponse['error'] === true) {

                return response()->json(
                    ['error' => $apiResponse['message']],
                    500
                );
            }

            $correntData = $apiResponse['d'];

            foreach ($correntData as $data) {
                $symbol = $data['n'];

                $trade = MarketBidMaster::where('Isactive', 1)
                    // ->orWhere('Isactive', 0)
                    ->where('Symbol', $data['n'])
                    ->where('UserId', Auth::guard('tradeuser')->user()->id)
                    ->first();

                // tarading timinng or holidays......


                $tradingTime = $this->getTradeTime($trade->TransactionMode,$trade->Symbol);

                if ($tradingTime) {
                    $errors[] = 'Can not close this trade becouse market has been closed';
                    continue;
                }

                $createdDate = Carbon::parse($trade->created_at);
                $interval = (int) $user->ProfitBookInterval;
                $targetTime = $createdDate->copy()->addMinutes($interval);

                if (!Carbon::now()->greaterThanOrEqualTo($targetTime)) {
                    $errors[] = "Can't Close: $symbol before {$user->ProfitBookInterval} minutes";
                    continue;
                }

                $bid = $data['v']['bid'];
                $ask = $data['v']['ask'];

                if (empty($sellPrice) || $sellPrice == 0) {
                    $priceData = $this->getBidAskPrice($symbol, $symbol);
                    $bid = $priceData['bid'];
                    $ask = $priceData['ask'];

                    // $errors[] = 'Your can not close trade...';
                    // continue;
                }

                if ($trade->Mode == 'BUY') {
                    $sellPrice =  $bid;
                } else {
                    $sellPrice =  $ask;
                }

                if ($trade) {

                    $exchange = explode(":", $symbol)[0];
                    $TradeUser = TradeUser::find($user->id);


                    // else if ($exchange == 'MCX') {
                    //     // if ($TradeUser->mcx_brokerage_type == 'per_crore') {
                    //     $TradeUser->decrement('balance', $TradeUser->MCXBrokerage);
                    //     // MCXBrokerage
                    //     $this->trnsectionDeatil($user->id, 'SELL brokerage charge', 'SELL brokerage charge', $TradeUser->MCXBrokerage, 0);
                    //     // }
                    // } 
                    // else if ($exchange == 'NSE') {
                    //     $TradeUser->decrement('balance', $TradeUser->NSEFuturesBrokerage);
                    //     $this->trnsectionDeatil($user->id, 'SELL brokerage charge', 'SELL brokerage charge', $TradeUser->NSEFuturesBrokerage, 0);
                    // }

                    $TradeUser->save();
                    // this is valume of lots 
                    $lotValumeSize = $this->getLotValume($symbol);

                    $lotValume = $lotValumeSize * $trade->Lots;

                    $salePrice = $sellPrice * $lotValume;
                    $buyPrice = $trade->BuyPrice * $lotValume;


                    // dd($valumePA);
                    if ($trade->Mode == 'SELL') {
                        // dd('sell');
                        $valumePA = $buyPrice - $salePrice;
                    } else {
                        $valumePA = $salePrice - $buyPrice;
                    }

                    // dd($valumePA,$sellPrice,$buyPrice,$salePrice,$trade->Mode,$valumePA,$buyPrice - $salePrice);
                    // $netBalance = $sellPrice * $trade->Lots;
                    $trade->used_margin_req = 0;
                    $trade->used_margin_req = 0;
                    $trade->updated_at = now();

                    // if (($valumePA + $TradeUser->balance) <= 0) {
                    //     $errors[] = "You have not sufficient balance";
                    //     continue;
                    // }

                    if ($trade->Bought_by == 'Admin') {
                        $totBrokrage = $this->brokarageCharge(0, $salePrice, $user, $symbol);
                    } else {
                        $totBrokrage = $this->brokarageCharge($buyPrice, $salePrice, $user, $symbol);
                    }

                    $TradeUser->increment('balance', $valumePA);
                    $TradeUser->increment('net_p_l', $valumePA);
                    $TradeUser->brokrage = $totBrokrage;
                    $trade->brokrage =     $totBrokrage;

                    // options config
                    if (substr($symbol, -2) === "CE" || substr($symbol, -2) === "PE") {
                        $this->optionConfig($symbol, $user, $trade);
                    } else {
                        $charge =  $totBrokrage;
                        $TradeUser->decrement('balance',  $charge);
                        //          $buyPrice = $request->Lots *  $request->input('tblfcbuyprice', 0);
                        $broker = DB::table('adminlogin')->where('PK_ID', $TradeUser->broker_id)->first();

                        if ($broker == null) {
                            $broker = DB::table('adminlogin')->where('PK_ID', 1)->first();
                        }
                        $msg = $TradeUser->Username . "(" . $TradeUser->user_id . ") " . $trade->Mode . " order of " . $trade->Lots . " lots of " . $symbol . " brokerage charge Rs." . $charge;
                        // $this->trnsectionDeatil($user->id, $msg, 'SELL brokerage charge Rs.', $charge, 0);
                    }

                    MarketBidMaster::where('Pk_id', $trade->Pk_id)
                        ->update([
                            'Isactive' => 2,
                            'SalePrice' => $sellPrice,
                            'sell_ip_address' => $request->ip()
                        ]);

                    $TradeUser->save();
                    $trade->save();

                    $symbolShort  = explode(':', $symbol)[1] ?? $symbol;
                    $msg = $TradeUser->Username . "(" . $TradeUser->user_id . ") "
                        . (($trade->Mode == 'BUY') ? 'SELL' : 'BUY')
                        . " order of " . $trade->Lots . " lots of " . $symbolShort
                        . " closed Successfully Price . " . $sellPrice . " (Exit)";
                    $this->trnsectionDeatil($user->id, $msg, 'Profit / Loss Price', $sellPrice, 1);
                }
            }
            if (!empty($errors)) {
                // return response()->json(['success' => false, 'message' => $errors], 500);
                return response()->json([
                    'success' => false,
                    'message' => $errors,
                ]);
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

    public function closeBulkTradesExist($request, $sellPrice, $isAdmin = false)
    {
        try {

            $exchangeType = $request->Pk_id;

            $allowedTypes = ['MCX', 'NSE', 'COMEX'];
            $sybmols = [];
            $user = Auth::guard('tradeuser')->user() ?? TradeUser::with('bidamount')->where('id', $request->UserId)->first();;


            if ($user->IsActive == 0) {
                return response()->json(['error' => 'You Account is Blocked'], 500);
            }

            if (is_numeric($exchangeType)) {
                $sybmols[] = MarketBidMaster::where('Pk_id', $exchangeType)->value('Symbol');
            } else {

                $closedCount = MarketBidMaster::where('Isactive', 1)
                    ->orWhere('Isactive', 0)
                    ->where('UserId', $user->id)
                    ->where('Symbol', 'like', $exchangeType . '%')->get();

                foreach ($closedCount as $val) {
                    $sybmols[] = $val->Symbol;
                }
            }

            // Bypass Fyers API entirely since price is passed directly as $sellPrice
            $correntData = [];
            foreach ($sybmols as $sym) {
                if ($sym) {
                    $correntData[] = ['n' => $sym];
                }
            }

            foreach ($correntData as $data) {
                $symbol = $data['n'];

                $user_id = $isAdmin ? $request->UserId : Auth::guard('tradeuser')->user()->id;
                if ($isAdmin) {
                    $trade = $request;
                } else {
                    if (is_numeric($exchangeType)) {
                        $trade = MarketBidMaster::where('Pk_id', $exchangeType)->first();
                    } else {
                        $trade = MarketBidMaster::where('Isactive', 1)
                            // ->orWhere('Isactive', 0)
                            ->where('Symbol', $data['n'])
                            ->where('UserId',  $user_id)
                            ->latest()
                            ->first();
                    }
                }

                // tarading timinng ......

                // $tradingTime = $this->getTradeTime($trade->TransactionMode,$trade->Symbol);

                // if ($tradingTime) {
                //     $errors[] = 'Can not close this trade becouse market has been closed';
                //     continue;
                // }

                $createdDate = Carbon::parse($trade->created_at);
                $interval = (int) $user->ProfitBookInterval;
                $targetTime = $createdDate->copy()->addMinutes($interval);

                // if (!Carbon::now()->greaterThanOrEqualTo($targetTime)) {
                //     $errors[] = "Can't Close: $symbol before {$user->ProfitBookInterval} minutes";
                //     continue;
                // }

                // if ($trade->Mode == 'BUY') {
                //     $sellPrice =  $data['v']['bid'];
                // } else {
                //     $sellPrice =  $data['v']['ask'];
                // }
                if ($trade) {

                    $exchange = explode(":", $symbol)[0];
                    $TradeUser = TradeUser::find($user->id);

                    // else if ($exchange == 'MCX') {
                    //     // if ($TradeUser->mcx_brokerage_type == 'per_crore') {
                    //     $TradeUser->decrement('balance', $TradeUser->MCXBrokerage);
                    //     // MCXBrokerage
                    //     $this->trnsectionDeatil($user->id, 'SELL brokerage charge', 'SELL brokerage charge', $TradeUser->MCXBrokerage, 0);
                    //     // }
                    // } 
                    // else if ($exchange == 'NSE') {
                    //     $TradeUser->decrement('balance', $TradeUser->NSEFuturesBrokerage);
                    //     $this->trnsectionDeatil($user->id, 'SELL brokerage charge', 'SELL brokerage charge', $TradeUser->NSEFuturesBrokerage, 0);
                    // }

                    $TradeUser->save();
                    // this is valume of lots 
                    $lotValumeSize = $this->getLotValume($symbol);

                    $lotValume = $lotValumeSize * $trade->Lots;

                    $salePrice = $sellPrice * $lotValume;
                    $buyPrice = $trade->BuyPrice * $lotValume;
                    // $valumePA = $salePrice - $buyPrice;

                    if ($trade->Mode == 'SELL') {
                        // dd('sell');
                        $valumePA = $buyPrice - $salePrice;
                    } else {
                        $valumePA = $salePrice - $buyPrice;
                    }
                    // $netBalance = $sellPrice * $trade->Lots;

                    $trade->used_margin_req = 0;
                    $trade->used_margin_req = 0;
                    $trade->updated_at = now();

                    $TradeUser->increment('balance', $valumePA);
                    $TradeUser->increment('net_p_l', $valumePA);

                    $TradeUser->brokrage = $this->brokarageCharge($buyPrice, $salePrice, $user, $symbol);
                    $trade->brokrage =    $this->brokarageCharge($buyPrice, $salePrice, $user, $symbol);
                    // options config
                    $broker = DB::table('adminlogin')->where('PK_ID', $TradeUser->broker_id)->first()
                        ?? DB::table('adminlogin')->where('PK_ID', 1)->first();

                    if (substr($symbol, -2) === "CE" || substr($symbol, -2) === "PE") {

                        $this->optionConfig($symbol, $user, $trade);
                    } else {

                        $charge =  $this->brokarageCharge($buyPrice, $salePrice, $user, $symbol);

                        $TradeUser->decrement('balance',  $charge);
                        $symbolShort  = explode(':', $symbol)[1] ?? $symbol;
                        $msg = $TradeUser->Username . "( " . $TradeUser->user_id . ") " . $trade->Mode . " order of " . $trade->Lots . " lots of " . $symbolShort . " closed Successfully Rs. " . $sellPrice;

                        $this->trnsectionDeatil($user->id, $msg, 'Profit / Loss Price', $valumePA, 1);
                    }

                    MarketBidMaster::where('Pk_id', $trade->Pk_id)
                        ->update([
                            'Isactive' => 2,
                            'SalePrice' => $sellPrice,
                            'sell_ip_address' => $isAdmin ? '127.0.0.1' : request()->getClientIp(),
                            'sold_by' => $isAdmin ? 'Admin' : 'Trader',
                        ]);

                    $TradeUser->save();
                    $trade->save();

                    // $this->trnsectionDeatil($user->id, 'SELL Profit / Loss', 'Profit / Loss Price', $valumePA, 1);
                }
            }
            if (!empty($errors)) {
                return [
                    'error'   => true,
                    'message' => $errors
                ];
            } else {
                return [
                    'error'   => true,
                    'message' => 'successfully'
                ];
            }
        } catch (\Exception $e) {
            return [
                'error'   => true,
                'message' => $e->getMessage()
            ];
        }
    }

    public function closeSingle($request, $idBrokage = false, $closePrice)
    {
        try {

            $user = TradeUser::find($request->UserId);

            $symbol = $request->Symbol;
            $trade = MarketBidMaster::where('Pk_id', $request->Pk_id)->first();

            // dd($trade);
            if (!$trade) {
                Log::warning('closeSingle: No active trade found', [
                    'symbol'  => $symbol,
                    'user_id' => $user->id,
                ]);
                return ['error' => true, 'message' => 'No active trade found for symbol: ' . $symbol];
            }
            // Determine sell price based on trade mode
            $responseApi = $this->getMarketData($symbol);
            // $sellPrice = $trade->Mode === 'BUY' ? $responseApi['data']['bid_price'] : $responseApi['data']['ask_price'];
            $sellPrice = $closePrice;
            $TradeUser = TradeUser::find($user->id);

            if (!$TradeUser) {
                Log::error('closeSingle: TradeUser not found', [
                    'user_id' => $user->id,
                    'symbol'  => $symbol,
                ]);
                return ['error' => true, 'message' => 'Trade user not found.'];
            }

            $lotVolume = $this->getLotValume($symbol) * $trade->Lots;
            $salePrice = $sellPrice * $lotVolume;
            $buyPrice  = $trade->BuyPrice * $lotVolume;

            // Calculate P&L
            $pnl = $trade->Mode === 'SELL'
                ? $buyPrice - $salePrice
                : $salePrice - $buyPrice;

            // Update trade record
            $data = [
                'Isactive'        => 2,
                'SalePrice'       => $sellPrice,
                'sold_by'         => 'Admin',
                'sell_ip_address'  => '127.0.0.1',
                'updated_at'      => now(),
                'created_at'         => Carbon::parse($trade->created_at)
            ];
           if ($idBrokage == false) {
                $data['brokrage'] = 0;
            }
                        
            MarketBidMaster::where('Pk_id', $trade->Pk_id)->update($data);

            // Credit P&L to user balance
            $TradeUser->increment('balance', $pnl);
            $TradeUser->increment('net_p_l', $pnl);

            // Log P&L transaction
            $symbolShort  = explode(':', $symbol)[1] ?? $symbol;
            $this->trnsectionDeatil($user->id, "P&L for {$symbolShort}", 'Profit / Loss Price', $pnl, 1);

            // Apply brokerage if enabled
            if ($idBrokage) {
                $isOption = str_ends_with($symbol, 'CE') || str_ends_with($symbol, 'PE');

                if ($isOption) {
                    $this->optionConfig($symbol, $user, $trade);
                } else {
                    $charge = $this->brokarageCharge($buyPrice, $salePrice, $user, $symbol);

                    if (!$charge && $charge !== 0) {
                        Log::warning('closeSingle: Brokerage charge calculation failed', [
                            'symbol'  => $symbol,
                            'user_id' => $user->id,
                        ]);
                    }

                    $TradeUser->decrement('balance', $charge);
                    $trade->brokrage = $charge;

                    $broker = DB::table('adminlogin')->where('PK_ID', $TradeUser->broker_id)->first()
                        ?? DB::table('adminlogin')->where('PK_ID', 1)->first();

                    if (!$broker) {
                        Log::error('closeSingle: Broker not found', [
                            'broker_id' => $TradeUser->broker_id,
                            'user_id'   => $user->id,
                        ]);
                        return ['error' => true, 'message' => 'Broker not found.'];
                    }
                    $symbolShort  = explode(':', $symbol)[1] ?? $symbol;
                    $msg = "{$TradeUser->Username} ({$TradeUser->user_id}) {$trade->Mode} order of "
                        . "{$trade->Lots} lots of {$symbolShort} brokerage charge Rs.{$charge}";

                    $this->trnsectionDeatil($user->id, $msg, 'SELL brokerage charge Rs.', $charge, 1);
                }

                // $trade->sold_by = 'Admin';
            }

            $TradeUser->save();
            $trade->save();

            $broker = $broker ?? DB::table('adminlogin')->where('PK_ID', $TradeUser->broker_id)->first()
                ?? DB::table('adminlogin')->where('PK_ID', 1)->first();
            $symbolShort  = explode(':', $symbol)[1] ?? $symbol;

            $msg = "{$TradeUser->Username} ({$TradeUser->user_id}) {$trade->Mode} order of "
                . "{$trade->Lots} lots of {$symbolShort} closed successfully Rs.{$salePrice}";

            $this->trnsectionDeatil($user->id, $msg, 'Profit / Loss Price', $pnl, 1);

            Log::info('closeSingle: Trade closed successfully', [
                'symbol'     => $symbol,
                'user_id'    => $user->id,
                'pnl'        => $pnl,
                'sale_price' => $salePrice,
                'trade_id'   => $trade->Pk_id,
            ]);
            // }

            return ['error' => false, 'message' => 'Trade closed successfully'];
        } catch (\Exception $e) {
            Log::error('closeSingle: Unexpected exception', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
                'user_id' => $user->id ?? null,
                'symbol'  => $request->Symbol ?? null,
            ]);

            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    public function optionConfig($symbol, $user, $trade)
    {
        $exchange = explode(':', $symbol)[0];

        $brokerage = 0;

        // NSE Options
        if (
            preg_match('/^NSE:(NIFTY|BANKNIFTY|FINNIFTY)/', $symbol)
        ) {
            $brokerage = $user->OptionsBrokerage;
        }

        // MCX Options
        elseif (
            $exchange === 'MCX' &&
            $user->options_mcx_brokerage_type === 'per_lot'
        ) {
            $brokerage = $user->OptionsMCXBrokerage;
        }

        // Equity Options
        elseif ($user->options_equity_brokerage_type === 'per_lot') {
            $brokerage = $user->OptionsEquityBrokerage;
        }

        // Skip if no brokerage
        if ($brokerage <= 0) {
            return;
        }

        $totalBrokerage = $brokerage * 2;

        // Direct query update (faster than find + save)
        TradeUser::where('id', $user->id)
            ->decrement('balance', $totalBrokerage);

        // Update trade brokerage
        $trade->update([
            'brokrage' => $totalBrokerage
        ]);
    }

    public function trnsectionDeatil($user_id, $remark, $transpage, $amount, $type)
    {

        Transdetail::create([
            'transaction_id' => date('ymdhis'),
            'MemberId'   => $user_id,
            'TransType'  => $transpage,
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

    public function updatePendingOrder(Request $request)
    {

        $request->validate([
            'id' => 'required'
        ]);

        $updated = DB::table('marketbidmaster')->where('trade_id', $request->id)
            ->update([
                'Isactive' => 1
            ]);

        if ($updated === 0) {
            return response()->json([
                'status'  => false,
                'message' => 'Trade not found'
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Pending order updated successfully'
        ], 200);
    }

    public function getMarginCheckAvilable($user, $SymbolShortName, $lotSize, $transctionMode, $lots, $buyprice = null, $symbol = null)
    {
        // dd($symbol);
        $resault = [];
        $needBalance = '';
        $usedMargin = 0;
        $currentTime = Carbon::now()->format('H:i:s');
        // dd(substr($symbol, -2) === "CE" || substr($symbol, -2) === "PE");
        if (substr($symbol, -2) === "CE" || substr($symbol, -2) === "PE") {

            $timing = DB::table('market_calendar')->where('market', 'OPTIONS')->where('type', 'TIME')->first();

            if (preg_match('/^NSE:(NIFTY|BANKNIFTY|FINNIFTY)/', $symbol)) {
                $margin = $user->OptionsIntradayMargin;
                $marginHolding = $user->OptionsHoldingMargin;
                // dd($buyprice , $lotSize , $lots);
                $resault['intraday'] = ($buyprice * $lotSize) / $margin;
                $resault['holding'] = ($buyprice * $lotSize) / $marginHolding;


                if ($currentTime >= $timing->start_time && $currentTime <= $timing->end_time) {

                    $needBalance = $resault['intraday'] * $lots;
                    $usedMargin += $user->bidamount()->whereIn('Isactive', [0, 1])->where(function ($q) {
                        $q->where('Symbol', 'LIKE', '%CE')
                            ->orWhere('Symbol', 'LIKE', '%PE');
                    })->sum('used_margin_req');
                } else {

                    $needBalance = $resault['holding'] * $lots;
                    $usedMargin += $user->bidamount()->whereIn('Isactive', [0, 1])->where(function ($q) {
                        $q->where('Symbol', 'LIKE', '%CE')->orWhere('Symbol', 'LIKE', '%PE');
                    })->sum('holding_margin_req');
                }
            }

            if ($transctionMode == 'MCX' && substr($symbol, -2) === "CE" || substr($symbol, -2) === "PE") {

                $margin = $user->OptionsMCXIntradayMargin;
                $marginHolding = $user->OptionsMCXHoldingMargin;

                $resault['intraday'] = ($buyprice * $lotSize) / $margin;
                $resault['holding'] = ($buyprice * $lotSize) / $marginHolding;

                if ($currentTime >= $timing->start_time && $currentTime <= $timing->end_time) {

                    $needBalance = $resault['intraday'] * $lots;
                    $usedMargin += $user->bidamount()->whereIn('Isactive', [0, 1])->where(function ($q) {
                        $q->where('Symbol', 'LIKE', '%CE')
                            ->orWhere('Symbol', 'LIKE', '%PE');
                    })->sum('used_margin_req');
                } else {

                    $needBalance = $resault['holding'] * $lots;
                    $usedMargin += $user->bidamount()->whereIn('Isactive', [0, 1])->where(function ($q) {
                        $q->where('Symbol', 'LIKE', '%CE')->orWhere('Symbol', 'LIKE', '%PE');
                    })->sum('holding_margin_req');
                }
            }

            if ($transctionMode == 'NSE' && substr($symbol, -2) === "CE" || substr($symbol, -2) === "PE") {

                $margin = $user->OptionsEquityIntradayMargin;
                $marginHolding = $user->OptionsEquityHoldingMargin;

                $resault['intraday'] = ($buyprice * $lotSize) / $margin;
                $resault['holding'] = ($buyprice * $lotSize) / $marginHolding;

                if ($currentTime >= $timing->start_time && $currentTime <= $timing->end_time) {

                    $needBalance = $resault['intraday'] * $lots;
                    $usedMargin += $user->bidamount()->whereIn('Isactive', [0, 1])->where(function ($q) {
                        $q->where('Symbol', 'LIKE', '%CE')
                            ->orWhere('Symbol', 'LIKE', '%PE');
                    })->sum('used_margin_req');
                } else {

                    $needBalance = $resault['holding'] * $lots;
                    $usedMargin += $user->bidamount()->whereIn('Isactive', [0, 1])->where(function ($q) {
                        $q->where('Symbol', 'LIKE', '%CE')->orWhere('Symbol', 'LIKE', '%PE');
                    })->sum('holding_margin_req');
                }
            }

            $resault['need_balance'] = $usedMargin + $needBalance;
            $resault['used_margin'] = $usedMargin;
            return $resault;
        }
        if ($transctionMode == 'MCX') {
            $timing = DB::table('market_calendar')->where('market', 'MCX')->where('type', 'TIME')->first();

            $mcxLotMargin = json_decode($user->MCXLotMarginJSON, true) ?? [];

            if ($user->MCXExposureType == 'per_turnover') {
                $resault['intraday'] = ($buyprice * $lotSize) / $user->MCXIntradayMargin;
                $resault['holding'] = ($buyprice * $lotSize) / $user->MCXHoldingMargin;
            } else {
                $resault['intraday']  = isset($mcxLotMargin[$SymbolShortName]) ? $mcxLotMargin[$SymbolShortName]['INTRADAY'] : 0;
                $resault['holding'] = isset($mcxLotMargin[$SymbolShortName]) ? $mcxLotMargin[$SymbolShortName]['HOLDING'] : 0;
            }

            if ($currentTime >= $timing->start_time && $currentTime <= $timing->end_time) {
                $needBalance = $resault['intraday'] * $lots;
                $usedMargin += $user->bidamount()->where('TransactionMode', 'MCX')->whereIn('Isactive', [0, 1])->sum('used_margin_req');
            } else {
                $needBalance = $resault['holding'] * $lots;
                $usedMargin += $user->bidamount()->where('TransactionMode', 'MCX')->whereIn('Isactive', [0, 1])->sum('holding_margin_req');
            }
        }

        if ($transctionMode == 'NSE') {
            $timing = DB::table('market_calendar')->where('market', 'NSE')->where('type', 'TIME')->first();

            $resault['intraday'] = ($buyprice * $lotSize) / $user->NSEFuturesIntradayMargin;
            $resault['holding'] =  ($buyprice * $lotSize) / $user->NSEFuturesHoldingMargin;
            // dd($buyprice,$timing,$user->balance , $lotSize , $user->NSEFuturesIntradayMargin);
        }
        if ($currentTime >= $timing->start_time && $currentTime <= $timing->end_time) {
            $needBalance = $resault['intraday'] * $lots;
            $usedMargin += $user->bidamount()->where('TransactionMode', 'NSE')->whereIn('Isactive', [0, 1])->sum('used_margin_req');
        } else {
            $needBalance = $resault['holding'] * $lots;
            $usedMargin = $user->bidamount()->where('TransactionMode', 'NSE')->whereIn('Isactive', [0, 1])->sum('holding_margin_req');
        }

        $resault['need_balance'] = $usedMargin + $needBalance;
        $resault['used_margin'] = $usedMargin;

        return $resault;
    }

    public function autoCloeOrder()
    {
        $orderTrades = MarketBidMaster::where('Isactive', 0)->delete();
        Log::info('all orders deleted in min Night...');
    }

    public function updateTrades($user)
    {
        $trades = MarketBidMaster::where('UserId', $user->id)->whereIn('Isactive', [0, 1])->get();

        if ($trades->isEmpty()) {
            return true;
        }

        foreach ($trades as $trade) {
            $newHolding = 0;
            $newUsed    = 0;
            // Get Lot Size
            $lotSize = $this->getLotValume($trade->Symbol);

            // Get Symbol Short Name (Safe Check)
            $symbolData = ForexOption::where('Symbol', $trade->Symbol)->first();
            if (!$symbolData) {
                continue;
            }

            $SymbolShortName = $symbolData->SymbolShortName;
            $transactionMode = $trade->TransactionMode;


            // Margin Calculation
            $marginAvailable = $this->getMarginCheckAvilable($user, $SymbolShortName, $lotSize, $transactionMode, $trade->Lots, $trade->BuyPrice, $trade->Symbol);
            // $newHolding = $marginAvailable['intraday'] ?? 1;
            // $newUsed = $marginAvailable['holding'] ?? 1;

            // Amount Calculations
            $amount = ($lotSize * $trade->Lots) * $trade->BuyPrice;
            $saleAmount = ($lotSize * $trade->Lots) * $trade->tblfcsellprice;

            $brokerage = $this->brokarageCharge($amount, $saleAmount, $user, $trade->Symbol);

            // Update Fields

            $newHolding = round((float)$marginAvailable['holding'] * (float)$trade->Lots, 2);
            $newUsed    = round((float)$marginAvailable['intraday'] * (float)$trade->Lots, 2);

            // dd($newUsed, $marginAvailable['intraday'],(float)$trade->Lots, $trade);

            $tradeds = DB::table('marketbidmaster')
                ->where('Pk_id', $trade->Pk_id)
                ->update([
                    'holding_margin_req' => $newHolding,
                    'used_margin_req'    => $newUsed,
                    'brokrage'           => $brokerage
                ]);
        }

        return true;
    }

    public function editTrade(Request $request)
    {

        $sybmol[] = $request->sybmol;
        $apiResponse = $this->fetchCorrentData($sybmol);

        if (isset($apiResponse['error']) && $apiResponse['error'] === true) {

            return response()->json([
                'status' => false,
                'message' => 'Api not responsing'
            ]);
        }

        return response()->json([
            'status' => true,
            'high_price'   => $apiResponse['d'][0]['v']['high_price'] ?? 0,
            'low_price' => $apiResponse['d'][0]['v']['low_price'] ?? 0
        ]);


        $correntData = $apiResponse['d'];
    }


    public function checkQtyExist($request, $existTrade, $marginAvilabe, $lots)
    {

        $inputQuentity =  $request->input('qty', 1);
        $usedmargin = $marginAvilabe['intraday'];
        $holdingmargin = $marginAvilabe['holding'];
        $qty = $existTrade->quentity;
        // dd($qty > $inputQuentity,$qty , $inputQuentity);
        if ($qty > $inputQuentity) {
            $existTrade->Lots = $existTrade->Lots - $lots;
            $existTrade->quentity = $existTrade->quentity - $request->input('qty', 1);
            $existTrade->save();

            $newData1 = $existTrade->toArray();
            unset($newData1['Pk_id']);
            $newData1['quentity'] = $request->input('qty', 1);
            $newData1['Lots'] = $lots;
            $newData1['trade_id'] = rand(10000000, 99999999);
            $newData1['holding_margin_req'] = $holdingmargin;
            $newData1['used_margin_req'] = $usedmargin;
            $newData1['updated_at'] = now();
            $newData1['created_at'] = Carbon::parse($newData1['created_at'])
                ->format('Y-m-d H:i:s');
            $newData1['updated_at'] = now()->format('Y-m-d H:i:s');

            $insertId = DB::table('marketbidmaster')->insertGetId($newData1);

            // get inserted data
            $existTrade1 = DB::table('marketbidmaster')
                ->where('Pk_id', $insertId)
                ->first();

            // $existTrade1 = MarketBidMaster::create($newData1);
            // dd($existTrade,$existTrade1);
            $sellPrice = $request->input('tblfcbuyprice', 0);
            $checked = $this->closeBulkTradesExist($existTrade1, $sellPrice);
        } else if ($qty < $inputQuentity) {
            // dd($request->input('Mode'));
            $existQty = $inputQuentity - $existTrade->quentity;

            $existLost = $lots - $existTrade->Lots;

            $newData = $existTrade->toArray();
            unset($newData['Pk_id']);
            $newData['quentity'] = $existQty;
            $newData1['Lots'] = $existLost;
            $newData['SalePrice'] = null;
            $newData['holding_margin_req'] = $holdingmargin * $existLost;
            $newData['used_margin_req'] = $usedmargin * $existLost;
            $newData['BuyPrice'] = $request->input('tblfcbuyprice', 0);
            $newData['trade_id'] = rand(10000000, 99999999);
            $newData['Mode'] = $request->input('Mode');
            $newData['updated_at'] = now();
            //    dd($request->input('tblfcbuyprice', 0),$newData,$existTrade);
            MarketBidMaster::create($newData);
            $sellPrice = $request->input('tblfcbuyprice', 0);
            $checked = $this->closeBulkTradesExist($existTrade, $sellPrice);
        } else {
            $sellPrice = $request->input('tblfcbuyprice', 0);
            $checked = $this->closeBulkTradesExist($existTrade, $sellPrice);
        }
        return $checked;
    }

    public function getBidAskPrice($symbol, $tradeSymbol = null)
    {
        if (!empty($symbol) && strpos($symbol, '&') !== false) {
            $symbol = str_replace('&', '%26', $symbol);
        }

        $data = $this->getMarketData($symbol);

        if (isset($data['data']['bid_price'])) {
            return [
                'bid' => $data['data']['bid_price'],
                'ask' => $data['data']['ask_price'],
            ];
        }

        $lastData = DB::table('forexoptions')
            ->where('Symbol', $tradeSymbol ?? $symbol)
            ->first();

        return [
            'bid' => $lastData->bid ?? 0,
            'ask' => $lastData->ask ?? 0,
        ];
    }
}

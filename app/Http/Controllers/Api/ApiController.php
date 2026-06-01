<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MarketBidMaster;
use Illuminate\Support\Facades\Http;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\TryCatch;
use App\Http\Controllers\User\HomeController;
use App\Models\ForexOption;
use App\Models\TradeUser;
use App\Models\DepositeMaster;
use Carbon\Carbon;

class ApiController extends Controller
{
   
    public function updatePendingOrder($id)
    {
        $homeCtrl = new HomeController();

        // 1. Get Order
        $order = MarketBidMaster::where('trade_id', $id)
            ->where('Isactive', 0)
            ->first();

        $dir = public_path('order_log');
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        // File path
        $filePath = $dir . '/' . $order->trade_id . '.txt';

        $logText  = "=============================\n";

        $logText .= "order order id : " . $id . "\n";

        $logText .= "Time: " . now() . "\n";

        if (!$order) {
            $logText .= "Order Not Found\n";
            return response()->json([
                'status' => false,
                'message' => 'Order not found or already active',
            ], 404);
        }

        $mode   = $order->Mode == 'BUY' ? 'SELL' : 'BUY';
        $symbol = $order->Symbol;

        if ($order->is_qty == 1 && $order->IsOrder == 1) {

            $logText .= "$order->is_qty == 1 && $order->IsOrder == 1 condition true,\n";

            $existTrade = MarketBidMaster::where('Mode', $mode)
                ->where('Symbol', $symbol)
                ->where('UserId', $order->UserId)
                ->where('is_qty', 1)
                ->where('Isactive', 1)
                ->first();

            $logText .= "exist:" . $existTrade . ",\n";
            file_put_contents($filePath, $logText, FILE_APPEND);
            if ($existTrade) {

                $user = TradeUser::findOrFail($order->UserId);

                $SymbolShortName = ForexOption::where('Symbol', $symbol)
                    ->value('SymbolShortName');

                $marginAvilabe = $homeCtrl->getMarginCheckAvilable(
                    $user,
                    $SymbolShortName,
                    $order->LotSize,
                    $order->TransactionMode,
                    $order->Lots,
                    $order->BuyPrice
                );

                $checked = $this->checkQtyExist(
                    $order,
                    $existTrade,
                    $marginAvilabe,
                    $order->Lots,
                    $logText
                );

                if ($checked['error'] == false) {

                    file_put_contents($filePath, $logText, FILE_APPEND);
                     $this->markOrderClosed($id);
                    $logText .= $order->Mode . "Already exists and then closed successfully:" . $existTrade . ",\n";
                    return response()->json([
                       
                        'message' => $order->Mode . ' Already exists and then closed successfully'
                    ]);
                }

                return response()->json(['message' => $checked['message']]);
            }

            return $this->activateOrder($id);
        }

        // 3. CASE: Normal Flow
        $existTrade = MarketBidMaster::where('Mode', $mode)
            ->where('Symbol', $symbol)
            ->where('UserId', $order->UserId)
            ->where('is_qty', 0)
            ->where('Isactive', 1)
            ->first();

        if ($order->IsOrder == 1 && $order->is_qty == 0) {
            if ($existTrade) {

                $user = TradeUser::findOrFail($order->UserId);

                $SymbolShortName = ForexOption::where('Symbol', $symbol)
                    ->value('SymbolShortName');

                $marginAvilabe = $homeCtrl->getMarginCheckAvilable(
                    $user,
                    $SymbolShortName,
                    $order->LotSize,
                    $order->TransactionMode,
                    $order->Lots,
                    $order->BuyPrice
                );

                $usedmargin    = $marginAvilabe['intraday'];
                $holdingmargin = $marginAvilabe['holding'];

                $inputLots = $order->Lots;

                // ---- LOT LOGIC ----

                if ($existTrade->Lots > $inputLots) {

                    $existTrade->Lots -= $inputLots;
                    $existTrade->save();

                    $newData = $existTrade->toArray();
                    unset($newData['Pk_id']);

                    $newData['Lots']               = $inputLots;
                    $newData['trade_id']           = rand(10000000, 99999999);
                    $newData['holding_margin_req'] = $holdingmargin * $inputLots;
                    $newData['used_margin_req']    = $usedmargin * $inputLots;
                    $newData['created_at'] = Carbon::parse($newData['created_at'])
                    ->format('Y-m-d H:i:s');
                    $newData['updated_at'] = now()->format('Y-m-d H:i:s');

                    $insertId = DB::table('marketbidmaster')->insertGetId($newData);

                    // get inserted data
                    $existTrade1 = DB::table('marketbidmaster')
                        ->where('Pk_id', $insertId)
                        ->first();

                    // $existTrade1 = MarketBidMaster::create($newData);
                    file_put_contents($filePath, $logText, FILE_APPEND);
                    $checked = $this->closeBulkTradesExist($existTrade1, $order->BuyPrice);
                } elseif ($existTrade->Lots < $inputLots) {

                    $existLost = $inputLots - $existTrade->Lots;

                    $newData = $existTrade->toArray();
                    unset($newData['Pk_id']);

                    $newData['Lots']               = $existLost;
                    $newData['SalePrice']          = null;
                    $newData['holding_margin_req'] = $holdingmargin * $existLost;
                    $newData['used_margin_req']    = $usedmargin * $existLost;
                    $newData['BuyPrice']           = $order->BuyPrice;
                    $newData['trade_id']           = rand(10000000, 99999999);
                    $newData['Mode']               = $order->Mode;

                    MarketBidMaster::create($newData);
                    file_put_contents($filePath, $logText, FILE_APPEND);
                    $checked = $this->closeBulkTradesExist($existTrade, $order->BuyPrice);
                } else {
                    $checked = $this->closeBulkTradesExist($existTrade, $order->BuyPrice);
                }

                if ($checked['error'] == false) {
                    $this->markOrderClosed($id);
                    $logText .= $order->Mode . ' Already exists and then closed successfully';

                    file_put_contents($filePath, $logText, FILE_APPEND);
                    return response()->json([
                        'message' => $order->Mode . ' Already exists and then closed successfully'
                    ]);
                }

                return response()->json(['message' => $checked['message']]);
            }

            return $this->activateOrder($id);
        }

        // 4. Default fallback
        file_put_contents($filePath, $logText, FILE_APPEND);
        return $this->activateOrder($id);
    }


    public function checkQtyExist($request, $existTrade, $marginAvilabe, $lots, $logText)
    {

        $inputQuentity = $request->quentity;
        $usedmargin = $marginAvilabe['intraday'];
        $holdingmargin = $marginAvilabe['holding'];
        $qty = $existTrade->quentity;
        $sellPrice = $request->BuyPrice ?? 0;
        $homeCtrl = new HomeController();

        if ($qty > $inputQuentity) {
            $logText .= "$qty > $inputQuentity condition true,\n";

            $existTrade->Lots = $existTrade->Lots - $lots;
            $existTrade->quentity = $existTrade->quentity - $request->quentity;
            $existTrade->save();

            $newData1 = $existTrade->toArray();

            unset($newData1['Pk_id']);

            $newData1['quentity'] = $request->quentity ?? 1;
            $newData1['Lots'] = $lots;
            $newData1['trade_id'] = rand(10000000, 99999999);
            $newData1['holding_margin_req'] = $holdingmargin;
            $newData1['used_margin_req'] = $usedmargin;
            $newData1['created_at'] = Carbon::parse($newData1['created_at'])
                    ->format('Y-m-d H:i:s');
                $newData1['updated_at'] = now()->format('Y-m-d H:i:s');

                $insertId = DB::table('marketbidmaster')->insertGetId($newData1);

                // get inserted data
                $existTrade1 = DB::table('marketbidmaster')
                    ->where('Pk_id', $insertId)
                    ->first();

            // $sellPrice = $request->BuyPrice ?? 0;

            $checked = $this->closeBulkTradesExist($existTrade1, $sellPrice);
        } else if ($qty < $inputQuentity) {
            $logText .= "$qty < $inputQuentity condition true,\n";
            $existQty = $inputQuentity - $existTrade->quentity;
            $existLost = $lots - $existTrade->Lots;

            $newData = $existTrade->toArray();
            unset($newData['Pk_id']);

            $newData['quentity'] = $existQty;
            $newData['Lots'] = $existLost; // fixed
            $newData['SalePrice'] = null;
            $newData['holding_margin_req'] = $holdingmargin * $existLost;
            $newData['used_margin_req'] = $usedmargin * $existLost;
            $newData['BuyPrice'] = $request->BuyPrice;
            $newData['trade_id'] = rand(10000000, 99999999);
            $newData['Mode'] = $request->Mode ?? null;
            $newData['updated_at'] = now()->format('Y-m-d H:i:s');

            MarketBidMaster::create($newData);

            // $sellPrice = $request->BuyPrice ?? 0;
            $logText .= $sellPrice . " sale Price,\n";
            $checked = $this->closeBulkTradesExist($existTrade, $sellPrice);
        } else {
            // $sellPrice = $request->BuyPrice ?? 0;
            $logText .= $sellPrice . " sale Price,\n";
            $checked = $this->closeBulkTradesExist($existTrade, $sellPrice);
        }

        return $checked;
    }


    public function closeBulkTradesExist($request, $sellPrice)
    {

        try {
            $homeCtrl = new HomeController();

            // ✅ Get user with relation (single query)
            $user = TradeUser::with('bidamount')->find($request->UserId);
            // dd($user);

            if (!$user || $user->IsActive == 0) {
                return response()->json(['error' => 'Your Account is Blocked'], 500);
            }

            // ✅ Get trade properly (DON'T trust request directly)
            $trade = $request;

            if (!$trade) {
                return [
                    'error' => true,
                    'message' => 'Trade not found or already closed'
                ];
            }

            $symbol = $trade->Symbol;

            // ✅ Lot calculation
            $lotSize = $homeCtrl->getLotValume($symbol);
            $volume  = $lotSize * $trade->Lots;

            $salePrice = $sellPrice * $volume;
            $buyPrice  = $trade->BuyPrice * $volume;
            
            if($trade->Mode=='SELL'){
            $profit = $buyPrice -$salePrice;
            }else{
            $profit = $salePrice - $buyPrice;
            }

            // ✅ Brokerage calculation
            $brokerage = $homeCtrl->brokarageCharge($buyPrice, $salePrice, $user, $symbol);
            
            // ✅ Update trade directly (avoid extra save)

            $dataUpated = [
                'Isactive'        => 2,
                'SalePrice'       => $sellPrice,
                'used_margin_req' => 0,
                'brokrage'        => $brokerage,
                'sell_ip_address' => request()->ip(),
                'sold_by'         => 'Trader',
                'created_at'      => Carbon::parse($trade->created_at)->format('Y-m-d H:i:s'),
                'updated_at'      => now()->format('Y-m-d H:i:s')
            ];

            DB::table('marketbidmaster')->where('Pk_id', $trade->Pk_id)->update($dataUpated);
            
            // ✅ Update user (single update instead of multiple increment calls)
            $user->balance += ($profit - $brokerage);
            $user->net_p_l += $profit;
            $user->brokrage = $brokerage;

            // ✅ Options handling
            if (str_ends_with($symbol, 'CE') || str_ends_with($symbol, 'PE')) {
                $homeCtrl->optionConfig($symbol, $user);
            } else {
                $symbolShort = explode(':', $symbol)[1] ?? $symbol;

                $msg = "{$user->Username} ({$user->user_id}) {$trade->Mode} order of {$trade->Lots} lots of {$symbolShort} closed Successfully Rs. {$sellPrice}";

                $homeCtrl->trnsectionDeatil(
                    $user->id,
                    $msg,
                    'Profit / Loss Price',
                    $profit,
                    1
                );
            }

            $user->save();


            return [
                'error'   => false,
                'message' => 'Successfully closed trades'
            ];
        } catch (\Exception $e) {
            dd($e->getMessage());


            return [
                'error'   => true,
                'message' => $e->getMessage()
            ];
        }
    }

    private function activateOrder($id)
    {
        $updated = DB::table('marketbidmaster')
            ->where('trade_id', $id)
            ->update([
                'Isactive' => 1,
                'deleted_at' => null
            ]);

        if ($updated === 0) {
            return response()->json([
                'status' => false,
                'message' => 'Trade not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Pending order updated successfully'
        ], 200);
    }

    private function markOrderClosed($id)
    {
        DB::table('marketbidmaster')
            ->where('trade_id', $id)
            ->update([
                'Isactive' => 3,
                 'is_execute' => 1,
                'deleted_at' => null
            ]);
    }

    
// update initail amount Api 
    public function updateInitialAmount()
    {
        $users = TradeUser::all();
        // $user = TradeUser::where('id',67)->first();
        foreach ($users as $user) {

            $fund = new DepositeMaster();
            $fund->UserId = $user->id;
            $fund->Amount = $user->balance;
            $fund->Approve_Status = 'APPROVED';
            $fund->notes = 'Opening Balance.';
            $fund->LastModify = now();
            $fund->type = 1;
            $fund->isAdmin = 1;
            $fund->save();
        }
    }
}

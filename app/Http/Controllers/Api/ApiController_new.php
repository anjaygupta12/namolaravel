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
    $filePath = $dir . '/' . $id . '.txt';

    $logText  = "=============================\n";
    $logText .= "Order ID: " . $id . "\n";
    $logText .= "Time: " . now() . "\n";

    if (!$order) {
        $logText .= "Order Not Found\n";
        file_put_contents($filePath, $logText, FILE_APPEND);
        return response()->json([
            'status'  => false,
            'message' => 'Order not found or already active',
        ], 404);
    }

    $logText .= "Order Found: trade_id={$order->trade_id}, Mode={$order->Mode}, Symbol={$order->Symbol}, Lots={$order->Lots}, is_qty={$order->is_qty}, IsOrder={$order->IsOrder}\n";

    $mode   = $order->Mode == 'BUY' ? 'SELL' : 'BUY';
    $symbol = $order->Symbol;

    // ── CASE 1: qty-based order ──────────────────────────────────────────────
    if ($order->is_qty == 1 && $order->IsOrder == 1) {

        $logText .= "$order->is_qty == 1 && $order->IsOrder == 1 condition true,\n";

        $existTrades = MarketBidMaster::where('Mode', $mode)
            ->where('Symbol', $symbol)
            ->where('UserId', $order->UserId)
            ->where('is_qty', 1)
            ->where('Isactive', 1)
            ->get();

        $logText .= "Existing trades found (is_qty=1): " . $existTrades->count() . "\n";
        foreach ($existTrades as $et) {
            $logText .= "  -> trade_id={$et->trade_id}, qty={$et->quentity}, lots={$et->Lots}\n";
        }

        if ($existTrades->isNotEmpty()) {

            $user = TradeUser::findOrFail($order->UserId);

            $SymbolShortName = ForexOption::where('Symbol', $symbol)
                ->value('SymbolShortName');

            $marginAvailable = $homeCtrl->getMarginCheckAvilable(
                $user,
                $SymbolShortName,
                $order->LotSize,
                $order->TransactionMode,
                $order->Lots,
                $order->BuyPrice
            );

            $result = $this->consumeQtyAcrossRows(
                $order,
                $existTrades,
                $marginAvailable,
                $logText
            );

            if ($result['error'] == false) {
                $this->markOrderClosed($id);
                $logText .= $order->Mode . " closed successfully across multiple rows.\n";
                file_put_contents($filePath, $logText, FILE_APPEND);
                return response()->json([
                    'message' => $order->Mode . ' closed successfully across multiple rows'
                ]);
            }

            $logText .= "Error during qty close: " . $result['message'] . "\n";
            file_put_contents($filePath, $logText, FILE_APPEND);
            return response()->json(['message' => $result['message']]);
        }

        $logText .= "No existing opposite trades found (is_qty=1). Activating order.\n";
        file_put_contents($filePath, $logText, FILE_APPEND);
        return $this->activateOrder($id);
    }

    // ── CASE 2: lot-based order ──────────────────────────────────────────────
    if ($order->IsOrder == 1 && $order->is_qty == 0) {

        $logText .= "$order->IsOrder == 1 && $order->is_qty == 0 condition true,\n";

        $existTrades = MarketBidMaster::where('Mode', $mode)
            ->where('Symbol', $symbol)
            ->where('UserId', $order->UserId)
            ->where('is_qty', 0)
            ->where('Isactive', 1)
            ->get();

        $logText .= "Existing trades found (is_qty=0): " . $existTrades->count() . "\n";
        foreach ($existTrades as $et) {
            $logText .= "  -> trade_id={$et->trade_id}, lots={$et->Lots}\n";
        }

        if ($existTrades->isNotEmpty()) {

            $user = TradeUser::findOrFail($order->UserId);

            $SymbolShortName = ForexOption::where('Symbol', $symbol)
                ->value('SymbolShortName');

            $marginAvailable = $homeCtrl->getMarginCheckAvilable(
                $user,
                $SymbolShortName,
                $order->LotSize,
                $order->TransactionMode,
                $order->Lots,
                $order->BuyPrice
            );

            $result = $this->consumeLotsAcrossRows(
                $order,
                $existTrades,
                $marginAvailable,
                $logText
            );

            if ($result['error'] == false) {
                $this->markOrderClosed($id);
                $logText .= $order->Mode . " closed successfully across multiple rows.\n";
                file_put_contents($filePath, $logText, FILE_APPEND);
                return response()->json([
                    'message' => $order->Mode . ' closed successfully across multiple rows'
                ]);
            }

            $logText .= "Error during lot close: " . $result['message'] . "\n";
            file_put_contents($filePath, $logText, FILE_APPEND);
            return response()->json(['message' => $result['message']]);
        }

        $logText .= "No existing opposite trades found (is_qty=0). Activating order.\n";
        file_put_contents($filePath, $logText, FILE_APPEND);
        return $this->activateOrder($id);
    }

    // ── Default fallback ─────────────────────────────────────────────────────
    $logText .= "Default fallback. Activating order.\n";
    file_put_contents($filePath, $logText, FILE_APPEND);
    return $this->activateOrder($id);
}


/**
 * Consume incoming ORDER LOTS across multiple existing trade rows.
 *
 * Rows are processed one by one. For each row:
 *   - row.Lots == remaining  → close whole row, remaining = 0, stop
 *   - row.Lots <  remaining  → close whole row, remaining -= row.Lots, continue to next row
 *   - row.Lots >  remaining  → split row: close `remaining` lots from this row,
 *                              keep (row.Lots - remaining) as active, stop
 *
 * If all rows are exhausted but remaining > 0,
 * a new active trade is created for the leftover lots.
 *
 * Example:
 *   Existing rows: [1 lot], [6 lots], [3 lots] → total 10 lots
 *   Order 10 lots  → all 3 rows closed fully
 *   Order 15 lots  → all 3 rows closed + new active row of 5 lots (order mode/price)
 *   Order 4 lots   → row[1 lot] closed fully, row[6 lots] split: 3 closed + 3 remain active
 */
private function consumeLotsAcrossRows($order, $existTrades, $marginAvailable, &$logText): array
{
    $remaining     = $order->Lots;
    $usedMargin    = $marginAvailable['intraday'];
    $holdingMargin = $marginAvailable['holding'];
    $lastError     = ['error' => false, 'message' => 'Successfully closed trades'];

    $filePath = public_path('order_log') . '/' . $order->trade_id . '.txt';

    $logText .= "--- consumeLotsAcrossRows START | order lots={$order->Lots} ---\n";
    file_put_contents($filePath, $logText, FILE_APPEND);
    $logText = '';

    foreach ($existTrades as $existTrade) {
        if ($remaining <= 0) break;

        $rowLots  = $existTrade->Lots;
        $logText .= "Processing trade_id={$existTrade->trade_id} | row.Lots={$rowLots} | remaining={$remaining}\n";

        if ($rowLots == $remaining) {
            // ── Exact match: close the whole row ──────────────────────────
            $lastError = $this->closeBulkTradesExist($existTrade, $order->BuyPrice);
            $logText  .= "EXACT MATCH – closed trade_id={$existTrade->trade_id}\n";
            $remaining = 0;

        } elseif ($rowLots < $remaining) {
            // ── Row is smaller: close entirely, continue ──────────────────
            $lastError  = $this->closeBulkTradesExist($existTrade, $order->BuyPrice);
            $remaining -= $rowLots;
            $logText   .= "ROW SMALLER – closed trade_id={$existTrade->trade_id}, remaining now={$remaining}\n";

        } else {
            // ── Row is larger: split it ───────────────────────────────────
            // Keep (rowLots - remaining) lots on the existing row (stays active)
            $existTrade->Lots -= $remaining;
            $existTrade->save();

            // Create a new row for the `remaining` lots and close it
            $newData                       = $existTrade->toArray();
            unset($newData['Pk_id']);
            $newData['Lots']               = $remaining;
            $newData['trade_id']           = rand(10000000, 99999999);
            $newData['holding_margin_req'] = $holdingMargin * $remaining;
            $newData['used_margin_req']    = $usedMargin    * $remaining;
            $newData['updated_at']         = now();

            $splitTrade = MarketBidMaster::create($newData);
            $lastError  = $this->closeBulkTradesExist($splitTrade, $order->BuyPrice);

            $logText   .= "ROW LARGER – split {$remaining} lots from trade_id={$existTrade->trade_id}. "
                        . "New split trade_id={$splitTrade->trade_id} closed. "
                        . "Original row now has " . $existTrade->Lots . " lots (active).\n";
            $remaining  = 0;
        }

        file_put_contents($filePath, $logText, FILE_APPEND);
        $logText = '';

        if ($lastError['error']) {
            $logText .= "ERROR in closeBulkTradesExist: {$lastError['message']}\n";
            file_put_contents($filePath, $logText, FILE_APPEND);
            $logText = '';
            return $lastError;
        }
    }

    // ── Leftover: order wants more lots than all existing rows combined ────
    if ($remaining > 0) {
        $logText .= "LEFTOVER {$remaining} lots – creating new active trade (order mode={$order->Mode}, price={$order->BuyPrice})\n";

        $newData                       = $existTrades->first()->toArray();
        unset($newData['Pk_id']);
        $newData['Lots']               = $remaining;
        $newData['SalePrice']          = null;
        $newData['BuyPrice']           = $order->BuyPrice;
        $newData['Mode']               = $order->Mode;
        $newData['Isactive']           = 1;
        $newData['deleted_at']         = null;
        $newData['holding_margin_req'] = $holdingMargin * $remaining;
        $newData['used_margin_req']    = $usedMargin    * $remaining;
        $newData['trade_id']           = rand(10000000, 99999999);
        $newData['updated_at']         = now();

        $newTrade = MarketBidMaster::create($newData);
        $logText .= "New active trade created: trade_id={$newTrade->trade_id}, lots={$remaining}\n";

        file_put_contents($filePath, $logText, FILE_APPEND);
        $logText = '';
    }

    $logText .= "--- consumeLotsAcrossRows END ---\n";
    file_put_contents($filePath, $logText, FILE_APPEND);
    $logText = '';

    return $lastError;
}


/**
 * Consume incoming ORDER QTY (quentity) across multiple existing trade rows.
 *
 * Same three-way split logic as consumeLotsAcrossRows() but driven by `quentity`.
 * Both `quentity` and `Lots` are tracked and updated together.
 *
 * Example:
 *   Existing rows: [qty=2, 1 lot], [qty=6, 6 lots], [qty=3, 3 lots] → total qty=11, lots=10
 *   Order qty=11, lots=10 → all rows closed
 *   Order qty=4,  lots=4  → row[qty=2] closed, row[qty=6] split: qty=2 closed + qty=4 remain active
 */
private function consumeQtyAcrossRows($order, $existTrades, $marginAvailable, &$logText): array
{
    $remainingQty  = $order->quentity;
    $remainingLots = $order->Lots;
    $usedMargin    = $marginAvailable['intraday'];
    $holdingMargin = $marginAvailable['holding'];
    $lastError     = ['error' => false, 'message' => 'Successfully closed trades'];

    $filePath = public_path('order_log') . '/' . $order->trade_id . '.txt';

    $logText .= "--- consumeQtyAcrossRows START | order qty={$order->quentity}, lots={$order->Lots} ---\n";
    file_put_contents($filePath, $logText, FILE_APPEND);
    $logText = '';

    foreach ($existTrades as $existTrade) {
        if ($remainingQty <= 0) break;

        $rowQty  = $existTrade->quentity;
        $rowLots = $existTrade->Lots;

        $logText .= "Processing trade_id={$existTrade->trade_id} | row.qty={$rowQty}, row.lots={$rowLots} | remainingQty={$remainingQty}, remainingLots={$remainingLots}\n";

        if ($rowQty == $remainingQty) {
            // ── Exact match ───────────────────────────────────────────────
            $lastError    = $this->closeBulkTradesExist($existTrade, $order->BuyPrice);
            $logText     .= "EXACT MATCH – closed trade_id={$existTrade->trade_id}\n";
            $remainingQty  = 0;
            $remainingLots = 0;

        } elseif ($rowQty < $remainingQty) {
            // ── Row smaller: consume entirely ─────────────────────────────
            $lastError     = $this->closeBulkTradesExist($existTrade, $order->BuyPrice);
            $remainingQty -= $rowQty;
            $remainingLots -= $rowLots;
            $logText      .= "ROW SMALLER – closed trade_id={$existTrade->trade_id}, remainingQty now={$remainingQty}, remainingLots now={$remainingLots}\n";

        } else {
            // ── Row larger: split ─────────────────────────────────────────
            // Lots consumed from this row, proportional to qty consumed
            $lotsToClose = $remainingLots;

            // Keep remainder on the existing row (stays active)
            $existTrade->quentity -= $remainingQty;
            $existTrade->Lots     -= $lotsToClose;
            $existTrade->save();

            // Create new row for the consumed qty/lots and close it
            $newData                       = $existTrade->toArray();
            unset($newData['Pk_id']);
            $newData['quentity']           = $remainingQty;
            $newData['Lots']               = $lotsToClose;
            $newData['trade_id']           = rand(10000000, 99999999);
            $newData['holding_margin_req'] = $holdingMargin * $lotsToClose;
            $newData['used_margin_req']    = $usedMargin    * $lotsToClose;
            $newData['updated_at']         = now();

            $splitTrade    = MarketBidMaster::create($newData);
            $lastError     = $this->closeBulkTradesExist($splitTrade, $order->BuyPrice);

            $logText      .= "ROW LARGER – split qty={$remainingQty}, lots={$lotsToClose} from trade_id={$existTrade->trade_id}. "
                           . "New split trade_id={$splitTrade->trade_id} closed. "
                           . "Original row now qty={$existTrade->quentity}, lots={$existTrade->Lots} (active).\n";
            $remainingQty  = 0;
            $remainingLots = 0;
        }

        file_put_contents($filePath, $logText, FILE_APPEND);
        $logText = '';

        if ($lastError['error']) {
            $logText .= "ERROR in closeBulkTradesExist: {$lastError['message']}\n";
            file_put_contents($filePath, $logText, FILE_APPEND);
            $logText = '';
            return $lastError;
        }
    }

    // ── Leftover: create new active trade for remaining qty/lots ──────────
    if ($remainingQty > 0) {
        $logText .= "LEFTOVER qty={$remainingQty}, lots={$remainingLots} – creating new active trade (order mode={$order->Mode}, price={$order->BuyPrice})\n";

        $newData                       = $existTrades->first()->toArray();
        unset($newData['Pk_id']);
        $newData['quentity']           = $remainingQty;
        $newData['Lots']               = $remainingLots;
        $newData['SalePrice']          = null;
        $newData['BuyPrice']           = $order->BuyPrice;
        $newData['Mode']               = $order->Mode;
        $newData['Isactive']           = 1;
        $newData['deleted_at']         = null;
        $newData['holding_margin_req'] = $holdingMargin * $remainingLots;
        $newData['used_margin_req']    = $usedMargin    * $remainingLots;
        $newData['trade_id']           = rand(10000000, 99999999);
        $newData['updated_at']         = now();

        $newTrade = MarketBidMaster::create($newData);
        $logText .= "New active trade created: trade_id={$newTrade->trade_id}, qty={$remainingQty}, lots={$remainingLots}\n";

        file_put_contents($filePath, $logText, FILE_APPEND);
        $logText = '';
    }

    $logText .= "--- consumeQtyAcrossRows END ---\n";
    file_put_contents($filePath, $logText, FILE_APPEND);
    $logText = '';

    return $lastError;
}


public function closeBulkTradesExist($request, $sellPrice)
{
    try {
        $homeCtrl = new HomeController();

        $user = TradeUser::with('bidamount')->find($request->UserId);

        if (!$user || $user->IsActive == 0) {
            return ['error' => true, 'message' => 'Your Account is Blocked'];
        }

        $trade = $request;

        if (!$trade) {
            return [
                'error'   => true,
                'message' => 'Trade not found or already closed'
            ];
        }

        $symbol = $trade->Symbol;

        $lotSize  = $homeCtrl->getLotValume($symbol);
        $volume   = $lotSize * $trade->Lots;

        $salePrice = $sellPrice * $volume;
        $buyPrice  = $trade->BuyPrice * $volume;
        $profit    = $salePrice - $buyPrice;

        $brokerage = $homeCtrl->brokarageCharge($buyPrice, $salePrice, $user, $symbol);

        $trade->update([
            'Isactive'        => 2,
            'SalePrice'       => $sellPrice,
            'used_margin_req' => 0,
            'brokrage'        => $brokerage,
            'sell_ip_address' => request()->ip(),
            'sold_by'         => 'Trader',
            'updated_at'      => now(),
        ]);

        $user->balance += ($profit - $brokerage);
        $user->net_p_l += $profit;
        $user->brokrage  = $brokerage;

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
            'Isactive'   => 1,
            'deleted_at' => null
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


private function markOrderClosed($id)
{
    DB::table('marketbidmaster')
        ->where('trade_id', $id)
        ->update([
            'Isactive'   => 2,
            'deleted_at' => null
        ]);
}


}
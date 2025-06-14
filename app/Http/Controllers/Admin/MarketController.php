<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ForexOption;
use App\Models\MarketMaster;
use App\Models\Position;
use App\Models\AdminLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MarketController extends Controller
{
    /**
     * Display market watch page
     */
    public function marketWatch()
    {
        $forexOptions = ForexOption::orderBy('DisplayOrder')->get();
        return view('admin.market-watch', compact('forexOptions'));
    }
    
    /**
     * Display scrip data page
     */
    public function scripData()
    {
        $forexOptions = ForexOption::orderBy('DisplayOrder')->get();
        return view('admin.scrip-data', compact('forexOptions'));
    }
    
    /**
     * Display market scripts page
     */
    public function marketScripts()
    {
        $forexOptions = ForexOption::orderBy('DisplayOrder')->get();
        return view('admin.market-scripts', compact('forexOptions'));
    }
    
    /**
     * Update forex option data
     */
    public function updateForexOption(Request $request, $id)
    {
        $forexOption = ForexOption::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'symbol' => 'required|string|max:20',
            'display_name' => 'required|string|max:50',
            'bid' => 'required|numeric',
            'ask' => 'required|numeric',
            'high' => 'required|numeric',
            'low' => 'required|numeric',
            'spread' => 'required|numeric|min:0',
            'pip_value' => 'required|numeric|min:0',
            'min_lot' => 'required|numeric|min:0',
            'max_lot' => 'required|numeric|min:0',
            'lot_step' => 'required|numeric|min:0',
            'leverage' => 'required|numeric|min:1',
            'display_order' => 'required|integer|min:0',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Update forex option
        $forexOption->Symbol = $request->symbol;
        $forexOption->DisplayName = $request->display_name;
        $forexOption->Bid = $request->bid;
        $forexOption->Ask = $request->ask;
        $forexOption->High = $request->high;
        $forexOption->Low = $request->low;
        $forexOption->Spread = $request->spread;
        $forexOption->PipValue = $request->pip_value;
        $forexOption->MinLot = $request->min_lot;
        $forexOption->MaxLot = $request->max_lot;
        $forexOption->LotStep = $request->lot_step;
        $forexOption->Leverage = $request->leverage;
        $forexOption->DisplayOrder = $request->display_order;
        $forexOption->IsActive = $request->has('is_active') ? 1 : 0;
        $forexOption->AllowBuy = $request->has('allow_buy') ? 1 : 0;
        $forexOption->AllowSell = $request->has('allow_sell') ? 1 : 0;
        $forexOption->LastModify = now();
        $forexOption->save();
        
        // Log the action
        AdminLog::create([
            'admin_id' => Session::get('admin_id'),
            'activity' => "Updated forex option {$forexOption->Symbol}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return redirect()->back()
            ->with('success', 'Forex option updated successfully.');
    }
    
    /**
     * Create a new forex option
     */
    public function createForexOption(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'symbol' => 'required|string|max:20|unique:ForexOptions,Symbol',
            'display_name' => 'required|string|max:50',
            'bid' => 'required|numeric',
            'ask' => 'required|numeric',
            'high' => 'required|numeric',
            'low' => 'required|numeric',
            'spread' => 'required|numeric|min:0',
            'pip_value' => 'required|numeric|min:0',
            'min_lot' => 'required|numeric|min:0',
            'max_lot' => 'required|numeric|min:0',
            'lot_step' => 'required|numeric|min:0',
            'leverage' => 'required|numeric|min:1',
            'display_order' => 'required|integer|min:0',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Create forex option
        $forexOption = new ForexOption();
        $forexOption->Symbol = $request->symbol;
        $forexOption->DisplayName = $request->display_name;
        $forexOption->Bid = $request->bid;
        $forexOption->Ask = $request->ask;
        $forexOption->High = $request->high;
        $forexOption->Low = $request->low;
        $forexOption->Spread = $request->spread;
        $forexOption->PipValue = $request->pip_value;
        $forexOption->MinLot = $request->min_lot;
        $forexOption->MaxLot = $request->max_lot;
        $forexOption->LotStep = $request->lot_step;
        $forexOption->Leverage = $request->leverage;
        $forexOption->DisplayOrder = $request->display_order;
        $forexOption->IsActive = $request->has('is_active') ? 1 : 0;
        $forexOption->AllowBuy = $request->has('allow_buy') ? 1 : 0;
        $forexOption->AllowSell = $request->has('allow_sell') ? 1 : 0;
        $forexOption->Timestamp = now();
        $forexOption->LastModify = now();
        $forexOption->save();
        
        // Log the action
        AdminLog::create([
            'admin_id' => Session::get('admin_id'),
            'activity' => "Created new forex option {$forexOption->Symbol}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return redirect()->route('admin.market-scripts')
            ->with('success', 'Forex option created successfully.');
    }
    
    /**
     * Update market prices
     */
    public function updateMarketPrices(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'forex_options' => 'required|array',
            'forex_options.*.id' => 'required|exists:ForexOptions,Id',
            'forex_options.*.bid' => 'required|numeric',
            'forex_options.*.ask' => 'required|numeric',
            'forex_options.*.high' => 'required|numeric',
            'forex_options.*.low' => 'required|numeric',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }
        
        DB::beginTransaction();
        
        try {
            foreach ($request->forex_options as $option) {
                $forexOption = ForexOption::find($option['id']);
                
                if ($forexOption) {
                    $forexOption->Bid = $option['bid'];
                    $forexOption->Ask = $option['ask'];
                    $forexOption->High = $option['high'];
                    $forexOption->Low = $option['low'];
                    $forexOption->LastModify = now();
                    $forexOption->save();
                    
                    // Check for stop loss and take profit triggers
                    $this->checkStopLossAndTakeProfit($forexOption);
                }
            }
            
            DB::commit();
            
            // Log the action
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => "Updated market prices for " . count($request->forex_options) . " forex options",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            return response()->json(['success' => true, 'message' => 'Market prices updated successfully.']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error updating market prices: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Check for stop loss and take profit triggers
     */
    private function checkStopLossAndTakeProfit(ForexOption $forexOption)
    {
        // Get open positions for this forex option
        $positions = Position::where('symbol_id', $forexOption->Id)
                           ->where('status', 'open')
                           ->get();
        
        foreach ($positions as $position) {
            $currentPrice = $position->trade_type == 'buy' ? $forexOption->Bid : $forexOption->Ask;
            $closePosition = false;
            $closeReason = '';
            
            // Check stop loss
            if ($position->stop_loss > 0) {
                if (($position->trade_type == 'buy' && $currentPrice <= $position->stop_loss) ||
                    ($position->trade_type == 'sell' && $currentPrice >= $position->stop_loss)) {
                    $closePosition = true;
                    $closeReason = 'stop_loss';
                }
            }
            
            // Check take profit
            if (!$closePosition && $position->take_profit > 0) {
                if (($position->trade_type == 'buy' && $currentPrice >= $position->take_profit) ||
                    ($position->trade_type == 'sell' && $currentPrice <= $position->take_profit)) {
                    $closePosition = true;
                    $closeReason = 'take_profit';
                }
            }
            
            // Close position if triggered
            if ($closePosition) {
                $this->closePosition($position, $currentPrice, $closeReason);
            }
        }
    }
    
    /**
     * Close a position due to stop loss or take profit
     */
    private function closePosition(Position $position, $closePrice, $reason)
    {
        DB::beginTransaction();
        
        try {
            $openPrice = $position->open_price;
            $volume = $position->volume;
            $tradeType = $position->trade_type;
            
            // Calculate profit/loss
            if ($tradeType == 'buy') {
                $pips = $closePrice - $openPrice;
            } else {
                $pips = $openPrice - $closePrice;
            }
            
            $pipValue = $position->pip_value;
            $profit = $pips * $pipValue * $volume;
            
            // Update position
            $position->close_price = $closePrice;
            $position->profit = $profit;
            $position->status = 'closed';
            $position->closed_at = now();
            $position->closed_by = $reason;
            $position->save();
            
            // Update user funds
            $fund = Fund::where('user_id', $position->user_id)->first();
            
            if ($fund) {
                $fund->balance += $profit;
                $fund->equity += $profit;
                $fund->free_margin += ($profit + $position->margin);
                $fund->margin -= $position->margin;
                $fund->save();
            }
            
            // Create trade history record
            TradeHistory::create([
                'user_id' => $position->user_id,
                'symbol_id' => $position->symbol_id,
                'trade_type' => $position->trade_type,
                'volume' => $position->volume,
                'open_price' => $position->open_price,
                'close_price' => $closePrice,
                'stop_loss' => $position->stop_loss,
                'take_profit' => $position->take_profit,
                'profit' => $profit,
                'commission' => $position->commission,
                'swap' => $position->swap,
                'opened_at' => $position->opened_at,
                'closed_at' => now(),
                'closed_by' => $reason,
                'is_deleted' => 0
            ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollBack();
            // Log error
            \Log::error('Error closing position: ' . $e->getMessage());
        }
    }
}

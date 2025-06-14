<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Models\TradeHistory;
use App\Models\TradeUser;
use App\Models\Fund;
use App\Models\ForexOption;
use App\Models\AdminLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class TradesController extends Controller
{
    /**
     * Display a listing of active positions
     */
    public function activePositions(Request $request)
    {
        $query = Position::with(['user', 'symbol'])
                        ->where('status', 'open');
        
        // Apply filters
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->has('symbol')) {
            $query->where('symbol_id', $request->symbol);
        }
        
        if ($request->has('trade_type')) {
            $query->where('trade_type', $request->trade_type);
        }
        
        $positions = $query->orderBy('opened_at', 'desc')->paginate(20);
        $users = TradeUser::where('IsActive', 1)->get();
        $symbols = ForexOption::where('IsActive', 1)->get();
        
        return view('admin.active-positions', compact('positions', 'users', 'symbols'));
    }
    
    /**
     * Display a listing of closed positions
     */
    public function closedPositions(Request $request)
    {
        $query = Position::with(['user', 'symbol'])
                        ->where('status', 'closed');
        
        // Apply filters
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->has('symbol')) {
            $query->where('symbol_id', $request->symbol);
        }
        
        if ($request->has('trade_type')) {
            $query->where('trade_type', $request->trade_type);
        }
        
        if ($request->has('date_from')) {
            $query->whereDate('closed_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('closed_at', '<=', $request->date_to);
        }
        
        if ($request->has('profit_type')) {
            if ($request->profit_type == 'profit') {
                $query->where('profit', '>', 0);
            } else if ($request->profit_type == 'loss') {
                $query->where('profit', '<', 0);
            }
        }
        
        $positions = $query->orderBy('closed_at', 'desc')->paginate(20);
        $users = TradeUser::where('IsActive', 1)->get();
        $symbols = ForexOption::where('IsActive', 1)->get();
        
        return view('admin.closed-positions', compact('positions', 'users', 'symbols'));
    }
    
    /**
     * Display a listing of pending orders
     */
    public function pendingOrders(Request $request)
    {
        $query = Position::with(['user', 'symbol'])
                        ->where('status', 'pending');
        
        // Apply filters
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->has('symbol')) {
            $query->where('symbol_id', $request->symbol);
        }
        
        if ($request->has('trade_type')) {
            $query->where('trade_type', $request->trade_type);
        }
        
        $positions = $query->orderBy('created_at', 'desc')->paginate(20);
        $users = TradeUser::where('IsActive', 1)->get();
        $symbols = ForexOption::where('IsActive', 1)->get();
        
        return view('admin.pending-orders', compact('positions', 'users', 'symbols'));
    }
    
    /**
     * Display a listing of deleted trades
     */
    public function deletedTrades(Request $request)
    {
        $query = TradeHistory::with(['user', 'symbol'])
                            ->where('is_deleted', 1);
        
        // Apply filters
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->has('symbol')) {
            $query->where('symbol_id', $request->symbol);
        }
        
        if ($request->has('date_from')) {
            $query->whereDate('deleted_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('deleted_at', '<=', $request->date_to);
        }
        
        $trades = $query->orderBy('deleted_at', 'desc')->paginate(20);
        $users = TradeUser::where('IsActive', 1)->get();
        $symbols = ForexOption::where('IsActive', 1)->get();
        
        return view('admin.deleted-trades', compact('trades', 'users', 'symbols'));
    }
    
    /**
     * Close a position manually
     */
    public function closePosition(Request $request, $id)
    {
        $position = Position::with(['user', 'symbol'])->findOrFail($id);
        
        // Check if already closed
        if ($position->status != 'open') {
            return redirect()->back()
                ->with('error', 'This position is not open.');
        }
        
        $validator = Validator::make($request->all(), [
            'close_price' => 'required|numeric',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        DB::beginTransaction();
        
        try {
            $closePrice = $request->close_price;
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
            $position->closed_by = 'admin';
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
                'closed_by' => 'admin',
                'is_deleted' => 0
            ]);
            
            // Log the action
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => "Manually closed position #{$id} for user ID {$position->user_id} with profit/loss of ₹{$profit}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            DB::commit();
            
            return redirect()->back()
                ->with('success', 'Position closed successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error closing position: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Delete a position
     */
    public function deletePosition(Request $request, $id)
    {
        $position = Position::with(['user', 'symbol'])->findOrFail($id);
        
        DB::beginTransaction();
        
        try {
            // If position is open, update user funds
            if ($position->status == 'open') {
                $fund = Fund::where('user_id', $position->user_id)->first();
                
                if ($fund) {
                    $fund->free_margin += $position->margin;
                    $fund->margin -= $position->margin;
                    $fund->save();
                }
            }
            
            // Create trade history record if not already in history
            if ($position->status != 'closed') {
                TradeHistory::create([
                    'user_id' => $position->user_id,
                    'symbol_id' => $position->symbol_id,
                    'trade_type' => $position->trade_type,
                    'volume' => $position->volume,
                    'open_price' => $position->open_price,
                    'close_price' => $position->close_price ?? $position->open_price,
                    'stop_loss' => $position->stop_loss,
                    'take_profit' => $position->take_profit,
                    'profit' => 0,
                    'commission' => $position->commission,
                    'swap' => $position->swap,
                    'opened_at' => $position->opened_at,
                    'closed_at' => now(),
                    'closed_by' => 'admin',
                    'is_deleted' => 1,
                    'deleted_at' => now()
                ]);
            } else {
                // Update existing history record
                $history = TradeHistory::where('position_id', $position->id)->first();
                if ($history) {
                    $history->is_deleted = 1;
                    $history->deleted_at = now();
                    $history->save();
                }
            }
            
            // Log the action
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => "Deleted position #{$id} for user ID {$position->user_id}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            // Delete the position
            $position->delete();
            
            DB::commit();
            
            return redirect()->back()
                ->with('success', 'Position deleted successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error deleting position: ' . $e->getMessage());
        }
    }
    
    /**
     * Modify a position
     */
    public function modifyPosition(Request $request, $id)
    {
        $position = Position::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'stop_loss' => 'nullable|numeric',
            'take_profit' => 'nullable|numeric',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Update position
        if ($request->has('stop_loss')) {
            $position->stop_loss = $request->stop_loss;
        }
        
        if ($request->has('take_profit')) {
            $position->take_profit = $request->take_profit;
        }
        
        $position->save();
        
        // Log the action
        AdminLog::create([
            'admin_id' => Session::get('admin_id'),
            'activity' => "Modified position #{$id} for user ID {$position->user_id}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return redirect()->back()
            ->with('success', 'Position modified successfully.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DepositeMaster;
use App\Models\WithdrawlMaster;
use App\Models\TradeUser;
use App\Models\Fund;
use App\Models\AdminLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class TransactionsController extends Controller
{
    /**
     * Display a listing of deposit requests
     */
    public function depositRequests(Request $request)
    {
        $query = DepositeMaster::with('user');
        
        // Apply filters
        if ($request->has('status')) {
            $query->where('Approve_Status', $request->status);
        } else {
            // Default to pending
            $query->where('Approve_Status', 'PENDING');
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('FullName', 'like', "%{$search}%")
                  ->orWhere('Username', 'like', "%{$search}%")
                  ->orWhere('Mobile', 'like', "%{$search}%");
            });
        }
        
        $deposits = $query->orderBy('Timestamp', 'desc')->paginate(15);
        
        return view('admin.deposit-requests', compact('deposits'));
    }
    
    /**
     * Display a listing of withdrawal requests
     */
    public function withdrawalRequests(Request $request)
    {
        $query = WithdrawlMaster::with('user');
        
        // Apply filters
        if ($request->has('status')) {
            $query->where('Status', $request->status);
        } else {
            // Default to pending
            $query->where('Status', 'PENDING');
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('FullName', 'like', "%{$search}%")
                  ->orWhere('Username', 'like', "%{$search}%")
                  ->orWhere('Mobile', 'like', "%{$search}%");
            });
        }
        
        $withdrawals = $query->orderBy('Timestamp', 'desc')->paginate(15);
        
        return view('admin.withdrawal-requests', compact('withdrawals'));
    }
    
    /**
     * Approve a deposit request
     */
    public function approveDeposit(Request $request, $id)
    {
        $deposit = DepositeMaster::findOrFail($id);
        
        // Check if already processed
        if ($deposit->Approve_Status != 'PENDING') {
            return redirect()->back()
                ->with('error', 'This deposit request has already been processed.');
        }
        
        DB::beginTransaction();
        
        try {
            // Update deposit status
            $deposit->Approve_Status = 'APPROVED';
            $deposit->Approve_date = now();
            $deposit->LastModify = now();
            $deposit->save();
            
            // Update user funds
            $fund = Fund::where('user_id', $deposit->UserId)->first();
            
            if (!$fund) {
                $fund = new Fund();
                $fund->user_id = $deposit->UserId;
                $fund->balance = 0;
                $fund->equity = 0;
                $fund->margin = 0;
                $fund->free_margin = 0;
                $fund->margin_level = 0;
            }
            
            $fund->balance += $deposit->Amount;
            $fund->equity += $deposit->Amount;
            $fund->free_margin += $deposit->Amount;
            $fund->updated_at = now();
            $fund->save();
            
            // Log the action
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => "Approved deposit #{$id} for ₹{$deposit->Amount}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            DB::commit();
            
            return redirect()->back()
                ->with('success', 'Deposit request approved successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error approving deposit: ' . $e->getMessage());
        }
    }
    
    /**
     * Reject a deposit request
     */
    public function rejectDeposit(Request $request, $id)
    {
        $deposit = DepositeMaster::findOrFail($id);
        
        // Check if already processed
        if ($deposit->Approve_Status != 'PENDING') {
            return redirect()->back()
                ->with('error', 'This deposit request has already been processed.');
        }
        
        // Update deposit status
        $deposit->Approve_Status = 'REJECTED';
        $deposit->Approve_date = now();
        $deposit->LastModify = now();
        $deposit->save();
        
        // Log the action
        AdminLog::create([
            'admin_id' => Session::get('admin_id'),
            'activity' => "Rejected deposit #{$id} for ₹{$deposit->Amount}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return redirect()->back()
            ->with('success', 'Deposit request rejected successfully.');
    }
    
    /**
     * Approve a withdrawal request
     */
    public function approveWithdrawal(Request $request, $id)
    {
        $withdrawal = WithdrawlMaster::findOrFail($id);
        
        // Check if already processed
        if ($withdrawal->Status != 'PENDING') {
            return redirect()->back()
                ->with('error', 'This withdrawal request has already been processed.');
        }
        
        DB::beginTransaction();
        
        try {
            // Check if user has sufficient funds
            $fund = Fund::where('user_id', $withdrawal->UserId)->first();
            
            if (!$fund || $fund->balance < $withdrawal->Amount) {
                return redirect()->back()
                    ->with('error', 'User does not have sufficient funds for this withdrawal.');
            }
            
            // Update withdrawal status
            $withdrawal->Status = 'APPROVED';
            $withdrawal->LastModify = now();
            $withdrawal->save();
            
            // Update user funds
            $fund->balance -= $withdrawal->Amount;
            $fund->equity -= $withdrawal->Amount;
            $fund->free_margin -= $withdrawal->Amount;
            $fund->updated_at = now();
            $fund->save();
            
            // Log the action
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => "Approved withdrawal #{$id} for ₹{$withdrawal->Amount}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            DB::commit();
            
            return redirect()->back()
                ->with('success', 'Withdrawal request approved successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error approving withdrawal: ' . $e->getMessage());
        }
    }
    
    /**
     * Reject a withdrawal request
     */
    public function rejectWithdrawal(Request $request, $id)
    {
        $withdrawal = WithdrawlMaster::findOrFail($id);
        
        // Check if already processed
        if ($withdrawal->Status != 'PENDING') {
            return redirect()->back()
                ->with('error', 'This withdrawal request has already been processed.');
        }
        
        // Update withdrawal status
        $withdrawal->Status = 'REJECTED';
        $withdrawal->LastModify = now();
        $withdrawal->save();
        
        // Log the action
        AdminLog::create([
            'admin_id' => Session::get('admin_id'),
            'activity' => "Rejected withdrawal #{$id} for ₹{$withdrawal->Amount}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return redirect()->back()
            ->with('success', 'Withdrawal request rejected successfully.');
    }
    
    /**
     * Show form to add funds manually
     */
    public function showAddFundsForm()
    {
        $users = TradeUser::where('IsActive', 1)->get();
        return view('admin.create-funds', compact('users'));
    }
    
    /**
     * Add funds manually
     */
    public function addFunds(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:TradeUser,UserId',
            'amount' => 'required|numeric|min:1',
            'notes' => 'nullable|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        DB::beginTransaction();
        
        try {
            // Create deposit record
            $deposit = new DepositeMaster();
            $deposit->UserId = $request->user_id;
            $deposit->Amount = $request->amount;
            $deposit->Approve_Status = 'APPROVED';
            $deposit->Approve_date = now();
            $deposit->Timestamp = now();
            $deposit->Isactive = 1;
            $deposit->save();
            
            // Update user funds
            $fund = Fund::where('user_id', $request->user_id)->first();
            
            if (!$fund) {
                $fund = new Fund();
                $fund->user_id = $request->user_id;
                $fund->balance = 0;
                $fund->equity = 0;
                $fund->margin = 0;
                $fund->free_margin = 0;
                $fund->margin_level = 0;
            }
            
            $fund->balance += $request->amount;
            $fund->equity += $request->amount;
            $fund->free_margin += $request->amount;
            $fund->updated_at = now();
            $fund->save();
            
            // Log the action
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => "Manually added ₹{$request->amount} to user ID {$request->user_id}" . 
                             ($request->notes ? " - Note: {$request->notes}" : ""),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.deposit-requests')
                ->with('success', 'Funds added successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error adding funds: ' . $e->getMessage())
                ->withInput();
        }
    }
}

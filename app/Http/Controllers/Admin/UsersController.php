<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TradeUser;
use App\Models\LoginMaster;
use App\Models\UserKyc;
use App\Models\Fund;
use App\Models\Position;
use App\Models\DepositeMaster;
use App\Models\WithdrawlMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = TradeUser::query();
        
        // Apply filters
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('FullName', 'like', "%{$search}%")
                  ->orWhere('Username', 'like', "%{$search}%")
                  ->orWhere('Email', 'like', "%{$search}%")
                  ->orWhere('Mobile', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('status')) {
            $query->where('IsActive', $request->status);
        }
        
        if ($request->has('account_type')) {
            $query->where('IsDemo', $request->account_type == 'demo' ? 1 : 0);
        }
        
        $users = $query->orderBy('CreatedDate', 'desc')->paginate(15);
        
        return view('admin.users', compact('users'));
    }
    
    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('admin.users.create');
    }
    
    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:TradeUser,Username',
            'email' => 'required|email|max:100|unique:TradeUser,Email',
            'mobile' => 'required|string|max:15|unique:TradeUser,Mobile',
            'password' => 'required|string|min:6',
            'is_demo' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        DB::beginTransaction();
        
        try {
            // Create the trade user
            $user = new TradeUser();
            $user->FullName = $request->fullname;
            $user->Username = $request->username;
            $user->Password = Hash::make($request->password);
            $user->Email = $request->email;
            $user->Mobile = $request->mobile;
            $user->Address = $request->address;
            $user->City = $request->city;
            $user->State = $request->state;
            $user->PinCode = $request->pincode;
            $user->IsActive = 1;
            $user->IsDemo = $request->has('is_demo') ? 1 : 0;
            $user->RefferalCode = Str::random(8);
            $user->TransPass = Hash::make($request->password); // Default transaction password same as login
            $user->save();
            
            // Create initial fund record
            Fund::create([
                'user_id' => $user->UserId,
                'balance' => 0,
                'equity' => 0,
                'margin' => 0,
                'free_margin' => 0,
                'margin_level' => 0
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.users')
                ->with('success', 'User created successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error creating user: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Display the specified user
     */
    public function show($id)
    {
        $user = TradeUser::findOrFail($id);
        
        $stats = [
            'total_deposits' => DepositeMaster::where('UserId', $id)->where('Approve_Status', 'APPROVED')->sum('Amount'),
            'total_withdrawals' => WithdrawlMaster::where('UserId', $id)->where('Status', 'APPROVED')->sum('Amount'),
            'open_positions' => Position::where('user_id', $id)->where('status', 'open')->count(),
            'closed_positions' => Position::where('user_id', $id)->where('status', 'closed')->count(),
        ];
        
        $deposits = DepositeMaster::where('UserId', $id)->orderBy('Timestamp', 'desc')->take(5)->get();
        $withdrawals = WithdrawlMaster::where('UserId', $id)->orderBy('Timestamp', 'desc')->take(5)->get();
        $positions = Position::where('user_id', $id)->orderBy('opened_at', 'desc')->take(5)->get();
        $kyc = UserKyc::where('user_id', $id)->first();
        
        return view('admin.users.show', compact('user', 'stats', 'deposits', 'withdrawals', 'positions', 'kyc'));
    }
    
    /**
     * Show the form for editing the specified user
     */
    public function edit($id)
    {
        $user = TradeUser::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }
    
    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        $user = TradeUser::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:TradeUser,Email,' . $id . ',UserId',
            'mobile' => 'required|string|max:15|unique:TradeUser,Mobile,' . $id . ',UserId',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $user->FullName = $request->fullname;
        $user->Email = $request->email;
        $user->Mobile = $request->mobile;
        $user->Address = $request->address;
        $user->City = $request->city;
        $user->State = $request->state;
        $user->PinCode = $request->pincode;
        $user->BankName = $request->bank_name;
        $user->AccountNumber = $request->account_number;
        $user->IFSCCode = $request->ifsc_code;
        $user->AccountHolderName = $request->account_holder_name;
        $user->IsActive = $request->has('is_active') ? 1 : 0;
        $user->IsDemo = $request->has('is_demo') ? 1 : 0;
        $user->AllowOrdersBeyondHighLow = $request->has('allow_orders_beyond_high_low') ? 1 : 0;
        $user->AllowOrdersBetweenHighLow = $request->has('allow_orders_between_high_low') ? 1 : 0;
        $user->AutoSquareOff = $request->has('auto_square_off') ? 1 : 0;
        $user->AutoSquareOffPercentage = $request->auto_square_off_percentage;
        $user->NotifyPercentage = $request->notify_percentage;
        
        if ($request->filled('password')) {
            $user->Password = Hash::make($request->password);
        }
        
        if ($request->filled('transaction_password')) {
            $user->TransPass = Hash::make($request->transaction_password);
        }
        
        $user->save();
        
        return redirect()->route('admin.users.show', $user->UserId)
            ->with('success', 'User updated successfully');
    }
    
    /**
     * Change user status (active/inactive)
     */
    public function changeStatus(Request $request, $id)
    {
        $user = TradeUser::findOrFail($id);
        $user->IsActive = !$user->IsActive;
        $user->save();
        
        return redirect()->back()
            ->with('success', 'User status updated successfully');
    }
    
    /**
     * Remove the specified user
     */
    public function destroy($id)
    {
        // Instead of deleting, we'll just deactivate the user
        $user = TradeUser::findOrFail($id);
        $user->IsActive = 0;
        $user->save();
        
        return redirect()->route('admin.users')
            ->with('success', 'User deactivated successfully');
    }
}

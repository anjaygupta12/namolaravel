<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Broker;
use App\Models\BrokerM2M;
use App\Models\AdminLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\AdminLogin;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\TradeUser;

class BrokerController extends Controller
{
    /**
     * Display a listing of brokers
     */
    public function index(Request $request)
    {

        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed admin dashboard',
                'ip_address' => request()->ip()
            ]);
        } else {
            return redirect('admin/login');
        }

        $adminId = Session::get('admin_id');

        $query = AdminLogin::with('role', 'children','parent','users');

        if ($request->filled('username')) {
            $query->where('username', 'like', '%' . $request->username . '%');
        }
        if ($request->filled('status')) {
            $query->where('account_status', $request->status);
        }

        $brokers = $query->where('PK_ID', '!=', 1)->get();
        if($adminId!=1){
            $brokers = $query->where('PK_ID', '!=', 1)
                    ->where('parent_id', $adminId)->get();

        // Add total_client

            }

        $brokers = $brokers->map(function ($q) {

            // Users count
            $totalUsers = $q->users ? $q->users->count() : 0;

            // Children IDs safe check
            $childrenIds = $q->children ? $q->children->pluck('PK_ID')->toArray() : [];

            // Children users count
            $totalChildren = !empty($childrenIds)
                ? TradeUser::whereIn('broker_id', $childrenIds)->count()
                : 0;

            // Set attribute properly
            $q->setAttribute('total_client', $totalUsers + $totalChildren);

            return $q;
        });

        // dd($brokers);
        
        return view('admin.brokers', compact('brokers'));
    }

    /**
     * Display broker M2M data
     */
    public function brokersM2m()
    {
        $brokerM2m = BrokerM2M::with('broker')->orderBy('date', 'desc')->paginate(20);
        $brokers = Broker::orderBy('name')->get();
        return view('admin.brokers-m2m', compact('brokerM2m', 'brokers'));
    }

    /**
     * Show the form for creating a new broker
     */
    public function create()
    {
        $roles = Role::where('id','!=',1)->get();
        return view('admin.new_broker', compact('roles'));
    }

    /**
     * Store a newly created broker
     */
    public function __store(Request $request)
    {
        // Create broker
        $adminUser = AdminLogin::where('PK_ID', Session::get('admin_id'))->first();

        if (!Hash::check($request->input('transaction_password'), $adminUser->TransPass)) {
            return redirect()->back()->with('error', 'Invalid transaction password.');
        }
        //    dd($request->all());
        $broker = $request->id ? AdminLogin::find($request->id) : new AdminLogin();
        $broker->user_id = rand(10000, 99999);
        $broker->Name = $request->name;
        $broker->UserName = $request->username;
        $broker->Password = bcrypt($request->input('password'));
        $request->password;
        $broker->TransPass =  bcrypt($request->input('password'));
        $request->tra_password;
        $broker->ref_code = $request->ref_code;
        $broker->broker_type = $request->usertype;
        $broker->account_status = $request->input('status', 0);
        $broker->UserType = 2;
        $broker->role_id = $request->role_id ?? 2;
        $broker->auto_square_off_percentage = $request->auto_square_off_percentage;
        $broker->notify_percentage = $request->notify_percentage;
        $broker->profit_share = $request->profit_share;
        $broker->brokerage_share = $request->brokerage_share;
        $broker->clients_limit = $request->clients_limit;
        $broker->sub_brokers_limit = $request->sub_brokers_limit;
        $broker->payin_allowed = $request->payin_allowed;
        $broker->payout_allowed = $request->payout_allowed;
        $broker->create_client_allowed = $request->create_client_allowed;
        $broker->client_tasks_allowed = $request->client_tasks_allowed;
        $broker->trade_activity_allowed = $request->trade_activity_allowed;
        $broker->notifications_allowed = $request->notifications_allowed;
        $broker->mcx_enabled = $request->mcx_enabled;
        $broker->mcx_brokerage_type = $request->mcx_brokerage_type;
        $broker->mcx_brokerage = $request->mcx_brokerage;
        $broker->mcx_exposure_type = $request->mcx_exposure_type;
        $broker->mcx_intraday_margin = $request->mcx_intraday_margin;
        $broker->mcx_holding_margin = $request->mcx_holding_margin;
        $broker->nse_enabled = $request->nse_enabled;
        $broker->nse_brokerage = $request->nse_brokerage;
        $broker->nse_intraday_margin = $request->nse_intraday_margin;
        $broker->nse_holding_margin = $request->nse_holding_margin;
        $broker->created_by = $request->created_by;
        $broker->save();

        $message = $broker = $request->id ? 'Updated' : 'created';
        return redirect()->route('admin.brokers')
            ->with('success', 'Broker ' . $message . ' successfully.');
    }

    public function store(Request $request)
    {
        $adminUser = AdminLogin::where('PK_ID', Session::get('admin_id'))->first();

        // --- Validation Rules ---
        $rules = [
            'transaction_password' => 'required|string|min:4',
            'name' => 'required|string|max:100',
            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('adminlogin', 'UserName')->ignore($request->id, 'PK_ID'),
            ],
            'password' => $request->id ? 'nullable|min:6' : 'required|min:6',
            'tra_password' => $request->id ? 'nullable|min:4' : 'required|min:4',
            'status' => 'nullable|in:0,1',
            'role_id' => 'nullable|integer|exists:roles,id',

            // Config
            'auto_square_off_percentage' => 'nullable|numeric|min:0|max:100',
            'notify_percentage' => 'nullable|numeric|min:0|max:100',
            'profit_share' => 'nullable|numeric|min:0|max:100',
            'brokerage_share' => 'nullable|numeric|min:0|max:100',
            'clients_limit' => 'nullable|integer|min:0',
            'sub_brokers_limit' => 'nullable|integer|min:0',

            // Permissions
            'sub_broker_tasks_allowed' => 'nullable|boolean',
            'payin_allowed' => 'nullable|boolean',
            'payout_allowed' => 'nullable|boolean',
            'create_client_allowed' => 'nullable|boolean',
            'client_tasks_allowed' => 'nullable|boolean',
            'trade_activity_allowed' => 'nullable|boolean',
            'notifications_allowed' => 'nullable|boolean',

            // MCX
            'mcx_enabled' => 'nullable|boolean',
            'mcx_brokerage_type' => 'nullable|string|in:per_crore,per_trade',
            'mcx_brokerage' => 'nullable|numeric|min:0',
            'mcx_exposure_type' => 'nullable|string|in:per_turnover,fixed',
            'mcx_intraday_margin' => 'nullable|numeric|min:0',
            'mcx_holding_margin' => 'nullable|numeric|min:0',

            // NSE
            'nse_enabled' => 'nullable|boolean',
            'nse_brokerage' => 'nullable|numeric|min:0',
            'nse_intraday_margin' => 'nullable|numeric|min:0',
            'nse_holding_margin' => 'nullable|numeric|min:0',
        ];

        $messages = [
            'username.unique' => 'This username is already taken.',
            'transaction_password.required' => 'Transaction password is required.',
            'password.required' => 'Password is required for new brokers.',
            'tra_password.required' => 'Transaction password is required for new brokers.',
            'usertype.required' => 'Please select a user type.',
        ];

        $validated = $request->validate($rules, $messages);

        // --- Check Admin Transaction Password ---
        if (!Hash::check($validated['transaction_password'], $adminUser->TransPass)) {
            return redirect()->back()->withInput()->with('error', 'Invalid transaction password.');
        }

        // --- Create or Update Broker ---
        $broker = $request->id ? AdminLogin::find($request->id) : new AdminLogin();

        if (!$request->id) {
            $broker->user_id = rand(10000, 99999);
        }

        $broker->Name = $validated['name'];
        $broker->UserName = $validated['username'];

        if (!empty($validated['password'])) {
            $broker->Password = bcrypt($validated['password']);
        }

        if (!empty($validated['tra_password'])) {
            $broker->TransPass = bcrypt($validated['tra_password']);
        }

        $broker->ref_code = $validated['ref_code'] ?? null;
        $broker->account_status = $request->input('status', 1);
        $broker->UserType = 2;
        $broker->role_id = $validated['role_id'] ?? 2;

        // --- Config Settings ---

        $broker->auto_square_off_percentage = $validated['auto_square_off_percentage'];
        $broker->notify_percentage = $validated['notify_percentage'];
        $broker->profit_share = $validated['profit_share'];
        $broker->brokerage_share = $validated['brokerage_share'];
        $broker->clients_limit = $validated['clients_limit'];
        $broker->sub_brokers_limit = $validated['sub_brokers_limit'];

        // --- Permissions ---
        $broker->sub_broker_tasks_allowed = $request->boolean('sub_broker_tasks_allowed');
        $broker->payin_allowed = $request->boolean('payin_allowed');
        $broker->payout_allowed = $request->boolean('payout_allowed');
        $broker->create_client_allowed = $request->boolean('create_client_allowed');
        $broker->client_tasks_allowed = $request->boolean('client_tasks_allowed');
        $broker->trade_activity_allowed = $request->boolean('trade_activity_allowed');
        $broker->notifications_allowed = $request->boolean('notifications_allowed');

        // --- MCX Settings ---
        $broker->mcx_enabled = $request->boolean('mcx_enabled');
        $broker->mcx_brokerage_type = $validated['mcx_brokerage_type'] ?? 'per_crore';
        $broker->mcx_brokerage = $validated['mcx_brokerage'] ?? 800.00;
        $broker->mcx_exposure_type = $validated['mcx_exposure_type'] ?? 'per_turnover';
        $broker->mcx_intraday_margin = $validated['mcx_intraday_margin'] ?? 500.00;
        $broker->mcx_holding_margin = $validated['mcx_holding_margin'] ?? 100.00;

        // --- NSE Settings ---
        $broker->nse_enabled = $request->boolean('nse_enabled');
        $broker->nse_brokerage = $validated['nse_brokerage'] ?? 800.00;
        $broker->nse_intraday_margin = $validated['nse_intraday_margin'] ?? 500.00;
        $broker->nse_holding_margin = $validated['nse_holding_margin'] ?? 100.00;

        $broker->created_by = Session::get('admin_id');
        $broker->parent_id = Session::get('admin_id');
        $broker->save();

        $message = $request->id ? 'updated' : 'created';
        return redirect()->route('admin.brokers')->with('success', "Broker {$message} successfully.");
    }

    /**
     * Show the form for editing the specified broker
     */
    public function edit($id)
    {

        $broker = AdminLogin::where('PK_ID', $id)->first();
        $roles = Role::where('id','!=',1)->get();

        return view('admin.new_broker', compact('broker', 'roles'));
    }
    public function copy($id)
    {
        $broker = AdminLogin::where('PK_ID', $id)->first();
         $roles = Role::where('id','!=',1)->get();
        return view('admin.new_broker', compact('broker', 'roles'));
    }

    /**
     * Update the specified broker
     */
    public function update(Request $request, $id)
    {
        $broker = Broker::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'api_key' => 'nullable|string|max:255',
            'api_secret' => 'nullable|string|max:255',
            'api_url' => 'nullable|url',
            'margin_percentage' => 'required|numeric|min:0|max:100',
            'commission_percentage' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update broker
        $broker->name = $request->name;
        $broker->api_key = $request->api_key;
        $broker->api_secret = $request->api_secret;
        $broker->api_url = $request->api_url;
        $broker->margin_percentage = $request->margin_percentage;
        $broker->commission_percentage = $request->commission_percentage;
        $broker->description = $request->description;
        $broker->is_active = $request->has('is_active') ? 1 : 0;
        $broker->save();

        // Log the action
        AdminLog::create([
            'admin_id' => Session::get('admin_id'),
            'activity' => "Updated broker: {$broker->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.brokers')
            ->with('success', 'Broker updated successfully.');
    }

    /**
     * Remove the specified broker
     */
    public function destroy($id)
    {
        $broker = AdminLogin::where('PK_ID', $id)->delete();
        return redirect()->route('admin.brokers')
            ->with('success', 'Broker deleted successfully.');
    }
    public function toggleStatus($id)
    {
        $broker = AdminLogin::with('users')->where('PK_ID', $id)->firstOrFail();

        $newStatus = $broker->account_status == 1 ? 0 : 1;

        $broker->update(['account_status' => $newStatus]);

        $broker->users()->update(
            $newStatus == 0
                ? ['IsActive' => 0, 'device_id' => null]
                : ['IsActive' => 1]
        );

        $message = $newStatus == 1 ? 'Broker activated successfully.' : 'Broker blocked successfully.';

        return redirect()->route('admin.brokers')
            ->with('success', $message);
    }

    /**
     * Add broker M2M data
     */
    public function addM2mData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'broker_id' => 'required|exists:brokers,id',
            'date' => 'required|date',
            'opening_balance' => 'required|numeric',
            'closing_balance' => 'required|numeric',
            'profit_loss' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if M2M data already exists for this broker and date
        $existingM2m = BrokerM2M::where('broker_id', $request->broker_id)
            ->where('date', $request->date)
            ->first();

        if ($existingM2m) {
            return redirect()->back()
                ->with('error', 'M2M data already exists for this broker and date.')
                ->withInput();
        }

        // Create M2M data
        $m2m = new BrokerM2M();
        $m2m->broker_id = $request->broker_id;
        $m2m->date = $request->date;
        $m2m->opening_balance = $request->opening_balance;
        $m2m->closing_balance = $request->closing_balance;
        $m2m->profit_loss = $request->profit_loss;
        $m2m->notes = $request->notes;
        $m2m->created_by = Session::get('admin_id');
        $m2m->save();

        // Log the action
        $broker = Broker::find($request->broker_id);
        AdminLog::create([
            'admin_id' => Session::get('admin_id'),
            'activity' => "Added M2M data for broker {$broker->name} on {$request->date}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.brokers-m2m')
            ->with('success', 'M2M data added successfully.');
    }

    /**
     * Update broker M2M data
     */
    public function updateM2mData(Request $request, $id)
    {
        $m2m = BrokerM2M::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'opening_balance' => 'required|numeric',
            'closing_balance' => 'required|numeric',
            'profit_loss' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update M2M data
        $m2m->opening_balance = $request->opening_balance;
        $m2m->closing_balance = $request->closing_balance;
        $m2m->profit_loss = $request->profit_loss;
        $m2m->notes = $request->notes;
        $m2m->save();

        // Log the action
        $broker = Broker::find($m2m->broker_id);
        AdminLog::create([
            'admin_id' => Session::get('admin_id'),
            'activity' => "Updated M2M data for broker {$broker->name} on {$m2m->date}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.brokers-m2m')
            ->with('success', 'M2M data updated successfully.');
    }

    /**
     * Delete broker M2M data
     */
    public function deleteM2mData($id)
    {
        $m2m = BrokerM2M::with('broker')->findOrFail($id);
        $brokerName = $m2m->broker->name;
        $date = $m2m->date;

        $m2m->delete();

        // Log the action
        AdminLog::create([
            'admin_id' => Session::get('admin_id'),
            'activity' => "Deleted M2M data for broker {$brokerName} on {$date}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('admin.brokers-m2m')
            ->with('success', 'M2M data deleted successfully.');
    }
}

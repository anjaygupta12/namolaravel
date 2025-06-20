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

class BrokerController extends Controller
{
    /**
     * Display a listing of brokers
     */
    public function index(Request $request)
    {
          $query = Broker::query();

    if ($request->filled('username')) {
        $query->where('username', 'like', '%' . $request->username . '%');
    }
    if ($request->filled('status')) {
        $query->where('account_status', $request->status);
    }
    $brokers = $query->get();

        if (Session::has('admin_id')) {
            AdminLog::create([
                'admin_id' => Session::get('admin_id'),
                'activity' => 'Viewed brokers list',
                'ip_address' => request()->ip()
            ]);
        }
        
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
        return view('admin.new_broker');
    }
    
    /**
     * Store a newly created broker
     */
    public function store(Request $request)
    {
        // Create broker
        $broker = new Broker();
        $broker->first_name = $request->first_name;
         $broker->last_name = $request->last_name;
          $broker->username = $request->username;
        $broker->password =  $request->password;
        $broker->transaction_password = $request->transaction_password;
        $broker->ref_code = $request->ref_code;
        $broker->user_type = $request->usertype;
        $broker->account_status = $request->status;
        $broker->auto_square_off_percentage = $request->auto_square_off_percentage;
        $broker->notify_percentage = $request->notify_percentage;
        $broker->profit_share = $request->profit_share;
        $broker->brokerage_share = $request->brokerage_share;
        $broker->clients_limit = $request->clients_limit;
        $broker->sub_brokers_limit = $request->sub_brokers_limit;
        $broker->payin_allowed = $request->payin_allowed;
        $broker->payout_allowed = $request->payout_allowed;
        $broker->create_client_allowed = $request->create_client_allowed;
        $broker->client_tasks_allowed= $request->client_tasks_allowed;
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
        
        return redirect()->route('admin.brokers')
            ->with('success', 'Broker created successfully.');
    }
    
    /**
     * Show the form for editing the specified broker
     */
    public function edit($id)
    {
        $broker = Broker::where('BrokerId',$id)->first();
        
        return view('admin.new_broker', compact('broker'));
    }
        public function copy($id)
    {
        $broker = Broker::where('BrokerId',$id)->first();
        
        return view('admin.new_broker', compact('broker'));
    }
    
    /**
     * Update the specified broker
     */
    public function update(Request $request, $id)
    {
        $broker = Broker::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:brokers,name,' . $id,
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
        $broker = Broker::where('BrokerId',$id)->delete();
        return redirect()->route('admin.brokers')
            ->with('success', 'Broker deleted successfully.');
    }
        public function toggleStatus($id)
    {
        $broker = Broker::where('BrokerId',$id)->first();
       
        if($broker->account_status==1){
            $status = 0;
        }else{
            $status = 1;
        }
        $broker = Broker::where('BrokerId',$id)->update(['account_status'=>$status]);
   
        return redirect()->route('admin.brokers')
            ->with('success', 'Broker Status successfully.');
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

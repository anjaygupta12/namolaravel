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
    public function index()
    {
        // Get all brokers without sorting in the database query
        $brokers = Broker::all();
        
        // Log admin activity
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
        return view('admin.new-broker');
    }
    
    /**
     * Store a newly created broker
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:brokers,name',
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
        
        // Create broker
        $broker = new Broker();
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
            'activity' => "Created new broker: {$broker->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return redirect()->route('admin.brokers')
            ->with('success', 'Broker created successfully.');
    }
    
    /**
     * Show the form for editing the specified broker
     */
    public function edit($id)
    {
        $broker = Broker::findOrFail($id);
        return view('admin.edit-broker', compact('broker'));
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
        $broker = Broker::findOrFail($id);
        $brokerName = $broker->name;
        
        // Check if broker has M2M data
        $hasM2mData = BrokerM2M::where('broker_id', $id)->exists();
        
        if ($hasM2mData) {
            return redirect()->back()
                ->with('error', 'Cannot delete broker with M2M data. Deactivate it instead.');
        }
        
        $broker->delete();
        
        // Log the action
        AdminLog::create([
            'admin_id' => Session::get('admin_id'),
            'activity' => "Deleted broker: {$brokerName}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        
        return redirect()->route('admin.brokers')
            ->with('success', 'Broker deleted successfully.');
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

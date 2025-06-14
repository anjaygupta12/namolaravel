<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    /**
     * Display general settings
     */
    public function general()
    {
        $settings = Setting::where('category', 'general')->get()->keyBy('key');
        
        return view('admin.settings.general', compact('settings'));
    }
    
    /**
     * Update general settings
     */
    public function updateGeneral(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'site_name' => 'required|string|max:100',
            'site_description' => 'nullable|string|max:255',
            'admin_email' => 'required|email',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $this->updateSettings($request->except('_token'), 'general');
        
        // Log admin action
        AdminLog::create([
            'admin_id' => Session::get('admin_id'),
            'activity' => 'Updated general settings',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return redirect()->back()->with('success', 'General settings updated successfully.');
    }
    
    /**
     * Display trading settings
     */
    public function trading()
    {
        $settings = Setting::where('category', 'trading')->get()->keyBy('key');
        
        return view('admin.settings.trading', compact('settings'));
    }
    
    /**
     * Update trading settings
     */
    public function updateTrading(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'default_margin_percentage' => 'required|numeric|min:0|max:100',
            'default_commission_percentage' => 'required|numeric|min:0|max:100',
            'trading_hours_start' => 'required|string',
            'trading_hours_end' => 'required|string',
            'trading_days' => 'required|array',
            'trading_days.*' => 'required|integer|min:0|max:6',
            'maintenance_mode' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Convert trading days array to JSON
        $tradingDays = json_encode($request->trading_days);
        $requestData = $request->except(['_token', 'trading_days']);
        $requestData['trading_days'] = $tradingDays;
        
        $this->updateSettings($requestData, 'trading');
        
        // Log admin action
        AdminLog::create([
            'admin_id' => Session::get('admin_id'),
            'activity' => 'Updated trading settings',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return redirect()->back()->with('success', 'Trading settings updated successfully.');
    }
    
    /**
     * Display payment settings
     */
    public function payment()
    {
        $settings = Setting::where('category', 'payment')->get()->keyBy('key');
        
        return view('admin.settings.payment', compact('settings'));
    }
    
    /**
     * Update payment settings
     */
    public function updatePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'min_deposit_amount' => 'required|numeric|min:0',
            'max_deposit_amount' => 'required|numeric|min:0',
            'min_withdrawal_amount' => 'required|numeric|min:0',
            'max_withdrawal_amount' => 'required|numeric|min:0',
            'withdrawal_fee_percentage' => 'required|numeric|min:0|max:100',
            'payment_gateway_api_key' => 'nullable|string',
            'payment_gateway_secret' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $this->updateSettings($request->except('_token'), 'payment');
        
        // Log admin action
        AdminLog::create([
            'admin_id' => Session::get('admin_id'),
            'activity' => 'Updated payment settings',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return redirect()->back()->with('success', 'Payment settings updated successfully.');
    }
    
    /**
     * Display notification settings
     */
    public function notification()
    {
        $settings = Setting::where('category', 'notification')->get()->keyBy('key');
        
        return view('admin.settings.notification', compact('settings'));
    }
    
    /**
     * Update notification settings
     */
    public function updateNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'notification_email_from' => 'required_if:email_notifications,1|nullable|email',
            'notification_email_name' => 'required_if:email_notifications,1|nullable|string|max:100',
            'sms_api_key' => 'required_if:sms_notifications,1|nullable|string',
            'sms_sender_id' => 'required_if:sms_notifications,1|nullable|string|max:20',
            'firebase_api_key' => 'required_if:push_notifications,1|nullable|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $this->updateSettings($request->except('_token'), 'notification');
        
        // Log admin action
        AdminLog::create([
            'admin_id' => Session::get('admin_id'),
            'activity' => 'Updated notification settings',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return redirect()->back()->with('success', 'Notification settings updated successfully.');
    }
    
    /**
     * Helper method to update settings
     */
    private function updateSettings(array $data, string $category)
    {
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key, 'category' => $category],
                ['value' => $value]
            );
        }
    }
}

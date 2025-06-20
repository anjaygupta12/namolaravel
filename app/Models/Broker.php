<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\support\Facades\Hash;

class Broker extends Model
{
    protected $table = 'brokers';
    
     protected $fillable = [
        'first_name', 'last_name', 'username',
        'password', 'transaction_password',
        'ref_code', 'user_type', 'account_status',
        'auto_square_off_percentage', 'notify_percentage',
        'profit_share', 'brokerage_share',
        'clients_limit', 'sub_brokers_limit',
        'sub_broker_tasks_allowed', 'payin_allowed',
        'payout_allowed', 'create_client_allowed',
        'client_tasks_allowed', 'trade_activity_allowed',
        'notifications_allowed',
        'mcx_enabled', 'mcx_brokerage_type', 'mcx_brokerage',
        'mcx_exposure_type', 'mcx_intraday_margin',
        'mcx_holding_margin',
        'nse_enabled', 'nse_brokerage',
        'nse_intraday_margin', 'nse_holding_margin',
        'created_by',
    ];

    /************
     * MUTATORS *
     ************/
    // public function setPasswordAttribute($value)
    // {
    //     $this->attributes['password'] = $value ? Hash::make($value) : null;
    // }

    public function setTransactionPasswordAttribute($value)
    {
        $this->attributes['transaction_password'] = $value ? Hash::make($value) : null;
    }
    protected $casts = [
        'margin_percentage' => 'decimal:2',
        'commission_percentage' => 'decimal:2',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    /**
     * Get the M2M data for this broker
     */
    public function m2mData()
    {
        return $this->hasMany(BrokerM2M::class, 'broker_id');
    }
}

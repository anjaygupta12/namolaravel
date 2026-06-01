<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLogin extends Model
{
    protected $table = 'adminlogin';
    protected $primaryKey = 'PK_ID';
    public $timestamps = false;

    protected $fillable = [
        'user_id','UserName', 'Password', 'TransPass', 'Name', 'Mobile', 'Email', 'UserType',
        'Timestamp', 'LastModify', 'Isactive','role_id','parent_id',
         'user_id',
        'ref_code', 'broker_type',
        'account_status',
        'auto_square_off_percentage',
         'notify_percentage',
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

    protected $hidden = [
        'Password', 'TransPass'
    ];

    protected $casts = [
        'Timestamp' => 'datetime',
        'LastModify' => 'datetime',
        'Isactive' => 'boolean'
    ];

    /**
     * Get all logs for this admin.
     */
    public function logs()
    {
        return $this->hasMany(AdminLog::class, 'admin_id', 'PK_ID');
    }

    /**
     * Get all notifications created by this admin.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'created_by', 'PK_ID');
    }


    // In your AdminLogin model
        public function role()
        {
            return $this->belongsTo(Role::class, 'role_id');
        }

        public function parent()
    {
        return $this->belongsTo(AdminLogin::class, 'parent_id');
    }

    // ✅ Get all children (broker → sub_brokers, admin → brokers)
    public function children()
    {
        return $this->hasMany(AdminLogin::class, 'parent_id');
    }

        public function users()
    {
        return $this->hasMany(TradeUser::class, 'broker_id');
    }
}

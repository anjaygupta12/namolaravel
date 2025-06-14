<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradeAccountDetail extends Model
{
    protected $table = 'TradeAccountDetail';
    protected $primaryKey = 'PK_Id';
    public $timestamps = false;
    
    protected $fillable = [
        'UserId', 'FullName', 'Mobile', 'OptionalMobile', 'Username', 'Password', 
        'OptionalPassword', 'City', 'OptionalCity', 'ConfigRemark', 'ConfigdemoAccount',
        'ConfigAllowFreshEntryOrder', 'ConfigAllowOrdersbetweenHighLow', 'ConfigTradeequityasunits',
        'ConfigAccountStatus', 'ConfigAutoCloseTrades', 'ConfigautoCloseall', 'ConfigNotifyclient',
        'ConfigMinTimeprofit', 'MCXTrading', 'MCXMinimumlotsize', 'MCXMaximumlotsize',
        'MCXMaximumlotsizeactively', 'MCXMaxSizeAllCommodity', 'MCXBrokerageType',
        'MCXbrokerage', 'MCXExposure', 'MCXIntradayExposure', 'MCXHoldingExposure',
        'Timestamp', 'LastModify', 'Isactive'
    ];
    
    protected $casts = [
        'ConfigdemoAccount' => 'boolean',
        'ConfigAllowFreshEntryOrder' => 'boolean',
        'ConfigAllowOrdersbetweenHighLow' => 'boolean',
        'ConfigTradeequityasunits' => 'boolean',
        'ConfigAccountStatus' => 'boolean',
        'ConfigAutoCloseTrades' => 'boolean',
        'ConfigautoCloseall' => 'decimal:2',
        'ConfigNotifyclient' => 'decimal:2',
        'ConfigMinTimeprofit' => 'decimal:2',
        'MCXTrading' => 'boolean',
        'MCXMinimumlotsize' => 'decimal:2',
        'MCXMaximumlotsize' => 'decimal:2',
        'MCXMaximumlotsizeactively' => 'decimal:2',
        'MCXMaxSizeAllCommodity' => 'decimal:2',
        'MCXbrokerage' => 'decimal:2',
        'MCXIntradayExposure' => 'decimal:2',
        'MCXHoldingExposure' => 'decimal:2',
        'Timestamp' => 'datetime',
        'LastModify' => 'datetime',
        'Isactive' => 'boolean'
    ];
    
    protected $hidden = [
        'Password', 'OptionalPassword',
    ];
    
    // Relationship with TradeUser
    public function user()
    {
        return $this->belongsTo(TradeUser::class, 'UserId', 'PK_Id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TradeUser extends Model
{
    use HasFactory, SoftDeletes;
    
    /**
     * Boot the model.
     * Override the default query to always include soft-deleted records
     */
    protected static function boot()
    {
        parent::boot();
        
        static::addGlobalScope('withTrashed', function($builder) {
            $builder->withTrashed();
        });
    }
    
    protected $table = 'tradeuser';
    protected $primaryKey = 'UserId';
    
   protected $fillable = [
    'UserId',
    'FullName',
    'Username',
    'Password',
    'Email',
    'Mobile',
    'Address',
    'City',
    'State',
    'PinCode',
    'PAN',
    'Aadhar',
    'BankName',
    'AccountNumber',
    'IFSCCode',
    'AccountHolderName',
    'IsActive',
    'IsDemo',
    'AllowOrdersBeyondHighLow',
    'AllowOrdersBetweenHighLow',
    'TradeEquityAsUnits',
    'AutoSquareOff',
    'AutoSquareOffPercentage',
    'NotifyPercentage',
    'MCXLotMarginJSON',
    'MCXLotBrokerageJSON',
    'MCXBidGapJSON',
    'NSEFuturesEnabled',
    'NSEOptionsEnabled',
    'MCXOptionsEnabled',
    'NSEFuturesMaxLotPerScrip',
    'NSEOptionsMaxLotPerScrip',
    'MCXOptionsMaxLotPerScrip',
    'NSEFuturesBrokerage',
    'NSEOptionsBrokerage',
    'MCXOptionsBrokerage',
    'NSEFuturesHoldingMargin',
    'NSEOptionsHoldingMargin',
    'MCXOptionsHoldingMargin',
    'NSEFuturesShortSellingAllowed',
    'NSEOptionsShortSellingAllowed',
    'MCXOptionsShortSellingAllowed',
    'CreatedBy',
    'CreatedDate',
    'ModifiedBy',
    'ModifiedDate',
    'TransPass',
    'RefferalCode',
    'ComexTradingEnabled',
    'ForexTradingEnabled',
    'ComexbrokerageType',
    'Comexbrokerage',
    'MinimumlotssingleComex',
    'MaximumlotsComex',
    'Maximumlotsallowed',
    'MaxSizeAllComex',
    'IntradayExposureMarginComex',
    'HoldingExposureMarginComex',
    'OrderspriceComex',
    'ForexbrokerageType',
    'Forexbrokerage',
    'MinimumlotssingleForex',
    'MaximumlotsForex',
    'MaximumlotsallowedForex',
    'MaxSizeAllForex',
    'IntradayExposureMarginForex',
    'HoldingExposureMarginForex',
    'OrderspriceForex',
    'CryptoTradingEnabled',
    'CryptobrokerageType',
    'Cryptobrokerage',
    'MinimumlotssingleCrypto',
    'MaximumlotsCrypto',
    'MaximumlotsallowedCrypto',
    'MaxSizeAllCrypto',
    'IntradayExposureMarginCrypto',
    'HoldingExposureMarginCrypto',
    'OrderspriceCrypto',
    'is_active'
];
    
    protected $casts = [
        // Boolean fields
        'is_active' => 'boolean',
        'is_demo' => 'boolean',
        'allow_orders_beyond_high_low' => 'boolean',
        'allow_orders_between_high_low' => 'boolean',
        'trade_equity_as_units' => 'boolean',
        'auto_square_off' => 'boolean',
        'nse_futures_enabled' => 'boolean',
        'nse_options_enabled' => 'boolean',
        'mcx_options_enabled' => 'boolean',
        'nse_futures_short_selling_allowed' => 'boolean',
        'nse_options_short_selling_allowed' => 'boolean',
        'mcx_options_short_selling_allowed' => 'boolean',
        'comex_trading_enabled' => 'boolean',
        'forex_trading_enabled' => 'boolean',
        'crypto_trading_enabled' => 'boolean',
        
        // Decimal fields
        'auto_square_off_percentage' => 'decimal:2',
        'notify_percentage' => 'decimal:2',
        'nse_futures_brokerage' => 'decimal:4',
        'nse_options_brokerage' => 'decimal:4',
        'mcx_options_brokerage' => 'decimal:4',
        'nse_futures_holding_margin' => 'decimal:4',
        'nse_options_holding_margin' => 'decimal:4',
        'mcx_options_holding_margin' => 'decimal:4',
        'comex_brokerage' => 'decimal:4',
        'minimum_lots_single_comex' => 'decimal:4',
        'maximum_lots_comex' => 'decimal:4',
        'maximum_lots_allowed' => 'decimal:4',
        'max_size_all_comex' => 'decimal:4',
        'intraday_exposure_margin_comex' => 'decimal:4',
        'holding_exposure_margin_comex' => 'decimal:4',
        'orders_price_comex' => 'decimal:4',
        'forex_brokerage' => 'decimal:4',
        'minimum_lots_single_forex' => 'decimal:4',
        'maximum_lots_forex' => 'decimal:4',
        'maximum_lots_allowed_forex' => 'decimal:4',
        'max_size_all_forex' => 'decimal:4',
        'intraday_exposure_margin_forex' => 'decimal:4',
        'holding_exposure_margin_forex' => 'decimal:4',
        'orders_price_forex' => 'decimal:4',
        'crypto_brokerage' => 'decimal:4',
        'minimum_lots_single_crypto' => 'decimal:4',
        'maximum_lots_crypto' => 'decimal:4',
        'maximum_lots_allowed_crypto' => 'decimal:4',
        'max_size_all_crypto' => 'decimal:4',
        'intraday_exposure_margin_crypto' => 'decimal:4',
        'holding_exposure_margin_crypto' => 'decimal:4',
        'orders_price_crypto' => 'decimal:4',
        
        // JSON fields
        'mcx_lot_margin_json' => 'array',
        'mcx_lot_brokerage_json' => 'array',
        'mcx_bid_gap_json' => 'array',
        
        // Date fields
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];
    
    protected $hidden = [
        'password', 'trans_pass'
    ];
    
    /**
     * Get the user's full balance (including all transactions)
     */
    public function getBalanceAttribute()
    {
        $deposits = $this->transactions()->where('type', 'deposit')->where('status', 'completed')->sum('amount');
        $withdrawals = $this->transactions()->where('type', 'withdrawal')->where('status', 'completed')->sum('amount');
        $bonuses = $this->transactions()->where('type', 'bonus')->where('status', 'completed')->sum('amount');
        
        return $deposits + $bonuses - $withdrawals;
    }
    
    /**
     * Relationship with user transactions
     */
    public function transactions()
    {
        return $this->hasMany(UserTransaction::class, 'user_id');
    }
    
    /**
     * Relationship with user notifications
     */
    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'notification_users', 'user_id', 'notification_id');
    }
    
    /**
     * Relationship with LoginMaster (legacy)
     */
    public function login()
    {
        return $this->hasOne(LoginMaster::class, 'UserId', 'Username');
    }
    
    /**
     * Relationship with deposits (legacy)
     */
    public function deposits()
    {
        return $this->hasMany(DepositeMaster::class, 'UserId', 'id');
    }
    
    /**
     * Relationship with withdrawals (legacy)
     */
    public function withdrawals()
    {
        return $this->hasMany(WithdrawlMaster::class, 'UserId', 'id');
    }
}

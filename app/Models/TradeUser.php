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
        // Basic user information
        'name', 'email', 'mobile', 'username', 'password', 'address', 'city', 'state',
        'pin_code', 'pan', 'aadhar', 'bank_name', 'account_number', 'ifsc_code', 'account_holder_name',
        'is_active', 'is_demo', 
        
        // Trading preferences
        'allow_orders_beyond_high_low', 'allow_orders_between_high_low',
        'trade_equity_as_units', 'auto_square_off', 'auto_square_off_percentage', 'notify_percentage',
        
        // MCX related fields
        'mcx_lot_margin_json', 'mcx_lot_brokerage_json', 'mcx_bid_gap_json',
        
        // NSE and MCX options
        'nse_futures_enabled', 'nse_options_enabled', 'mcx_options_enabled',
        
        // Lot limits
        'nse_futures_max_lot_per_scrip', 'nse_options_max_lot_per_scrip', 'mcx_options_max_lot_per_scrip',
        
        // Brokerage
        'nse_futures_brokerage', 'nse_options_brokerage', 'mcx_options_brokerage',
        
        // Holding margins
        'nse_futures_holding_margin', 'nse_options_holding_margin', 'mcx_options_holding_margin',
        
        // Short selling
        'nse_futures_short_selling_allowed', 'nse_options_short_selling_allowed', 'mcx_options_short_selling_allowed',
        
        // Authentication
        'trans_pass', 'referral_code',
        
        // Comex Trading
        'comex_trading_enabled', 'comex_brokerage_type', 'comex_brokerage',
        'minimum_lots_single_comex', 'maximum_lots_comex', 'maximum_lots_allowed',
        'max_size_all_comex', 'intraday_exposure_margin_comex', 'holding_exposure_margin_comex',
        'orders_price_comex',
        
        // Forex Trading
        'forex_trading_enabled', 'forex_brokerage_type', 'forex_brokerage',
        'minimum_lots_single_forex', 'maximum_lots_forex', 'maximum_lots_allowed_forex',
        'max_size_all_forex', 'intraday_exposure_margin_forex', 'holding_exposure_margin_forex',
        'orders_price_forex',
        
        // Crypto Trading
        'crypto_trading_enabled', 'crypto_brokerage_type', 'crypto_brokerage',
        'minimum_lots_single_crypto', 'maximum_lots_crypto', 'maximum_lots_allowed_crypto',
        'max_size_all_crypto', 'intraday_exposure_margin_crypto', 'holding_exposure_margin_crypto',
        'orders_price_crypto',
        
        // Tracking fields
        'created_by', 'modified_by', 'created_at', 'updated_at'
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

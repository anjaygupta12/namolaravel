<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class TradeUser extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable;
    
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
    protected $primaryKey = 'id';
    
  // In your User model
protected $fillable = [
    'id',
    'user_id',
    'broker_id',
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
    'StopTrade',
    'AllowOrdersBeyondHighLow',
    'AllowOrdersBetweenHighLow',
    'TradeEquityAsUnits',
    'AutoSquareOff',
    'AutoSquareOffPercentage',
    'NotifyPercentage',
    'ProfitBookInterval',
    'MCXEnabled',
    'MCXMinLotPerTrade',
    'MCXMaxLotPerTrade',
    'MCXMaxLotPerScrip',
    'MaxCommodityLots',
    'MCXBrokerage',
    'MCXBrokerageType',
    'MCXExposureType',
    'MCXIntradayMargin',
    'MCXHoldingMargin',
    'MCXLotMarginJSON',
    'MCXLotBrokerageJSON',
    'MCXBidGapJSON',
    'NSEFuturesEnabled',
    'NSEFuturesBrokerage',
    'NSEFuturesMinLotPerTrade',
    'NSEFuturesMaxLotPerTrade',
    'NSEIndexMinLotPerTrade',
    'NSEIndexMaxLotPerTrade',
    'NSEFuturesMaxLotPerScrip',
    'NSEIndexMaxLotPerScrip',
    'MaxNSEFuturesLots',
    'MaxNSEIndexLots',
    'NSEFuturesIntradayMargin',
    'NSEFuturesHoldingMargin',
    'NSEBidGapPercentage',
    'NSEOptionsEnabled',
    'EquityOptionsEnabled',
    'MCXOptionsEnabled',
    'OptionsBrokerageType',
    'OptionsBrokerage',
    'OptionsEquityBrokerageType',
    'OptionsEquityBrokerage',
    'OptionsMCXBrokerageType',
    'OptionsMCXBrokerage',
    'OptionsMinimumBid',
    'OptionsShortSellingAllowed',
    'OptionsEquityShortSellingAllowed',
    'OptionsMCXShortSellingAllowed',
    'OptionsEquityMinLotPerTrade',
    'OptionsEquityMaxLotPerTrade',
    'OptionsIndexMinLotPerTrade',
    'OptionsIndexMaxLotPerTrade',
    'OptionsMCXMinLotPerTrade',
    'OptionsMCXMaxLotPerTrade',
    'OptionsEquityMaxLotPerScrip',
    'OptionsIndexMaxLotPerScrip',
    'OptionsMCXMaxLotPerScrip',
    'MaxOptionsEquityLots',
    'MaxOptionsIndexLots',
    'MaxOptionsMCXLots',
    'OptionsIntradayMargin',
    'OptionsHoldingMargin',
    'OptionsEquityIntradayMargin',
    'OptionsEquityHoldingMargin',
    'OptionsMCXIntradayMargin',
    'OptionsMCXHoldingMargin',
    'OptionsBidGapPercentage',
    'OptionsSSBrokerageType',
    'OptionsSSBrokerage',
    'OptionsSSEquityBrokerageType',
    'OptionsSSEquityBrokerage',
    'OptionsSSMCXBrokerageType',
    'OptionsSSMCXBrokerage',
    'OptionsSSEquityMinLotPerTrade',
    'OptionsSSEquityMaxLotPerTrade',
    'OptionsSSMCXMinLotPerTrade',
    'OptionsSSMCXMaxLotPerTrade',
    'OptionsSSIndexMinLotPerTrade',
    'OptionsSSIndexMaxLotPerTrade',
    'OptionsSSEquityMaxLotPerScrip',
    'OptionsSSIndexMaxLotPerScrip',
    'OptionsSSMCXMaxLotPerScrip',
    'MaxOptionsSSEquityLots',
    'MaxOptionsSSIndexLots',
    'MaxOptionsSSMCXLots',
    'OptionsSSIntradayMargin',
    'OptionsSSHoldingMargin',
    'OptionsSSEquityIntradayMargin',
    'OptionsSSEquityHoldingMargin',
    'OptionsSSMCXIntradayMargin',
    'OptionsSSMCXHoldingMargin',
    'Notes',
    'BrokerId',
    'TransPass',
    'CreatedBy',
    'CreatedDate',
    'ModifiedBy',
    'ModifiedDate',
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
    'is_active',
    'mcx_brokerage_type',
    'balance',
    'deposits',
    'withdrawals',
    'net_p_l',
    'comex_trading_enabled',
     'comex_brokerage_type',
    'comex_brokerage',
    'minimum_lots_single_comex',
    'maximum_lots_comex',
    'maximum_lots_allowed',
    'max_size_all_comex',
    'intraday_exposure_margin_comex',
    'holding_exposure_margin_comex',
    'orders_price_comex',

    // Forex
    'forex_trading_enabled',
    'forex_brokerage_type',
    'forex_brokerage',
    'minimum_lots_single_forex',
    'maximum_lots_forex',
    'maximum_lots_allowed_forex',
    'max_size_all_forex',
    'intraday_exposure_margin_forex',
    'holding_exposure_margin_forex',
    'orders_price_forex',

    // Crypto
    'crypto_trading_enabled',
    'crypto_brokerage_type',
    'crypto_brokerage',
    'minimum_lots_single_crypto',
    'maximum_lots_crypto',
    'maximum_lots_allowed_crypto',
    'max_size_all_crypto',
    'intraday_exposure_margin_crypto',
    'holding_exposure_margin_crypto',
    'orders_price_crypto',
    'device_id',
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
        'NSEFuturesMinLotPerTrade',
        'NSEFuturesMaxLotPerTrade',
        'NSEFuturesIntradayMargin',
        'profit_book_interval',
        'funds',
        'Notes'
        // Date fields
    ];
    
    protected $hidden = [
        'password', 'trans_pass'
    ];
    
    /**
     * Get the user's full balance (including all transactions)
     */
    
    /**
     * Relationship with user transactions
     */
    public function transactions()
    {
        return $this->hasMany(Transdetail::class, 'MemberId','id');
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
        public function bidamount()
    {
        return $this->hasMany(MarketBidMaster::class, 'UserId', 'id');
    }
    
    /**
     * Relationship with withdrawals (legacy)
     */
    public function withdrawals()
    {
        return $this->hasMany(WithdrawlMaster::class, 'UserId', 'id');
    }

        public function broker()
    {
       return $this->belongsTo(AdminLogin::class, 'broker_id', 'PK_ID');
    }

       public function reset()
    {
        // General
        $this->funds = 0;
         $this->deposits = 0;
        $this->withdrawals = 0;
        $this->net_p_l = 0;
        $this->broker_id = 0;
        $this->MCXBrokerage = 0;

        $this->broker_id = 0;
        $this->Notes = null;
        $this->IsDemo = 0;
        $this->StopTrade = 0;
        $this->IsActive = 1;
        $this->AutoSquareOff = 0;
        $this->TradeEquityAsUnits = 0;
        $this->AllowOrdersBeyondHighLow = 0;
        $this->AllowOrdersBetweenHighLow = 0;

        // Risk management
        $this->AutoSquareOffPercentage = 90;
        $this->NotifyPercentage = 70;
        $this->ProfitBookInterval = 120;

        // MCX
        $this->MCXEnabled = 0;
        $this->MCXMinLotPerTrade = 0;
        $this->MCXMaxLotPerTrade = 20;
        $this->MCXMaxLotPerScrip = 50;
        $this->MaxCommodityLots = 100;
        $this->mcx_brokerage_type = 'per_turnover';
        $this->MCXBrokerage = 0;
        $this->MCXExposureType = 'per_turnover';
        $this->MCXIntradayMargin = 0;
        $this->MCXHoldingMargin = 0;
        $this->MCXLotMarginJSON = json_encode([]);
        $this->MCXLotBrokerageJSON = json_encode([]);
        $this->MCXBidGapJSON = json_encode([]);

        // NSE Futures
        $this->NSEFuturesEnabled = 0;
        $this->NSEFuturesBrokerage = 0;
        $this->NSEFuturesMinLotPerTrade = 0;
        $this->NSEFuturesMaxLotPerTrade = 50;
        $this->NSEIndexMinLotPerTrade = 0;
        $this->NSEIndexMaxLotPerTrade = 20;
        $this->NSEFuturesMaxLotPerScrip = 100;
        $this->NSEIndexMaxLotPerScrip = 100;
        $this->MaxNSEFuturesLots = 100;
        $this->MaxNSEIndexLots = 100;
        $this->NSEFuturesIntradayMargin = 500;
        $this->NSEFuturesHoldingMargin = 100;
        $this->NSEBidGapPercentage = 0;

        // NSE Options
        $this->NSEOptionsEnabled = 0;
        $this->EquityOptionsEnabled = 0;
        $this->OptionsBrokerageType = 'per_lot';
        $this->OptionsBrokerage = 25;
        $this->OptionsEquityBrokerageType = 'per_lot';
        $this->OptionsEquityBrokerage = 40;
        $this->OptionsMinimumBid = 2;
        $this->OptionsShortSellingAllowed = 1;
        $this->OptionsEquityShortSellingAllowed = 0;
        $this->OptionsEquityMinLotPerTrade = 1;
        $this->OptionsEquityMaxLotPerTrade = 10;
        $this->OptionsIndexMinLotPerTrade = 1;
        $this->OptionsIndexMaxLotPerTrade = 20;
        $this->OptionsEquityMaxLotPerScrip = 10;
        $this->OptionsIndexMaxLotPerScrip = 20;
        $this->MaxOptionsEquityLots = 10;
        $this->MaxOptionsIndexLots = 10;
        $this->OptionsIntradayMargin = 7;
        $this->OptionsHoldingMargin = 3;
        $this->OptionsEquityIntradayMargin = 10;
        $this->OptionsEquityHoldingMargin = 3;
        $this->OptionsBidGapPercentage = 0;

        // MCX Options
        $this->MCXOptionsEnabled = 0;
        $this->OptionsMCXBrokerageType = 'per_lot';
        $this->OptionsMCXBrokerage = 50;
        $this->OptionsMCXShortSellingAllowed = 0;
        $this->OptionsMCXMinLotPerTrade = 1;
        $this->OptionsMCXMaxLotPerTrade = 20;
        $this->OptionsMCXMaxLotPerScrip = 10;
        $this->MaxOptionsMCXLots = 10;
        $this->OptionsMCXIntradayMargin = 10;
        $this->OptionsMCXHoldingMargin = 3;

        // Options Shortselling Config
        $this->OptionsSSBrokerageType = 'per_lot';
        $this->OptionsSSBrokerage = 50;
        $this->OptionsSSEquityBrokerageType = 'per_lot';
        $this->OptionsSSEquityBrokerage = 20;
        $this->OptionsSSMCXBrokerageType = 'per_lot';
        $this->OptionsSSMCXBrokerage = 20;
        $this->OptionsSSEquityMinLotPerTrade = 0;
        $this->OptionsSSEquityMaxLotPerTrade = 2;
        $this->OptionsSSMCXMinLotPerTrade = 1;
        $this->OptionsSSMCXMaxLotPerTrade = 3;
        $this->OptionsSSIndexMinLotPerTrade = 0;
        $this->OptionsSSIndexMaxLotPerTrade = 10;
        $this->OptionsSSEquityMaxLotPerScrip = 5;
        $this->OptionsSSIndexMaxLotPerScrip = 10;
        $this->OptionsSSMCXMaxLotPerScrip = 7;
        $this->MaxOptionsSSEquityLots = 10;
        $this->MaxOptionsSSIndexLots = 10;
        $this->MaxOptionsSSMCXLots = 30;
        $this->OptionsSSIntradayMargin = 2;
        $this->OptionsSSHoldingMargin = 1;
        $this->OptionsSSEquityIntradayMargin = 3;
        $this->OptionsSSEquityHoldingMargin = 2;
        $this->OptionsSSMCXIntradayMargin = 5;
        $this->OptionsSSMCXHoldingMargin = 3;

        return $this;
    }
}

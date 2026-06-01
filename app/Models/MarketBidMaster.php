<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketBidMaster extends Model
{
     use SoftDeletes;
     
    protected $table = 'marketbidmaster';
    protected $primaryKey = 'Pk_id';
    public $timestamps = false;
    
    protected $fillable = [
        'trade_id','device_id','TransactionId', 'Mode', 'ToAmount', 'TransactionMode', 'SalePrice', 'BuyPrice',
        'Bid', 'Ask', 'High', 'Low', 'TradeLast', 'Change', 'TradeOpen',
        'Volume', 'LastTradeQty', 'Atp', 'LotSize','quentity', 'OpenInterest',
        'BidQty', 'AskQty', 'PrevClose', 'UpperCircuit', 'LowerCircuit',
        'Timestamp', 'Lastmodify', 'Isactive', 'UserId', 'Symbol','IsOrder',
        'IsMin', 'Lots', 'Price', 'IpAddress','OPTION','Min','Mega','holding_margin_req',
        'used_margin_req','brokrage','activeConditions','nse_type','sold_by','Bought_by','sell_ip_address','is_qty','is_execute'
    ];
    
    protected $casts = [
        'ToAmount' => 'decimal:2',
        'SalePrice' => 'decimal:2',
        'BuyPrice' => 'decimal:2',
        'Bid' => 'decimal:2',
        'Ask' => 'decimal:2',
        'High' => 'decimal:2',
        'Low' => 'decimal:2',
        'TradeLast' => 'decimal:2',
        'Change' => 'decimal:2',
        'TradeOpen' => 'decimal:2',
        'Volume' => 'decimal:2',
        'LastTradeQty' => 'decimal:2',
        'Atp' => 'decimal:2',
        'OpenInterest' => 'decimal:2',
        'BidQty' => 'decimal:2',
        'AskQty' => 'decimal:2',
        'PrevClose' => 'decimal:2',
        'UpperCircuit' => 'decimal:2',
        'LowerCircuit' => 'decimal:2',
        'Price' => 'decimal:2',
        'Timestamp' => 'datetime',
        'Lastmodify' => 'datetime'
    ];

    public function getCreatedAtAttribute($value)
{
    return \Carbon\Carbon::parse($value)->setTimezone('Asia/Kolkata')->format('Y-m-d h:i:s A');
}

public function getUpdatedAtAttribute($value)
{
    return \Carbon\Carbon::parse($value)->setTimezone('Asia/Kolkata')->format('Y-m-d h:i:s A');
}

        public function user()
    {
           return $this->belongsTo(TradeUser::class, 'UserId');
    }

}

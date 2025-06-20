<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketPlaceMaster extends Model
{
    protected $table = 'marketplacemaster';
    protected $primaryKey = 'Pk_id';
    public $timestamps = false;
    
    protected $fillable = [
        'TransactionId', 'Mode', 'ToAmount', 'TransactionMode',
        'Bid', 'Ask', 'High', 'Low', 'TradeLast', 'Change', 'TradeOpen',
        'Volume', 'LastTradeQty', 'Atp', 'LotSize', 'OpenInterest',
        'BidQty', 'AskQty', 'PrevClose', 'UpperCircuit', 'LowerCircuit',
        'Timestamp', 'Lastmodify', 'Isactive', 'UserId', 'Symbol',
        'IsMin', 'IsMega', 'Lots', 'Price', 'Status_Exec', 'Exitrate',
        'BUYPRICE', 'SELLPRICE', 'IpAddress'
    ];
    
    protected $casts = [
        'ToAmount' => 'decimal:2',
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
        'LotSize' => 'decimal:2',
        'OpenInterest' => 'decimal:2',
        'BidQty' => 'decimal:2',
        'AskQty' => 'decimal:2',
        'PrevClose' => 'decimal:2',
        'UpperCircuit' => 'decimal:2',
        'LowerCircuit' => 'decimal:2',
        'Lots' => 'decimal:2',
        'Price' => 'decimal:2',
        'Exitrate' => 'decimal:2',
        'BUYPRICE' => 'decimal:2',
        'SELLPRICE' => 'decimal:2',
        'Timestamp' => 'datetime',
        'Lastmodify' => 'datetime',
        'Isactive' => 'boolean'
    ];
}

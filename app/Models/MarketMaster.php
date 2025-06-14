<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketMaster extends Model
{
    protected $table = 'MarketMaster';
    protected $primaryKey = 'PK_Id';
    public $timestamps = false;
    
    protected $fillable = [
        'Symbol', 'Name', 'MarketType', 'Bid', 'Ask', 'High', 'Low', 
        'TradeLast', 'Change', 'TradeOpen', 'Volume', 'LastTradeQty',
        'Atp', 'LotSize', 'OpenInterest', 'BidQty', 'AskQty', 'PrevClose',
        'UpperCircuit', 'LowerCircuit', 'Timestamp', 'LastModify', 'Isactive'
    ];
    
    protected $casts = [
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
        'Timestamp' => 'datetime',
        'LastModify' => 'datetime',
        'Isactive' => 'boolean'
    ];
}

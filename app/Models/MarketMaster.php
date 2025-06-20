<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketMaster extends Model
{
    protected $table = 'marketmaster';
    protected $primaryKey = 'ScriptId';
    public $timestamps = false;
    
    protected $fillable = ['ScriptName','MarketType','LotSize','TickSize',
        'Timestamp','CreatedBy','CreatedAt','UpdatedBy','UpdatedAt','LastModify','Isactive'
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

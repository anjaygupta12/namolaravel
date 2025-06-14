<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForexOption extends Model
{
    protected $table = 'ForexOptions';
    protected $primaryKey = 'Id';
    public $timestamps = false;
    
    protected $fillable = [
        'Symbol', 'Description', 'Exchange', 'Segment', 'TickSize',
        'TradingSession', 'ExpiryDate', 'InstrumentToken', 'ExchangeInstrument',
        'LotSize', 'InstrumentType', 'FyToken', 'Underlying', 'Multiplier',
        'StrikePrice', 'OptionType', 'FyTokenUnderlying', 'LastUpdateDate',
        'Isactive', 'ExName', 'SymbolShortName', 'DataDate', 'instrument'
    ];
    
    protected $casts = [
        'Exchange' => 'integer',
        'Segment' => 'integer',
        'TickSize' => 'decimal:6',
        'ExpiryDate' => 'date',
        'InstrumentToken' => 'integer',
        'LotSize' => 'integer',
        'InstrumentType' => 'integer',
        'FyToken' => 'integer',
        'Multiplier' => 'integer',
        'StrikePrice' => 'decimal:2',
        'FyTokenUnderlying' => 'integer',
        'LastUpdateDate' => 'datetime',
        'Isactive' => 'boolean',
        'DataDate' => 'date'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $table = 'positions';
    protected $fillable = [
        'user_id', 'symbol', 'position_type', 'quantity', 'entry_price',
        'current_price', 'stop_loss', 'take_profit', 'pnl', 'status',
        'opened_at', 'closed_at'
    ];
    
    public $timestamps = false;
    
    protected $casts = [
        'quantity' => 'integer',
        'entry_price' => 'decimal:2',
        'current_price' => 'decimal:2',
        'stop_loss' => 'decimal:2',
        'take_profit' => 'decimal:2',
        'pnl' => 'decimal:2',
        'opened_at' => 'datetime',
        'closed_at' => 'datetime'
    ];
    
    /**
     * Get the user that owns the position.
     */
    public function user()
    {
        return $this->belongsTo(TradeUser::class, 'user_id', 'PK_Id');
    }
    
    /**
     * Get the trade history for this position.
     */
    public function history()
    {
        return $this->hasMany(TradeHistory::class, 'trade_id', 'id');
    }
    
    /**
     * Scope a query to only include open positions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }
    
    /**
     * Scope a query to only include closed positions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }
}

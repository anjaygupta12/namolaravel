<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradeHistory extends Model
{
    protected $table = 'trade_history';
    protected $fillable = [
        'user_id', 'trade_id', 'action', 'price', 'quantity', 'total'
    ];
    
    public $timestamps = false;
    
    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'total' => 'decimal:2',
        'created_at' => 'datetime'
    ];
    
    /**
     * Get the user that owns the trade history.
     */
    public function user()
    {
        return $this->belongsTo(TradeUser::class, 'user_id', 'PK_Id');
    }
    
    /**
     * Get the position that this history belongs to.
     */
    public function position()
    {
        return $this->belongsTo(Position::class, 'trade_id', 'id');
    }
}

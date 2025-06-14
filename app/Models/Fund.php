<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fund extends Model
{
    protected $table = 'funds';
    protected $fillable = [
        'user_id', 'balance', 'equity', 'margin', 'free_margin', 'margin_level'
    ];
    
    public $timestamps = false;
    
    protected $casts = [
        'balance' => 'decimal:2',
        'equity' => 'decimal:2',
        'margin' => 'decimal:2',
        'free_margin' => 'decimal:2',
        'margin_level' => 'decimal:2',
        'updated_at' => 'datetime'
    ];
    
    /**
     * Get the user that owns the funds.
     */
    public function user()
    {
        return $this->belongsTo(TradeUser::class, 'user_id', 'PK_Id');
    }
}

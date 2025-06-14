<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientSubscription extends Model
{
    protected $table = 'ClientSubscription';
    protected $primaryKey = 'PK_ID';
    public $timestamps = false;
    
    protected $fillable = [
        'Symbol', 'UserId', 'Timestamp', 'LastModify', 'Isactive'
    ];
    
    protected $casts = [
        'Timestamp' => 'datetime',
        'LastModify' => 'datetime',
        'Isactive' => 'boolean'
    ];
    
    // Relationship with TradeUser
    public function user()
    {
        return $this->belongsTo(TradeUser::class, 'UserId', 'PK_Id');
    }
    
    // Relationship with MarketMaster
    public function market()
    {
        return $this->belongsTo(MarketMaster::class, 'Symbol', 'Symbol');
    }
}

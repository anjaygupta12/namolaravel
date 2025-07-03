<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawlMaster extends Model
{
    protected $table = 'withdrawlmaster';
    protected $primaryKey = 'PK_Id';
    public $timestamps = false;
    
    protected $fillable = [
        'UserId', 'PaymentMethod', 'Amount', 'Mobile', 'AccountHolder',
        'AccountNo', 'IFSC', 'Status', 'Timestamp', 'LastModify', 'Isactive','notes'
    ];
    
    protected $casts = [
        'Amount' => 'decimal:2',
        'Timestamp' => 'datetime',
        'LastModify' => 'datetime',
        'Isactive' => 'boolean'
    ];
    
    // Relationship with TradeUser
    public function user()
    {
        return $this->belongsTo(TradeUser::class, 'UserId', 'UserId');
    }
}

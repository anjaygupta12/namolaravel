<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class DepositeMaster extends Model
{
    protected $table = 'depositemaster';
    protected $primaryKey = 'PK_Id';
    public $timestamps = false;
    
    protected $fillable = [
        'UserId', 'Amount', 'ScreenShot', 'Approve_Status', 'Approve_date',
        'Timestamp', 'LastModify', 'Isactive'
    ];
    
    protected $casts = [
        'Amount' => 'decimal:2',
        'Approve_date' => 'datetime',
        'Timestamp' => 'datetime',
        'LastModify' => 'datetime',
        'Isactive' => 'boolean'
    ];
    
    // Relationship with TradeUser
    public function user()
    {
        return $this->belongsTo(TradeUser::class, 'UserId', 'UserId');
    }
    
    /**
     * Scope a query to only include pending deposits.
     */
    public function scopePending($query)
    {
        return $query->where('Approve_Status', 'PENDING');
    }
    
    /**
     * Scope a query to only include approved deposits.
     */
    public function scopeApproved($query)
    {
        return $query->where('Approve_Status', 'APPROVED');
    }
    
    /**
     * Scope a query to only include rejected deposits.
     */
    public function scopeRejected($query)
    {
        return $query->where('Approve_Status', 'REJECTED');
    }
}

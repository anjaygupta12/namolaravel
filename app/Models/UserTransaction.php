<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTransaction extends Model
{
    protected $table = 'user_transactions';
    
    protected $fillable = [
        'user_id', 'type', 'amount', 'status', 'description', 
        'transaction_id', 'payment_method', 'payment_details',
        'created_by', 'approved_by', 'created_at', 'updated_at'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'payment_details' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    /**
     * Get the user that owns the transaction.
     */
    public function user()
    {
        return $this->belongsTo(TradeUser::class, 'user_id');
    }
    
    /**
     * Get the admin who created the transaction.
     */
    public function creator()
    {
        return $this->belongsTo(AdminLogin::class, 'created_by');
    }
    
    /**
     * Get the admin who approved the transaction.
     */
    public function approver()
    {
        return $this->belongsTo(AdminLogin::class, 'approved_by');
    }
    
    /**
     * Scope a query to only include deposits.
     */
    public function scopeDeposits($query)
    {
        return $query->where('type', 'deposit');
    }
    
    /**
     * Scope a query to only include withdrawals.
     */
    public function scopeWithdrawals($query)
    {
        return $query->where('type', 'withdrawal');
    }
    
    /**
     * Scope a query to only include bonuses.
     */
    public function scopeBonuses($query)
    {
        return $query->where('type', 'bonus');
    }
    
    /**
     * Scope a query to only include completed transactions.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
    
    /**
     * Scope a query to only include pending transactions.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    /**
     * Scope a query to only include rejected transactions.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}

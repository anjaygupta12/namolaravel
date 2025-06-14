<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserKyc extends Model
{
    protected $table = 'user_kyc';
    protected $fillable = [
        'user_id', 'document_type', 'document_number', 'document_front',
        'document_back', 'status', 'remarks', 'verified_by', 'verified_at'
    ];
    
    protected $casts = [
        'verified_at' => 'datetime',
    ];
    
    /**
     * Get the user that owns the KYC.
     */
    public function user()
    {
        return $this->belongsTo(TradeUser::class, 'user_id', 'PK_Id');
    }
    
    /**
     * Get the admin who verified the KYC.
     */
    public function verifier()
    {
        return $this->belongsTo(AdminLogin::class, 'verified_by', 'PK_Id');
    }
    
    /**
     * Scope a query to only include pending KYC documents.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    /**
     * Scope a query to only include approved KYC documents.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
    
    /**
     * Scope a query to only include rejected KYC documents.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}

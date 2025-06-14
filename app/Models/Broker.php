<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Broker extends Model
{
    protected $table = 'brokers';
    
    protected $fillable = [
        'name', 'api_key', 'api_secret', 'api_url', 'margin_percentage',
        'commission_percentage', 'description', 'is_active'
    ];
    
    protected $casts = [
        'margin_percentage' => 'decimal:2',
        'commission_percentage' => 'decimal:2',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    /**
     * Get the M2M data for this broker
     */
    public function m2mData()
    {
        return $this->hasMany(BrokerM2M::class, 'broker_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrokerM2M extends Model
{
    protected $table = 'broker_m2m';
    
    protected $fillable = [
        'broker_id', 'date', 'opening_balance', 'closing_balance', 
        'profit_loss', 'notes', 'created_by'
    ];
    
    protected $casts = [
        'date' => 'date',
        'opening_balance' => 'decimal:2',
        'closing_balance' => 'decimal:2',
        'profit_loss' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    /**
     * Get the broker that owns the M2M data.
     */
    public function broker()
    {
        return $this->belongsTo(Broker::class, 'broker_id');
    }
    
    /**
     * Get the admin who created the M2M data.
     */
    public function admin()
    {
        return $this->belongsTo(AdminLogin::class, 'created_by', 'PK_ID');
    }
}

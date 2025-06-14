<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginMaster extends Model
{
    protected $table = 'LoginMaster';
    protected $primaryKey = 'PK_ID';
    public $timestamps = false;
    
    protected $fillable = [
        'FullName', 'Mobile', 'UserId', 'Password', 'RefferalCode',
        'Timestamp', 'LasmtModify', 'Isactive', 'TransPass'
    ];
    
    protected $casts = [
        'Timestamp' => 'datetime',
        'LasmtModify' => 'datetime',
        'Isactive' => 'boolean'
    ];
    
    protected $hidden = [
        'Password',
        'TransPass'
    ];
    
    /**
     * Get the trade user associated with this login.
     */
    public function tradeUser()
    {
        return $this->belongsTo(TradeUser::class, 'UserId', 'Username');
    }
}

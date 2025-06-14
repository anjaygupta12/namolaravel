<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LedgerHistory extends Model
{
    protected $table = 'LedgerHistory';
    protected $primaryKey = 'PK_ID';
    public $timestamps = false;
    
    protected $fillable = [
        'MESSAGETYPE', 'TIMESTAMP', 'LASTMODIFY', 'IS_ADMIN', 'ISACTIVE'
    ];
    
    protected $casts = [
        'TIMESTAMP' => 'datetime',
        'LASTMODIFY' => 'datetime',
        'ISACTIVE' => 'boolean'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrokerDeleteHistory extends Model
{
    protected $table = 'broker_deletehistory';
    protected $primaryKey = 'DeleteId';
    public $timestamps = false;
    
    protected $fillable = [
        'BrokerId', 'Username', 'FirstName', 'LastName', 'RefCode', 
        'UserType', 'DeletedBy', 'DeletedAt'
    ];
    
    protected $casts = [
        'DeletedAt' => 'datetime'
    ];
    
    // Relationship with AdminLogin (who deleted the broker)
    public function deletedBy()
    {
        return $this->belongsTo(AdminLogin::class, 'DeletedBy', 'PK_Id');
    }
}

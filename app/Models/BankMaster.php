<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankMaster extends Model
{
    protected $table = 'BankMaster';
    protected $primaryKey = 'PK_Id';
    public $timestamps = false;
    
    protected $fillable = [
       'PK_ID','AdminID','AccountHolder','AccountNumber','BankName','IFSC','PhonePe','GooglePay','Paytm',
       'UPIID','QRCode','Timestamp','LastModify','Isactive'
    ];
    
    protected $casts = [
        'Timestamp' => 'datetime',
        'LastModify' => 'datetime',
        'Isactive' => 'boolean'
    ];
}

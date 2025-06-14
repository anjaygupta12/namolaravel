<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transdetail extends Model
{
    protected $table = 'Transdetail';
    protected $primaryKey = 'Pk_id';
    public $timestamps = false;
    
    protected $fillable = [
        'MemberId', 'TransType', 'TransPage', 'Type', 'TransDate', 'Amount', 
        'AmountS', 'Remark', 'LoginId', 'Pass', 'Expass', 'BitIsActive', 
        'CounterId', 'eWalletBit', 'PayoutId', 'tmpStr', 'productcode', 
        'RefTransID', 'AddRemark', 'ProductClaim', 'PayMode', 'PayRemark', 
        'TransId', 'TransHash', 'VoucherNO', 'TopUpTranId', 'AdminStatus'
    ];
    
    protected $casts = [
        'TransDate' => 'datetime',
        'Amount' => 'decimal:2',
        'AmountS' => 'decimal:2',
        'BitIsActive' => 'boolean',
        'eWalletBit' => 'boolean'
    ];
    
    protected $hidden = [
        'Pass', 'Expass',
    ];
}

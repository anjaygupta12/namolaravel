<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LotSize extends Model
{
    protected $table = 'lot_size';
    protected $fillable  =['name','qty','market'];   

}

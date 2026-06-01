<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketCalendar extends Model
{
    protected $table = 'market_calendar';

    protected $fillable = [
        'market',
        'type',
        'start_time',
        'end_time',
        'holiday_date',
        'title'
    ];
}

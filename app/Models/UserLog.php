<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    protected $table = 'user_logs';
    protected $fillable = ['user_id', 'action', 'ip_address', 'user_agent'];
    public $timestamps = false;
    
    protected $casts = [
        'created_at' => 'datetime'
    ];
    
    /**
     * Get the user that owns the log.
     */
    public function user()
    {
        return $this->belongsTo(TradeUser::class, 'user_id', 'PK_Id');
    }
    
    /**
     * Log a user action
     *
     * @param int $userId
     * @param string $action
     * @param string $ipAddress
     * @param string $userAgent
     * @return UserLog
     */
    public static function logAction($userId, $action, $ipAddress = null, $userAgent = null)
    {
        return self::create([
            'user_id' => $userId,
            'action' => $action,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent
        ]);
    }
}

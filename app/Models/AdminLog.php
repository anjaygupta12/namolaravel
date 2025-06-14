<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    protected $table = 'admin_logs';
    protected $fillable = ['admin_id', 'activity', 'ip_address'];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    /**
     * Get the admin that owns the log.
     */
    public function admin()
    {
        return $this->belongsTo(AdminLogin::class, 'admin_id', 'PK_ID');
    }
    
    /**
     * Log an admin action
     *
     * @param int $adminId
     * @param string $activity
     * @param string $ipAddress
     * @return AdminLog
     */
    public static function logAction($adminId, $activity, $ipAddress = null)
    {
        return self::create([
            'admin_id' => $adminId,
            'activity' => $activity,
            'ip_address' => $ipAddress
        ]);
    }
}

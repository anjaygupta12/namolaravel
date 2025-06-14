<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLogin extends Model
{
    protected $table = 'adminlogin';
    protected $primaryKey = 'PK_ID';
    public $timestamps = false;
    
    protected $fillable = [
        'UserName', 'Password', 'TransPass', 'Name', 'Mobile', 'Email', 'UserType',
        'Timestamp', 'LastModify', 'Isactive'
    ];
    
    protected $hidden = [
        'Password', 'TransPass'
    ];
    
    protected $casts = [
        'Timestamp' => 'datetime',
        'LastModify' => 'datetime',
        'Isactive' => 'boolean'
    ];
    
    /**
     * Get all logs for this admin.
     */
    public function logs()
    {
        return $this->hasMany(AdminLog::class, 'admin_id', 'PK_ID');
    }
    
    /**
     * Get all notifications created by this admin.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'created_by', 'PK_ID');
    }
}

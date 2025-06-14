<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    
    protected $fillable = [
        'title', 'message', 'type', 'for_all_users', 'user_ids', 'created_by'
    ];
    
    protected $casts = [
        'for_all_users' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    /**
     * Get the admin who created the notification.
     */
    public function admin()
    {
        return $this->belongsTo(AdminLogin::class, 'created_by', 'PK_ID');
    }
    
    /**
     * Get the users this notification is for.
     * 
     * @return array
     */
    public function getUserIdsAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }
    
    /**
     * Set the users this notification is for.
     * 
     * @param array $value
     * @return void
     */
    public function setUserIdsAttribute($value)
    {
        $this->attributes['user_ids'] = $value ? json_encode($value) : null;
    }
    
    /**
     * Scope a query to only include notifications for a specific user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where(function($query) use ($userId) {
            $query->where('for_all_users', true)
                  ->orWhereRaw("JSON_CONTAINS(user_ids, '".$userId."')");
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = ['name', 'description'];
    
    /**
     * Get the permissions for the role.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id');
    }
    
    /**
     * Get the admins that belong to the role.
     */
    public function admins()
    {
        return $this->belongsToMany(AdminLogin::class, 'admin_roles', 'role_id', 'admin_id');
    }
}

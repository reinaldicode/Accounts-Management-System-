<?php
// app/Models/Role.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'description',
        'department',
        'section',
        'title',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'role_permissions',
            'role_id',
            'permission_id'
        );
    }

    public function userSystemAccess()
    {
        return $this->hasMany(UserSystemAccess::class, 'role_id');
    }

    // Helper methods
    public function hasPermission($permissionName)
    {
        return $this->permissions()->where('name', $permissionName)->exists();
    }

    public function grantPermission($permissionId)
    {
        return $this->permissions()->attach($permissionId);
    }

    public function revokePermission($permissionId)
    {
        return $this->permissions()->detach($permissionId);
    }
}
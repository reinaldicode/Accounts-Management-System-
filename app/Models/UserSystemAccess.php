<?php
// app/Models/UserSystemAccess.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSystemAccess extends Model
{
    protected $table = 'user_system_access';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'system_id',
        'role_id',
        'is_active',
        'granted_at',
        'granted_by',
        'expires_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'granted_at' => 'datetime',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'iduser');
    }

    public function system()
    {
        return $this->belongsTo(System::class, 'system_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function grantedBy()
    {
        return $this->belongsTo(User::class, 'granted_by', 'iduser');
    }

    // Helper methods
    public function isActive()
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    public function hasExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}
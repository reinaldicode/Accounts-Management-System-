<?php
// app/Models/System.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    protected $table = 'systems';
    public $timestamps = true;

    protected $fillable = [
        'system_code',
        'system_name',
        'system_url',
        'api_key',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function userAccess()
    {
        return $this->hasMany(UserSystemAccess::class, 'system_id');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'system_id');
    }

    // Helper methods
    public function isActive()
    {
        return $this->is_active === true;
    }

    public function validateApiKey($apiKey)
    {
        return $this->api_key === $apiKey;
    }
}
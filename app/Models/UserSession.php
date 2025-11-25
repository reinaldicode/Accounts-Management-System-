<?php
// app/Models/UserSession.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    protected $table = 'user_sessions';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'token',
        'refresh_token',
        'ip_address',
        'user_agent',
        'expires_at',
        'last_activity_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'iduser');
    }

    // Helper methods
    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    public function isValid()
    {
        return !$this->isExpired();
    }

    public function updateActivity()
    {
        $this->update(['last_activity_at' => now()]);
    }

    public function revoke()
    {
        return $this->delete();
    }
}
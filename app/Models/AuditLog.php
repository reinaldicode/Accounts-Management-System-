<?php
// app/Models/AuditLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'audit_logs';
    public $timestamps = false; // Only created_at
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'system_id',
        'action',
        'ip_address',
        'user_agent',
        'status',
        'additional_data',
    ];

    protected $casts = [
        'additional_data' => 'array',
        'created_at' => 'datetime',
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

    // Helper methods
    public static function log($userId, $systemId, $action, $status = 'success', $additionalData = null)
    {
        return self::create([
            'user_id' => $userId,
            'system_id' => $systemId,
            'action' => $action,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'status' => $status,
            'additional_data' => $additionalData,
        ]);
    }
}
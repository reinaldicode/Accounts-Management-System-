<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use SoftDeletes;

    protected $table = 'user';
    protected $primaryKey = 'iduser';
    public $timestamps = true;
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';

    protected $fillable = [
        'empid',
        'username',
        'password',
        'firstname',
        'middlename',
        'lastname',
        'slug',
        'email_corp',
        'email_personal',
        'extnum',
        'mobnum1',
        'mobnum2',
        'address1',
        'address2',
        'state',
        'city',
        'country',
        'postcode',
        'deptid',
        'sectid',
        'titleid',
        'globalid',
        'roleid',
        'active',
        'pwdexp',
        'havepc',
        'failed_login_attempts',
        'locked_until',
        'last_login_at',
        'inactivated_at',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'failed_login_attempts' => 'integer',
        'locked_until' => 'datetime',
        'last_login_at' => 'datetime',
        'pwdexp' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'inactivated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function systemAccess()
    {
        return $this->hasMany(UserSystemAccess::class, 'user_id', 'iduser');
    }

    public function sessions()
    {
        return $this->hasMany(UserSession::class, 'user_id', 'iduser');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'user_id', 'iduser');
    }

    public function passwordHistories()
    {
        return $this->hasMany(PasswordHistory::class, 'user_id', 'iduser');
    }

    // Helper methods
    public function isActive()
    {
        return $this->active === 'y';
    }

    public function isLocked()
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    public function incrementFailedAttempts()
    {
        $this->increment('failed_login_attempts');
    }

    public function resetFailedAttempts()
    {
        $this->update(['failed_login_attempts' => 0, 'locked_until' => null]);
    }

    public function lockAccount($minutes = 30)
    {
        $this->update([
            'locked_until' => now()->addMinutes($minutes)
        ]);
    }
}
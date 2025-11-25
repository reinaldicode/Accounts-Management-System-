<?php
// app/Models/PasswordHistory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordHistory extends Model
{
    protected $table = 'password_histories';
    public $timestamps = false;
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'iduser');
    }

    // Helper methods
    public static function addHistory($userId, $password)
    {
        return self::create([
            'user_id' => $userId,
            'password' => $password,
        ]);
    }

    public static function checkPasswordReuse($userId, $newPassword, $historyCount = 5)
    {
        $histories = self::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($historyCount)
            ->get();

        foreach ($histories as $history) {
            if ($history->password === $newPassword) {
                return true; // Password sudah pernah dipakai
            }
        }

        return false; // Password belum pernah dipakai
    }
}
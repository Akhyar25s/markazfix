<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetOtp extends Model
{
    protected $table = 'password_reset_otps';

    protected $fillable = [
        'identifier',
        'otp',
        'token',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Cek apakah OTP sudah kedaluwarsa.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}

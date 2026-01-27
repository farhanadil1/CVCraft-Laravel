<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    protected $fillable = [
        'email',
        'full_name',
        'password',
        'role',
        'refresh_token',
    ];

    protected $hidden = [
        'password',
        'refresh_token',
    ];

    /**
     * Auto-hash password 
     */
    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    /**
     * Compare password
     */
    public function comparePassword(string $password): bool
    {
        return Hash::check($password, $this->password);
    }

    /**
     * Role helpers
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }
    public function getNameAttribute()
    {
        return $this->full_name;
    }
}

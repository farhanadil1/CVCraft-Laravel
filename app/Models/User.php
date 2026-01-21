<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = [
        'email',
        'name',
        'password',
        'refresh_token',
    ];

    protected $hidden = [
        'password',
        'refresh_token',
    ];

    /**
     * Automatically hash password
     * (Equivalent to mongoose pre('save'))
     */
    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    /**
     * Compare password
     * (Equivalent to userSchema.methods.comparePassword)
     */
    public function comparePassword(string $password): bool
    {
        return Hash::check($password, $this->password);
    }
}

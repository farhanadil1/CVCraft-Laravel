<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    protected $fillable = [
        'user_id',
        'resume_name',
        'template_id',
        'form_data',
    ];

    protected $casts = [
        'form_data' => 'array',
    ];

    /**
     * Resume belongs to a User
     * (Equivalent to ref: 'User')
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

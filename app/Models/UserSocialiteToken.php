<?php

namespace App\Models;

class UserSocialiteToken extends BasicModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'provider',
        'token',
        'refresh_token',
        'expires_in'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

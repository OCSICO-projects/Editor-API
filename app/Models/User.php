<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'authy_id',
        'email_verified_at',
        'password',
        'phone_number_verified_at',
        'remember_token',
    ];

    public function socialiteTokens()
    {
        return $this->hasMany(UserSocialiteToken::class, 'user_id');
    }

    public function folders()
    {
        return $this->hasMany(Folder::class, 'user_id');
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function files()
    {
        return $this->hasMany(File::class, 'user_id');
    }

    public function resources()
    {
        return $this->hasMany(Resource::class, 'user_id');
    }

    public function hasVerifiedPhone()
    {
        return !!$this->phone_number_verified_at;
    }
}

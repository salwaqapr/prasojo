<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'nama',
        'email',
        'username',
        'password',
        'role',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];
}

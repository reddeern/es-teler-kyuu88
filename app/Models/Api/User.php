<?php

namespace App\Models\Api;

use App\Models\User as BaseUser;

class User extends BaseUser
{
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'email_verified_at',
    ];
}

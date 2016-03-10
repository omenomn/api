<?php

namespace Dandaj\Api\Models;

use Illuminate\Database\Eloquent\Model;

class OAuthUser extends Model
{
    protected $table = 'oauth_users';

    protected $fillable = ['login', 'password'];

    public function clients()
    {
        return $this->hasMany(OAuthClient::class, 'oauth_user_id');
    }
}

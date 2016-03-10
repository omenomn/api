<?php

namespace Dandaj\Api\Models;

use Illuminate\Database\Eloquent\Model;

class OAuthClient extends Model
{
    protected $table = 'oauth_clients';

    protected $fillable = ['external_id', 'secret', 'website_address', 'oauth_user_id'];

    public function tokens()
    {
        return $this->hasMany(OAuthToken::class, 'oauth_client_id');
    }
}

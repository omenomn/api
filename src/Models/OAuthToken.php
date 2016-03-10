<?php

namespace Dandaj\Api\Models;

use Illuminate\Database\Eloquent\Model;

class OAuthToken extends Model
{
    protected $table = 'oauth_tokens';

    protected $fillable = ['token', 'oauth_client_id'];
}

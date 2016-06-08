<?php

namespace Dandaj\Api\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Dandaj\Api\Models\OAuthToken as Token;
use Dandaj\Api\Http\Controllers\ApiController;

class Api extends ApiController
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->header('auth-token'))
            return $this->respondNotFound('You must send token in header');

        $token = Token::where('token', 'like', $request->header('auth-token'))
                        ->first();

        if (!$token)
            return $this->respondNotFound('Token does not correct');

        $dateNow = time();
        $tokenCreatedAt = strtotime($token->created_at);
        
        $diff = $dateNow - $tokenCreatedAt;
        
        if ($diff >= config('api.expire'))
            return $this->setStatusCode(403)
                ->respondWithError('Token is already expired');

        return $next($request);
    }
}

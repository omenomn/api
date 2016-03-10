<?php

namespace Dandaj\Api\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Dandaj\Api\Transformers\TokenTransformer;
use Dandaj\Api\Models\OAuthUser as User;
use Dandaj\Api\Models\OAuthToken as Token;


class AuthController extends ApiController
{
    protected $tokenTransformer;

    function __construct(TokenTransformer $tokenTransformer)
    {
        $this->tokenTransformer = $tokenTransformer;

    }

	public function test()
	{
		return 'test from AuthController';
	}

	public function verify(Request $request)
   	{
        $credentials = $request->json()->all();

        $validator = \Validator::make($credentials, [
            'login' => 'required',
            'password' => 'required',
            'client_id' => 'required',
            'client_secret' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(422)
                        ->respondWithError($validator->errors()->all());
        }

        $user = User::where('login', $credentials['login'])
                    ->first();

        if (!$user) 
            return $this->respondNotFound('Użytkownik nie istnieje');

        if (!\Hash::check($credentials['password'], $user->password)) 
            return $this->setStatusCode(422)
                        ->respondWithError('Hasło niepoprawne');


        $client = $user->clients()
                        ->where('external_id', $credentials['client_id'])
                        ->where('secret', $credentials['client_secret'])
                        ->first();

        if (!$client) 
            return $this->respondNotFound('Klient nie istnieje');

        $token = $client->tokens()
                        ->create(['token' => str_random(40)]);

        if (!$token) 
            return $this->respondNotFound('Nie udało się utworzyć tokena, spróbuj jeszcze raz');

        return $this->setStatusCode(200)->respond([
            'status' => 'success',
            'message' => 'Token successfully created',
            'token' => $this->tokenTransformer->transform($token),
        ]);  
    }

    public function verifyTest()
    {
        $data = [
            'login' => 'test',
            'password' => 'test',
            'client_id' => 'AIAZrzA6EUJweg11Zg7X',
            'client_secret' => 'qSGn1J5HxheJp7Wp3i4P7NCex92fFRCzRHiS2BpkMyJXRaxsYQxTYHt7KH8t',
        ];

        $data = json_encode($data);

        $url = route('api.token');

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            ]
        );

        $result = curl_exec($ch);

        dd($result);
    }
}
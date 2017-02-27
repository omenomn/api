<?php

namespace Dandaj\Api\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Dandaj\Api\Models\OAuthUser as User;
use Dandaj\Api\Models\OAuthToken as Token;


class AuthController extends ApiController
{

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
                        ->first();

        if (!$client) 
            return $this->respondNotFound('Klient nie istnieje');

        if (!\Hash::check($credentials['client_secret'], $client->secret)) 
            return $this->setStatusCode(422)
                        ->respondWithError('Klucz prywatny niepoprawny');

        $token = $client->tokens()
                        ->create(['token' => str_random(40)]);

        if (!$token) 
            return $this->respondNotFound('Nie udało się utworzyć tokena, spróbuj jeszcze raz');

        return $this->setStatusCode(200)->respond([
            'status' => 'success',
            'message' => 'Token successfully created',
            'token' => $token->token,
        ]);  
    }

    public function verifyTest()
    {
        $data = [
            'login' => config('api.defaultCredentials.login'),
            'password' => config('api.defaultCredentials.password'),
            'client_id' => config('api.defaultCredentials.client_id'),
            'client_secret' => config('api.defaultCredentials.client_secret'),
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

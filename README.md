# api
Small api package with authorization and token system

1. Add to composer.json if not exists
 "minimum-stability": "dev",
 "prefer-stable" : true
2. composer require dandaj/api
3. config/app.php add to providers: Dandaj\Api\ApiServiceProvider::class
4. php artisan vendor:publish
5. composer dumpautoload
6. Add to DatabaseSeeder.php to "run" function: $this->call('OAuthUsersTableSeeder');
7. php artisan migrate
8. php artisan db:seed
9. Transformers use example:
  1. Create UserTransformer class in the previously created folder Transformers or other name in the app folder and create public function transform with model param.
<?php
namespace App\Transformers;

Dandaj\Api\Transformers\Transformer;

class UserTransformer extends Transformer
{
  public function transform($example)
      {
  		return [
  			'first_name' => $example['first_name'],
  			'last_name' => $example['last_name'],
  			'external_id' => $example['external_id'],
  			'email' => $example['email'],
  		];
  	}
	}

1. Add to Http/kernel.php routeMiddleware - 'dandaj.api' => \Dandaj\Api\Http\Middleware\Api::class, to check route group for api auth user
2. To use models of package paste that's line in file:
  1. use Dandaj\Api\Models\OAuthClient;
  2. use Dandaj\Api\Models\OAuthToken;
  3. use Dandaj\Api\Models\OAuthUser;
3. if you want to create controller for api use this is example:

<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\User;
use Dandaj\Api\Http\Controllers\ApiController;
use App\Transformers\UserTransformer;

class UsersController extends ApiController
{
    protected $userTransformer;
  
  	function __construct(UserTransformer $userTransformer)
  	{
  		$this->userTransformer = $userTransformer;
  
  	}
  
  	public function index()
  	{
  		$users = User::all();
  
      // use transformCollection for return collection of users 
  		return $this->respond([
  			'data' => $this->userTransformer->transformCollection($users)),
  		]);
  	}
  	
    public function store(Request $request)
    {
      // get credentials
    	$credentials = $request->json()->all();

      // validate credentials
    	$validator = $this->validator($credentials);
      
      // if you use respondWithError you must first set status code, if not status code will have 200 value
      if ($validator->fails()) {
          return $this->setStatusCode(422)
                      ->respondWithError($validator->errors()->all());
      }
      
      // Some code to create user from api request
      //..

      // Response with the status code, message, and status
    	return $this->setStatusCode(200)->respond([
            'status' => 'success',
            'message' => 'Api controller work perfectly',
        ]);  

    }
    
    public function show($externalId)
  	{
  	  // get user
  		$user = User::where('external_id', $externalId)->first();
  
      // respondNotFound has status code 404
  		if (!$user) {
  			return $this->respondNotFound('User not found');
  		}
      
      // use transform function of userTransformer to response with one user
  		return $this->respond([
  			'data' => $this->userTransformer->transform($ticket),
  		]);
  	}
    
    
    protected function validator($data)
    {
      return \Validator::make($data, [
        //some validation rules
      ]);
    }
}

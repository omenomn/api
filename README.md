Welcome to dandaj/api!
===================


Small Api package with authorization and token system.

----------


Documents
-------------

 - Add to composer.json if not exists:

```
"minimum-stability": "dev", 
"prefer-stable" : true
```

 - composer require dandaj/api
 -  config/app.php add to providers:

 
```
Dandaj\Api\ApiServiceProvider::class,
```
 - php artisan vendor:publish
 - composer dumpautoload
 - Add to database/seeds/DatabaseSeeder.php to "run" function:

```
$this->call('OAuthUsersTableSeeder');
```

 - php artisan migrate
 - php artisan db:seed
 - Transformers example of use.
	 - Create UserTransformer class in the previously created folder Transformers or other name in the app folder and create public function transform with model param. Transformers serve to return selected fields of model in response:

```
<?php 

namespace App\Transformers;

use Dandaj\Api\Transformers\Transformer;

class UserTransformer extends Transformer 
{ 
	public function transform($example) 
	{ 
	return [ 
		'first_name' => $example['first_name'],
		'last_name' => $example['last_name'],
		'external_id' => $example['external_id'], 
		'email' => $example['email'], ]; 
	} 
}
```

 - Add to app/Http/kernel.php routeMiddleware to check route group for api auth user this line:

```
'dandaj.api' => \Dandaj\Api\Http\Middleware\Api::class,
```

 - To use models of package paste that's line in file:

```
use Dandaj\Api\Models\OAuthClient;
```
```
use Dandaj\Api\Models\OAuthToken;
```
```
use Dandaj\Api\Models\OAuthUser;
```

 - If you want to create controller for api, this is example:

```
<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests; use App\Models\User; 
use Dandaj\Api\Http\Controllers\ApiController; 
use App\Transformers\UserTransformer;

class UsersController extends ApiController 
{
	protected $userTransformer;

	function __construct(UserTransformer userTransformer)
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
	
	  // if you use respondWithError, must first set status code, if not, status code will have 200 value
	  if ($validator->fails()) {
	      return $this->setStatusCode(422)
	                  ->respondWithError($validator->errors()->all());
	  }
	
	  // Some code to create user from api request
	  //..
	
	  // Response with the status code, message, and status
	    return $this->setStatusCode(200)->respond([
	        'status' => 'success',
	        'message' => 'User was added',
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
	
	  // use transform function of UserTransformer to response with one user
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
```
- If you want to test api, type url /oauth/token/test:
- To send request for api, you should add to header "auth-token" param with current token which you get from api:

- For authenticate in api you must send in method post in json format, the following data:
	- login:
	- password:
	- client_id:
	- secret:

	On url address /oauth/token


<?php


Route::group([
		'prefix' => config('api.prefix'), 
		'namespace' => 'Dandaj\Api\Http\Controllers'
	], function() {
		Route::get(config('api.paramUri') . '/test', [
			'uses' => 'AuthController@verifyTest', 
			'as' => 'api.token.test'
		]);
		Route::post(config('api.paramUri'), [
			'uses' => 'AuthController@verify', 
			'as' => 'api.token'
		]);
});

<?php

Route::get('test', 'Dandaj\Api\Http\Controllers\AuthController@test');
Route::get('test', 'Dandaj\Api\Http\Controllers\AuthController@test');
Route::get('test', 'Dandaj\Api\Http\Controllers\AuthController@test');

Route::group([
		'prefix' => 'oauth', 
		'namespace' => 'Dandaj\Api\Http\Controllers'
	], function() {
		Route::get('/token/test', [
			'uses' => 'AuthController@verifyTest', 
			'as' => 'api.token.test'
		]);
		Route::post('/token', [
			'uses' => 'AuthController@verify', 
			'as' => 'api.token'
		]);
});

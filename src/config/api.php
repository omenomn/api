<?php

return [

	// Time after the token expires
	'expire' => 3600,

	// Prefix for routing api authentication and test
	'prefix' => 'oauth',

	// Url param for get route method to auth and get token in response
	'paramUri' => 'token',

	// Default credentials for auth
	'defaultCredentials' => [
		'login' => 'test',
		'password' => 'test',
		'client_id' => 'AIAZrzA6EUJweg11Zg7X',
		'client_secret' => 'qSGn1J5HxheJp7Wp3i4P7NCex92fFRCzRHiS2BpkMyJXRaxsYQxTYHt7KH8t',
		'website_address' => 'http://example.com',
	]



];
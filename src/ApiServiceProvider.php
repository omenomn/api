<?php

namespace Dandaj\Api;

use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->app->bind('api', function ($app) {
			return new Api;
		});
	}

	public function boot()
	{
		// loading the routes file
		require __DIR__ . '/Http/routes.php';

		// define the files which are going to be published
		$migration = 'database/migrations/';
		$migrationPath = __DIR__ . '/' . $migration;
		
		$this->publishes([
			$migrationPath . '2016_03_10_000000_create_oauth_users_table.php' => base_path($migration . '2016_03_10_000000_create_oauth_users_table.php'),
			$migrationPath . '2016_03_10_100000_create_oauth_clients_table.php' => base_path($migration . '2016_03_10_100000_create_oauth_clients_table.php'),
			$migrationPath . '2016_03_10_200000_create_oauth_tokens_table.php' => base_path($migration . '2016_03_10_100000_create_oauth_tokens_table.php'),
			__DIR__ . '/database/seeds/OAuthUsersTableSeeder.php' => base_path('database/seeds/OAuthUsersTableSeeder.php'),
		]);
	}
}
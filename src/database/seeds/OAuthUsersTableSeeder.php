<?php

use Illuminate\Database\Seeder;
use Dandaj\Api\Models\OAuthUser as User;

class OAuthUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::beginTransaction();

        $user = User::create([
        	'login' => config('api.defaultCredentials.login'),
        	'password' => bcrypt(config('api.defaultCredentials.password')),
        ]);

        $user->clients()->create([
        	'external_id' => config('api.defaultCredentials.client_id'),
        	'secret' => config('api.defaultCredentials.client_secret'),
        	'website_address' => config('api.defaultCredentials.website_address'),
        ]);

        if ($user) {
            DB::commit();            
        } else {
            DB::rollBack();
            throw new GDataException('Nie udana transakcja');
        }
    }
}

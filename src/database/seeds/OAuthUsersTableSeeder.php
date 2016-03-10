<?php

use Illuminate\Database\Seeder;
use App\Models\OAuthUser as User;

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
        	'login' => 'test',
        	'password' => bcrypt('test'),
        ]);

        $user->clients()->create([
        	'external_id' => 'AIAZrzA6EUJweg11Zg7X',
        	'secret' => 'qSGn1J5HxheJp7Wp3i4P7NCex92fFRCzRHiS2BpkMyJXRaxsYQxTYHt7KH8t',
        	'website_address' => 'http://example.com',
        ]);

        if ($user) {
            DB::commit();            
        } else {
            DB::rollBack();
            throw new GDataException('Nie udana transakcja');
        }
    }
}

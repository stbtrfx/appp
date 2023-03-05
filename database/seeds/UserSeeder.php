<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Amr',
            'email' => 'amr@gmail.com',
            'email_verified_at' => \Carbon\Carbon::now() ,
            'password' => Hash::make('secret'),
            'remember_token' => '' ,
            'phone' => '01011941903' ,
            'role' => 'admin' 

            
        ]);
;
    }
}

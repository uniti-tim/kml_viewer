<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        //make admin
        User::create([
          'name' => 'DPT Admin',
          'email' => 'dpt_admin@uniti.com',
          'password' => bcrypt('adminunitifiber!!'),
          'role' => 'admin'
        ]);

        //make user
        User::create([
          'name' => 'DPT User',
          'email' => 'dpt_tool@uniti.com',
          'password' => bcrypt('unitifiber!!'),
          'role' => 'user'
        ]);
    }
}

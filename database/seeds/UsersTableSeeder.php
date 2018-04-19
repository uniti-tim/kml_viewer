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
        User::create([
          'name' => 'DPT User',
          'email' => 'dpt_tool@uniti.com',
          'password' => bcrypt('unitifiber!!')
        ]);
    }
}

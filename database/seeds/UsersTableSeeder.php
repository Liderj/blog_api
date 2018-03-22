<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('users')->insert([
        'nickname' => 'admin',
        'mobile' => '13551031357',
        'password' => bcrypt('123456'),
        'level' => '0',
      ]);
    }
}

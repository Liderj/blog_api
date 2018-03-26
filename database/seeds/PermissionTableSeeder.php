<?php

use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('permissions')->insert([
        'pid' => 0,
        'type' => 0,
        'status' =>1,
        'name' => '权限管理',
        'url'=>'/permission'
      ]);
      DB::table('roles_permissions')->insert([
        'rid' => 1,
        'perid' => 1,
      ]);
    }
}

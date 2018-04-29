<?php

use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      factory(\App\Post::class, 50)->create([
        'pid'=>1,
        'cid'=>1,
        'title'=>'微博测试',
        'likes'=>0
      ]);
    }
}

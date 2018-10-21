<?php

use Illuminate\Database\Seeder;
use App\Post;
use App\User;

class LikeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $n = random_int(10, Post::count()/2);
        $posts = Post::inRandomOrder()->limit($n)->get();
        foreach ($posts as $post)   {
            $n = random_int(0, User::count()/2);
            $users = User::inRandomOrder()->limit($n)->get();
            foreach ($users as $user)   {
                $post->likes()->attach($user);
            }
        }
    }
}

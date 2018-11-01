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
        $c = Post::count();
        $posts = Post::inRandomOrder()->limit($c/4)->get();
        foreach ($posts as $post)   {
            $n = random_int(1, $c/4);
            $users = User::inRandomOrder()->limit($n)->get();
            foreach ($users as $user)   {
                $post->likes()->attach($user);
            }
        }
    }
}

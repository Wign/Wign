<?php

use Illuminate\Database\Seeder;
use App\User;

class WordsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::inRandomOrder()->get();
        $countuser = User::count();
        factory(App\Word::class, 1000)->create()->each(function($u) {
            $random = random_int(0,$countuser)
            if(random_int(0,4) == 0)    {
                $u->requests()->attach($user);
            } else {
                $post = factory(\App\Post::class)->create();
                $u->posts()->attach($post, ['user_id' => $user->id]);
            }
        });

        // CORNER CASES
        $words = ['Blåbærgrød', 'Æble', 'Østrig', 'Årstid'];
        foreach ($words as $word)   {
            $u = new \App\Word([
                'word' => $word,
            ]);
            $u->save();
            $user = User::inRandomOrder()->first();
            $u->posts()->attach(factory(App\Post::class)->create(), ['user_id' => $user->id]);
        }


    }
}

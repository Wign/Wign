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
        factory(App\Word::class, 1000)->create()->each(function($u) {
            $user = User::inRandomOrder()->first();
            if(random_int(0,4) == 0)    {
                $u->requests()->attach($user);
            } else {
                $u->posts()->attach(factory(\App\Post::class)->create(), ['user_id' => $user->id]);
            }
        });

        // CORNER CASES
        $words = ['Blåbærgrød', 'Æble', 'Østrig', 'Årstid'];
        foreach ($words as $word)   {
            $w = new \App\Word([
                'word' => $word,
            ]);
            $w->save();
            $user = User::inRandomOrder()->first();
            $w->posts()->attach(factory(App\Post::class)->create(), ['user_id' => $user->id]);
        }


    }
}

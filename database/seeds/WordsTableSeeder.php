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
            if(random_int(0,4) == 0)    {
                $user = User::inRandomOrder()->first();
                $u->requests()->attach($user);
            }
        });

        // CORNER CASES
        $words = ['Blåbærgrød', 'Æble', 'Østrig', 'Årstid'];
        foreach ($words as $word)   {
            $w = new \App\Word([
                'word' => $word,
            ]);
            $w->save();
            //$w->posts()->attach(factory(App\Post::class)->make());
        }


    }
}

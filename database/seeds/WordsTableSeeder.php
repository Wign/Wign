<?php

use Illuminate\Database\Seeder;

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
            //$u->posts()->save(factory(App\Post::class)->make());
        });

        $words = ['Blåbærgrød', 'Æble', 'Østrig', 'Årstid'];
        foreach ($words as $word)   {
            $w = new \App\Word([
                'word' => $word,
            ]);
            $w->save();
            //$w->posts()->save(factory(App\Post::class)->make());
        }


    }
}

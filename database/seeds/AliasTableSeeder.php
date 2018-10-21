<?php

use Illuminate\Database\Seeder;
use App\Word;

class AliasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $n = random_int(1, Word::has('posts')->count()/20);
        $words = Word::inRandomOrder()->limit($n)->get();
        foreach ($words as $word)   {
            $words->forget($word->id);
            $parent = $words->random();
            $word->alias_parents()->attach($parent);
        }
    }
}

<?php

use Illuminate\Database\Seeder;
use App\User;

class RequestWordTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Word::class, 50)->create()->each(function($u) {
            $numUsers = User::count();
            $n1 = random_int(0, $numUsers/2);
            $n2 = random_int(0, $numUsers/2);
            $n = $n1 < $n2 ? $n1 : $n2;

            $users = User::inRandomOrder()->limit($n)->get();
            foreach ($users as $user)   {
                $u->requests()->attach($user);
            }
        });
    }
}

<?php

use Illuminate\Database\Seeder;
use App\User;

class ReviewTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Review::class, 50)->create()->each(function ($u)   {
            $numUsers = User::count();
            $terminated = random_int(0, 4) == 0 ? now() : null;
            if ($terminated)    {
                $u->deleted_at = $terminated;
            }
            if ($numUsers < 10) {
                $admins = User::where('type', 'admin')->get();
                foreach ($admins as $admin) {
                    $u->voters()->attach($admin);   // Trigger all admins, but only one will decide the review
                }
            } else {
                echo 'NEW';
                $n = log($numUsers);
                $n = intval($n * $n);
                if ($n < 5) {
                    $n = 5;
                } elseif ($n > 200) {
                    $n = 200;
                }
                $il = $u->postRank();
                $users = null;
                $mixedUsers = User::where('type', 'default')->inRandomOrder();
                //
                $dist = [.6, .4];
                $users = $mixedUsers->with('rank', $il)->take($n * $dist[0])->get();
                //
                /*$rankMax = config('global.rank_max');
                if (true) { //$il > $rankMax - 2)    {
                    $dist = config('global.ballot_2_dist');
                    $users = $mixedUsers->has('rank()', 4)->take($n * $dist[0])->get();
                    $users->merge($mixedUsers->with('rank()', $rankMax)->take($n * $dist[1])->get());
                } else {
                    $dist = config('global.ballot_3_dist');
                    $users = $mixedUsers->with('rank()', $rankMax - 1);
                    $users = $users->take($n * $dist[0])->get();
                    $users->merge($mixedUsers->where('postRank()', ($il + 1))->take($n * $dist[1])->get());
                    $users->merge($mixedUsers->where('postRank()', ($il + 2))->take($n * $dist[2])->get());
                }*/
                foreach ($users as $user)   {
                    echo 'Do';
                    $u->voters()->attach($user);
                }
            }
        });

        // factory of terminated reviews
    }
}

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
                $n = log($numUsers);
                $n = intval($n * $n);
                if ($n < 6) {
                    $n = 6;
                } elseif ($n > 200) {
                    $n = 200;
                }
                $il = $u->postRank();
                $qcvs = null;
                $rankMax = config('global.rank_max');
                if ($il > $rankMax - 2)    {    //TODO: Need to exclude the admins
                    $dist = [.6, .4];
                    $qcvs1 = \App\Qcv::inRandomOrder()->where('rank', $rankMax - 1)->take($dist[0] * $n)->get();
                    $qcvs2 = \App\Qcv::inRandomOrder()->where('rank', $rankMax)->take($dist[1] * $n)->get();

                    $qcvs = $qcvs1->merge($qcvs2);
                } else {
                    $dist = [.5, .3, .2];
                    $qcvs1 = \App\Qcv::inRandomOrder()->where('rank', $il)->take($dist[0] * $n)->get();
                    $qcvs2 = \App\Qcv::inRandomOrder()->where('rank', $il+1)->take($dist[1] * $n)->get();
                    $qcvs3 = \App\Qcv::inRandomOrder()->where('rank', $il+2)->take($dist[2] * $n)->get();

                    $qcvs = $qcvs1->merge($qcvs2)->merge($qcvs3);
                }
                foreach ($qcvs as $qcv) { //TODO: Fix 'qcv->user->type'
                    /*
                    if ($qcv->user->type == 'admin') {
                        continue;
                    }*/
                    $u->voters()->attach($qcv);
                }
            }
        });
    }
}

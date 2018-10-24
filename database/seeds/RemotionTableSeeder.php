<?php

use Illuminate\Database\Seeder;
use App\User;

class RemotionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Remotion::class, 20)->create()->each(function ($u)   {
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
                $qcv = $u->userRank();
                $users = null;
                $rankMax = config('global.rank_max');
                if ($qcv > $rankMax - 2)    {   //TODO: Need to exclude the admins
                    $dist = [.6, .4];
                    $qcvs = \App\Qcv::whereRank($rankMax-1)->inRandomOrder()->take($n * $dist[0])->pluck('user_id')->toArray();
                    $users = User::findMany($qcvs);

                    $qcvs = \App\Qcv::whereRank($rankMax)->inRandomOrder()->take($n * $dist[1])->pluck('user_id')->toArray();
                    $users2 = User::findMany($qcvs);

                    $allUsers = $users->merge($users2);
                } else {
                    if ($qcv == 0)  {
                        $qcv++;
                    }
                    $dist = [.5, .3, .2];
                    $qcvs = \App\Qcv::whereRank($qcv)->inRandomOrder()->take($n * $dist[0])->pluck('user_id')->toArray();
                    $users = User::findMany($qcvs);

                    $qcvs = \App\Qcv::whereRank($qcv+1)->inRandomOrder()->take($n * $dist[1])->pluck('user_id')->toArray();
                    $users2 = User::findMany($qcvs);

                    $qcvs = \App\Qcv::whereRank($qcv+2)->inRandomOrder()->take($n * $dist[2])->pluck('user_id')->toArray();
                    $users3 = User::findMany($qcvs);

                    $allUsers = $users->merge($users2)->merge($users3);
                }
                foreach ($allUsers as $user) {
                    echo 'Do'.$user->rank();
                    if ($user->type == 'admin') echo 'ALERT';
                    $u->voters()->attach($user);
                }
            }

        });
    }
}

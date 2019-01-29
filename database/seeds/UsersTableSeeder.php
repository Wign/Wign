<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 100)->create()->each(function($u) {

            $u->qcvs()->save(factory(App\Qcv::class)->make());

            $q = $u->qcvs()->first()->rank;
            while ($q > 0) {
                if (random_int(0, 9) == 0 && $q <= config('global.rank_max'))  {
                    $q++;
                    $u->qcvs()->save(factory(App\Qcv::class)->make(['rank' => $q, 'deleted_at' => now()]));
                } else {
                    $q--;
                    $u->qcvs()->save(factory(\App\Qcv::class)->make(['rank' => $q, 'deleted_at' => now()]));
                }
            }
        });

        $user = new \App\User([
            'name' => 'admin',
            'email' => 'a@a.dk',
            'password' => bcrypt('admin'),
            'remember_token' => str_random(10),
            'type' => 'admin',
            'last_login' => now(),
        ]);
        $user->save();
        $user->qcvs()->save(factory(App\Qcv::class)->make(['rank' => 5]));

        for ($i = 0; $i <= config('global.rank_max'); $i++) {
            $user = new \App\User([
                'name' => 'user',
                'email' => $i . '@u.dk',
                'password' => bcrypt('user'),
                'remember_token' => str_random(10),
                'type' => 'default',
                'last_login' => now(),
            ]);
            $user->save();
            $user->qcvs()->save(factory(App\Qcv::class)->make(['rank' => $i]));
        }



    }
}

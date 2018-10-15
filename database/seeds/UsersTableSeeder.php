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
        });

        $user = new \App\User([
            'name' => 'admin',
            'email' => 'test@test.dk',
            'password' => bcrypt('admin'),
            'remember_token' => str_random(10),
            'type' => 'admin'
        ]);
        $user->save();
        $user->qcvs()->save(factory(App\Qcv::class)->make(['rank' => 5]));
    }
}

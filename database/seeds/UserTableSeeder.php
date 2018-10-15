<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 100)->create()->each(function($u) {
            $u->descriptions()->save(factory(App\Description::class)->make());
        });

        $user = new \App\User([
            'name' => 'test',
            'email' => 'test@test.dk',
            'password' => 'testtest',
            'type' => 'admin'
        ]);
        $user->save();
        $QCV = new \App\Qcv();
        $user->QCVs()->save($QCV);
    }
}

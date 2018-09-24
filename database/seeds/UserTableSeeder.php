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
        $user = new \App\Models\User([
            'name' => 'test',
            'email' => 'test@test.dk',
            'password' => 'testtest',
            'admin' => true
        ]);
        $user->save();

        $user = new \App\Models\User([
            'name' => 'Alice',
            'email' => 'alice@test.dk',
            'password' => 'testtest',
            'admin' => true
        ]);
        $user->save();

        $user = new \App\Models\User([
            'name' => 'Bob',
            'email' => 'bob@test.dk',
            'password' => 'testtest',
            'admin' => false
        ]);
        $user->save();

        $user = new \App\Models\User([
            'name' => 'Charlie',
            'email' => 'charlie@test.dk',
            'password' => 'testtest',
            'admin' => false
        ]);
        $user->save();

        $user = new \App\Models\User([
            'name' => 'Doris',
            'email' => 'doris@test.dk',
            'password' => 'testtest',
            'admin' => false
        ]);
        $user->save();

        $user = new \App\Models\User([
            'name' => 'Eva',
            'email' => 'eva@test.dk',
            'password' => 'testtest',
            'admin' => false
        ]);
        $user->save();

        $user = new \App\Models\User([
            'name' => 'Fred',
            'email' => 'fred@test.dk',
            'password' => 'testtest',
            'admin' => false
        ]);
        $user->save();
    }
}

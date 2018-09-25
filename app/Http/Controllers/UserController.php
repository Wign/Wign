<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;


class UserController extends Controller //TODO: Fill the controller
{

    public function userCreate(Request $request )  //TODO: fill the data when creating this user
    {
        /**
         * - check the blacklist and email
         * if negative do
         *  create the user with filled data and set QCV to 0
        */
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required',
            'password' => 'required|min:8'
        ]);
        $user = new User([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'admin' => false
        ]);
        $user->save();

       return null;
    }
}

<?php

namespace App\Http\Controllers;

use App\Qcv;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getIndex() //TODO: udvid med Auth!
    {
        $user = Auth::user();

        $coll1 = $user->reviewVotings()->get();
        $coll2 = $user->remotionVotings()->get();

        $awaitings = $coll1->merge($coll2)->sortBy('created_at');

        return view('profile', compact(['user', 'awaitings']));
    }

    //TODO: Add update user

    //TODO: Add remove user

}

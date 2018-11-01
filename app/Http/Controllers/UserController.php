<?php

namespace App\Http\Controllers;

use App\Qcv;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getIndex()
    {
        $user = Auth::user();
        $coll1 = null; //$user->qcvs()->reviewVotings()->get();
        foreach ($user->qcvs()->get() as $qcv)  {

            $coll1->;
    }
        //$coll2 = $user->qcvs()->remotionVotings()->get();

        //$votings = $coll1->merge($coll2)->sortBy('created_at');

        return view('profile', compact(['user', 'awaitings']));
    }

    //TODO: Add update user

    //TODO: Add remove user

}

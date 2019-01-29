<?php

namespace App\Http\Controllers;

use App\Qcv;
use App\Review;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getIndex()
    {
        $user = Auth::user();
        $reviews = $user->qcvs()->get()->pluck('reviewVotings')->all();
        $remotions = $user->qcvs()->get()->pluck('remotionVotings')->all();

        $count = 0;
        foreach ($user->qcvs as $qcv)   {
            $rv = $qcv->reviewVotings()->wherePivot('approve', null)->count();
            $rm = $qcv->remotionVotings()->wherePivot('approve', null)->count();
            $count += $rv + $rm;
        }
        $pendings = $user->reviewAuthor()->count();

        return view('profile')->with(compact(['user', 'count', 'pendings']));
    }

    public function getGuest( $id )
    {
        $user = User::find($id);
        $user->created = $user->created_at->toFormattedDateString();
        $user->videoCreatorCount = $user->videos()->count();

        return view('guest')->with(compact(['user']));
    }

    public function promoteUser( $id )
    {
        $user = User::find($id);
        $qcv = $user->qcv();
        if ($qcv->rank < config('global.rank_max'))    {
            $qcv->delete();
            $user->qcvs()->save(new Qcv(['rank' => $qcv->rank + 1]));
        } else {    // If cannot promote further
            // Do nothing
        }
    }

    public function demoteUser( $id )
    {
        $user = User::find($id);
        $qcv = $user->qcv();
        if ($qcv->rank > 0)    {
            $qcv->delete();
            $user->qcvs()->save(new Qcv(['rank' => $qcv->rank - 1]));

            if ($qcv->rank == 0)    {
                self::_detachBallots($user);
            }
        } else {    // If cannot demote further
            // Do nothing
        }
    }

    //////////////////////

    /**
     * @param $user
     *
     * Delete all ballots there are not redeemed by this user
     */
    private function _detachBallots( $user )   {
        $user->qcvs()->whereHas('reviewVotings', function ($q){
            $q->where('approve', null)->delete();
        });
    }

}

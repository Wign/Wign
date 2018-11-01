<?php

namespace App\Http\Controllers;

use App\Qcv;
use App\Review;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VotingController extends Controller
{
    public function postNewReview( Post $oldId, Request $request)
    {
        $userID = Auth::user();
        $post = $request->input('post');

        $review = new Review([
            'IL_id' => $post->ILs()->first('id'),
            'user_id' => $userID
        ]);

        $postIL = $post->ILs()->first('rank');
        if ( $postIL >= 4)  {
            // Collect users with rank 4 and 5
            $users[0] = User::QCVs()->where('rank', 4)->get();
            $users[1] = User::QCVs()->where('rank', 5)->get();
        } else {
            // Collect users with Qcv i, i+1, i+2
            $users[0] = User::QCVs()->where('rank', $postIL)->get();
            $users[1] = User::QCVs()->where('rank', ($postIL + 1))->get();
            $users[2] = User::QCVs()->where('rank', ($postIL + 2))->get();
        }

        // Random selection of reviewer from $users: $users(i) > $users(i+1) > $users(i+2)

    }

    public function postUpdateReview( Request $request)
    {

    }

    public function postNewRemotion( Request $request)
    {

    }

    public function postUpdateRemotion( Request $request)
    {

    }

    public function postPromoteuser( $id )
    {
        $user = User::find($id);

        $qcv = new Qcv();
        $qcv->rank = $user->qcvRank() + 1;
        $user->qcvs()->save($qcv);  //overwrite the old QCV
    }
    //TODO: Demote user
}

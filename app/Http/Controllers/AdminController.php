<?php

namespace App\Http\Controllers;

use App\Qcv;
use App\Review;
use App\Remotion;
use App\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getIndex()
    {
        $review_count = Review::where('decided', 0)->doesntHave('voters')->count();
        $remotion_count = Remotion::where('decided', 0)->doesntHave('voters')->count();

        $voting_count = $review_count + $remotion_count;

        return view('admin')->with(compact([ 'voting_count' ]));
    }

    public function getVote()
    {
        $review_count = Review::where('decided', 0)->doesntHave('voters')->count();
        $remotion_count = Remotion::where('decided', 0)->doesntHave('voters')->count();

        if ($remotion_count + $review_count > 0)    {
            $random = (bool) random_int(0, 1); // random pick to show either review or remotion

            if ($remotion_count > 0 && ($review_count == 0 || $random)) {
                $remotion = Remotion::where('decided', 0)->doesntHave('voters')->inRandomOrder()->first();

                $user = $remotion->qcv->user;
                $user->created_at = $user->created_at->toDateTimeString();
                //$user->post_count = $user->withTrashed()->postsEditor()->count();

                return view('partials.adminRemotion')->with(compact(['remotion', 'user']));

            } else {
                $review = Review::where('decided', 0)->doesntHave('voters')->inRandomOrder()->first();

                $newPost = $review->newIl->post;
                $oldPost = $review->oldIl === null ? null : $review->oldIl->post;

                return view('partials.adminReview')->with(compact(['review', 'newPost', 'oldPost']));
            }
        }
        return redirect()->route('admin.index');
    }

    public function decideReview( $id, Request $request )
    {
        $review = Review::find($id);
        if( $review->decided == 0)  {
            $review->decided = 1;   // reduce the risk of collision in the critical section
            $review->save();

            $qcv = \Auth::user()->qcv();
            $vote = (bool) $request->input('approve');
            $review->voters()->attach($qcv, ['approve' => $vote]);

            app('App\Http\Controllers\VotingController')->reviewAftermath($review, $vote);
        }
        return $this->getVote();
    }

    public function decideRemotion( $id, Request $request )
    {
        $remotion = Remotion::find($id);
        if( $remotion->decided == 0)  {
            $remotion->decided = 1;   // reduce the risk of collision in the critical section
            $remotion->save();

            $qcv = \Auth::user()->qcv;
            $vote = (bool) $request->input('approve');
            $remotion->voters()->attach($qcv, ['approve' => $vote]);

            app('App\Http\Controllers\VotingController')->remotionAftermath($remotion, $vote);
        }
        return $this->getVote();
    }

    public function banUser( $id, $reason = "auto ban" )
    {
        $user = User::find($id);
        $user->ban_reason = $reason;
        self::_detachBallots($user);
        //TODO Make sure that in view none can go to page of this author (remove href if deleted_at !== null)
        $user->delete();

        return redirect()->route('index')->with('message', 'Brugeren er blokeret');
    }

    public function deleteUser( $id )
    {
        $user = User::find($id);
        self::_detachBallots($user);
        //TODO Redesign so it is possible to remove the videos created by this user without destroy any posts
        //TODO Make sure that in view none can go to page of this author (remove href if deleted_at !== null)
        $user->fill([
            'name' => 'removed',
            'email' => 'removed',
        ]);
        $user->forceDelete();
        return redirect()->route('index')->with('message', 'Brugeren er slettet fra systemet');
    }

    public function reactivateUser( $id )
    {
        $user = User::onlyTrashed()->find($id);
        $user->ban_reason = null;
        $user->restore();

        return redirect()->route('index')->with('message', 'Brugeren er genaktiveret');
    }

    //////////////////////////

    private function _detachBallots( $user )
    {
        $qcvs = $user->qcvs()->withTrashed()->get();
        foreach ($qcvs as $qcv) {
            $qcv->reviewVotings()->detach();
            $qcv->remotionVotings()->detach();
        }
    }

}

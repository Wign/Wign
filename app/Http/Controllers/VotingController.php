<?php

namespace App\Http\Controllers;

use App\Post;
use App\Qcv;
use App\Remotion;
use App\Review;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VotingController extends Controller
{
    const BALLOTS_DIST_2 = [.6, .4];    // Used when two high-most ranks participate the voting
    const BALLOTS_DIST_3 = [.5, .3, .2];    // Standard

    const VOTE_WEIGHT = [1, 2, 3, 5, 8]; // Fibonacci

    const LINEAR_THRESHOLD = [.5, .6, .7, .8, .9];  // Standard
    const UNIFORM_THRESHOLD = [.5, .5, .5, .5, .5]; // Used if demote an user

    public function getVote()   {
        $user = Auth::user();
        $reviews = $user->qcvs()->get()->pluck('reviewVotings')->all();
        $remotions = $user->qcvs()->get()->pluck('remotionVotings')->all();

        try {
            $review = null;
            foreach ($reviews[0] as $r) {
                if ($r->pivot->approve === null) {
                    $review = $r;
                    break;
                }
            }
        } catch (\Error $e)  {
        }
        try {
            $remotion = null;
            foreach ($remotions[0] as $r) {
                if ($r->pivot->approve === null) {
                    $remotion = $r;
                    break;
                }
            }
        }   catch (\Error $e)   {
        }
        if ($review === null && $remotion === null)    {
            return redirect()->route('user.index');

        } elseif ($remotion === null)    {
            return self::_returnReview($review);

        } elseif ($review === null)  {
            return self::_returnRemotion($remotion);
        }

        if ($review->created_at < $remotion->created_at)    {
            return self::_returnReview($review);

        } else {
            return self::_returnRemotion($remotion);
        }
    }

    public function postNewReview()
    {
        $newPost = \Session::pull('newPost');
        $oldPost = \Session::pull('oldPost');   //null if entry user triggered the voting

        //TODO Restriction to trigger multiple votings on same post

        $review = Review::create([
            'new_post_il_id' => $newPost,
            'old_post_il_id' => $oldPost,
            'user_id' => Auth::user()->id,
        ]);
        if ($oldPost === null)  {
            $rank = 1;
        } else {
            $rank = Post::withTrashed()->find($oldPost)->il()->rank;
        }

        return self::_voteDistribution($review, $rank);
    }

    public function postUpdateReview($id, Request $request)
    {
        $this->validate($request, [
            'approve' => 'required|in:true,false'
        ]);
        $qcv = Auth::user()->qcv();
        try {   // Ensure that none are allowed to vote the expired voting.
            $voting = Review::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            redirect()->back();
        }

        $bool = $request->input('approve') == 'true' ? 1 : 0;
        $voting->voters()->updateExistingPivot($qcv->id, ['approve' => $bool]);

        if (!$voting->decided)   {
            $rank = $voting->oldIl !== null ? ($voting->oldIl->rank) : ($voting->newIl->rank);
            $result = self::_votingCalculation($voting, $rank-1, true);

            if ($result != 0)   {
                $voting->decided = true;
                self::reviewAftermath($voting, $result);
            }
        }

        return self::getVote();
    }

    public function reviewAftermath( $review, $result )
    {
        if ($result == 1)   {
            if ($review->oldIl !== null)    {
                $review->oldIl->post->delete();
            }
            $review->newIl->post->restore();
            $review->delete();
        } elseif ($result == -1) {
            // Do nothing
        }
    }

    public function newPromotion( $id )
    {
        return self::_postNewRemotion($id, true);
    }

    public function newDemotion( $id )
    {
        return self::_postNewRemotion($id, false);
    }

    private function _postNewRemotion( $id, $promote)      //TODO: Verify the function call
    {
        Auth::check();
        $targetUser = User::find($id);
        $rank = $targetUser->qcv()->rank;
        $p = $promote ? 1 : 0;

        //TODO: restrict the multiple votings

        if ( ($promote && $rank == config('global.rank_max')) || (!$promote && $rank == 0))    {
            return route('index');
        }
        $remotion = new Remotion([
            'user_id' => Auth::user()->id,
            'qcv_id' => $targetUser->qcv()->id,
            'promotion' => $promote,
        ]);
        $remotion->save();

        return self::_voteDistribution($remotion, $rank, $targetUser);
    }

    public function postUpdateRemotion($id, Request $request)
    {
        $this->validate($request, [
            'approve' => 'required|in:true,false'
        ]);
        $qcv = Auth::user()->qcv();
        try {   // Ensure that none are allowed to vote the expired voting.
            $voting = Remotion::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            redirect()->back();
        }
        $bool = $request->input('approve') == 'true' ? 1 : 0;
        $voting->voters()->updateExistingPivot($qcv->id, ['approve' => $bool]);

        if (!$voting->decided)   {
            $rank = $voting->qcv->rank == 0 ? 0 : $voting->qcv->rank - 1;
            $demote = !$voting->promotion;
            $result = self::_votingCalculation($voting, $rank, false, $demote);

            if ($result != 0)   {
                $voting->decided = true;
                self::remotionAftermath($voting, $result);
            }
        }

        return self::getVote();
    }

    public function remotionAftermath( $remotion, $result )
    {
        if ($result == 1)   {
            if ($remotion->promotion) {
                redirect()->action('UserController@promoteUser', ['id' => $remotion->qcv->user_id]);
            } else {
                redirect()->action('UserController@demoteUser', ['id' => $remotion->qcv->user_id]);
            }
        }elseif ($result == -1) {
            // Do nothing
        }
    }

 /*********************/
 /* PRIVATE FUNCTIONS */
 /*********************/

    private function _votingCalculation($voting, $rank, $isReview, $demote = false)   {
        if ($isReview || $demote)    {
            $approveThreshold = self::UNIFORM_THRESHOLD;
        } else {
            $approveThreshold = self::LINEAR_THRESHOLD;
        }
        $maxScore = 0;
        $yesScore = 0;
        $noScore = 0;
        for ($i = 1; $i <= config('global.rank_max'); $i++)  {
            $weight = self::VOTE_WEIGHT[$i - 1];

            $numUsers = $voting->voters()->where('rank', $i)->count();
            $maxScore =+ $numUsers * $weight;

            $numApproved = $voting->voters()->wherePivot('approve', 1)->count();
            $yesScore =+ $numApproved * $weight;

            $numDeclined = $voting->voters()->wherePivot('approve', 0)->count();
            $noScore =+ $numDeclined * $weight;
        }

        $scoreThreshold = $maxScore * $approveThreshold[$rank];
        if ($yesScore >= $scoreThreshold)   {   // When voting is approved
            return 1;
        } elseif ($noScore > $maxScore - $scoreThreshold) { // When the threshold is unreachable
            return -1;
        } else {    // Keep voting active
            return 0;
        }
    }

    private function _returnReview($review) {
        $newPost = $review->newIl->post;
        $oldPost = $review->oldIl === null ? null : $review->oldIl->post;

        return view('review')->with( compact(['review', 'newPost', 'oldPost']));
    }

    private function _returnRemotion($remotion) {
        $user = $remotion->qcv->user;
        $user->created_at = $user->created_at->toFormattedDateString();
        $user->post_count = $user->withTrashed()->postsEditor()->count();

        return view('remotion')->with(compact(['remotion', 'user']));
    }

    private function _voteDistribution($election, $rank, $remotionUser = null)    {
        $t = (log(Qcv::has('user')->where('rank', '!=', 0)->count()));
        $numUsers = (int)($t/8)+($t*$t); // number of participants
        if ($numUsers < config('global.min_ballots'))  {   // Only admins can decide the voting
            return redirect()->route('index')->with('message', 'Afstemningen er igangsat');
        }

        $rankMax = config('global.rank_max');
        $rank = $rank < 1 ? 1 : ($rank == $rankMax ? $rankMax - 1 : $rank); // $rank musts be between 1 and max-1
        $dist = $rank >= $rankMax - 1 ? self::BALLOTS_DIST_2 : self::BALLOTS_DIST_3;
        $voters = null;
        for ($i = 0; ($i + $rank <= $rankMax && $i < 3); $i++)  {
            // Get all users within this rank and excluded this user who created the voting
            $v = Qcv::where('rank', $i + $rank)->where('user_id', '!=', $election->user_id);
            if ($remotionUser !== null) {   // If it is a voting of remotion, then the target user also is excluded
                $v = $v->where('user_id', '!=', $remotionUser->id);
            }
            $v = $v->inRandomOrder()->take($dist[$i] * $numUsers)->get(); // Make a random pick of n users;

            if ($v === null)    {   // merge-function does act well on NULL collection, so it has to check before merge
                continue;
            } elseif ($i == 0 || $voters === null)  {
                $voters = $v;
            } else {
                $voters = $voters->merge($v);
            }
        }

        if ( $voters->count() >= $numUsers) {
            //TODO: need to fix
            /*
            // Invite other QCV with the leftover ballots
            $diff = $numUsers - $voters->count();
            $v = Qcv::has('user')->where('rank', '!=', 0)->whereNotIn($voters)->inRandomOrder()->take($diff)->get();
            $voters = $voters->merge($v);
            */
            return redirect()->route('index')->with('message', 'Afstemningen er sendt til administrator');
        }

        foreach ($voters as $voter) {
            $election->voters()->attach($voter);
        }

        return redirect()->route('index')->with('message', 'Afstemningen er igangsat');
    }
}

<?php namespace App\Http\Controllers;

use App\Vote;
use Request;
use Redirect;
use Response;
use Input;

class VoteController extends Controller {

    public function createVote() {
        
        $q = Input::all();

        $vote = Vote::create(array(
                'sign_id' => $q['sign'],
                'ip' => Request::getClientIp()
            ));
        
        $votes = Vote::where('sign_id', '=', $q['sign'])->count();

        $response = array(
            'status' => 'success',
            'msg' => 'Vote successfully inset',
            'votes' => $votes
        );
 
        return Response::json( $response );
    }

    public function deleteVote()
    {
        $q = Input::all();
        $myIP = Request::getClientIp();

        $vote = Vote::where('sign_id', '=', $q['sign'])->where('ip', '=', $myIP)->delete();
        
        $votes = Vote::where('sign_id', '=', $q['sign'])->count();

        $response = array(
            'status' => 'success',
            'msg' => 'Vote successfully removed',
            'votes' => $votes
        );
 
        return Response::json( $response );
    }

}
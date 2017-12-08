<?php namespace App\Http\Controllers;

use App\Vote;
use Request;
use Redirect;
use Response;
use Input;

class VoteController extends Controller {

	/**
	 * Insert a vote (like) of the sign into database, linked to the IP address of the user.
	 * It utilize the POST input to gain the sign id.
	 *
	 * @return \Symfony\Component\HttpFoundation\Response a JSON response with status,
	 * message and resulting number of signs if it succeed
	 */
	public function createVote() {

		$q = Input::all();

		$vote = Vote::create( array(
			'sign_id' => $q['sign'],
			'ip'      => Request::getClientIp()
		) );

		if ( $vote ) {

			$votes = Vote::countVotes( $q['sign'] );

			$response = array(
				'status' => 'success',
				'msg'    => 'Vote successfully inserted',
				'votes'  => $votes
			);
		} else {
			$response = array(
				'status' => 'failed',
				'msg'    => 'Somehow we failed to insert the vote into our database...'
			);
		}

		return Response::json( $response );
	}

	/**
	 * Removes a vote (like) of the sign from database, linked to the IP address of the user.
	 * It utilize the POST input to gain the sign id.
	 *
	 * @return \Symfony\Component\HttpFoundation\Response a JSON response with status,
	 * message and resulting number of signs if it succeed
	 */
	public function deleteVote() {
		$q    = Input::all();
		$myIP = Request::getClientIp();

		$vote = Vote::where( 'sign_id', '=', $q['sign'] )->where( 'ip', '=', $myIP )->delete();

		if ( $vote ) {

			$votes = Vote::countVotes( $q['sign'] );

			$response = array(
				'status' => 'success',
				'msg'    => 'Vote successfully removed',
				'votes'  => $votes
			);
		} else {
			$response = array(
				'status' => 'failed',
				'msg'    => 'Somehow we failed to remove your vote from database...'
			);
		}

		return Response::json( $response );
	}

}
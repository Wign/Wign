<?php namespace App\Http\Controllers;

use App\Vote;
use Illuminate\Http\Request;
use Response;

class VoteController extends Controller {

	/**
	 * Insert a vote (like) of the sign into database, linked to the IP address of the user.
	 * It utilize the POST input to gain the sign id.
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse a JSON response with status,
	 * message and resulting number of signs if it succeed
	 */
	public function createVote( Request $request ) {
		
		$signID = $request->get( 'sign' );
		$IP     = $request->getClientIp();

		$vote = Vote::create( array(
			'sign_id' => $signID,
			'ip'      => $IP
		) );

		if ( $vote ) {

			$votes = Vote::countVotes( $signID );

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
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse a JSON response with status,
	 * message and resulting number of signs if it succeed
	 */
	public function deleteVote( Request $request ) {
		$signID = $request->get( 'sign' );
		$myIP   = $request->getClientIp();

		$vote = Vote::where( 'sign_id', $signID )->where( 'ip', $myIP )->delete();

		if ( $vote ) {

			$votes = Vote::countVotes( $signID );

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
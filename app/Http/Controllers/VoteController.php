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
				'msg'    => __('flash.vote.insert.success'),
				'votes'  => $votes
			);
		} else {
			$response = array(
				'status' => 'failed',
				'msg'    => __('flash.vote.insert.failed')
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
	 * @throws \Exception
	 */
	public function deleteVote( Request $request ) {
		$signID = $request->get( 'sign' );
		$myIP   = $request->getClientIp();

		$vote = Vote::where( 'sign_id', $signID )->where( 'ip', $myIP )->delete();

		if ( $vote ) {

			$votes = Vote::countVotes( $signID );

			$response = array(
				'status' => 'success',
				'msg'    => __('flash.vote.delete.success'),
				'votes'  => $votes
			);
		} else {
			$response = array(
				'status' => 'failed',
				'msg'    => __('flash.vote.delete.failed')
			);
		}

		return Response::json( $response );
	}

}
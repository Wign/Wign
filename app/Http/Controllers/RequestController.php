<?php namespace App\Http\Controllers;

use Helper;

use App\RequestWord;
use App\Word;
use Request;
use URL;

class RequestController extends Controller {

	/**
	 * Display a list of all sign requests
	 *
	 * @return \Illuminate\View\View of the list
	 */
	public function showList() {
	    $requests = Word::has('requests')->withCount('requests')->orderBy('requests_count', 'desc')->paginate(50);

		return view( 'requests' )->with( compact('requests') );
	}

	/**
	 * Stores the request "vote" in the database and send the user to the request page with a JSON response.
	 * It makes some validation check before storing, as bot-check, about the sign already is requested or such.
	 *
	 * @param String $word the requested word
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store( $word ) {
		if ( empty( $word ) ) {
			return redirect()->back()->with( 'message', __('flash.request.word.missing') );
		}

		$word = Helper::underscoreToSpace( $word );
		$myIP = Request::getClientIp();

		$hasWord = Word::firstOrCreate( [ 'word' => $word ] );

		$hasSign = $hasWord->signs->first();
		if ( $hasSign ) {
			$flash = array(
				'message' => __('flash.sign.already', ['word' => $word]),
				'url'     => URL::to( config( 'wign.urlPath.sign' ) . '/' . $word )
			);

			return redirect( config( 'wign.urlPath.request' ) )->with( $flash );
		}

		$hasVote = $hasWord->request->where( 'ip', $myIP )->first();
		if ( $hasVote ) {
			return redirect( config( 'wign.urlPath.request' ) )->with( 'message',  __('flash.request.already', ['word' => $word]));
		} else {
			// Check if client is bot. If true, reject the creation!
			if ( Helper::detect_bot() ) {
				return redirect( config( 'wign.urlPath.request' ) )->with( 'message', __('flash.bot.refuse') );
			} else {
				$requestID = RequestWord::create( [ 'word_id' => $hasWord['id'], 'ip' => $myIP ] );
				if ( $requestID ) {
					$flash = [
						'message' => __('flash.request.successful', ['word' => $word]),
						'url'     => URL::to( config( 'wign.urlPath.sign' ) . '/' . $word )
					];

					return redirect( config( 'wign.urlPath.request' ) )->with( $flash );
				} else {
					// Something went wrong...
					return redirect( config( 'wign.urlPath.request' ) )->with( 'message',  __('flash.request.failed'));
				}
			}
		}
	}
}

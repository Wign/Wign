<?php namespace App\Http\Controllers;

use App\Helpers\Helper;

use App\RequestWord;
use App\Word;
use Request;
use DB;
use URL;

class RequestController extends Controller {

	/**
	 * Display a list of all sign requests
	 *
	 * @return \Illuminate\View\View of the list
	 */
	public function showList() {
		$requests = DB::select( DB::raw( '
            SELECT words.word, COUNT(request_words.id) AS request_count
            FROM words LEFT JOIN request_words
            ON words.id = request_words.word_id
            WHERE (SELECT count(*) FROM request_words WHERE request_words.word_id = words.id) >= 1 
                AND (SELECT count(*) FROM signs WHERE signs.word_id = words.id) <= 0
            GROUP BY words.word
            ORDER BY request_count DESC, words.word ASC
        ' ) );

		return view( 'requests' )->with( 'requests', $requests );
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
			return redirect( config( 'wign.urlPath.request' ) )->with( 'message', __('flash.request.word.missing') );
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

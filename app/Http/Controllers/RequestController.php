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
	public function showList() {    //TODO: fix query bec it does not exclude word with no requests
	    $limit = config('global.list_limit');
	    $requests = Word::doesntHave('posts')->has('requests')->withCount('requests')->orderBy('requests_count', 'desc')->orderBy('word')->paginate($limit);

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
		$user = \Auth::user();

		$hasWord = Word::firstOrCreate( [
		    'word' => $word
        ], [
            'creator_id' => $user->id,
            'editor_id' => $user->id,
        ] );

		$hasPost = $hasWord->posts->first();
		if ( $hasPost ) {
			$flash = array(
				'message' => __('flash.sign.already', ['word' => $word]),
				'url'     => URL::to( config( 'wign.urlPath.sign' ) . '/' . $word )
			);

			return redirect( config( 'wign.urlPath.request' ) )->with( $flash );
		}

		$hasVote = $hasWord->requests()->find($user->id);
		if ( !empty($hasVote) ) {
			return redirect( config( 'wign.urlPath.request' ) )->with( 'message',  __('flash.request.already', ['word' => $word]));
		} else {
			// Check if client is bot. If true, reject the creation!
			if ( Helper::detect_bot() ) {
				return redirect( config( 'wign.urlPath.request' ) )->with( 'message', __('flash.bot.refuse') );
			} else {
			    try {
                    $request = $hasWord->requests()->attach($user);
                } catch (\Exception $e)  {
                    return redirect( config( 'wign.urlPath.request' ) )->with( 'message',  __('flash.request.failed'));
                }
                $flash = [
                    'message' => __('flash.request.successful', ['word' => $word]),
                    'url'     => URL::to( config( 'wign.urlPath.sign' ) . '/' . $word )
                ];

                return redirect( config( 'wign.urlPath.request' ) )->with( $flash );
			}
		}
	}
}

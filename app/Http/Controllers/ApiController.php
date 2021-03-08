<?php namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Word;
use Illuminate\Support\Collection;
use DB;

class ApiController extends Controller {

	/**
	 * Display a greeting and short documentation of the API.
	 *
	 * @link https://api.wign.dk/
	 *
	 * @return \Illuminate\View\View
	 */
	public function index() {
		return view( 'ApiGreet' );
	}

	/**
	 * Checks whether we have at least one sign attached to the word
	 *
	 * @link https://api.wign.dk/hasSign/$word
	 *
	 * @param string $word the query word
	 *
	 * @return array with [$word => boolean]. If signs exist, it returns [$word => true]
	 */
	public function hasSign( $word = null ) {
		if ( empty( $word ) ) {
			return array();
		}
		$word            = Helper::underscoreToSpace( $word );
		$numWords        = Word::whereWord( $word )->count();
		$result[ $word ] = $numWords > 0;

		return $result;
	}

	/**
	 * Returns a list of video data for the signs of the queried $word.
	 *
	 * It finds all signs attached to $word, and for each signs it fetch their data and
	 * returns them. Returns empty array if no words is found, or no $word is provided.
	 *
	 * @link https://api.wign.dk/video/$word
	 *
	 * @param string $word - the query word
	 *
	 * @return Collection with all video data, or an empty Collection if none $word is provided
	 */
	public function getSign( $word = null ) {
		if ( isset( $word ) ) {
			$word   = Helper::underscoreToSpace( $word );
			$result = Word::join( 'signs', 'words.id', '=', 'signs.word_id' )->where( 'words.word', $word )->whereNull( 'signs.deleted_at' )->get( array(
				'video_uuid as videoID',
				'description',
				'thumbnail_url as thumb',
				'signs.created_at'
			) );
		} else {
			$result = new Collection;
		}

		return $result;
	}

	/**
	 * Find all words like the query $word.
	 *
	 * Returns a list of words that begin or end with $word. Returns empty array if no words is found.
	 * Returns a full list of words if no $word is provided. It is a good way to search for the right word,
	 * for example when using auto-completion.
	 *
	 * @link https://api.wign.dk/words/$word
	 *
	 * @param string $word
	 *
	 * @return Collection
	 */
	public function getWords( $word = "" ) {
		if ( isset( $word ) ) {
			$word = Helper::underscoreToSpace( $word );
		}

		return response( Word::getQueriedWord( $word )->get( array( 'word as label' ) ) )->header( 'Access-Control-Allow-Origin', '*' );
	}


	public function getRequests() {
		$requests = DB::select( DB::raw( '
            SELECT words.word, COUNT(request_words.id) AS request_count
			FROM words INNER JOIN request_words
			ON words.id = request_words.word_id
			WHERE (SELECT count(*) FROM signs WHERE signs.word_id = words.id AND signs.deleted_at IS NULL AND signs.flag_reason IS NULL) <= 0
			GROUP BY words.word
			ORDER BY request_count DESC, words.word ASC
        ' ) );

		return $requests;
	}
}
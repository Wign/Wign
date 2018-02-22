<?php namespace App\Http\Controllers;

use App\Services\SignService;
use App\Services\TagService;
use App\Word;
use App\Sign;

use DB;
use App\Helpers\Helper;
use URL;
use Redirect;
use \Illuminate\Http\Request;

class SignController extends Controller {

	// our services
	protected $sign_service;
	protected $tag_service;


	/**
	 * SignController constructor.
	 *
	 * @param SignService $sign_service
	 * @param TagService $tag_service
	 */
	public function __construct( SignService $sign_service, TagService $tag_service ) {
		$this->sign_service = $sign_service;
		$this->tag_service = $tag_service;
	}


	/**
	 * Show the sign page.
	 * Display all the signs if $word is non-null and does exist in database
	 * Otherwise show the 'no sign' page
	 *
	 * @param string $word - a nullable string with the query $word
	 *
	 * @return \Illuminate\View\View
	 */
	public function showSign( $word = null ) {
		if ( empty( trim( $word ) ) ) {
			return view( 'nosign' );
			// @TODO: Redirect user to index with flash that the sign don't exist
		}

		$word = Helper::underscoreToSpace( $word );

		$wordData = Word::where( 'word', $word )->first();
		$wordID   = $wordData['id'];

		// If word exist in database
		if ( $wordID && $this->hasSign( $wordID ) ) {
			// Query the database for the signs AND the number of votes they have and true if the user've voted it.
			$signs = DB::select( DB::raw( '
                SELECT signs.id, signs.video_uuid, signs.description, COUNT(votes.id) AS sign_count, GROUP_CONCAT(votes.ip ORDER BY votes.id) AS votesIP
                FROM signs LEFT JOIN votes
                ON signs.id = votes.sign_id
                WHERE signs.word_id = :wordID AND signs.deleted_at IS NULL
                GROUP BY signs.id, signs.video_uuid, signs.description
                ORDER BY sign_count DESC
            ' ), array( 'wordID' => $wordData["id"] ) );

			// Has the user voted for the signs?
			$signs = $this->hasVoted( $signs );
			$signs = $this->sign_service->isSignTagged( $signs );

			return view( 'sign' )->with( array( 'word' => $wordData->word, 'signs' => $signs) );
		}

		// If no word exist in database; make a list of suggested word and display the 'no sign' view.
		$suggestWords = $this->findAlikeWords( $word );

		return view( 'nosign' )->with( [ 'word' => $word, 'suggestions' => $suggestWords ] );
	}

	/**
	 * Show the recent # words which have been assigned with a sign
	 *
	 * @param int $number of recent results
	 *
	 * @return \Illuminate\View\View
	 */
	public function showRecent( $number = 25 ) {
		$words = Word::withSign()->latest( $number )->get();

		return view( 'list' )->with( [ 'words' => $words, 'number' => $number ] );
	}

	/**
	 * Show all words with assigned sign, sorted by word ASC
	 *
	 * @return \Illuminate\View\View
	 */
	public function showAll() {
		$words = Word::withSign()->orderBy( 'word' )->get();

		return view( 'listAll' )->with( [ 'words' => $words ] );
	}

	/**
	 * Display the "create a sign" view with the relevant data attached.
	 * If a word is set, it's checked if it already has a sign to it.
	 *
	 * @param String $word the queried word. Nullable.
	 *
	 * @return \Illuminate\View\View of "create a sign"
	 */
	public function createSign( $word = null ) {
		if ( empty( $word ) ) {
			return view( 'create' );
		}

		$hasSign         = Word::where( 'word', $word )->withSign()->first();
		$data['hasSign'] = empty( $hasSign ) ? 0 : 1;
		$data['word']    = $word;

		return view( 'create' )->with( $data );
	}

	/**
	 * Validate and save the sign created by the user (And send a Slack message).
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function saveSign( Request $request ) {
		// Validating the incoming request
		$this->validate( $request, [
			'word'        => 'required|string',
			'description' => 'nullable|string',
			'wign01_uuid' => 'required'
		] );

		$q = $request->all();

		$findWord = Word::firstOrCreate( [ 'word' => $q['word'] ] );
		$wordID   = $findWord->id;
		$word     = $q['word'];

		$sign = Sign::create( array(
			'word_id'             => $wordID,
			'description'         => $q['description'],
			'video_uuid'          => $q['wign01_uuid'],
			'video_url'           => $q['wign01_vga_mp4'],
			'thumbnail_url'       => $q['wign01_vga_thumb'],
			'small_thumbnail_url' => $q['wign01_qvga_thumb'],
			'ip'                  => $request->ip()
		) );

		$this->tag_service->storeTags($sign);

		if ( $sign ) {
			$this->sendSlack( $word, $sign );

			$flash = [
				'message' => __('flash.sign.created'),
			];
		} else {
			// Something went wrong! The sign isn't created!
			$flash = [
				'message' => __('flash.sign.create.failed'),
			];
		}
		$flash['url'] = URL::to( config( 'wign.urlPath.create' ) );

		return redirect( config( 'wign.urlPath.sign' ) . '/' . $word )->with( $flash );
	}

	/**
	 * Show the view which the user can flag a certain sign for e.g. offensive content.
	 *
	 * @param integer $id
	 *
	 * @return \Illuminate\View\View
	 */
	public function flagSignView( $id ) {
		$sign = Sign::where( 'id', $id )->first();

		return view( 'form.flagSign' )->with( [
			'id'   => $id,
			'img'  => $sign->small_thumbnail_url,
			'word' => $sign->word
		] );

	}

	/**
	 * Perform bot-check, validate and flag the sign for the reason - hiding the sign until someone take a look at it.
	 * Redirects the user to the front page with a flash message.
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 * @throws \Exception
	 */
	public function flagSign( Request $request ) {
		// Check if client is bot. If true, reject the flagging!
		if ( Helper::detect_bot() ) {
			return redirect( '/' )->with( 'message', __('flash.bot.refuse') );
		}

		$this->validate( $request, [
			'content' => 'required',
			'email'   => 'email'
		] );

		$q = $request->all(); // content, commentar, id, email

		$theSign = Sign::where( 'id', $q['id'] )->first();

		$theSign->flag_reason  = $q['content'];
		$theSign->flag_comment = $q['commentar'];
		$theSign->flag_email   = $q['email'];
		$theSign->flag_ip      = $request->ip();

		$saved = $theSign->save();

		$success = false;

		if ( $saved ) {
			$deleted = $theSign->delete();
			if ( $deleted ) {
				$success = true;
			}
		}

		if ( $success ) {
			return Redirect::to( '/' )->with( 'message', __('flash.report.successful') );
		} else {
			$flash = [
				'message' => __('flash.report.failed'),
				'url'     => 'mailto:' . config( 'wign.email' )
			];

			return Redirect::to( config( 'wign.urlPath.sign' ) . '/' . $saved->word )->with( $flash );
		}
	}

	/**
	 * Returns true if the word ID has at least one sign attached to it, otherwise false.
	 *
	 * @param $wordID
	 *
	 * @return bool
	 */
	private function hasSign( $wordID ) {
		return Sign::findByWordID( $wordID )->count() > 0;
	}

	/**
	 * Searching for words that looks alike the queried $word
	 * Current uses Levenshtein distance, and return the 5 words with the least distance to $word
	 *
	 * @param $word - the query string
	 *
	 * @return array|null - array with words as value
	 */
	private function findAlikeWords( $word ) {
		if ( empty( $word ) ) {
			return null;
		} else {
			$max_levenshtein = 5;
			$min_levenshtein = PHP_INT_MAX;
			$words           = Word::withSign()->get();
			$tempArr         = array();

			foreach ( $words as $compareWord ) {
				$levenDist = levenshtein( strtolower( $word ), strtolower( $compareWord->word ) );
				if ( $levenDist > 5 || $levenDist > $min_levenshtein ) {
					continue;
				} else {
					$tempArr[ $compareWord->word ] = $levenDist;
					if ( count( $tempArr ) == $max_levenshtein + 1 ) {
						asort( $tempArr );
						$min_levenshtein = array_pop( $tempArr );
					}
				}
			};

			if ( empty( $tempArr ) ) {
				return null; // There are none word with nearly the same "sounding" as $word
			} else {
				asort( $tempArr );
				$suggestWords = [];
				foreach ( $tempArr as $key => $value ) {
					$suggestWords[] = $key;
				}

				return $suggestWords;
			}
		}
	}

	/**
	 * Updates the collection, inserting a key with a boolean value for each sign,
	 * which tells whether the user have voted the sign or not.
	 *
	 * @param array $signs
	 *
	 * @return array updated with the values
	 */
	private function hasVoted( array $signs ): array {
		$myIP = \Request::getClientIp();
		foreach ( $signs as $sign ) {
			if ( empty( $sign->votesIP ) ) {
				continue;
			}
			$result = false;

			if ( ! is_array( $sign->votesIP ) ) {
				if( $sign->votesIP == $myIP ) {
					$result = true;
				}
			} else {
				foreach ( $sign->votesIP as $vote ) {
					if ( $vote == $myIP ) {
						$result = true;
						break;
					}
				}
			}
		$sign->voted = $result;
		}

		return $signs;
	}

	/**
	 * Nice little function to send a Slack greet using webhook each time a new sign is posted on Wign.
	 * It's to keep us busy developers awake! Thank you for your contribution!
	 *
	 * @param String $word
	 * @param \Illuminate\Database\Eloquent\Model $sign - the $sign object, from which we can extract the information from.
	 */
	private function sendSlack( $word, $sign ) {
		$url     = URL::to( config( 'wign.urlPath.sign' ) . '/' . $word );
		$video   = 'https:' . $sign->video_url;
		$message = [
			"attachments" => [
				[
					"fallback"     => "Videoen kan ses her: " . $video . "!",
					"color"        => "good",
					"pretext"      => "Et ny tegn er kommet!",
					"title"        => $word,
					"title_link"   => $url,
					"text"         => "Se <" . $video . "|videoen>!",
					"unfurl_links" => true,
					"image_url"    => "https:" . $sign->thumbnail_url,
					"thumb_url"    => "https:" . $sign->small_thumbnail_url,
				]
			],
		];
		Helper::sendJSON( $message, config( 'social.slack.webHook' ) );
	}

}
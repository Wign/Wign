<?php namespace App\Http\Controllers;

use App\Services\SignService;
use App\Services\TagService;
use App\Services\WordService;
use App\Word;
use App\Sign;

use App\Helpers\Helper;
use Response;
use URL;
use Redirect;
use Illuminate\Http\Request;

class SignController extends Controller {

	// our services
	protected $word_service;
	protected $sign_service;
	protected $tag_service;


	/**
	 * SignController constructor.
	 *
	 * @param SignService $sign_service
	 * @param TagService $tag_service
	 * @param WordService $word_service
	 */
	public function __construct( SignService $sign_service, TagService $tag_service, WordService $word_service ) {
		$this->sign_service = $sign_service;
		$this->tag_service  = $tag_service;
		$this->word_service = $word_service;
	}


	/**
	 * Show the sign page.
	 * Display all the signs if $word is non-null and does exist in database
	 * Otherwise show the 'no sign' page
	 *
	 * @param string $word - a nullable string with the query $word
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
	 */
	public function showSign( $word ) {
		$word      = $this->word_service->underscoreToSpace( $word );
		$wordModel = $this->word_service->getWordByWord( $word );

		// If word exist in database
		if ( isset( $wordModel ) ) {
			$signs = $this->sign_service->getVotedSigns( $wordModel );
			$signs = $signs->sortByDesc( 'num_votes' ); // Sort the signs according to the number of votes

			return view( 'sign' )->with( array( 'word' => $wordModel->word, 'signs' => $signs ) );
		}

		// If no word exist in database; make a list of suggested word and display the 'no sign' view.
		$suggestWords = $this->word_service->getAlikeWords( $word, 5 );

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
		$recent = $this->word_service->getRecentWords( $number );

		return view( 'list' )->with( [ 'words' => $recent, 'number' => $number ] );
	}

	/**
	 * Show all words with assigned sign, sorted by word ASC
	 *
	 * @return \Illuminate\View\View
	 */
	public function showAll() {
		$words = $this->word_service->getAllWordsSorted();

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

		$wordData        = $this->word_service->getWordByWord( $word );
		$data['hasSign'] = empty( $wordData ) ? 0 : 1;
		$data['word']    = empty( $wordData ) ? $word : $wordData->word;

		return view( 'create' )->with( $data );
	}

	/**
	 * Validate and save the sign created by the user (And send a Slack message).
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function saveSign( Request $request ) {
		// Validating the incoming request
		$request->validate( [
			'word'              => 'required|string',
			'description'       => 'nullable|string',
			'wign01_uuid'       => 'required',
			'wign01_vga_mp4'    => 'required',
			'wign01_vga_thumb'  => 'required',
			'wign01_qvga_thumb' => 'required',
		] );

		$q = $request->all();

		$findWord = Word::firstOrCreate( [ 'word' => $q['word'] ] );
		$wordID   = $findWord->id;
		$word     = $findWord->word;

		$sign = Sign::create( array(
			'word_id'             => $wordID,
			'description'         => $q['description'],
			'video_uuid'          => $q['wign01_uuid'],
			'video_url'           => $q['wign01_vga_mp4'],
			'thumbnail_url'       => $q['wign01_vga_thumb'],
			'small_thumbnail_url' => $q['wign01_qvga_thumb'],
			'ip'                  => $request->ip()
		) );

		$this->tag_service->storeTags( $sign );

		if ( $sign ) {
			$this->sendSlack( $word, $sign );

			$flash = [
				'message' => __( 'flash.sign.created' ),
			];
		} else {
			// Something went wrong! The sign isn't created!
			$flash = [
				'message' => __( 'flash.sign.create.failed' ),
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
		$sign = $this->sign_service->getSignByID( $id );

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
			return redirect( '/' )->with( 'message', __( 'flash.bot.refuse' ) );
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
			return Redirect::to( '/' )->with( 'message', __( 'flash.report.successful' ) );
		} else {
			$flash = [
				'message' => __( 'flash.report.failed' ),
				'url'     => 'mailto:' . config( 'wign.email' )
			];

			return Redirect::to( config( 'wign.urlPath.sign' ) . '/' . $saved->word )->with( $flash );
		}
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
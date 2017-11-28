<?php namespace App\Http\Controllers;

use App\Word;
use App\Sign;
use App\Helpers\Helper;

use DB;
use Illuminate\Database\Eloquent\Collection;
use URL;
use Request;
use Redirect;

class SignController extends Controller {

	/**
	 * Show the sign page.
	 * Display all the signs if $word is non-null and does exist in database
	 * Otherwise show the 'no sign' page
	 *
	 * @link www.wign.dk/tegn/
	 *
	 * @param string $word - a nullable string with the query $word
	 *
	 * @return \Illuminate\View\View
	 */
	public function showSign( $word = null ) {
		if ( empty( trim( $word ) ) ) {
			return view( 'nosign' );
		}

		$word = Helper::underscoreToSpace( $word );

		$wordData = Word::where( 'word', $word )->first();
		$wordID   = $wordData['id'];

		// If word exist in database
		if ( $wordID && $this->hasSign( $wordID ) ) {
			// Query the database for the signs AND the number of votes they have and true if the user've voted it.
			$signs = DB::select( DB::raw( '
                SELECT signs.*, COUNT(votes.id) AS sign_count, GROUP_CONCAT(votes.ip ORDER BY votes.id) AS votesIP
                FROM signs LEFT JOIN votes
                ON signs.id = votes.sign_id
                WHERE signs.word_id = :wordID AND signs.deleted_at IS NULL
                GROUP BY signs.id 
                ORDER BY sign_count DESC
            ' ), array( 'wordID' => $wordData["id"] ) );

			// Has the user voted for the signs?
			$signs = $this->hasVoted( $signs );
			// @TODO add a select clause (in later version) to only fetch the needed keys from the collection.

			return view( 'sign' )->with( array( 'word' => $wordData->word, 'signs' => $signs ) );
		}

		// If no word exist in database; make a list of suggested word and display the 'no sign' view.
		$suggestWords = $this->findAlikeWords( $word );

		return view( 'nosign' )->with( [ 'word' => $word, 'suggestions' => $suggestWords ] );
	}

	public function visSeneste() {
		$antal = 25;
		$words = Word::has( 'signs' )->latest( $antal )->get();

		return view( 'list' )->with( [ 'words' => $words, 'antal' => $antal ] );
	}

	public function visAlle() {
		$words = Word::has( 'signs' )->orderBy( 'word' )->get();

		return view( 'listAll' )->with( [ 'words' => $words ] );
	}

	public function gemTegn( Request $request ) {
		$this->validate( $request, [
			'tegn'        => 'required|string',
			'beskr'       => 'string',
			'wign01_uuid' => 'required'
		] );

		$q = $request->all();

		$hasWord = Word::firstOrCreate( [ 'word' => $q['tegn'] ] ); //@TODO Change the id of the field to 'sign' - and 'beskr' => 'description'!
		$wordID  = $hasWord->id;

		// Define the values for easier fetch
		$sign        = $q['tegn'];
		$description = $q['beskr'];
		$video_uuid  = $q['wign01_uuid'];
		$video_url   = $q['wign01_vga_mp4'];
		$thumb       = $q['wign01_vga_thumb'];
		$thumb_small = $q['wign01_qvga_thumb'];

		$signId = Sign::create( array(
			'word_id'             => $wordID,
			'description'         => $description,
			'video_uuid'          => $video_uuid,
			'video_url'           => $video_url,
			'thumbnail_url'       => $thumb,
			'small_thumbnail_url' => $thumb_small,
			'ip'                  => $request->ip()
		) );

		if ( $signId ) {
			$url     = URL::to( '/tegn/' . $sign );
			$video   = 'https:' . $video_url;
			$message = [
				"attachments" => [
					[
						"fallback"     => "Videoen kan ses her: " . $video . "!",
						"color"        => "good",
						"pretext"      => "Et ny tegn er kommet!",
						"title"        => $sign,
						"title_link"   => $url,
						"text"         => "Se <" . $video . "|videoen>!",
						"unfurl_links" => true,
						"image_url"    => "https:" . $thumb,
						"thumb_url"    => "https:" . $thumb_small,
					]
				],
			];
			Helper::sendJSON( $message, config( 'social.slack.webHook' ) );

			$flash = [
				'message' => 'Tegnet er oprettet. Tusind tak for din bidrag! Tryk her for at opret flere tegn',
				'url'     => URL::to( '/opret' )
			];

			return redirect( '/tegn/' . $q['tegn'] )->with( $flash );
		}
	}

	public function flagSignView( $id ) {

		$word = Sign::where( 'id', $id )->first()->word;
		$img  = Sign::where( 'id', $id )->pluck( 'small_thumbnail_url' );

		return view( 'form.flagSign' )->with( [ 'id' => $id, 'img' => $img, 'word' => $word ] );

	}

	public function flagSign( Request $request ) {
		// Check if client is bot. If true, reject the flagging!
		$bot = Helper::detect_bot();
		if ( $bot ) {
			$flash = [
				'message' => 'Det ser ud til at du er en bot. Vi må desværre afvise din rapportering af tegnet!'
			];

			return redirect( '/' )->with( $flash );
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

		if ( $saved ) {
			$deleted = $theSign->delete();

			if ( $deleted ) {
				return Redirect::to( '/' )->with( 'message', 'Tusind tak for din rapportering af tegnet. Videoen er fjernet indtil vi kigger nærmere på den. Du hører fra os.' );
			} else {
				$flash = [
					'message' => 'Der skete en fejl med at rapportere det. Prøv venligst igen, eller kontakt os i Wign. På forhånd tak.',
					'url'     => 'mailto:' . config( 'wign.email' )
				];

				return Redirect::to( '/flagSignView/' . $q['id'] )->with( $flash );
			}
		}
	}

	private function hasSign( $id ) {
		return Sign::findByWordID( $id )->count() > 0;
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
	 * Inserting a boolean value for each sign, which tells whether the user have voted the sign or not.
	 *
	 * @param Collection $signs
	 *
	 * @return Collection updated with the values
	 */
	private function hasVoted( $signs ) {
		$myIP = Request::getClientIp();
		foreach ( $signs as $sign ) {
			$count = count( $sign->votesIP );

			$result = false;
			if ( $count == 0 ) {
				continue;
			} else if ( $count == 1 ) {
				if ( $sign->votesIP == $myIP ) {
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

}
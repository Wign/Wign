<?php

namespace App\Services;

use App\Sign;
use App\Word;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class SignService
 * @package App\Services
 * @deprecated
 */
class SignService {

    /**
     * @return mixed
     * @deprecated
     */
	public function getAllSigns() {
		return Sign::noFlagged()->get(); // Need -get for "maketags.php"
	}

    /**
     * @param int $id
     * @return mixed
     * @deprecated
     */
	public function getSignByID( int $id ) {
		return Sign::all()->find( $id );
	}

    /**
     * @param string $word
     * @return mixed
     * @deprecated
     */
	public function getSignByWord( string $word ) {
		return Word::whereWord( $word )->first()->signs;
	}

    /**
     * @param integer $wordID
     * @return mixed
     * @deprecated
     */
	public function getSignByWordID( integer $wordID ) {
		return Word::whereID( $wordID )->signs;
	}

    /**
     * @return mixed
     * @deprecated
     */
	public function countSigns() {
		return $this->getAllSigns()->count();
	}

    /**
     * @param Sign $sign
     * @return mixed
     * @deprecated
     */
	public function getTags( Sign $sign ) {
		return $sign->tags()->get();
	}

    /**
     * @param Sign $sign
     * @return Sign
     * @deprecated
     */
	public function isSignTagged( Sign $sign ) {
		if ( $sign->tags()->count() > 0 ) {
			$sign->isTagged = true;
		} else {
			$sign->isTagged = false;
		}

		return $sign;
	}

	/**
	 * Updates $sign with
	 * 1) Number of votes
	 * 2) Whether it has at least one vote, or not
	 * 3) Whether it's voted by the user (based on the IP address), or not
	 *
	 * @param Sign $sign
	 *
	 * @return Sign
     * @deprecated
	 */
	public function assignVotesToSign( Sign $sign ) {
		$myIP            = \Request::getClientIp();
		$votes           = $sign->votes;
		$sign->num_votes = count( $votes );
		$sign->hasVoted  = count( $votes ) > 0 ? true : false;

		$result = false;
		foreach ( $votes as $vote ) {
			if ( $vote->ip == $myIP ) {
				$result = true;
			}
		}
		$sign->voted = $result;

		return $sign;
	}

	/**
	 * Find all signs to $word, and assign number of votes, vote ip's and
	 *
	 * @param Word $word
	 *
	 * @return Collection|static[]
     * @deprecated
	 */
	public function getVotedSigns( Word $word ) {
		$signs = Sign::whereWordId( $word->id )->get();
		foreach ( $signs as $sign ) {
			$this->assignVotesToSign( $sign );
			$this->isSignTagged( $sign );
		}

		return $signs;
	}

    /**
     * @param int $id
     * @return string
     * @deprecated
     */
	public function getWordBySignID( int $id ): string {
		return Sign::all()->find( $id )->word->word;
	}

    /**
     * @param Sign $sign
     * @return string
     * @deprecated
     */
	public function getWordBySign( Sign $sign ): string {
		return $sign->word->word;
	}
}
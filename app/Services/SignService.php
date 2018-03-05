<?php

namespace App\Services;

use App\Sign;
use App\Word;
use Illuminate\Database\Eloquent\Collection;

class SignService {

	public function getAllSigns() {
		return Sign::noFlagged()->get(); // Need -get for "maketags.php"
	}

	public function getSignByID( int $id ) {
		return Sign::noFlagged()->find( $id );
	}

	public function getSignByWord( string $word ) {
		return Word::whereWord( $word )->signs;
	}

	public function getSignByWordID( integer $wordID ) {
		return Word::whereID( $wordID )->signs;
	}

	public function countSigns() {
		return $this->getAllSigns()->count();
	}

	public function getTags( Sign $sign ) {
		return $sign->tags()->get();
	}

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
	 */
	public function getVotedSigns( Word $word ) {
		$signs = Sign::whereWordId( $word->id )->get();
		foreach ( $signs as $sign ) {
			$this->assignVotesToSign( $sign );
			$this->isSignTagged( $sign );
		}

		return $signs;
	}

	public function getWordBySignID( int $id ): string {
		return Sign::all()->find( $id )->word->word;
	}
}
<?php
namespace App\Services;

use App\Word;

class WordService {

	/**
	 * Returns all words that is attached to at least one sign
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function getAllWords() {
		return Word::withSign()->all();
	}

	/**
	 * Returns all words that looks alike $search
	 * @param string $search
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function getQueriedWords( string $search ) {
		return Word::getQueriedWord($search)->get();
	}

}
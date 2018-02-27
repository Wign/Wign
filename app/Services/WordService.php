<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Word;

class WordService {

	/**
	 * Get all words with assigned sign
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function getAllWords() {
		return Word::withSign()->get();
	}

	/**
	 * Get all words with assigned sign, sorted by word ASC
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function getAllWordsSorted() {
		return Word::withSign()->orderBy( 'word' )->get();
	}

	/**
	 * Simple function to get the Model Word with a string word.
	 *
	 * @param string $word
	 *
	 * @return \Illuminate\Database\Eloquent\Model|null|object|static
	 */
	public function getWordByWord( string $word ) {
		return Word::whereWord( $word )->withSign()->first();
	}

	/**
	 * Returns all words that looks alike $search
	 *
	 * @param string $search
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function getQueriedWords( string $search ) {
		return Word::getQueriedWord( $search )->get();
	}

	/**
	 * Get the recent # words which have been assigned with a sign
	 *
	 * @param int $number
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
	 */
	public function getRecentWords( int $number ) {
		return Word::withSign()->latest( $number )->get();
	}

	/**
	 * Searching for words that looks alike the queried $word
	 * Current uses Levenshtein distance, and return the 5 words with the least distance to $word
	 *
	 * @param string $word
	 *
	 * @return array|null
	 */
	public function getAlikeWords( string $word ) {
		$max_levenshtein = 5;
		$min_levenshtein = PHP_INT_MAX;
		$words           = $this->getAllWords();
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

	/**
	 * Returns true if the word has at least one sign attached to it, otherwise false.
	 *
	 * @param Word $word
	 *
	 * @return bool
	 */
	public function hasSign( Word $word ): bool {
		return $word->signs->count() > 0;
	}

	/**
	 * Converts any underscores (_) in strings to spaces ( )
	 *
	 * It is the opposite of {@see \App\Services\Word->makeUrlString}, which converts spaces to underscores
	 * so it's easier to parse as a URL string.
	 *
	 * @param string $s
	 *
	 * @return string
	 */
	public function underscoreToSpace( string $s ): string {
		return Helper::underscoreToSpace($s);
	}

	/**
	 * Converts any string to URL-friendly string
	 *
	 * It converts all spaces to underscore (_), lowercase the string and
	 * removes all "-" and "_" in the beginning or end of the string
	 *
	 * @param string $s
	 *
	 * @return string
	 */
	public static function makeUrlString( string $s ): string {
		return Helper::makeUrlString($s);
	}



}
<?php

namespace App\Services;


use App\Sign;
use App\Word;

class SignService {

	public function getAllSigns() {
		return Sign::all();
	}

	public function getSignByID( int $id ) {
		return Sign::find($id);
	}

	public function getSignByWord( string $word ) {
		return Word::whereWord($word)->signs;
	}

	public function countSigns() {
		return count( $this->getAllSigns() );
	}

	public function getTags( Sign $sign ) {
		return $sign->tags()->get();
	}

	public function hasTag( $signs ) {
		foreach ($signs as $sign) {
			if(count($this->getTags($this->getSignByID($sign->id))) > 0) {
				$sign->isTagged = true;
			}
			else {
				$sign->isTagged = false;
			}
		}
		return $signs;
	}

}
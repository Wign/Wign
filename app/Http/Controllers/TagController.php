<?php

namespace App\Http\Controllers;

use App\Services\SignService;
use App\Services\TagService;
use Redirect;

class TagController extends Controller {
	protected $tag;
	protected $sign;

	public function findTags( $tag ) {
		$theTag = $this->tag->findTagByName( $tag );

		$signs = $this->tag->getTaggedSigns( $theTag );
		if ( empty( $signs ) ) {
			abort( 404, __( 'text.sign.not.have' ) );
		}
		foreach ( $signs as $sign ) {
			$this->sign->isSignTagged( $sign );
			$this->sign->assignVotesToSign( $sign );
			$sign->theWord = $this->sign->getWordBySign( $sign );
		}

		// Sorts the signs according to the words (And not according to number of votes as in "sign" page)
		$signs = $signs->sortBy( function ( $sign ) {
			return strtolower( $sign->theWord );
		} );

		return view( 'sign' )->with( array( 'word' => '#' . $theTag->tag, 'signs' => $signs, 'hashtag' => true ) );

	}
}
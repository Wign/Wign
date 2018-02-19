<?php

namespace App\Http\Controllers;

use App\Services\SignService;
use App\Services\TagService;
use Redirect;

class TagController extends Controller {
	protected $tag;

	/**
	 * TagController constructor.
	 *
	 * @param TagService $tag
	 */
	public function __construct( TagService $tag ) {
		$this->tag = $tag;
	}

	public function findTags( $name = null ) {
		$theTag = $this->tag->findTagByName( $name );

		if ( empty( $theTag ) ) {
			Redirect::to( '/' ); //@TODO: Add a flash message about the tag doesn't exist
		}

		$signService = new SignService();

		$signs = $this->tag->getTaggedSigns($theTag);
		$signService->hasTag($signs);

		return view( 'sign' )->with( array( 'word' => '#'.$name, 'signs' => $signs, 'hashtag' => true ) );

	}
}
<?php

namespace App\Http\Controllers;

use App\Services\SignService;
use App\Services\TagService;
use Redirect;

class TagController extends Controller {
	protected $tag;
	protected $sign;


	/**
	 * TagController constructor.
	 *
	 * @param TagService $tag
	 * @param SignService $sign
	 */
	public function __construct( TagService $tag, SignService $sign ) {
		$this->tag  = $tag;
		$this->sign = $sign;
	}

	public function findTags( $tag = null ) {
		if ( isset( $tag ) ) {
			$theTag = $this->tag->findTagByName( $tag );
		}
		if ( empty( $theTag ) ) {
			$flash['message'] = empty( $tag ) ? __( 'flash.tag.empty' ) : __( 'flash.tag.nonexistent', [ 'tag' => $tag ] );

			return Redirect::to( '/' )->with( $flash );
		}

		$signs = $this->tag->getTaggedSigns( $theTag );
		if ( empty( $signs ) ) {
			abort( 404, __( 'text.sign.not.have' ) );
		}
		$this->sign->isSignTagged( $signs );


		return view( 'sign' )->with( array( 'word' => '#' . $theTag->tag, 'signs' => $signs, 'hashtag' => true ) );

	}
}
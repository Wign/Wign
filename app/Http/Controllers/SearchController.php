<?php namespace App\Http\Controllers;

use App\Services\TagService;
use App\Services\WordService;
use Illuminate\Http\Request;
use Redirect;
use Response;
use URL;

class SearchController extends Controller {

	// our services
	protected $word_service;
	protected $tag_service;

	/**
	 * SearchController constructor.
	 *
	 * @param $word_service
	 * @param $tag_service
	 */
	public function __construct( WordService $word_service, TagService $tag_service ) {
		$this->word_service = $word_service;
		$this->tag_service  = $tag_service;
	}


	/**
	 * Redirects all search queries to /tegn/{word} using SignController
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function redirect( Request $request ) {
		return Redirect::action( 'PostController@getPosts', [ 'word' => $request->word ] );
	}

	/**
	 * Provides data to autocomplete function
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function autocomplete( Request $request ) {
		$search = $request->term;
		$list   = $this->getTagsAndWords( $search );

		return Response::json( $list );
	}

	private function getTagsAndWords( string $query ): array {
		if ( empty( $query ) ) {
			return [];
		}

		$hash    = [ '#', '%23' ];
		$hashtag = starts_with( $query, $hash );

		if ( $hashtag ) {
			$query = str_replace( $hash, '', $query );
		}

		$list = [];

		$tags = $this->tag_service->getQueriedTags( $query );
		foreach ( $tags as $tag ) {
			$url    = URL::to( config( 'wign.urlPath.tags' ) . '/' . rawurlencode( $tag->tag ) );
			$list[] = [
				'label' => '#' . $tag->tag,
				'dtype' => 'tag',
				'id'    => $tag->id,
				'value' => $tag->tag,
				'url'   => $url
			];
		}

		$words = $this->word_service->getQueriedWords( $query );
		foreach ( $words as $word ) {
			$url    = URL::to( config( 'wign.urlPath.sign' ) . '/' . rawurlencode( $word->word ) );
			$list[] = [
				'label' => $word->word,
				'dtype' => 'word',
				'id'    => $word->id,
				'value' => $word->word,
				'url'   => $url
			];
		}

		return $list;
	}
}

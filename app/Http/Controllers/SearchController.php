<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller {

	/**
	 * Redirects all search queries to /tegn/{word} using SignController
	 *
	 * @todo Later we will add hashtags and thus must redirects to /results/{word} including hashtags and other search results.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function redirect( Request $request ) {
		$word = $request->get( 'word' );

		return redirect()->action( 'SignController@showSign', [ 'word' => $word ] );
	}
}

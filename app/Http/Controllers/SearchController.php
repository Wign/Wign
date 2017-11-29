<?php namespace App\Http\Controllers;

use Input;
use Redirect;
use App\Helpers\Helper;

class SearchController extends Controller {

	/**
	 * Redirects all search queries to /tegn/{word}
	 * @todo Later we will add hashtags and thus must redirects to /results/{word} including hashtags and other search results.
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function redirect() {
		$q = Helper::makeUrlString( Input::get( 'word' ) );

		return Redirect::to( config( 'wign.urlPath.sign' ) . '/' . $q );
	}
}

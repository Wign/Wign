<?php namespace App\Http\Controllers;

use App;
Use App\Word;
Use App\Sign;

class IndexController extends Controller {

	/**
	 * Show the main index
	 *
	 * Using a random word, # of signs, and # of words from DB
	 *
	 * @link www.wign.dk
	 * @return \Illuminate\View\View
	 */
	public function index() {   //TODO
		$words = Post::withSign();
		$wordCount = $words->count();
		$randomWord = $words->random( 1, $wordCount )->first();
		$signCount = Sign::count();

		return view( 'index' )->with( [
			'randomWord' => $randomWord,
			'signCount'  => $signCount,
			'wordCount'  => $wordCount
		] );
	}

	/**
	 * Show the about page
	 *
	 * @link www.wign.dk/about
	 * @return \Illuminate\View\View
	 */
	public function about() {
		return view( 'about' );
	}

	/**
	 * Show the help page
	 *
	 * @link www.wign.dk/help
	 * @return \Illuminate\View\View
	 */
	public function help() {
		return view( 'help' );
	}

	/**
	 * Show the policy page
	 *
	 * @link www.wign.dk/policy
	 * @return \Illuminate\View\View
	 */
	public function policy() {
		$lang = App::getLocale();
		return view( $lang.'.policy' );
	}

	/**
	 * Show the "You're blacklisted" page
	 *
	 * @link www.wign.dk/blacklist
	 * @return \Illuminate\View\View
	 */
	public function blacklist() {
		return view( 'blacklist' );
	}

}

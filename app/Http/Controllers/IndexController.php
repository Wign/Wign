<?php namespace App\Http\Controllers;

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
	public function index() {
		$words = Word::withSign();
		$wordCount = $words->count();
		$randomWord = $words->random( 1 )->first();
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
	 * @link www.wign.dk/om
	 * @return \Illuminate\View\View
	 */
	public function about() {
		return view( 'about' );
	} // @TODO: Change it to "about".

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
	 * @link www.wign.dk/retningslinjer
	 * @return \Illuminate\View\View
	 */
	public function policy() {
		return view( 'policy' );
	} // @TODO Change the web address to "policy"

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

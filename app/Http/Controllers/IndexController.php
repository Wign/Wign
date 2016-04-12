<?php namespace App\Http\Controllers;

Use DB;
use Illuminate\Http\Request;
Use App\Word;
Use App\Blacklist;

class IndexController extends Controller {

	/**
	 * Show the main index 
     * 
     * @link www.wign.dk
	 * @return View
	 */
	public function index() {
        $randomWord = Word::has('signs')->random()->first();
        $signCount = Word::has('signs')->count();
		return view('index')->with(['randomWord' => $randomWord, 'signCount' => $signCount]);
	}

    /**
     * Show the about page
     * 
     * @link www.wign.dk/om
     * @return View
     */
    public function about() {
        return view('about');
    }

    /**
     * Show the help page
     * 
     * @link www.wign.dk/help
     * @return View
     */
    public function help() {
        return view('help');
    }

    /**
     * Show the "fuck you" page
     * 
     * @return View
     */
    public function blacklist() {
        return view('blacklist');
    }

}

<?php namespace App\Http\Controllers;

Use DB;
Use App\Word;

class IndexController extends Controller {

	/**
	 * Show the main index.
	 * @return View
	 */
	public function index() {
        $randomWord = Word::has('signs')->random()->first();
		return view('index')->with('randomWord', $randomWord);
	}

    /**
     * Show the about page.
     * @return View
     */
    public function about() {
        return view('about');
    }

    /**
     * Show the help page.
     * @return View
     */
    public function help() {
        return view('help');
    }

}

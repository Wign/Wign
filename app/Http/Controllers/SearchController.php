<?php namespace App\Http\Controllers;

use Input;
use Redirect;

class SearchController extends Controller {

    /**
     * Redirects the search queries to /tegn/{word}
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect()
    {
        $q = GenerateUrl(Input::get('tegn'));
        return Redirect::to('/tegn/'.$q);
    }
}

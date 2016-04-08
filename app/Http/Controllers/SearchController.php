<?php namespace App\Http\Controllers;

use Input;
use Redirect;

class SearchController extends Controller {

    public function redirect()
    {
        $q = null;
        $q = GenerateUrl(Input::get('tegn'));
        return Redirect::to('/tegn/'.$q);
    }

}
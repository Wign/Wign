<?php namespace App\Http\Controllers;

use Input;
use Redirect;

class SearchController extends Controller {

    /**
     * Redirects the search queries to /tegn/{word}
     * @return Redirect [description]
     */
    public function redirect()
    {
        $q = null;
        $q = Input::get('tegn');
        return Redirect::to('/tegn/'.$q);
    }

        /**
     * Genererer en URL fra en string - skaber en læservenligt webadresse til brug som adresse
     * @param string $s
     */
    function makeUnderstrokes($s) {

        //Convert accented characters
        //$from = explode(",", "ç,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û");
        //$to = explode(",", "c,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u");
             
        //Do the replacements, and convert all spaces to understrokes
        //$s = str_replace($from, $to, trim($s));

        //Konverter alle mellemrum til "_"
        $s = preg_replace('/\s+/', '_', $s);
        
        //Fjerner "-" eller "_" i begyndelsen eller slutningen
        return preg_replace('/^(-|_)+/', '', preg_replace('/(-|_)+$/', '', $s));
    }
}
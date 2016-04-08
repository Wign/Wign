<?php namespace App\Helpers;

class ClientHelper {

    /**
     * A helper function to detect the most suspicious bots/crawlers
     * @return TRUE if the user agent of the client seems suspicious.
     */
    public static function detect_bot() {
        if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|slurp|spider/i', $_SERVER['HTTP_USER_AGENT'])) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }
}
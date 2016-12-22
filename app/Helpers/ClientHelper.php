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

    public static function sendJSON($message, $url) {
        $data_string = json_encode($message);
                                                                                                                     
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
        );

        $result = curl_exec($ch);
    }
}
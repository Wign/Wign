<?php namespace App\Helpers;

class Helper {

	/**
	 * Detecting the most suspicious bots/crawlers
	 * @return boolean if the user agent of the client seems suspicious, it returns TRUE.
	 */
	public static function detect_bot() {
		if ( isset( $_SERVER['HTTP_USER_AGENT'] ) && preg_match( '/bot|crawl|slurp|spider/i', $_SERVER['HTTP_USER_AGENT'] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Converts any underscores (_) in strings to spaces ( )
	 *
	 * It is the opposite of {@see \App\Helpers\Helper::makeUrlString}, which converts spaces to underscores
	 * so it's easier to parse as a URL string.
	 *
	 * @param string $s
	 *
	 * @return string with spaces
	 */
	public static function underscoreToSpace( $s ) {
		return trim( str_replace( "_", " ", $s ) );
	}

	/**
	 * Converts any string to URL-friendly string
	 *
	 * It converts all spaces to underscore (_), lowercase the string and
	 * removes all "-" and "_" in the beginning or end of the string
	 *
	 * @param string $s
	 *
	 * @return string the url-friendly string
	 */
	public static function makeUrlString( $s ) {
		//Converts all spaces to underscore (_) and lowercase the string
		$s = preg_replace( '/\s/', '_', strtolower( $s ) );

		//Removes "-" or "_" in the beginning or in the end of the string
		return preg_replace( '/^(-|_)/', '', preg_replace( '/(-|_)$/', '', $s ) );
	}

	/**
	 * Sends a JSON $message to a $url
	 *
	 * Using {@link http://php.net/manual/en/function.curl-init.php curl} it sends a json-encoded message with POST to
	 * a url target. Used here for Slack Webhooks and much more.
	 *
	 * @param mixed $message it can be any type except of resource. It uses {@link http://php.net/manual/en/function.json-encode.php json-encode}
	 * to encode the message)
	 * @param string $url the target URL.
	 *
	 * @return void
	 */
	public static function sendJSON( $message, $url ) {
		$data_string = json_encode( $message );

		$ch = curl_init( $url );
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $data_string );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Content-Length: ' . strlen( $data_string )
			)
		);

		curl_exec( $ch );
	}
}
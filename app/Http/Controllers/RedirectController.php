<?php

namespace App\Http\Controllers;

class RedirectController extends Controller
{
	/**
	 * Redirecting traffic from danish to english URL to view posts
	 * @param null $word
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	function post($word) {
		return redirect()->route( 'post', ['word' => $word], 301 );
	}

	/**
	 * Redirecting traffic from danish to english URL to create posts
	 * @param null $word
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	function new($word) {
		return redirect()->route( 'post.new', ['word' => $word], 301 );
	}
}

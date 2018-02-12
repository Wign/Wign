<?php

namespace App\Http\Controllers;

class RedirectController extends Controller
{
	/**
	 * Redirecting traffic from danish to english URL to view signs
	 * @param null $word
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	function sign($word = null) {
		return redirect()->route( 'sign', ['word' => $word], 301 );
	}

	/**
	 * Redirecting traffic from danish to english URL to create signs
	 * @param null $word
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	function new($word = null) {
		return redirect()->route( 'new', ['word' => $word], 301 );
	}
}

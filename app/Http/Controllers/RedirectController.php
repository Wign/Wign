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
	function sign($word) {
		return redirect()->route( 'sign', ['word' => $word], 301 );
	}

	/**
	 * Redirecting traffic from danish to english URL to create signs
	 * @param null $word
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	function new($word) {
		return redirect()->route( 'new', ['word' => $word], 301 );
	}
}

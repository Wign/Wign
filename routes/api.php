<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
	return $request->user();
});
*/

// The routes for our api
Route::group( [
	'domain' => 'api.' . env( 'APP_DOMAIN', 'wign.dk' ),
	'middleware'=>['throttle:100000'],
	],
	function () {
		Route::get( '/', 'ApiController@index' );
		Route::get( 'hasSign/{word?}', 'ApiController@hasSign' );
		Route::get( 'video/{word?}', 'ApiController@getSign' );
		Route::get( 'words/{query?}', array( 'as' => 'words', 'uses' => 'ApiController@getWords' ) );
	} );
<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// The routes for our api
$domain = env( 'APP_DOMAIN', 'wign.dk' );
Route::group( [ 'domain' => 'api.' . $domain ], function () {
	Route::get( '/', 'ApiController@index' );
	Route::get( 'hasSign/{word?}', 'ApiController@hasSign' );
	Route::get( 'video/{word?}', 'ApiController@getSign' );
	Route::get( 'words/{query?}', array( 'as' => 'words', 'uses' => 'ApiController@getWords' ) );
} );

// First, the blacklist if someone is on it
$router->get( config( 'wign.urlPath.blacklist' ), 'IndexController@blacklist' );

// Index and static pages
$router->get( '/', 'IndexController@index' );
$router->get( config( 'wign.urlPath.about' ), 'IndexController@about' );
$router->get( config( 'wign.urlPath.help' ), 'IndexController@help' );

// Search route
$router->post( 'redirect', 'SearchController@redirect' );

// Dynamic routes
$router->get( config( 'wign.urlPath.sign' ) . '/{word?}', 'SignController@showSign' );
$router->get( config( 'wign.urlPath.recent' ), 'SignController@showRecent' );
$router->get( config( 'wign.urlPath.all' ), 'SignController@showAll' );
$router->get( config( 'wign.urlPath.request' ), 'RequestController@showList' );

$router->get( config( 'wign.urlPath.create' ) . '/{word?}', 'SignController@createSign' );
$router->get( config( 'wign.urlPath.createRequest' ) . '/{word?}', 'RequestController@store' );

$router->get( config( 'wign.urlPath.policy' ), 'IndexController@policy' );
//$router->get('brugersvilkår', 'IndexController@retningslinjer'); // Ændre den fordansket udtryk
$router->get( config( 'wign.urlPath.flagSign' ) . '/{id}', 'SignController@flagSignView' )->where( 'id', '[0-9]+' ); // Find some better url than "flagSignView"!

// Post routes
$router->post( 'saveSign', 'SignController@saveSign' );
$router->post( 'createVote', 'VoteController@createVote' );
$router->post( 'deleteVote', 'VoteController@deleteVote' );
$router->post( 'flagSign', 'SignController@flagSign' ); // this too...

$router->get( 'home', 'HomeController@index' ); // Login (Need?)
$router->controllers( [
	'auth'     => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
] );
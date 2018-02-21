<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// First, the blacklist if someone is on it
Route::get( config( 'wign.urlPath.blacklist' ), 'IndexController@blacklist' );

// REDIRECTING old url's to the new (Danish to English)
Route::redirect( 'tegn', config( 'wign.urlPath.sign' ), 301 );
Route::redirect( 'opret', config( 'wign.urlPath.create' ), 301 );
Route::redirect( 'requests', config( 'wign.urlPath.request' ), 301 );
Route::redirect( 'seneste', config( 'wign.urlPath.recent' ), 301 );
Route::redirect( 'alle', config( 'wign.urlPath.all' ), 301 ); // TODO: Redirect all traffic to "signs"
Route::redirect( 'om', config( 'wign.urlPath.about' ), 301 );
Route::redirect( 'help', config( 'wign.urlPath.help' ), 301 ); // Same...
Route::redirect( 'retningslinjer', config( 'wign.urlPath.policy' ), 301 );

// Index and static pages
Route::get( '/', 'IndexController@index' );
Route::get( config( 'wign.urlPath.about' ), 'IndexController@about' );
Route::get( config( 'wign.urlPath.help' ), 'IndexController@help' );
Route::get( config( 'wign.urlPath.policy' ), 'IndexController@policy' );

Route::get( config( 'wign.urlPath.recent' ), 'SignController@showRecent' );
Route::get( config( 'wign.urlPath.all' ), 'SignController@showAll' );
Route::get( config( 'wign.urlPath.request' ), 'RequestController@showList' );

// Search route
Route::post( 'redirect', 'SearchController@redirect' );
Route::get( 'autocomplete', 'SearchController@autocomplete' );

// Dynamic routes
Route::get( config( 'wign.urlPath.sign' ) . '/{word?}', 'SignController@showSign' );
Route::get( config( 'wign.urlPath.create' ) . '/{word?}', 'SignController@createSign' );
Route::get( config( 'wign.urlPath.createRequest' ) . '/{word?}', 'RequestController@store' );
Route::get( config( 'wign.urlPath.tags' ) . '/{tag?}', 'TagController@findTags' );


Route::get( config( 'wign.urlPath.flagSign' ) . '/{id}', 'SignController@flagSignView' )->where( 'id', '[0-9]+' ); // Find some better url than "flagSignView"!

// Post routes
Route::post( 'createVote', 'VoteController@createVote' );
Route::post( 'deleteVote', 'VoteController@deleteVote' );
Route::post( 'saveSign', 'SignController@saveSign' );
Route::post( 'flagSign', 'SignController@flagSign' ); // this too...

Route::get( 'home', 'HomeController@index' ); // Login (Need?)

/*
Route::get('/', function () {
	return view('welcome');
}); */
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

// REDIRECTING old url's to the new (Danish to English)
Route::get( 'tegn/{word}', 'RedirectController@sign' );
Route::get( 'opret/{word}', 'RedirectController@new' );
Route::redirect( 'tegn', 'index', 301 );
Route::redirect( 'opret', config( 'wign.urlPath.create' ), 301 );
Route::redirect( 'requests', config( 'wign.urlPath.request' ), 301 );
Route::redirect( 'seneste', config( 'wign.urlPath.recent' ), 301 );
Route::redirect( 'alle', config( 'wign.urlPath.all' ), 301 );
Route::redirect( 'om', config( 'wign.urlPath.about' ), 301 );
Route::redirect( 'retningslinjer', config( 'wign.urlPath.policy' ), 301 );

// INDEX
Route::get( '/',        ['uses' => 'IndexController@index', 'as' => 'index'] );
Route::get( 'about',    ['uses' => 'IndexController@about', 'as' => 'index.about'] );
Route::get( 'help',     ['uses' => 'IndexController@help', 'as' => 'index.help'] );
Route::get( 'policy',   ['uses' => 'IndexController@policy', 'as' => 'index.policy'] );

// POST
Route::get( 'new' . '/{word?}',  ['uses' => 'PostController@getPostIndex', 'as' => 'post.new'] );
Route::get( 'post/{word}', ['uses' => 'PostController@getPosts', 'as' => 'post.get'] );
Route::get( config( 'wign.urlPath.all' ), 'PostController@showAll' );
Route::get( 'recent',   ['uses' => 'PostController@showRecent', 'as' => 'post.recent']);
Route::post( 'postNewPost', ['uses' => 'PostController@postNewPost', 'as' => 'newPost'] );
//----
Route::get( config( 'wign.urlPath.flagSign' ) . '/{id}', 'SignController@flagSignView' )->where( 'id', '[0-9]+' );
Route::post( 'flagSign', 'SignController@flagSign' ); // this too...

// SEARCH
Route::post( 'redirect', 'SearchController@redirect' );
Route::get( 'autocomplete', 'SearchController@autocomplete' );

// REQUEST
Route::get( 'ask',  ['uses' => 'RequestController@showList', 'as' => 'request.index'] );
Route::get( config( 'wign.urlPath.createRequest' ) . '/{word}', 'RequestController@store' );

// TAG
Route::get( config( 'wign.urlPath.tags' ) . '/{tag}', 'TagController@findTags' );

// VOTE
Route::post( 'createVote', 'VoteController@createVote' );
Route::post( 'deleteVote', 'VoteController@deleteVote' );

// HOME
Route::get( 'home', 'HomeController@index' ); // Login (Need?)

// REDIRECTION
Route::redirect( config( 'wign.urlPath.sign' ), '/' );
Route::redirect( config( 'wign.urlPath.tags' ), '/' );
Route::redirect( config( 'wign.urlPath.createRequest' ), '/' );




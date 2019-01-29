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
Route::group(['prefix' => 'post'], function() {
    Route::post( 'create', ['uses' => 'PostController@postNewPost', 'as' => 'post.create'] );
    Route::get( '{word}', ['uses' => 'PostController@getPosts', 'as' => 'post.get'] );
});
Route::get( 'edit' . '/{id}', ['uses' => 'PostController@getEdit', 'as' => 'post.edit'])->middleware(['auth']);
Route::post( 'save' . '/{id}', ['uses' => 'PostController@postEdit', 'as' => 'post.edit.save'])->middleware('auth');
Route::get( 'new' . '/{word?}',  ['uses' => 'PostController@getPostIndex', 'as' => 'post.new'] );
Route::get( config( 'wign.urlPath.all' ), 'PostController@showAll' );
Route::get( 'recent',   ['uses' => 'PostController@showRecent', 'as' => 'post.recent']);


// LIKE
Route::group(['prefix' => 'like'], function ()  {
    Route::post( 'create', ['uses' => 'LikeController@create', 'as' => 'like.create'])->middleware('auth');
    Route::post( 'delete', ['uses' => 'LikeController@delete', 'as' => 'like.delete'])->middleware('auth');
});

// SEARCH
Route::post( 'redirect', ['uses' => 'SearchController@redirect', 'as' => 'redirect'] );
Route::get( 'autocomplete', ['uses' => 'SearchController@autocomplete', 'as' => 'autocomplete'] );

// REQUEST
Route::get( 'ask',  ['uses' => 'RequestController@showList', 'as' => 'request.index'] );
Route::get( config( 'wign.urlPath.createRequest' ) . '/{word}', 'RequestController@store' );

// TAG
Route::get( config( 'wign.urlPath.tags' ) . '/{tag}', 'TagController@findTags' );

// VOTING
Route::get( 'vote', ['uses' => 'VotingController@getVote', 'as' => 'vote.index'])->middleware('auth');
Route::group(['prefix' => 'review', 'middelware' => ['auth']], function() {
    Route::get( 'new', ['uses' => 'VotingController@postNewReview', 'as' => 'review.new'] );
    Route::post( 'update' . '/{id}', ['uses' => 'VotingController@postUpdateReview', 'as' => 'review.update'] );
});
Route::group(['prefix' => 'remotion', 'middelware' => ['auth']], function() {
    Route::get( 'promote' . '/{id}', ['uses' => 'VotingController@newPromotion', 'as' => 'promotion.new'] );
    Route::get( 'demote'. '/{id}', ['uses' => 'VotingController@newDemotion', 'as' => 'demotion.new'] );
    Route::post( 'update' . '/{id}', ['uses' => 'VotingController@postUpdateRemotion', 'as' => 'remotion.update'] );
});

// USER
Route::group(['prefix' => 'user', 'middelware' => ['auth']], function()  {
    Route::get( 'profile', ['uses' => 'UserController@getIndex', 'as' => 'user.index']);
    Route::get( 'guest' . '/{id}', ['uses' => 'UserController@getGuest', 'as' => 'user.guest']);
    Route::get( 'promote' . '/{id}', ['uses' => 'UserController@promoteUser', 'as' => 'user.promote']);
    Route::get( 'demote' . '/{id}', ['uses' => 'UserController@demoteUser', 'as' => 'user.demote']);
});

// HOME
Route::get( 'home', 'HomeController@index' ); // Login (Need?)

// REDIRECTION
Route::redirect( config( 'wign.urlPath.sign' ), '/' );
Route::redirect( config( 'wign.urlPath.tags' ), '/' );
Route::redirect( config( 'wign.urlPath.createRequest' ), '/' );

// ADMIN
Route::group(['prefix' => 'admin', 'auth' => ['admin']], function()  {
    Route::get( 'index', ['uses' => 'AdminController@getIndex', 'as' => 'admin.index']);
    Route::get('vote', ['uses' => 'AdminController@getVote', 'as' => 'admin.vote']);
    Route::post('review' . '/{id}', ['uses' => 'AdminController@decideReview', 'as' => 'admin.review']);
    Route::post('remotion' . '/{id}', ['uses' => 'AdminController@decideRemotion', 'as' => 'admin.remotion']);
    Route::get('ban' . '/{id}', ['uses' => 'AdminController@banUser', 'as' => 'admin.ban']);
});

// AUTH
Auth::routes();
Route::get('profile', function () {
    // Only authenticated users may enter...
})->middleware('auth');

Route::get('/home', 'HomeController@index')->name('home');

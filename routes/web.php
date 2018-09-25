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
Route::get( 'tegn/{word}', 'RedirectController@sign' );
Route::get( 'opret/{word}', 'RedirectController@new' );
Route::redirect( 'tegn', '/', 301 );
Route::redirect( 'opret', config( 'wign.urlPath.create' ), 301 );
Route::redirect( 'requests', config( 'wign.urlPath.request' ), 301 );
Route::redirect( 'seneste', config( 'wign.urlPath.recent' ), 301 );
Route::redirect( 'alle', config( 'wign.urlPath.all' ), 301 ); // TODO: Redirect all traffic to "signs" - in new design
Route::redirect( 'om', 'index.about', 301 );
Route::redirect( 'retningslinjer', 'index.policy', 301 );

// INDEX
Route::get( '/',        ['uses' => 'IndexController@index', 'as' => 'index'] );
Route::get( 'about',    ['uses' => 'IndexController@about', 'as' => 'index.about'] );
Route::get( 'help',     ['uses' => 'IndexController@help', 'as' => 'index.help'] );
Route::get( 'policy',   ['uses' => 'IndexController@policy', 'as' => 'index.policy'] );

// USER
Route::get( 'user',     ['uses' => 'UserController@index', 'as' => 'user.index']); //TODO Enter this user profile

// POST
Route::get( 'edit/{id}', ['uses' => 'PostController@getPostEdit', 'as' => 'post.edit'] );    //TODO Initial the edit on this post
Route::get('post', [ 'uses' => 'PostController@getPost', 'as' => 'post.index']);

// SIGN (OLD)
Route::get( 'recent', ['uses' => 'SignController@showRecent', 'as' => 'sign.recent'] );
Route::get( 'all', ['uses' => 'SignController@showAll', 'as' => 'sign.all'] );

Route::get( config( 'wign.urlPath.create' ) . '/{word?}', 'SignController@createSign' )->name( 'new' );
Route::get( config( 'wign.urlPath.sign' ) . '/{word}', 'SignController@showSign' )->name( 'sign' );

Route::get( config( 'wign.urlPath.flagSign' ) . '/{id}', 'SignController@flagSignView' )->where( 'id', '[0-9]+' ); // Find some better url than "flagSignView"!
Route::post( 'saveSign', 'SignController@saveSign' );
Route::post( 'flagSign', 'SignController@flagSign' ); // this too...

// REQUEST
Route::get( 'request', ['uses' => 'RequestController@showList', 'as' => 'requests'] );
Route::get( config( 'wign.urlPath.createRequest' ) . '/{word}', 'RequestController@store' );

// VOTE
Route::post( 'createVote', 'VoteController@createVote' );
Route::post( 'deleteVote', 'VoteController@deleteVote' );

// TAG
Route::get( config( 'wign.urlPath.tags' ) . '/{tag}', 'TagController@findTags' );

// SEARCH
Route::get( 'autocomplete', 'SearchController@autocomplete' );
Route::post( 'redirect', 'SearchController@redirect' );

// Dynamic routes with empty string (Redirecting)
Route::redirect( config( 'wign.urlPath.sign' ), '/' );
Route::redirect( config( 'wign.urlPath.tags' ), '/' );
Route::redirect( config( 'wign.urlPath.createRequest' ), '/' );

// AUTHENTICATION
Auth::routes(); // /register and /login
Route::post('login', ['uses' => 'SigninController@signin', 'as' => 'Auth.signin'] );

//////////////////////////////////////////////
///////////////////EOF////////////////////////
//////////////////////////////////////////////

Route::get( 'post/{post}/vote', function () {return view('post.vote');})->name('post.vote');    //TODO Allow if the voter holding the valid ballot
Route::get( 'user/{user}/vote', function () {return view('user.vote');})->name('user.vote');    //TODO Allow if the voter holding the valid ballot


//TODO Check if the route works
/*Route::post('create', function(\Illuminate\Http\Request $request, \Illuminate\Validation\Factory $validator)    {
    if ($validation->fails())  {
        return redirect()->back()->withErrors($validation);
    }
    return redirect()->route('index')->with('info', $request->input('word') . ' er oprettet, tak for dit bidrag.');
})->name('post.create');

Route::get( 'home', 'HomeController@index' ); // Login (Need?)

Route::group(['prefix' => 'admin'], function () {   //TODO Enter admin page if allowed
    Route::get('', [
            'uses' => 'AdminController@getIndex',
            'as' => 'admin.index'
        ]);
    Route::get( 'create', function ()   {return view('admin.create');})->name('admin.create');
});*/


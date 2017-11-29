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

$domain = env('APP_DOMAIN', 'wign.dk');
Route::group(['domain' => 'api.'.$domain], function () {
    Route::get('/', 'ApiController@index');
    Route::get('hasSign/{word?}', 'ApiController@hasSign');
    Route::get('video/{word?}', 'ApiController@getSign');
    Route::get('words/{query?}', 'ApiController@getWords');
});

$router->get('/blacklist', 'IndexController@blacklist');

$router->get('/', 'IndexController@index');
$router->post('redirect', 'SearchController@redirect');

$router->get('tegn/{word?}', 'SignController@showSign'); // @TODO change it to ../sign
$router->get('seneste', 'SignController@showRecent'); // @TODO change it to ../recent
$router->get('alle', 'SignController@showAll'); // @TODO change it to ../all
$router->get('requests', 'WordController@listRequests');

// @TODO TWO requests pages! Change it to one!
$router->get('request/{word}', 'WordController@requestWord');
$router->get('efterlys/{word?}', 'WordController@requestWord');

$router->post('saveSign', 'SignController@saveSign');

$router->post('createVote', 'VoteController@createVote');
$router->post('deleteVote', 'VoteController@deleteVote');

$router->get('flagSignView/{id}', 'SignController@flagSignView')->where('id', '[0-9]+'); // Find some better url than "flagSignView"!
$router->post('flagSign', 'SignController@flagSign'); // this too...

$router->get('retningslinjer', 'IndexController@policy'); // @TODO change it to ../policy
//$router->get('brugersvilkår', 'IndexController@retningslinjer'); // Ændre den fordansket udtryk

$router->get('opret/{word?}', 'WordController@createWord');


$router->get('om', 'IndexController@about'); // @TODO change it to ../about
$router->get('help', 'IndexController@help'); // @TODO change it to ../help

$router->get('home', 'HomeController@index'); // Login (Need?)

$router->get('all_words_json/{query?}', 'ApiController@getWords'); //@TODO: Remove it - we can move over to api.wign.dk for this.

$router->controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
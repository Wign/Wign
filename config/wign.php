<?php

/*
|--------------------------------------------------------------------------
| WIGN
|--------------------------------------------------------------------------
|
| All the important configurations for Wign to function.
|
*/
return [
	'version'   => '1.0',
	'email'     => 'wign@wign.dk',
	'cameratag' => [
		'id' => 'a-49088bd0-39cc-0132-ccc4-12313914f10b'
	],
	/*
	|--------------------------------------------------------------------------
	| URL paths
	|--------------------------------------------------------------------------
	|
	| Here we define the paths in URL to Wign. It can either be static values or
	| based on the localization of Wign.
	|
	*/
	'urlPath'   => [
		'sign'          => 'tegn',
		'create'        => 'opret',
		'createRequest' => 'request', // Redirecting page to create request
		'request'       => 'requests', // Page showing all requests
		'recent'        => 'seneste',
		'all'           => 'alle',
		'about'         => 'om',
		'policy'        => 'retningslinjer',
		'help'          => 'help',
		'blacklist'     => 'blacklist',
		'flagSign'      => 'flagSignView',
		'results'       => 'results', // The search result page
	]
];
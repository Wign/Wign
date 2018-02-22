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
	'version'   => 'v1.1',
	'email'     => 'wign@wign.dk',
	'cameratag' => [
		'id' => 'a-49088bd0-39cc-0132-ccc4-12313914f10b'
	],

	'tagRegexp' => '/#(?P<tags>[\p{L}\p{N}][\w]+)/ui',

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
		'sign'          => 'sign',
		'create'        => 'new',
		'createRequest' => 'request', // Redirecting page to create request
		'request'       => 'ask', // Page showing all requests
		'recent'        => 'signs',
		'all'           => 'all',
		'about'         => 'about',
		'policy'        => 'policy',
		'help'          => 'help',
		'blacklist'     => 'blacklist',
		'flagSign'      => 'flagSignView', // Best with a popup window and not a separate url
		'results'       => 'results', // The search result page TODO: BETTER WITH www.wign.dk/search?q=xxx&y=xxx !!
		'tags'          => 'hashtag',
	]
];
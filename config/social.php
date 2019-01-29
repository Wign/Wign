<?php

/*
|--------------------------------------------------------------------------
| WIGN :: Social media info
|--------------------------------------------------------------------------
|
| Here all our social media links and data is filled.
|
*/
return [
	'facebook' => [
		'url' => 'https://www.facebook.com/wign.dk/',
	],
	'github'   => [
		'url' => 'https://github.com/Wign/Wign',
	],
	'slack'    => [
		'webHook' => 'https://hooks.slack.com/services/'.env('SLACK_KEY'),
	]
];
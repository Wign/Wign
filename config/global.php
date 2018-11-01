<?php

return [

    /*
    |--------------------------------------------------------------------------
    | User Defined Variables
    |--------------------------------------------------------------------------
    |
    | This is a set of global variables that are made specific to this application
    | that are better placed here rather than in .env file.
    | Use config('your_key') to get the values.
    |
    */

    'rank_max' => env('RANK_MAX', 5 ),
    //'ballot_3_dist' => env('BALLOT_3_DIST', [.5, .3, .2]),
    //'ballot_2_dist' => env('BALLOT_2_DIST'. [.6, .4]),
    'min_users' => env('MIN_USERS', 10),
    'min_ballots' => env('MIN_BALLOTS', 5),
    'max_ballots' => env('MAX_BALLOTS', 200),

    'debug' => env('DEBUG', true),

];
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
    'min_users' => env('MIN_USERS', 10),
    'min_ballots' => env('MIN_BALLOTS', 5),
    'vote_duration' => env('DURATION', 30),
    'list_limit' => env('LIST_LIMIT', 50),  // For the use of pagination

    // Arrays of threshold, share of distributions and so on, can be found in VotingController.php

    'debug' => env('DEBUG', false),  // for the use of debug the views or identify the data on the view

];
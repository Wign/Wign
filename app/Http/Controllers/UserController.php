<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Models\User;
use Illuminate\Http\Request;


class UserController extends Controller //TODO: Fill the controller
{
    // our services
    protected $user_service;

    /**
     * UserController constructor.
     *
     * @param  UserService $user_service
     */
    public function __construct(UserService $user_service)
    {
        $this->user_service = $user_service;
    }

    /**
     * Display the "create an user" view with the relevant data attached.
     * Check if the email already is registered in the DB, as if forget the password,
     * or if the user is in blacklist, show the ban-page
     *
     * @param String $user .
     *
     * @return \Illuminate\View\View of "create me as user"
     */
    public function createUser( ) { //TODO: fill the data when creating this user
        /**
         * - check the blacklist and email
         * if negative do
         *  create the user with filled data and set QCV to 0
         */
       return null;
    }
}

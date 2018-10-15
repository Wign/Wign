<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //TODO: getIndex
    //TODO: set QCV
    //TODO: set IL
    //TODO add blacklist
    //TODO remove blacklist
}

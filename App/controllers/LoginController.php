<?php

namespace App\controllers;

// Start a Session
if( !session_id() ) @session_start();

use App\Redirect;
use App\Template;
use App\User;

class LoginController
{
    public function template()
    {
        Template::template('page_login');
    }

    public function login()
    {
        $user = new User();
        $user->login();
        Redirect::to('');
    }
}
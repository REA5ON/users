<?php

namespace App\controllers;

// Start a Session
if (!session_id()) @session_start();

use App\Redirect;
use App\User;
use Delight\Auth\Auth;
use League\Plates\Engine;
use PDO;

class LoginController
{
    protected $user;
    protected $engine;

    public function __construct(User $user, Engine $engine)
    {
        $this->user = $user;
        $this->engine = $engine;
    }

    public function index()
    {
        echo $this->engine->render('page_login');
    }

    public function login()
    {
        $this->user->login();
        Redirect::to('');
    }

    public static function logOut()
    {
        $pdo = new PDO('mysql:host=localhost;dbname=app3', 'root', 'root');
        $auth = new Auth($pdo);
        $auth->logOut();
        Redirect::to('login');
    }
}
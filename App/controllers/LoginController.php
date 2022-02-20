<?php

namespace App\controllers;

// Start a Session
if (!session_id()) @session_start();

use App\Pagination;
use App\Redirect;
use App\User;
use App\Validation;
use Delight\Auth\Auth;
use League\Plates\Engine;
use PDO;

class LoginController
{
    protected $user;
    protected $engine;
    protected $valid;

    public function __construct(User $user, Engine $engine, Validation $validation)
    {
        $this->user = $user;
        $this->engine = $engine;
        $this->valid = $validation;
    }

    public function index()
    {
        echo $this->engine->render('page_login');
    }

    public function login()
    {
        //validation POST
        $this->valid->validation(
            [
                'required' => [['email', 'password', 'username']],
                'email' => [['email']],
                'lengthMin' => [['password', 6]]
            ]
        );
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
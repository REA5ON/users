<?php

namespace App;

use Delight\Auth\Auth;
use Delight\Auth\Role;
use PDO;

class User
{
    protected $pdo;
    protected $auth;


    public function __construct()
    {
        $this->pdo = new PDO('mysql:host=localhost;dbname=app3', 'root', 'root');
        $this->auth = new Auth($this->pdo);
    }


    public function registration()
    {
        try {
            $userId = $this->auth->register($_POST['email'], $_POST['password'], $_POST['username'], function ($selector, $token) {
//                echo 'Send ' . $selector . ' and ' . $token . ' to the user (e.g. via email)';
//                echo '  For emails, consider using the mail(...) function, Symfony Mailer, Swiftmailer, PHPMailer, etc.';
//                echo '  For SMS, consider using a third-party service and a compatible SDK';
                flash()->success("Registration complete");
            });

            return $userId;
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            flash()->error('Invalid email address');
            Redirect::to('registration');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error('Invalid password');
            Redirect::to('registration');
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            flash()->error('User already exists');
            Redirect::to('registration');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error('Too many requests');
            Redirect::to('registration');
        }
    }


    public function login(){
        try {
            $this->auth->login($_POST['email'], $_POST['password']);
            flash()->success('User is logged in');
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            flash()->error('Wrong email address');
            Redirect::to('login');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error('Wrong password');
            Redirect::to('login');
        }
        catch (\Delight\Auth\EmailNotVerifiedException $e) {
            flash()->error('Email not verified');
            Redirect::to('login');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error('Too many requests');
            Redirect::to('login');
        }
    }

    public function createUser() {
        try {
            $userId = $this->auth->admin()->createUser($_POST['email'], $_POST['password'], $_POST['username']);
            flash()->success('New user has been added!');
            return $userId;
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            flash()->error('Invalid email address');
            Redirect::to('create');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error('Invalid password');
            Redirect::to('create');
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            flash()->error('User already exists');
            Redirect::to('create');
        }
    }

    public static function isAdmin()
    {
        $pdo = new PDO('mysql:host=localhost;dbname=app3', 'root', 'root');
        $auth = new Auth($pdo);
        if ($auth->hasRole(\Delight\Auth\Role::ADMIN)) {
            return true;
        }
    }
}
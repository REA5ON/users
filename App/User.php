<?php

namespace App;

use Delight\Auth\Auth;
use Delight\Auth\Role;
use PDO;
use SimpleMail;

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
                $url = 'level3/verify_email/' . \urlencode($selector) . '&' . \urlencode($token);
                $send = SimpleMail::make()
                    ->setTo('test@gmail.com', 'Test')
                    ->setMessage($url)
                    ->send();
                flash()->success("Registration complete!");
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

    public function isAdminOrAuthor($vars)
    {
        $id = intval($vars['id']);
        if (!User::isAdmin() && $this->auth->getUserId() !== $id) {
            flash()->error('You can modify just your profile!');
            Redirect::to('');
        }
    }

    /** Changing the current user’s password */
    public function changePassword()
    {
        try {
            $this->auth->changePassword($_POST['password'], $_POST['newPassword']);

            echo 'Password has been changed';
        }
        catch (\Delight\Auth\NotLoggedInException $e) {
            die('Not logged in');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            die('Invalid password(s)');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            die('Too many requests');
        }
    }

    /** Changing the current user’s email address */
    public function changeEmail()
    {
        try {
            if ($this->auth->reconfirmPassword($_POST['password'])) {
                $this->auth->changeEmail($_POST['email'], function ($selector, $token) {
                    $url = 'level3/change_email/' . \urlencode($selector) . '&' . \urlencode($token);
                    $send = SimpleMail::make()
                        ->setTo('test@gmail.com', 'Test')
                        ->setMessage($url)
                        ->send();

                    flash()->success('Mail was be send to new email address');
                    return true;
                });

                flash()->success('The change will take effect as soon as the new email address has been confirmed');
                Redirect::to('');
            }
            else {
                flash()->error('We can\'t say if the user is who they claim to be');
            }
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            flash()->error('Invalid email address');
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            flash()->error('Email address already exists');
        }
        catch (\Delight\Auth\EmailNotVerifiedException $e) {
            flash()->error('Account not verified');
        }
        catch (\Delight\Auth\NotLoggedInException $e) {
            flash()->error('Not logged in');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error('Too many requests');
        }
    }

    /** Changing the current user’s password as Admin */
    public function changePasswordAsAdmin($id, $newPassword)
    {
        try {
            $this->auth->admin()->changePasswordForUserById($id, $newPassword);
        }
        catch (\Delight\Auth\UnknownIdException $e) {
            flash()->error('Unknown ID');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error('Invalid password');
        }
    }

    public function emailVerification($selector, $token)
    {
        try {
            $this->auth->confirmEmail($selector, $token);

            flash()->success('Email address has been verified');
        }
        catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
            flash()->error('Invalid token');
        }
        catch (\Delight\Auth\TokenExpiredException $e) {
            flash()->error('Token expired');
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            flash()->error('Email address already exists');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error('Too many requests');
        }
    }
}
<?php

namespace App;

use Delight\Auth\Auth;
use Delight\Auth\AuthError;
use Delight\Auth\Role;
use PDO;
use SimpleMail;

class User
{
    protected $pdo;
    protected $auth;


    public function __construct(PDO $pdo, Auth $auth)
    {
        $this->pdo = $pdo;
        $this->auth = $auth;
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
                flash()->success("Please check your email! We have sent an " . $_POST['email'] . " instructions to you.");
            });

            return $userId;
        } catch (\Delight\Auth\InvalidEmailException $e) {
            flash()->error('Invalid email address');
            Redirect::stay();
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error('Invalid password');
            Redirect::stay();
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            flash()->error('User already exists');
            Redirect::stay();
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error('Too many requests');
            Redirect::stay();
        }
    }


    public function login()
    {
        try {
            if ($_POST['rememberme'] == 'on') {
                // keep logged in for one year
                $rememberDuration = (int) (60 * 60 * 24 * 365.25);
            }
            else {
                // do not keep logged in after session ends
                $rememberDuration = null;
            }
            $this->auth->login($_POST['email'], $_POST['password'], $rememberDuration);
            flash()->success('User is logged in');
        } catch (\Delight\Auth\InvalidEmailException $e) {
            flash()->error('Wrong email address');
            Redirect::stay();
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error('Wrong password');
            Redirect::stay();
        } catch (\Delight\Auth\EmailNotVerifiedException $e) {
            flash()->error('Email not verified');
            Redirect::stay();
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error('Too many requests');
            Redirect::stay();
        }
    }

    public function createUser()
    {
        try {
            $userId = $this->auth->admin()->createUser($_POST['email'], $_POST['password'], $_POST['username']);
            flash()->success('New user has been added!');
            return $userId;
        } catch (\Delight\Auth\InvalidEmailException $e) {
            flash()->error('Invalid email address');
            Redirect::stay();
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error('Invalid password');
            Redirect::stay();
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            flash()->error('User already exists');
            Redirect::stay();
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

    public static function getUserId()
    {
        $pdo = new PDO('mysql:host=localhost;dbname=app3', 'root', 'root');
        $auth = new Auth($pdo);
        $id = $auth->getUserId();
        return strval($id);
    }

    /** Changing the current user’s password */
    public function changePassword()
    {
        try {
            $this->auth->changePassword($_POST['oldPassword'], $_POST['newPassword']);

            flash()->success('Password has been changed!');
        }
        catch (\Delight\Auth\NotLoggedInException $e) {
            flash()->error('Not logged in');
            Redirect::stay();
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error('Invalid password(s)');
            Redirect::stay();
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error('Too many requests');
            Redirect::stay();
        } catch (AuthError $e) {
            d($e);
        }
    }

    /** Changing the current user’s email address */
    public function changeEmail()
    {
        try {
            if ($this->auth->reconfirmPassword($_POST['password']) && $this->auth->admin()) {
                $this->auth->changeEmail($_POST['newEmail'], function ($selector, $token) {
                    $url = 'level3/change_email/' . \urlencode($selector) . '&' . \urlencode($token);
                    $send = SimpleMail::make()
                        ->setTo('test@gmail.com', 'Test')
                        ->setMessage($url)
                        ->send();

                    flash()->success('Mail was be send to new email address');
                    return true;
                });

                flash()->success('The change will take effect as soon as the new email address has been confirmed');
            } else {
                flash()->error('We can\'t say if the user is who they claim to be');
            }
        } catch (\Delight\Auth\InvalidEmailException $e) {
            flash()->error('Invalid email address');
            Redirect::stay();
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            flash()->error('Email address already exists');
            Redirect::stay();
        } catch (\Delight\Auth\EmailNotVerifiedException $e) {
            flash()->error('Account not verified');
            Redirect::stay();
        } catch (\Delight\Auth\NotLoggedInException $e) {
            flash()->error('Not logged in');
            Redirect::stay();
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error('Too many requests');
            Redirect::stay();
        }
    }

    /** Changing the current user’s password as Admin */
    public function changePasswordAsAdmin($id, $newPassword)
    {
        try {
            $this->auth->admin()->changePasswordForUserById($id, $newPassword);
        } catch (\Delight\Auth\UnknownIdException $e) {
            flash()->error('Unknown ID');
            Redirect::stay();
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error('Invalid password');
            Redirect::stay();
        }
    }

    public function emailVerification($selector, $token)
    {
        try {
            $this->auth->confirmEmail($selector, $token);
            flash()->success('Email address has been verified');
            return true;

        } catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
            flash()->error('Invalid token');
            Redirect::to('login');
        } catch (\Delight\Auth\TokenExpiredException $e) {
            flash()->error('Token expired');
            Redirect::to('login');
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            flash()->error('Email address already exists');
            Redirect::to('login');
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error('Too many requests');
            Redirect::to('login');
        } catch (AuthError $e) {
            flash()->error('XD');
        }
    }

    /** check user in session */
    public function isLoggedIn()
    {
        if (!$this->auth->check()) {
            Redirect::to('login');
        }
    }

    /** if user has role ADMIN */
    public function admin()
    {
        if ($this->auth->hasRole(1)) {
            return true;
        } else {
            flash()->error('Not enough rights to action!');
            Redirect::to('');
        }
    }

    public function isAuthor($id)
    {
        $sessionId = $this->auth->getUserId();
        if ($sessionId !== $id)
        {
            flash()->error('You can modify just your profile!');
            Redirect::to('');
        }
    }

    /** Check roles */
    public function isAuthorOrAdmin($id)
    {
        $userId = intval($id['id']);

        if (!$this->auth->hasRole(1) && ($this->auth->getUserId() !== $userId))
            {
                flash()->error('Not enough rights to action!');
                Redirect::to('');
            }
    }
}
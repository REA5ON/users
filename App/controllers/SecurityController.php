<?php

namespace App\controllers;

use App\QueryBuilder;
use App\Redirect;
use App\Template;
use App\User;
use Delight\Auth\Auth;
use PDO;

class SecurityController
{
    protected $user, $qb, $auth;

    public function __construct()
    {
        $this->user = new User();
        $this->qb = new QueryBuilder();
        $pdo = new PDO('mysql:host=localhost;dbname=app3', 'root', 'root');
        $this->auth = new Auth($pdo);
    }

    public function template($vars)
    {
        $isAdminOrAuthor = $this->user;
        $isAdminOrAuthor->isAdminOrAuthor($vars);

        $user = $this->qb;
        $user = $user->getOne('user_data', $vars['id']);
        Template::template('security',
            [
                'user' => $user
            ]);
    }


    public function editCredential($vars)
    {
        $id = intval($vars['id']);

        //if changed email
        if ($_POST['email'] !== $this->auth->getEmail()) {
            $this->user->changeEmail();
        }

        //change password
        if ($this->auth->admin()) {
            $this->user->changePasswordAsAdmin($id, $_POST['newPassword']);
        } else {
            $this->user->changePassword();
        }
        Redirect::to('');
    }

    public function emailVerification($vars)
    {
        $this->user->emailVerification($vars['selector'], $vars['token']);
        Redirect::to('');
    }

    public function changeEmail($vars)
    {
        $this->user->emailVerification($vars['selector'], $vars['token']);
        $email = $this->auth->getEmail();
        $id = $this->auth->getUserId();
        $this->qb->update('user_data', ['email' => $email], $id);
        Redirect::to('');
    }

}
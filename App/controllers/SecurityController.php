<?php

namespace App\controllers;

use App\QueryBuilder;
use App\Redirect;
use App\User;
use Delight\Auth\Auth;
use League\Plates\Engine;

class SecurityController
{
    protected $user;
    protected $engine;
    protected $qb;
    protected $auth;

    public function __construct(User $user, QueryBuilder $qb, Engine $engine, Auth $auth)
    {
        $this->user = $user;
        $this->qb = $qb;
        $this->engine = $engine;
        $this->auth = $auth;

        $this->user->isNotLoggedIn();
    }

    public function index($vars)
    {
//        $this->user->isAdminOrAuthor($vars);

        $user = $this->qb->getOne('user_data', $vars['id']);
        echo $this->engine->render('security', ['user' => $user]);
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
        flash()->success('Password was be changed!');
        Redirect::to('');
    }

    public function emailVerification($vars)
    {
        $this->user->emailVerification($vars['selector'], $vars['token']);
    }

    /** Верифицирует имейл и обновляет его в таблице user_data */
    public function changeEmail($vars)
    {
        $this->user->emailVerification($vars['selector'], $vars['token']);
        $email = $this->auth->getEmail();
        $id = $this->auth->getUserId();
        $this->qb->update('user_data', ['email' => $email], $id);
    }
}
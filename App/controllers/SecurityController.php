<?php

namespace App\controllers;

use App\QueryBuilder;
use App\Redirect;
use App\User;
use App\Validation;
use Delight\Auth\Auth;
use League\Plates\Engine;

class SecurityController
{
    protected $user;
    protected $engine;
    protected $qb;
    protected $auth;
    protected $valid;

    public function __construct(User $user, QueryBuilder $qb, Engine $engine, Auth $auth, Validation $validation)
    {
        $this->user = $user;
        $this->qb = $qb;
        $this->engine = $engine;
        $this->auth = $auth;
        $this->valid = $validation;

        $this->user->isLoggedIn();
    }


    public function index($vars)
    {
        $this->user->isAuthorOrAdmin($vars);

        $user = $this->qb->getOne('user_data', $vars['id']);
        echo $this->engine->render('security', ['user' => $user]);
    }


    /** Edit user credential */
    public function editCredential($vars)
    {
        $this->user->isAuthorOrAdmin($vars);

        $this->valid->validation(
            [
                'required' => [['newEmail', 'oldPassword', 'newPassword', 'newPasswordAgain']],
                'lengthMin' => [['newPassword', 6]],
                'equals' => [['newPassword', 'newPasswordAgain']]
            ]
        );

        $id = intval($vars['id']);
        //return array
        $email = $this->qb->getOne('users', $id);
        //if changed email
        if ($_POST['newEmail'] !== $email['email']) {
            $this->user->changeEmail();
        }

        //change password
        if ($this->auth->hasRole(1)) {
            $this->user->changePasswordAsAdmin($id, $_POST['newPassword']);
        } else {
            $this->user->changePassword();
        }

        Redirect::to('');
    }

    public function emailVerification($vars)
    {
        $this->user->emailVerification($vars['selector'], $vars['token']);
        Redirect::to('login');
    }

    /** Verifies the email and updates it in the table user_data */
    public function changeEmail($vars)
    {
        $this->user->emailVerification($vars['selector'], $vars['token']);
        $email = $this->auth->getEmail();
        $id = $this->auth->getUserId();
        $this->qb->update('user_data', ['email' => $email], $id);
    }
}
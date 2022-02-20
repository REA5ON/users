<?php

namespace App\controllers;

use App\QueryBuilder;
use App\Redirect;
use App\User;
use League\Plates\Engine;

class StatusController
{
    protected $user;
    protected $qb;
    protected $engine;

    public function __construct(User $user, QueryBuilder $qb, Engine $engine)
    {
        $this->user = $user;
        $this->qb = $qb;
        $this->engine = $engine;

        $this->user->isLoggedIn();
    }

    public function index($vars)
    {
        //check roles
        $this->user->isAuthorOrAdmin($vars);

        //return array
        $user = $this->qb->getOne('user_data', $vars['id']);
        echo $this->engine->render('status', ['user' => $user]);
    }

    public function setStatus($vars)
    {
        $this->user->isAuthorOrAdmin($vars);

        $this->qb->update('user_data',
            ['status' => $_POST['status']],
            $vars['id']);

        flash()->success('Status was be changed!');
        Redirect::to('');
    }
}
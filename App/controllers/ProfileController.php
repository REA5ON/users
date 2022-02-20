<?php

namespace App\controllers;

use App\QueryBuilder;
use App\User;
use League\Plates\Engine;

class ProfileController
{
    protected $qb;
    protected $engine;
    protected $user;

    public function __construct(User $user, QueryBuilder $qb, Engine $engine)
    {
        $this->qb = $qb;
        $this->engine = $engine;
        $this->user = $user;

        $this->user->isLoggedIn();
    }

    public function index($vars)
    {
        //return array
        $user = $this->qb->getOne('user_data', $vars['id']);
        echo $this->engine->render('page_profile', ['user' => $user]);
    }
}
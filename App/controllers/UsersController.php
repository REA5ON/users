<?php

namespace App\controllers;
// Start a Session
if( !session_id() ) @session_start();

use App\QueryBuilder;
use App\User;
use League\Plates\Engine;


class UsersController
{
    protected $qb;
    protected $engine;
    protected $user;

    public function __construct(QueryBuilder $qb, Engine $engine, User $user)
    {
        $this->qb = $qb;
        $this->engine = $engine;
        $this->user = $user;

        $this->user->isLoggedIn();
    }


    public function index()
    {
        $users = $this->qb->getAll('user_data');
        echo $this->engine->render('users', ['users' => $users]);
    }
}
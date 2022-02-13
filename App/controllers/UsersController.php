<?php

namespace App\controllers;
// Start a Session
if( !session_id() ) @session_start();

use App\QueryBuilder;
use League\Plates\Engine;
use function Symfony\Component\Translation\t;


class UsersController
{
    protected $qb;
    protected $pdo;
    protected $auth;
    protected $engine;

    public function __construct(QueryBuilder $qb, Engine $engine)
    {
        $this->qb = $qb;
        $this->engine = $engine;
    }


    public function index()
    {
        $users = $this->qb->getAll('user_data');
        echo $this->engine->render('users', ['users' => $users]);
    }
}
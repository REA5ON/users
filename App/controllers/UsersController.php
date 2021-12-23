<?php

namespace App\controllers;
// Start a Session
if( !session_id() ) @session_start();

use App\QueryBuilder;
use App\Template;


class UsersController
{
    protected $qb;

    public function __construct()
    {
        $this->qb = new QueryBuilder();
    }


    public function getAllUsers()
    {
        $users = $this->qb->getAll('user_data');
        Template::template('users', ['users' => $users]);
    }
}
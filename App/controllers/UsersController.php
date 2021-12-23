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



    public function createUser()
    {
        $createUser = $this->qb;
        //Создать в таблице 'users'
        $id = $createUser->insert('users',
            [
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'password' => $_POST['password']
            ]);

        //Возвращаем id и вставляем данные в таблицу 'users_data'
        $createUser->insert('user_data',
            [
                'user_id' => $id,
                'username' => $_POST['username'],
                'email' => $_POST['email']
            ]);
    }
}
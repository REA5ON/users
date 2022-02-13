<?php

namespace App\controllers;

// Start a Session
if (!session_id()) @session_start();

use App\QueryBuilder;
use App\Redirect;
use App\Template;
use App\User;
use League\Plates\Engine;

class RegisterController
{
    protected $user;
    protected $engine;
    protected $qb;

    public function __construct(User $user, QueryBuilder $qb, Engine $engine)
    {
        $this->user = $user;
        $this->qb = $qb;
        $this->engine = $engine;
    }

    public function index()
    {
        echo $this->engine->render('page_register');
    }


    public function registration()
    {
        //если проходит регистрация - получаем ID
        $id = $this->user->registration();

        //Вставляем данные в таблицу user_data
        $this->qb->insert('user_data', [
            'id' => $id,
            'username' => $_POST['username'],
            'email' => $_POST['email'],
            'status' => 'success'
        ]);
        Redirect::to('login');
    }
}
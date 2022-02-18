<?php

namespace App\controllers;

// Start a Session
if (!session_id()) @session_start();

use App\QueryBuilder;
use App\Redirect;
use App\User;
use App\Validation;
use League\Plates\Engine;

class RegisterController
{
    protected $user;
    protected $engine;
    protected $qb;
    protected $valid;

    public function __construct(User $user, QueryBuilder $qb, Engine $engine, Validation $validation)
    {
        $this->user = $user;
        $this->qb = $qb;
        $this->engine = $engine;
        $this->valid = $validation;

    }

    public function index()
    {
        echo $this->engine->render('page_register');
    }


    public function registration()
    {
        //validation POST
        $this->valid->validation(
            [
                'required' => [['email', 'password', 'username']],
                'email' => [['email']],
                'length' => [['password', 6]]
            ]
        );

        //if registration was completed - return ID
        $id = $this->user->registration();

        //Insert data to table "user_data"
        $this->qb->insert('user_data', [
            'id' => $id,
            'username' => $_POST['username'],
            'email' => $_POST['email'],
            'status' => 'success'
        ]);
        Redirect::to('login');
    }
}
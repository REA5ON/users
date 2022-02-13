<?php

namespace App\controllers;

use App\QueryBuilder;
use App\Redirect;
use App\User;
use League\Plates\Engine;

class EditController
{
    protected $user;
    protected $qb;
    protected $engine;

    public function __construct(User $user, QueryBuilder $qb, Engine $engine)
    {
        $this->user = $user;
        $this->qb = $qb;
        $this->engine = $engine;
    }

    public function index($vars)
    {
        $this->user->isAdminOrAuthor($vars);

        $user = $this->qb->getOne('user_data', $vars['id']);
        echo $this->engine->render('edit', ['user' => $user]);
    }

    public function editUser($vars)
    {
        $id = $vars['id'];
        $this->qb->update('users', ['username' => $_POST['username']], $id);
        $this->qb->update('user_data',
            [
                'username' => $_POST['username'],
                'place_of_work' => $_POST['place_of_work'],
                'phone' => $_POST['phone'],
                'address' => $_POST['address']
            ], $id);
        Redirect::to('');
    }
}
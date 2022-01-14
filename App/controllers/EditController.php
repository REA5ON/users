<?php

namespace App\controllers;

use App\QueryBuilder;
use App\Redirect;
use App\Template;
use App\User;
use Delight\Auth\Auth;
use PDO;

class EditController
{

    public function template($vars)
    {
        $isAdminOrAuthor = new User();
        $isAdminOrAuthor->isAdminOrAuthor($vars);

        $user = new QueryBuilder();
        $user = $user->getOne('user_data', $vars['id']);
        Template::template('edit', ['user' => $user]);
    }

    public function editUser($vars)
    {
        $id = $vars['id'];
        $qb = new QueryBuilder();
        $qb->update('users', ['username' => $_POST['username']], $id);
        $qb->update('user_data',
            [
                'username' => $_POST['username'],
                'place_of_work' => $_POST['place_of_work'],
                'phone' => $_POST['phone'],
                'address' => $_POST['address']
            ], $id);
        Redirect::to('');
    }
}
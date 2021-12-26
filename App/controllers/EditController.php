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
        $pdo = new PDO('mysql:host=localhost;dbname=app3', 'root', 'root');
        $auth = new Auth($pdo);
        $id = intval($vars['id']);
        if (!User::isAdmin() && $auth->getUserId() !== $id) {
            flash()->error('You can modify just your profile!');
            Redirect::to('');
        }


        $user = new QueryBuilder();
        $user = $user->getOne('user_data', $id);
        Template::template('edit',
            [
                'user' => $user
            ]);
    }

    public function editUser()
    {

    }
}
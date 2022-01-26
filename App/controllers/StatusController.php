<?php

namespace App\controllers;

use App\QueryBuilder;
use App\Redirect;
use App\Template;
use App\User;

class StatusController
{
    public function template($vars){
        $isAdminOrAuthor = new User();
        $isAdminOrAuthor->isAdminOrAuthor($vars);

        $user = new QueryBuilder();
        $user = $user->getOne('user_data', $vars['id']);
        Template::template('status',
            [
                'user' => $user
            ]);
    }

    public function setStatus($vars)
    {
        $isAdminOrAuthor = new User();
        $isAdminOrAuthor->isAdminOrAuthor($vars);

        $db = new QueryBuilder();
        $db->update('user_data',
            ['status' => $_POST['status']],
            $vars['id']);

        flash()->success('Статус обновлен!');
        Redirect::to('');
    }
}
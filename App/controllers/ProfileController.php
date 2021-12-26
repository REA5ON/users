<?php

namespace App\controllers;

use App\QueryBuilder;
use App\Template;
use App\User;

class ProfileController
{
    public function template($vars)
    {
        $user = new QueryBuilder();
        $user = $user->getOne('user_data', $vars['id']);
        Template::template('page_profile',
        [
            'user' => $user
        ]);
    }
}
<?php

namespace App\controllers;

use App\Template;
use App\User;

class CreateController
{
    public function template() {
        Template::template('create_user');
    }

    public function createUser() {
        $user = new User();
        $id = $user->createUser();
    }
}
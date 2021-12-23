<?php

namespace App\controllers;

use App\Template;

class CreateController
{
    public function template() {
        Template::template('create_user');
    }
}
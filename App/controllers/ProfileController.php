<?php

namespace App\controllers;

use App\QueryBuilder;
use App\Template;
use App\User;
use League\Plates\Engine;

class ProfileController
{
    protected $qb;
    protected $engine;

    public function __construct(QueryBuilder $qb, Engine $engine)
    {
        $this->qb = $qb;
        $this->engine = $engine;
    }

    public function index($vars)
    {
        $user = $this->qb->getOne('user_data', $vars['id']);
        echo $this->engine->render('page_profile', ['user' => $user]);
    }
}
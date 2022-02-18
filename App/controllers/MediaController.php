<?php

namespace App\controllers;

use App\Image;
use App\QueryBuilder;
use App\Redirect;
use App\User;
use League\Plates\Engine;

class MediaController
{
    protected $user;
    protected $qb;
    protected $image;
    protected $engine;

    public function __construct(User $user, QueryBuilder $qb, Image $image, Engine $engine)
    {
        $this->user = $user;
        $this->qb = $qb;
        $this->image = $image;
        $this->engine = $engine;

        $this->user->isLoggedIn();
    }

    public function index($vars){
        $this->user->isAuthorOrAdmin($vars['id']);

        $user = $this->qb->getOne('user_data', $vars['id']);
        echo $this->engine->render('media', ['user' => $user]);
    }

    public function updateImage($vars)
    {
        $this->user->isAuthorOrAdmin($vars['id']);
        $this->image->updateImage($vars['id'], $_FILES['image']['tmp_name'], '/App/views/img/users_images/');
        flash()->success('Avatar has been update!');
        Redirect::to('');
    }
}
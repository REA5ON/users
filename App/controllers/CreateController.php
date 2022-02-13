<?php

namespace App\controllers;

use App\Image;
use App\QueryBuilder;
use App\Redirect;
use App\User;
use League\Plates\Engine;

class CreateController
{
    protected $engine;
    protected $qb;
    protected $image;
    protected $user;

    public function __construct(User $user, Image $image, QueryBuilder $qb,Engine $engine)
    {
        if (!User::isAdmin()) {
            Redirect::to('');
        }

        $this->user = $user;
        $this->image = $image;
        $this->qb = $qb;
        $this->engine = $engine;

        $this->user->isNotLoggedIn();
    }


    public function index()
    {
        echo $this->engine->render('create_user');
    }


    public function createUser()
    {
        $id = $this->user->createUser();

        $image = $this->image->saveImage($_FILES['image']['tmp_name'], '/App/views/img/users_images/');
        $this->qb->insert('user_data',
            [
                'id' => $id,
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'place_of_work' => $_POST['place_of_work'],
                'address' => $_POST['address'],
                'status' => $_POST['status'],
                'vk' => $_POST['vk'],
                'telegram' => $_POST['telegram'],
                'instagram' => $_POST['instagram'],
                'image' => $image
            ]);
        Redirect::to('');
    }
}
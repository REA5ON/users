<?php

namespace App\controllers;

use App\Image;
use App\QueryBuilder;
use App\Redirect;
use App\User;
use App\Validation;
use League\Plates\Engine;

class CreateController
{
    protected $engine;
    protected $qb;
    protected $image;
    protected $user;
    protected $valid;

    public function __construct(User $user, Image $image, QueryBuilder $qb,Engine $engine, Validation $validation)
    {
        $this->user = $user;
        $this->image = $image;
        $this->qb = $qb;
        $this->engine = $engine;
        $this->valid = $validation;

        $this->user->admin();
        $this->user->isLoggedIn();
    }


    public function index()
    {
        echo $this->engine->render('create_user');
    }


    public function createUser()
    {
        //validation POST
        $this->valid->validation(
            [
                'required' => [['email', 'password', 'username']],
                'email' => [['email']],
                'length' => [['password', 6]]
            ]
        );

        //email, password, username
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
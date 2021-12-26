<?php

namespace App\controllers;

use App\Image;
use App\QueryBuilder;
use App\Redirect;
use App\Template;
use App\User;

class CreateController
{
    public function __construct()
    {
        if (!User::isAdmin()) {
            Redirect::to('');
        }
    }
    public function template()
    {
        Template::template('create_user');
    }

    public function createUser()
    {
        $user = new User();
        $id = $user->createUser();

        $image = new Image();
        $image = $image->saveImage($_FILES['image']['tmp_name'], '/App/views/img/users_images/');
        $db = new QueryBuilder();
        $db->insert('user_data',
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
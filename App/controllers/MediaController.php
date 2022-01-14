<?php

namespace App\controllers;

use App\Image;
use App\QueryBuilder;
use App\Redirect;
use App\Template;
use App\User;

class MediaController
{
    public function template($vars){
        $isAdminOrAuthor = new User();
        $isAdminOrAuthor->isAdminOrAuthor($vars);

        $user = new QueryBuilder();
        $user = $user->getOne('user_data', $vars['id']);
        Template::template('media',
            [
                'user' => $user
            ]);
    }

    /**
     * @throws \Tamtamchik\SimpleFlash\Exceptions\FlashTemplateNotFoundException
     */
    public function updateImage($vars)
    {
        $image = new Image();
        $image->updateImage($vars['id'], $_FILES['image']['tmp_name'], '/App/views/img/users_images/');
        flash()->error('Avatar has been update!');
        Redirect::to('');
    }
}
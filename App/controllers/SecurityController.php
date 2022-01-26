<?php

namespace App\controllers;

use App\QueryBuilder;
use App\Template;
use App\User;
use Delight\Auth\Auth;
use PDO;

class SecurityController
{
    public function template($vars){
        $isAdminOrAuthor = new User();
        $isAdminOrAuthor->isAdminOrAuthor($vars);

        $user = new QueryBuilder();
        $user = $user->getOne('user_data', $vars['id']);
        Template::template('security',
            [
                'user' => $user
            ]);
    }


    public function editCredential($vars)
    {
        $pdo = new PDO('mysql:host=localhost;dbname=app3', 'root', 'root');
        $auth = new Auth($pdo);
        $user = new User();
        $user->isAdminOrAuthor($vars['id']);

        //Если email совпадает с тем что в системе - меняем только пароль
        if ($_POST['email'] === $auth->getEmail()) {
            //если админ
            if ($auth->admin()) {
                $user->changePasswordAsAdmin($vars['id'], $_POST['newPassword']);
            }

            $user->changePassword();
            exit;
        }

        //меняем email
        $user->changeEmail();


    }

    public function verify($vars)
    {
        var_dump($vars);die;
    }
}
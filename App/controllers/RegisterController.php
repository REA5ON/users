<?php

namespace App\controllers;

// Start a Session
if( !session_id() ) @session_start();

use App\QueryBuilder;
use App\Redirect;
use App\Template;
use App\User;

class RegisterController
{
    public function template()
    {
        Template::template('page_register');
    }


    public function registr()
    {
        $user = new User();
        //если проходит регистрация - получаем ID
        $user_id = $user->registration();

        //Вставляем данные в таблицу user_data
        $db = new QueryBuilder();
        $db->insert('user_data',[
           'user_id' => $user_id,
           'username' => $_POST['username'],
           'email' => $_POST['email']
        ]);
        Redirect::to('login');
    }
}
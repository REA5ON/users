<?php
namespace App;

use League\Plates\Engine;

class Template
{
    public static function template($render, $data = [])
    {
        $templates = new Engine('../App/views');
        echo $templates->render($render ,$data);
    }
}
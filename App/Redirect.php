<?php

namespace App;

class Redirect
{
    public static function to($location) {
        header('Location:/' . $location);
        exit;
    }

    public static function stay()
    {
        self::to($_SERVER['QUERY_STRING']);
    }
}
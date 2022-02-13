<?php

namespace App;

use claviska\SimpleImage;
use Exception;

class Image
{
    protected $image, $qb;

    public function __construct(SimpleImage $image, QueryBuilder $qb)
    {
        $this->image = $image;
        $this->qb = $qb;
    }

    public function saveImage($from, $path)
    {
        try {
            // Create a new SimpleImage object
            $newName = uniqid();
            $newImagePatch = $path . $newName . '.png';

            $this->image
                ->fromFile($from)                     // load image.jpg
                ->resize(296, 296)                          // resize to 296x296 pixels
                ->toFile('..' . $newImagePatch, 'image/png'); // convert to PNG and save a copy to new-image.png

            return $newImagePatch;

        } catch (Exception $err) {
            // Handle errors
            echo $err->getMessage();
        }

    }

    public function updateImage($userId, $from, $path)
    {
        try {
            $user = $this->qb->getOne('user_data', $userId);

            if ($user['image'] !== '/App/views/img/users_images/empty_image.png') {
                unlink('..' . $user['image']);
            }
            $image = $this->saveImage($from, $path);
            $this->qb->update('user_data', ['image' => $image], $userId);

        } catch (Exception $err) {
            echo $err->getMessage();
        }

    }

    public static function emptyImage($image)
    {
        if ($image === '') {
            echo '/App/views/img/users_images/empty_image.png';
        } else {
            echo $image;
        }

    }
}
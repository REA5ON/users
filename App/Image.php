<?php

namespace App;

use claviska\SimpleImage;
use Exception;

class Image
{
    public function saveImage($from, $path)
    {
        try {
            // Create a new SimpleImage object
            $image = new \claviska\SimpleImage();
            $newName = uniqid();
            $newImagePatch = $path . $newName . '.png';

            $image
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
            $qb = new QueryBuilder();
            $user = $qb->getOne('user_data', $userId);

            if ($user['image'] !== '/App/views/img/users_images/empty_image.png') {
                unlink('..' . $user['image']);
            }
            $image = $this->saveImage($from, $path);
            $qb->update('user_data', ['image' => $image], $userId);

        } catch (Exception $err) {
            echo $err->getMessage();
        }

    }
}
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
            $newName = uniqid();
            $newImagePatch = $path . $newName . '.png';

            $this->image
                ->fromFile($from)                     // load image.jpg
                ->resize(296, 296)                          // resize to 296x296 pixels
                ->toFile('..' . $newImagePatch, 'image/png'); // convert to PNG and save a copy to new-image.png

            return $newImagePatch;

        } catch (Exception $err) {
            // Handle errors
//            flash()->error($err->getMessage());
        }

    }

    public function updateImage($userId, $from, $path)
    {
        try {
            //get image path
            $user = $this->qb->getOne('user_data', $userId);

            //delete
            if (!empty($user['image'])) {
                unlink('..' . $user['image']);
            }
            //save
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
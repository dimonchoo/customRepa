<?php

namespace Parser\Core;

use Parser\Interfaces\ImageDownloadInterface;

class ImageDownload implements ImageDownloadInterface
{
    public static function downloadImage($imageUrl, $id_record)
    {
        self::createFolder();

        if (is_null($imageUrl)) {
            return false;
        }

        copy($imageUrl, self::IMG_FOLDER . DIRECTORY_SEPARATOR . $id_record . '.jpg');
    }

    private static function createFolder()
    {
        if (!file_exists(self::IMG_FOLDER)) {
            mkdir(self::IMG_FOLDER);
        }
    }
}
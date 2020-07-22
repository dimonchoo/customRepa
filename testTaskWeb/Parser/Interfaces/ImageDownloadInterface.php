<?php

namespace Parser\Interfaces;

interface ImageDownloadInterface
{
    const IMG_FOLDER = 'uploads';

    /**
     * @param string $imageUrl
     * @param int $id_record
     * @return void
     */
    public static function downloadImage($imageUrl, $id_record);
}
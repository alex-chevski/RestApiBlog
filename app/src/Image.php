<?php

declare(strict_types=1);

namespace Alex\RestApiBlog;

use Slim\Psr7\UploadedFile;

class Image
{
    private static string $path_img = '';

    public static function saveImage(UploadedFile $img): void
    {
        $uploadDir = 'storage/images/'.date('Y-m-d');

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $img_name_without_ext = pathinfo($img->getClientFilename(), PATHINFO_FILENAME);

        $ext = pathinfo($img->getClientFilename(), PATHINFO_EXTENSION);

        $img_name = $img_name_without_ext.'_'.date('H:i:s').'.'.$ext;

        if (move_uploaded_file($img->getFilePath(), "{$uploadDir}/{$img_name}")) {
            self::$path_img = stristr(realpath("{$uploadDir}/{$img_name}"), 'storage'); // тут проверка если фотографию пользователь не выбрал
        }
    }

    public static function getPathImage(): string
    {
        return self::$path_img;
    }
}

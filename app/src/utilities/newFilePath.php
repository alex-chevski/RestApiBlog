<?php

declare(strict_types=1);

namespace Alex\RestApiBlog\utilities;

function newFilePath($name_img_default): string
{
    $uploadDir = 'storage/images/'.date('Y-m-d');
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    $img_name_without_ext = pathinfo($name_img_default, PATHINFO_FILENAME);
    $ext = pathinfo($name_img_default, PATHINFO_EXTENSION);
    $new_img_name = $img_name_without_ext.'_'.date('H:i:s').'.'.$ext;

    return "{$uploadDir}/{$new_img_name}";
}

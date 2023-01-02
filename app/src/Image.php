<?php

declare(strict_types=1);

namespace Alex\RestApiBlog;

use function Alex\RestApiBlog\utilities\newFilePath;

use Slim\Psr7\UploadedFile;

class Image
{
    private string $path_img = '';
    private ?UploadedFile $upload;

    public function saveImage(UploadedFile $upload): void
    {
        $this->upload = $upload;

		$newFilePath = newFilePath($this->upload->getClientFilename());

        if (move_uploaded_file($this->upload->getFilePath(), $newFilePath)) {
            $this->path_img = stristr(realpath($newFilePath), 'storage'); // тут проверка если фотографию пользователь не выбрал то дать дефолтную
        }
    }

    // метод для удаления фотографии в будущем
    // метод для обновления фотографии в будущем

    public function getPathImage(): string
    {
        return $this->path_img;
    }
}

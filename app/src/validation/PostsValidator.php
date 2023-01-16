<?php

declare(strict_types=1);

namespace Alex\RestApiBlog\validation;

use Alex\RestApiBlog\Models\PostMapper;
use Slim\Psr7\UploadedFile;

class PostsValidator
{
    public function __construct(private PostMapper $post, private $err = [])
    {
        $this->post = $post;
    }

    public function validateImg(UploadedFile $file): array
    {
        // 1 проверка на расширение
        $fileName = $file->getClientFilename();
        $fileType = $file->getClientMediaType();
        $fileSize = $file->getSize();

        // проверка расширения img
        if (preg_match('#image/(jpeg|jpg|jpe|png)#', $fileType) && preg_match('#\.(jpg|jpeg|jpe|png)$#', $fileName)) {
            // проверка размера img
            $fileSize <= 1999000 ?: $this->setErrors('post_image', 'Размер изображения должен быть меньше 1999KB!');
        } else {
            $this->setErrors('post_image', 'Загрузите изображение');
        }

        return $this->getErrors();
    }

    /**
     * validateData.
     */
    public function validateData(array $fields): array
    {
        $field = array_filter($fields, fn ($key) => 'title' === $key, ARRAY_FILTER_USE_KEY);

        $fields = array_diff_key($fields, ['title' => 'xy']);

        if (!preg_match('#^[a-z][\w-]{0,20}$#', $field['title'])) {
            $this->setErrors('title', "Поле 'title' должно содержать только буквы и цифры");

            return $this->getErrors();
        }

        // Валидация title
        if ($field['title'] && strlen($field['title']) > 3) {
            $post = $this->post->getByUrlKey(str_replace(' ', '-', $field['title']));
            if ($post) {
                // сравнить по пользователю если у этого пользователя который создает есть такой заголовок то все ок если нет то ошибка
                // .....
                //
                //
                $this->setErrors('title', "Такое имя {$field['title']} уже существует");

                return $this->getErrors();
            }
        } else {
            $this->setErrors('title', "Поле 'title' должно быть больше 3 символов");

            return $this->getErrors();
        }

        // Валидация остальных полей
        foreach ($fields as $key => $val) {
            if (empty($val) or strlen($val) < 3) {
                $this->setErrors('title', "Поле {$key} должно быть больше 3 символов");

                return $this->getErrors();
            }
        }

        return $this->getErrors();
    }

    /**
     * setErrors.
     *
     * @param string $key
     * @param string $value
     */
    private function setErrors($key, $value): void
    {
        $this->err[$key] = $value;
    }

    private function getErrors(): array
    {
        return $this->err;
    }
}

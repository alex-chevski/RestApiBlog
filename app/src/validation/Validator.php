<?php

declare(strict_types=1);

namespace Alex\RestApiBlog\validation;

use Alex\RestApiBlog\Models\PostMapper;

class Validator
{
    public function __construct(private PostMapper $post)
    {
        $this->post = $post;
    }

    /**
     * validate.
     */
    public function validate(array $fields): array
    {
        // 1 ключ всегда имя поля как для пользователя так и для постов важно передавать имя
        $key = array_key_first($fields); // title
        // $file = array_key_last($fields); // image

        $err = []; // массив для ошибок
        // Валидация title
        if ($fields[$key] or strlen($fields[$key]) > 3) {
            $post = $this->post->getByUrlKey(str_replace(' ', '-', $fields[$key]));
            if ($post) {
                // сравнить по пользователю если у этого пользователя который создает есть такой заголовок то все ок если нет то ошибка
                // .....
                //
                //
                $err[$key] = "Такое имя {$fields[$key]} уже существует";

                return $err;
            }
        } else {
            $err[$key] = "Поле {$key} пустое либо слишком короткое";

            return $err;
        }

        // Валидация остальных полей
        foreach ($fields as $key => $val) {
            if (empty($val) or strlen($val) < 3) {
                $err[$key] = "Поле {$key} пустое либо слишком короткое";

                return $err;
            }
        }

        // Валидация фотографии
        print_r($file);

        return $err;
    }
}

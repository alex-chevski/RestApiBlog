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

    public function validate(array $fields): array
    {
        // 1 ключ всегда имя поля как для пользователя так и для постов важно передавать имя
        $key = array_key_first($fields);

        $err = [];
        if ($fields[$key] or strlen($fields[$key]) > 3) {
            $post = $this->post->getByUrlKey(str_replace(' ', '-', $fields[$key]));
            if ($post) {
                $err[$key] = "Такое имя {$fields[$key]} уже существует";

                return $err;
            }
        } else {
            $err[$key] = "Поле {$key} пустое либо слишком короткое";

            return $err;
        }

        foreach ($fields as $key => $val) {
            if (empty($val) or strlen($val) < 3) {
                $err[$key] = "Поле {$key} пустое либо слишком короткое";

                return $err;
            }
        }

        return $err;
    }
}

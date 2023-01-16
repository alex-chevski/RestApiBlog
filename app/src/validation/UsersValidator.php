<?php

declare(strict_types=1);

namespace Alex\RestApiBlog\validation;

use Alex\RestApiBlog\Models\UserMapper;

class UsersValidator
{
    public function __construct(private UserMapper $user, private $err = [])
    {
        $this->user = $user;
        $this->err = $err;
    }

    public function validateFirstUniqueNames($data)
    {
        if (false === $userData = $this->user->getByFirstName($data['email'])) {
            $this->setErrors('firstname', 'Пользователь с таким никнеймом или email не найден');

            return $this->getErrors();
        }

        // Проверка пароля
        if (false === password_verify($data['password'], $userData['password'])) {
            $this->setErrors('password', 'Пользователь с таким паролем не найден');

            return $this->getErrors();
        }

        return $this->getErrors();
    }

    public function validateData(array $data)
    {
        if (!preg_match('#^[a-z][\w-]{0,15}$#', $data['firstname'])) {
            $this->setErrors('firstname', 'Никнейм должен только состоять из буквенно-цифровых символов');

            return $this->getErrors();
        }

        if (!preg_match('#^[a-z][\w\.\-]{0,15}\@\w{1,20}\.[a-z]{2,6}$#', $data['email'])) {
            $this->setErrors('email', 'Укажите правильный адрес электронной почты');

            return $this->getErrors();
        }

        if ($this->user->getByFirstName($data['firstname'], $data['email'])) {
            $this->setErrors('firstname', 'Такой  никнейм или email уже существует');

            return $this->getErrors();
        }

        if (!is_string($data['password']) || '' == $data['password'] || strlen($data['password']) < 8
            || strlen($data['password']) > 64) {
            $this->setErrors('password', 'Введите корректный пароль');

            return $this->getErrors();
        }

        return $this->getErrors();
    }

    private function setErrors($key, $value): void
    {
        $this->err[$key] = $value;
    }

    private function getErrors()
    {
        return $this->err;
    }
}

<?php

declare(strict_types=1);

namespace Alex\RestApiBlog\Models;

use Alex\RestApiBlog\Database;

class UserMapper
{
    public function __construct(private Database $database)
    {
        $this->database = $database;
    }

    public function create(array $data): bool
    {
        $stm = $this->getConnection()->prepare(
            'INSERT INTO users (firstName, email, password, created) VALUES(:firstName, :email, :password, :created)'
        );

        $stm->bindParam(':firstName', $data['firstname']);
        $stm->bindParam(':email', $data['email']);
        $stm->bindParam(':password', password_hash($data['password'], PASSWORD_BCRYPT));
        $stm->bindParam(':created', date('Y-m-d H:i:s'));

        return $stm->execute();
    }

    public function getByFirstName(string|null $firstName = '', string|null $email = '')
    {
        // Проверка если данные email тогда по email поиск если нет то по никнейму
        $email = preg_match('#^[a-z][\w\.\-]{0,15}\@\w{1,20}\.[a-z]{2,6}$#', $firstName) ? $firstName : $email;

        $stm = $this->getConnection()->prepare('SELECT * FROM users WHERE firstName = :firstName or email = :email LIMIT 0, 1');
        $stm->bindParam(':firstName', $firstName);
        $stm->bindParam(':email', $email);

        $stm->execute();

        return $stm->fetch();
    }

    private function getConnection(): \PDO
    {
        return $this->database->getConnection();
    }
}

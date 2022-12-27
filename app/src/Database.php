<?php

declare(strict_types=1);

namespace Alex\RestApiBlog;

class Database
{
    public function __construct(private \PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getConnection(): \PDO
    {
        return $this->connection;
    }
}

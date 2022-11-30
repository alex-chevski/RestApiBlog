<?php

declare(strict_types=1);

use Alex\RestApiBlog\Database;

use function DI\autowire;
use function DI\get;

return [
    Database::class => autowire()
        ->constructorParameter('connection', get(\PDO::class)),

    \PDO::class => autowire()
        ->constructorParameter('dsn', getenv('DATABASE_DSN'))
        ->constructorParameter('username', getenv('DATABASE_USERNAME'))
        ->constructorParameter('password', getenv('DATABASE_PASSWORD'))
        ->constructorParameter('options', []),
];

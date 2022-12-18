<?php

declare(strict_types=1);

use Alex\RestApiBlog\Database;

use function DI\autowire;
use function DI\get;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

return [
    FilesystemLoader::class => autowire()
        ->constructorParameter('paths', 'templates'),

    Environment::class => autowire()
        ->constructorParameter('loader', get(FilesystemLoader::class)),

    Database::class => autowire()
        ->constructorParameter('connection', get(\PDO::class)),

    \PDO::class => autowire()
        ->constructor(
            getenv('DATABASE_DSN'),
            getenv('DATABASE_USERNAME'),
            getenv('DATABASE_PASSWORD'),
            []
        )
        ->method('setAttribute', PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION)
        ->method('setAttribute', PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC),
];

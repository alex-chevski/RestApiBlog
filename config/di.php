<?php

declare(strict_types=1);

use Alex\RestApiBlog\api\token\Token;
use Alex\RestApiBlog\Database;
use Alex\RestApiBlog\Image;
use Alex\RestApiBlog\Route\posts\Create;
use Alex\RestApiBlog\Route\users\SignIn;

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

    Create::class => autowire()
        ->constructorParameter('img', get(Image::class)),

    SignIn::class => autowire()
        ->constructorParameter('token', get(Token::class)),

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

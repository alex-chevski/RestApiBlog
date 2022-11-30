<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use Alex\RestApiBlog\Database;
use Alex\RestApiBlog\Route\posts\Index;
use DevCoder\DotEnv;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

// use Twig\Environment;
// use Twig\Loader\FilesystemLoader;

// $loader = new FilesystemLoader('../templates');
// $view = new Environment($loader);

$builder = new ContainerBuilder();

$builder->addDefinitions(__DIR__.'/../config/di.php');

(new DotEnv(__DIR__.'/../.env'))->load();

$container = $builder->build();

// AppFactory::setContainer($container);

$app = AppFactory::createFromContainer($container);

// $container->get(Database::class);
// $app = AppFactory::create();
// print_r(Database::class);

// $connection = $container->get(Database::class)->getConnection();

$app->addErrorMiddleware(true, true, true);

$app->get('/posts', Index::class.':execute');

$app->run();

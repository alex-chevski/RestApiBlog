<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

// use Twig\Environment;
// use Twig\Loader\FilesystemLoader;

// $loader = new FilesystemLoader('../templates');
// $view = new Environment($loader);

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write('');

    return $response;
});

$app->run();

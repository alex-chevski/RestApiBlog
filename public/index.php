<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use Alex\RestApiBlog\Route\posts\Create;
use Alex\RestApiBlog\Route\posts\Delete;
use Alex\RestApiBlog\Route\posts\Index;
// use Alex\RestApiBlog\Route\posts\PostsPage;
use Alex\RestApiBlog\Route\posts\Read;
use Alex\RestApiBlog\Route\posts\ReadOne;
use Alex\RestApiBlog\Route\posts\Update;
use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;

$container = require_once __DIR__.'/../bootstrap/container.php';

$app = AppFactory::createFromContainer($container);

$app->addErrorMiddleware(true, true, true);

$app->add(MethodOverrideMiddleware::class);

$app->post('/posts', Create::class);

$app->get('/', Index::class);

$app->get('/posts', Read::class)->setName('getPosts');

$app->patch('/posts/{url_key}', Update::class);

$app->delete('/posts/{url_key}', Delete::class);

$app->get('/posts/{url_key}', ReadOne::class);

// $app->get('/blog/{page}', PostsPage::class)->setName('pagination');

$app->run();

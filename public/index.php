<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use Alex\RestApiBlog\Route\posts\Index;
use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;

$container = require_once __DIR__.'/../bootstrap/container.php';

$app = AppFactory::createFromContainer($container);

$app->addErrorMiddleware(true, true, true);

$app->add(MethodOverrideMiddleware::class);

// main-route
$app->get('/', Index::class);

require_once __DIR__.'/../bootstrap/route/posts/route.php';

require_once __DIR__.'/../bootstrap/route/users/route.php';

$app->run();

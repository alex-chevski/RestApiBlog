<?php

declare(strict_types=1);
use Alex\RestApiBlog\Route\users\SignIn;
use Alex\RestApiBlog\Route\users\SignUp;
use Slim\Routing\RouteCollectorProxy;

$app->group('/users', function (RouteCollectorProxy $group): void {
    $group->post('/sign_up', SignUp::class);
    $group->post('/sign_in', SignIn::class);
});

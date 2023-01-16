<?php

declare(strict_types=1);

use Alex\RestApiBlog\Route\posts\Create;
use Alex\RestApiBlog\Route\posts\Delete;
use Alex\RestApiBlog\Route\posts\Read;
use Alex\RestApiBlog\Route\posts\ReadOne;
use Alex\RestApiBlog\Route\posts\Update;
use Slim\Routing\RouteCollectorProxy;

$app->group('/posts', function (RouteCollectorProxy $group): void {
    $group->post('', Create::class);
    $group->get('', Read::class)->setName('getPosts');
    $group->patch('/{url_key}', Update::class);
    $group->delete('/{url_key}', Delete::class);
    $group->get('/{url_key}', ReadOne::class);
});

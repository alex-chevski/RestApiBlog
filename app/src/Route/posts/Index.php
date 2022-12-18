<?php

declare(strict_types=1);

namespace Alex\RestApiBlog\Route\posts;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Twig\Environment;

class Index
{
    public function __construct(private Environment $view)
    {
        $this->view = $view;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $body = $this->view->render('index.twig');
        $response->getBody()->write($body);

        return $response;
    }
}

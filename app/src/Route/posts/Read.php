<?php

declare(strict_types=1);

namespace Alex\RestApiBlog\Route\posts;

use Alex\RestApiBlog\Models\PostMapper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Twig\Environment;

class Read
{
    public function __construct(private PostMapper $posts, private Environment $view)
    {
        $this->posts = $posts;
        $this->view = $view;
    }

    public function __invoke(Request $request, Response $response)
    {
        $posts = $this->posts->get(3);
        $posts = json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($posts);

        return $response->withHeader('Content-Type', 'application/json');
    }
}

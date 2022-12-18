<?php

declare(strict_types=1);

namespace Alex\RestApiBlog\Route\posts;

use Alex\RestApiBlog\Models\{PostMapper};
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Twig\Environment;

class ReadOne
{
    public function __construct(private PostMapper $posts, private Environment $view)
    {
        $this->posts = $posts;
        $this->view = $view;
    }

    public function __invoke(Request $request, Response $response, array $args = [])
    {
        $post = json_encode($this->posts->getByUrlKey($args['url_key']), JSON_UNESCAPED_UNICODE);

        // Если запись не найдена
        if (!$post) {
            $post = json_encode([
                'data' => [],
                'code' => 1,
                'message' => 'Запись не найдена или была удалена',
            ]);
        }
        $response->getBody()->write($post);

        return $response->withHeader('Content-Type', 'application/json');
    }
}

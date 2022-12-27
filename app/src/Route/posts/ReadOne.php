<?php

declare(strict_types=1);

namespace Alex\RestApiBlog\Route\posts;

use Alex\RestApiBlog\Models\{PostMapper};
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ReadOne
{
    public function __construct(private PostMapper $posts)
    {
        $this->posts = $posts;
    }

    public function __invoke(Request $request, Response $response, array $args = [])
    {
        $raw_temp = htmlspecialchars(strip_tags($args['url_key']));

        $post = json_encode($this->posts->getByUrlKey($raw_temp), JSON_PRETTY_PRINT, JSON_UNESCAPED_UNICODE);

        // Если запись не найдена
        if (!empty($post)) {
            $response->getBody()->write($post);

            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus(200)
            ;
        }

        $post = json_encode(
            [
                'data' => [],
                'status' => 'false',
                'message' => 'Запись не найдена или была удалена',
            ],
            JSON_PRETTY_PRINT,
            JSON_UNESCAPED_UNICODE
        );
        $response->getBody()->write($post);

        return $response->withoutHeader('Content-Type', 'application/json')
            ->withStatus(404)
        ;
    }
}

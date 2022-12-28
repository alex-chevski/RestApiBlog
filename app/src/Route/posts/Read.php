<?php

declare(strict_types=1);

namespace Alex\RestApiBlog\Route\posts;

use Alex\RestApiBlog\Models\PostMapper;

use function Alex\RestApiBlog\utilities\getPaging;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Read
{
    public function __construct(private PostMapper $post)
    {
        $this->post = $post;
    }

    public function __invoke(Request $request, Response $response)
    {
        // for search
        $keywords = $request->getQueryParam('keywords', '');
        // for pagination
        // сколько записей может быть на странице
        $limit = $request->getQueryParam('limit', 3);

        // страница через queryString
        $page = $request->getQueryParam('page', 1);

        $keywords = $keywords ? (string) $keywords : '';
        $page = $page ? (int) $page : 1;
        $limit = $limit ? (int) $limit : 3;

        // все посты из бд
        $posts['records'] = $this->post->getList($page, $limit, $keywords, 'ASC');

        // paging
        $total_rows = $this->post->getTotalCount();
        $posts['paging'] = getPaging($page, $total_rows, $limit, $page_url = 'http://10.0.2.10/posts?');

        // Если посты есть
        if ($posts['records']) {
            $posts = json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($posts);

            return $response->withHeader('Content-Type', 'application/json;charset=UTF-8')
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withStatus(200)
            ;
        }
        // иначе  постов нету

        $response->getBody()->write(json_encode($posts, JSON_UNESCAPED_UNICODE, JSON_PRETTY_PRINT));
        // здесь можно сделать перенаправление я сделал в js

        return $response->withHeader('Content-Type', 'application/json;charset=UTF-8')
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withStatus(200)
        ;
    }
}

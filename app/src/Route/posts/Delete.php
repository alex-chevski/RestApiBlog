<?php

declare(strict_types=1);

namespace Alex\RestApiBlog\Route\posts;

use Alex\RestApiBlog\Models\PostMapper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Delete
{
    public function __construct(private PostMapper $post)
    {
        $this->post = $post;
    }

    public function __invoke(Request $request, Response $response, $args): Response
    {
        $post_id = htmlspecialchars($args['url_key']);

        $exist_post = $this->post->getByUrlKey($post_id);

        if ($exist_post) {
            $this->post->delete($post_id);
            $out = json_encode(
                [
                    'status' => true,
                    'message' => "Пост {$post_id} успешно удален",
                ],
                JSON_PRETTY_PRINT
            );
        } else {
            $out = json_encode(
                [
                    'status' => false,
                    'message' => 'Пост не существует',
                ],
                JSON_PRETTY_PRINT
            );
        }

        $response->getBody()->write($out);

        return $response->withHeader('Content-Type', 'application\json');
    }
}

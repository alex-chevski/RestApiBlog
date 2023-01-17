<?php

declare(strict_types=1);

namespace Alex\RestApiBlog\Route\posts;

use Alex\RestApiBlog\api\token\Token;
use Alex\RestApiBlog\Models\PostMapper;
use Alex\RestApiBlog\validation\PostsValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Update
{
    public function __construct(private PostMapper $post, private PostsValidator $validator, private Token $token)
    {
        $this->post = $post;
        $this->validator = $validator;
        $this->token = $token;
    }

    public function __invoke(Request $request, Response $response, $args): Response
    {
        // инЪекция
        $requestData = array_map(fn ($val) => htmlspecialchars(strip_tags($val)), $request->getParsedBody());
        $cookie = $request->getCookieParams('jwt');
        $existsToken = $this->token->checkToken($cookie['jwt']) ? true : false;
        // $requestData = array_filter($requestData, fn ($key) => '_METHOD' !== $key, ARRAY_FILTER_USE_KEY);
        $url_key = htmlspecialchars($args['url_key']);

        // проверяем
        $err = $this->validator->validateData($requestData, $this->token->decoded['firstName'], 'UPDATE');

        // если ошибок нет
        if (!$err && $existsToken) {
            $requestData['author'] = $this->token->decoded['firstName'];
            // если запись обновилась
            if ($this->post->update($requestData, $url_key)) {
                $out = json_encode(
                    [
                        'status' => true,
                        'message' => "Пост {$requestData['title']} успешно обновлен",
                    ],
                    JSON_PRETTY_PRINT,
                    JSON_UNESCAPED_UNICODE
                );
                $response->getBody()->write($out);

                return $response->withHeader('Content-Type', 'application\json')
                    ->withStatus(201)
                ;
            }
            $out = json_encode(
                [
                    'status' => false,
                    'message' => 'Пост невозможно обновить',
                ],
                JSON_PRETTY_PRINT,
                JSON_UNESCAPED_UNICODE
            );
            $response->getBody()->write($out);

            return $response->withHeader('Content-Type', 'application\json')
                ->withStatus(503)
            ;
        }
        $out = json_encode(
            [
                'status' => false,
                'message' => implode('', array_values($err)), ],
            JSON_PRETTY_PRINT,
        );

        $response->getBody()->write($out);

        return $response->withHeader('Content-Type', 'application\json')
            ->withStatus(400)
        ;
    }
}

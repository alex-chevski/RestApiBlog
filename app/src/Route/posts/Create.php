<?php

declare(strict_types=1);

namespace Alex\RestApiBlog\Route\posts;

use Alex\RestApiBlog\api\token\Token;
use Alex\RestApiBlog\Image;
use Alex\RestApiBlog\Models\PostMapper;
use Alex\RestApiBlog\validation\PostsValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Create
{
    public function __construct(private PostMapper $post, private PostsValidator $validator, private Image $img, private Token $token)
    {
        $this->post = $post;
        $this->validator = $validator;
        $this->img = $img;
        $this->token = $token;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $cookie = $request->getCookieParams('jwt');
        $existsToken = $this->token->checkToken($cookie['jwt']) ? true : false;
        // инЪекция
        $requestData = array_map(fn ($val) => htmlspecialchars(strip_tags($val)), $request->getParsedBody());

        $uploadFile = $request->getUploadedFiles();

        // проверяем данные формы
        $err = $this->validator->validateData($requestData, $this->token->decoded['firstName'], 'CREATE');
        // если ошибок нет данные полные и заголовок уникальный

        // проверяем файл который прикрепил пользователь
        empty($err) ? $err = $this->validator->validateImg($uploadFile['post_image']) : $err;

        if (!$err && $existsToken) {
            // если ошибок нет

            // сохраняем фотографию
            $this->img->saveImage($uploadFile['post_image']);

            // получаем путь фотографии
            $requestData['image_path'] = $this->img->getPathImage();
            $requestData['author'] = $this->token->decoded['firstName'];

            // добавление в базу данных формы
            if ($this->post->create($requestData)) {
                $out = json_encode(
                    [
                        'status' => true,
                        'message' => "Пост {$requestData['title']} успешно  создан",
                    ],
                    JSON_PRETTY_PRINT,
                );

                $response->getBody()->write($out);

                return $response->withHeader('Content-Type', 'application\json')
                    ->withStatus(201)
                ;
            }

            $out = json_encode(
                [
                    'status' => false,
                    'message' => 'Невозможно создать пост',
                ],
                JSON_UNESCAPED_UNICODE,
                JSON_PRETTY_PRINT
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

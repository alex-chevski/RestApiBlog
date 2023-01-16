<?php

declare(strict_types=1);

namespace Alex\RestApiBlog\Route\users;

use Alex\RestApiBlog\Models\UserMapper;
use Alex\RestApiBlog\validation\UsersValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SignUp
{
    public function __construct(private UserMapper $user, private UsersValidator $validate)
    {
        $this->user = $user;
        $this->validate = $validate;
    }

    public function __invoke(Request $request, Response $response)
    {
        // сделать экранирование и убрать ненужные пробелы
        $requestData = array_map(fn ($val) => htmlspecialchars(strip_tags($val)), $request->getParsedBody());
        $err = $this->validate->validateData($requestData);

        if (!$err) {
            if ($this->user->create($requestData)) {
                $out = json_encode(
                    [
                        'status' => true,
                        'message' => 'Пользователь успешно зарегистрирован',
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
                    'message' => 'Невозможно зарегистрироваться',
                ],
                JSON_UNESCAPED_UNICODE,
                JSON_PRETTY_PRINT
            );
            $response->getBody()->write($out);

            return $response->withHeader('Content-Type', 'application\json')
                ->withStatus(503)
            ;
        }
        $out = json_encode(['status' => false, 'message' => implode('', array_values($err))]);
        $response->getBody()->write($out);

        return $response->withHeader('Content-Type', 'application\json')
            ->withStatus(400)
        ;
    }
}

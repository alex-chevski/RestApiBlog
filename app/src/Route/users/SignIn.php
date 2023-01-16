<?php

declare(strict_types=1);

namespace Alex\RestApiBlog\Route\users;

use Alex\RestApiBlog\api\token\Token;
use Alex\RestApiBlog\validation\UsersValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SignIn
{
    public function __construct(private UsersValidator $validate, private Token $token)
    {
        $this->validate = $validate;
        $this->token = $token;
    }

    public function __invoke(Request $request, Response $response)
    {
        // сделать экранирование и убрать ненужные пробелы
        $requestData = array_map(fn ($val) => htmlspecialchars(strip_tags($val)), $request->getParsedBody());

        $err = $this->validate->validateFirstUniqueNames($requestData);

        if (!$err) {
            $this->token->createToken($requestData['email']);
            $encode = $this->token->encode;

            $out = json_encode(['message' => 'Успешный вход в систему', 'jwt' => $encode,
            ], JSON_PRETTY_PRINT, JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($out);

            return $response->withHeader('Content-Type', 'application\json; charset=UTF-8')
                ->withStatus(200)
            ;
        }
        $out = json_encode([
            'message' => implode('', array_values($err)),
        ], JSON_PRETTY_PRINT);
        $response->getBody()->write($out);

        return $response->withHeader('Content-Type', 'application\json')
            ->withStatus(401)
        ;
    }
}

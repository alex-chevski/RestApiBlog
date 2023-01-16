<?php

declare(strict_types=1);

namespace Alex\RestApiBlog\Route\posts;

use Alex\RestApiBlog\api\token\Token;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Twig\Environment;

class Index
{
    /**
     * @param private Environment $view
     *
     * @return mixed
     */
    public function __construct(private Environment $view, private Token $token)
    {
        $this->view = $view;
        $this->token = $token;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        // проверить существует ли пользователь если да то отправить в index.twig пользователя его имя
        // если нет то это гость сделать так чтобы он зарегистировался
        $data = $request->getCookieParams('jwt');
        if ($this->token->checkToken($data['jwt'])) {
            $user = $this->token->decoded;

            // $out = json_encode(['user' => $user_id], JSON_PRETTY_PRINT, JSON_UNESCAPED_UNICODE);
            // $out = json_encode(['message' => 'Доступ разрешен', 'user' => $user_id]);

            $body = $this->view->render('index.twig', ['user' => $user]);
        } else {
            // $out = json_encode(['message' => 'Доступ закрыт']);

            $body = $this->view->render('index.twig');
        }

        $response->getBody()->write($body);

        return $response;
    }
}

<?php

declare(strict_types=1);

namespace Alex\RestApiBlog\api\token;

use Alex\RestApiBlog\Models\UserMapper;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

final class Token
{
    public function __construct(private UserMapper $user, private string $encode = '', private array $decoded = [])
    {
        $this->user = $user;
        $this->encode = $encode;
        $this->decoded = $decoded;
    }

    public function __get(string $property): string|array
    {
        return $this->{$property};
    }

    public function createToken(string $idName): void
    {
        $user = $this->user->getByFirstName($idName);

        $token = [
            // 'exp' => time() + 3600,
            // 'iat' => time(),
            'data' => [
                'id' => $user['id'],
                'firstName' => $user['firstName'],
                'email' => $user['email'],
            ],
        ];

        $this->encode = JWT::encode($token, getenv('KEY'), 'HS256');
    }

    public function checkToken(?string $token): bool
    {
        $jwt = $token ?? '';
        if ($jwt) {
            try {
                $decoded = JWT::decode($jwt, new Key(getenv('KEY'), 'HS256'));
                $id = $decoded->data->id;
                $firstName = $decoded->data->firstName;
                $email = $decoded->data->email;

                $this->decoded = compact('id', 'firstName', 'email');

                return true;
            } catch (\Exception $e) {
                print_r($e->getMessage());

                return false;
            }
        }

        return false;
    }
}

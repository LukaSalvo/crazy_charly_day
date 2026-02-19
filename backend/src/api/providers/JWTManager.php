<?php

namespace toybox\api\providers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
class JWTManager
{
    public function createAccessToken($user)
    {
        $payload['user'] = [
            'id'    => $user->getId(),
            'email' => $user->getEmail(),
            'role'  => $user->getRole(),
        ];
        $payload['exp'] = time() + 15 * 60;
        return JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');
    }

    public function createRefreshToken(array $payload)
    {
        $payload['exp'] = time() + 7 * 24 * 60 * 60;
        $refreshToken = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');
        return $refreshToken;
    }

    public function decodeToken($token)
    {
        try {
            $decoded = JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));
            return (array) $decoded;
        } catch (\Exception $e) {
            return null;
        }
    }
}
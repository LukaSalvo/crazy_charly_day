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
        return JWT::encode($payload, $this->getSecret(), 'HS256');
    }

    public function createRefreshToken(array $payload)
    {
        $payload['exp'] = time() + 7 * 24 * 60 * 60;
        return JWT::encode($payload, $this->getSecret(), 'HS256');
    }

    public function decodeToken($token)
    {
        try {
            $secret = $this->getSecret();
            $decoded = JWT::decode($token, new Key($secret, 'HS256'));
            return (array) $decoded;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getSecret(): string
    {
        $secret = $_ENV['JWT_SECRET'] ?? $_SERVER['JWT_SECRET'] ?? getenv('JWT_SECRET');
        
        if ($secret === false || $secret === null || $secret === '') {
            $secret = 'super_secret_key_12345';
        }
        
        return trim((string)$secret);
    }
}
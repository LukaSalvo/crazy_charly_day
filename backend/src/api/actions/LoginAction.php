<?php

namespace toybox\api\actions;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use toybox\infra\repositories\UserRepository;
use toybox\api\providers\JWTManager;

class LoginAction
{
    public function __construct(
        private UserRepository $userRepository,
        private JWTManager $jwtManager
    ) {}

    public function __invoke(Request $request, Response $response): Response
    {
        $data     = $request->getParsedBody();
        $email    = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';

        if (!$email || !$password) {
            $response->getBody()->write(json_encode(['error' => 'Email et mot de passe requis']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $user = $this->userRepository->findByEmail($email);

        if (!$user || !password_verify($password, $user->getPasswordHash())) {
            $response->getBody()->write(json_encode(['error' => 'Identifiants incorrects']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }

        $token = $this->jwtManager->createAccessToken($user);

        $response->getBody()->write(json_encode([
            'token' => $token,
            'user'  => [
                'id'    => $user->getId(),
                'email' => $user->getEmail(),
                'role'  => $user->getRole(),
            ],
        ]));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}

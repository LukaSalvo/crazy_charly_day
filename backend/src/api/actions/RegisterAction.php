<?php

namespace toybox\api\actions;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use toybox\infra\repositories\UserRepository;
use toybox\api\providers\JWTManager;

class RegisterAction
{
    public function __construct(
        private UserRepository $userRepository,
        private JWTManager $jwtManager
    ) {}

    public function __invoke(Request $request, Response $response): Response
    {
        $data  = $request->getParsedBody();
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';

        // Validation basique
        if (!$email || !$password) {
            $response->getBody()->write(json_encode(['error' => 'Email et mot de passe requis']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response->getBody()->write(json_encode(['error' => 'Email invalide']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        // Vérifier si l'email est déjà utilisé
        if ($this->userRepository->findByEmail($email)) {
            $response->getBody()->write(json_encode(['error' => 'Email déjà utilisé']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(409);
        }

        // Créer l'utilisateur
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $user = $this->userRepository->create($email, $hash, $email);

        // Générer le token
        $token = $this->jwtManager->createAccessToken($user);

        $response->getBody()->write(json_encode([
            'token' => $token,
            'user'  => [
                'id'    => $user->getId(),
                'email' => $user->getEmail(),
                'role'  => $user->getRole(),
            ],
        ]));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }
}

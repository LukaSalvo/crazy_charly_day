<?php

namespace toybox\api\middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use toybox\api\providers\JWTManager;
use Slim\Psr7\Response as SlimResponse;

class SimpleAuthMiddleware implements MiddlewareInterface
{
    private JWTManager $jwtManager;
    private ?string $requiredRole;

    public function __construct(JWTManager $jwtManager, ?string $requiredRole = null)
    {
        $this->jwtManager = $jwtManager;
        $this->requiredRole = $requiredRole;
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        $authHeader = $request->getHeaderLine('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return $this->errorResponse('Authentification requise', 401);
        }

        $token = substr($authHeader, 7);
        $decoded = $this->jwtManager->decodeToken($token);

        if (!$decoded) {
            return $this->errorResponse('Token invalide ou expiré', 401);
        }

        $user = (object)$decoded['user'];

        // Authorization check
        if ($this->requiredRole && $user->role !== $this->requiredRole && $user->role !== 'admin') {
            return $this->errorResponse('Accès interdit : privilèges insuffisants', 403);
        }

        // Attach user to request
        $request = $request->withAttribute('authenticated_user', $user);

        return $handler->handle($request);
    }

    private function errorResponse(string $message, int $status): Response
    {
        $response = new SlimResponse();
        $response->getBody()->write(json_encode(['error' => $message]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
}

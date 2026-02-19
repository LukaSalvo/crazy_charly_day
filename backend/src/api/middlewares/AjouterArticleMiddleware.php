<?php

namespace toybox\api\middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\Uuid;
use toybox\core\application\ports\api\dtos\AjoutArticleDTO;

class AjouterArticleMiddleware
{
    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $recup = $request->getParsedBody();
        $id = Uuid::uuid4();
        $ajoutArticleDTO = new AjoutArticleDTO(
            $id,
            $recup['designation'],
            $recup['categorie'],
            $recup['age'],
            $recup['etat'],
            $recup['prix'],
            $recup['poids']
        );
        return $handler->handle($request->withAttribute('ajoutArticleDTO', $ajoutArticleDTO));
    }
}
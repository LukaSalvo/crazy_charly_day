<?php

namespace toybox\api\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toybox\core\application\usecases\Service;

class ListerBoxAction
{
    private Service $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $idClient = $args['id'];
        $boxArticles = $this->service->listerBoxUser($idClient);

        $response->getBody()->write(json_encode($boxArticles));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
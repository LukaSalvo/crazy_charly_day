<?php

namespace toybox\api\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toybox\core\application\usecases\Service;

class ListerCampagneAction
{
    private Service $service;

    public function __construct(Service $service){
        $this->service = $service;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args){
        $campagnes = $this->service->listerCampagnes();
        $response->getBody()->write(json_encode($campagnes, JSON_PRETTY_PRINT));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}

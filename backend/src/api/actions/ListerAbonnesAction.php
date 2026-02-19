<?php

namespace toybox\api\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toybox\core\application\usecases\Service;

class ListerAbonnesAction
{
    private Service $service;

    public function __construct(Service $service){
        $this->service = $service;
    }

    public function __invoke( ServerRequestInterface $request, ResponseInterface $response, $args){
        $abonnes = $this->service->listerAbonnes();
        $response->getBody()->write(json_encode($abonnes, JSON_PRETTY_PRINT));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
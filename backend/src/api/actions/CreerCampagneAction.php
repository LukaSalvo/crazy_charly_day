<?php

namespace toybox\api\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toybox\core\application\usecases\Service;

class CreerCampagneAction
{
    private Service $service;
    public function __construct(Service $service){
        $this->service = $service;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $args){
        $recup = $request->getParsedBody();
        $poids_max = $recup['poids_max'];
        $prix_max = $recup['prix_max'];
        $prix_min = $recup['prix_min'];
        $this->service->creerCampagne($poids_max, $prix_min, $prix_max);

        return $response->withStatus(201);
    }
}
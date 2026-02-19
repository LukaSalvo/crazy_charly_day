<?php

namespace toybox\api\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toybox\core\application\usecases\Service;

class ValiderBoxAction
{
    private Service $service;

    public function __construct(Service $service){
        $this->service = $service;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response,$args)
    {
        $id = $args['id'];
        $res = $this->service->validerBox($id);
        if(!$res){
            return $response->withStatus(400);
        }
        return $response->withStatus(200);
    }
}
<?php

namespace toybox\api\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toybox\core\application\usecases\Service;

class SupprimerArticleAction
{
    private Service $service;

    public function __construct(Service $service){
        $this->service = $service;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response,array $args)
    {

    }
}
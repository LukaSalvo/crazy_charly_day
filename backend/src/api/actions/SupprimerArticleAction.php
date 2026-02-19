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
        if($args['id']){
            try {
                $id = $args['id'];
                $this->service->SupprimerArticle($id);
                $response->getBody()->write(json_encode($id, JSON_PRETTY_PRINT));
                return $response->withStatus(200)
                    ->withHeader('Content-Type', 'application/json');
            }catch (\Throwable $th) {
                return $response->withStatus(400);
            }
        }else{
            return $response->withStatus(400);
        }
    }
}
<?php

namespace toybox\api\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toybox\application\ports\api\dtos\AjoutArticleDTO;
use toybox\core\application\usecases\Service;

class AjouterArticleAction
{
    private Service $service;

    public function __construct(Service $service){
        $this->service = $service;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response,array $args){
        $article = $request->getAttribute('ajoutArticleDTO');

        $this->service->AjouterArticle($article);
        return $response->withStatus(201);

    }
}
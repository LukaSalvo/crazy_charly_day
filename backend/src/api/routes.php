<?php

use Slim\App;
use toybox\api\actions\AjouterArticleAction;
use toybox\api\actions\ListerArticlesAction;
use toybox\api\actions\SupprimerArticleAction;
use toybox\api\middlewares\AjouterArticleMiddleware;

return function (App $app):App {

    $app->get('/articles', ListerArticlesAction::class)->setName('lister_articles');
    $app->options('/articles', function ($request, $response) {
        return $response;
    });
    $app->post('/articles', AjouterArticleAction::class)->add(AjouterArticleMiddleware::class)->setName('ajouter_article');
    $app->options('/articles/{id}', function ($request, $response) {
        return $response;
    });
    $app->delete('/articles/{id}', SupprimerArticleAction::class)->setName('supprimer_article');

    
    return $app;
};
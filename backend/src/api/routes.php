<?php

use Slim\App;
use toybox\api\actions\ListerArticlesAction;
use toybox\api\actions\SupprimerArticleAction;
use toybox\api\actions\LoginAction;
use toybox\api\actions\RegisterAction;

return function (App $app): App {

    // Auth routes
    $app->post('/auth/register', RegisterAction::class);
    $app->post('/auth/login', LoginAction::class);

    // Article routes
    $app->get('/articles', ListerArticlesAction::class)->setName('lister_articles');
    $app->options('/articles', function ($request, $response) {
        return $response;
    });
    $app->options('/articles/{id}', function ($request, $response) {
        return $response;
    });
    $app->delete('/articles/{id}', SupprimerArticleAction::class)->setName('supprimer_article');

    return $app;
};
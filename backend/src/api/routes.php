<?php

use Slim\App;
use toybox\api\actions\AjouterArticleAction;
use toybox\api\actions\CreerCampagneAction;
use toybox\api\actions\ListerAbonnesAction;
use toybox\api\actions\ListerArticlesAction;
use toybox\api\actions\SupprimerAbonneAction;
use toybox\api\actions\SupprimerArticleAction;
use toybox\api\middlewares\AjouterArticleMiddleware;
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
    $app->post('/articles', AjouterArticleAction::class)->add(AjouterArticleMiddleware::class)->setName('ajouter_article');
    $app->options('/articles/{id}', function ($request, $response) {
        return $response;
    });
    $app->delete('/articles/{id}', SupprimerArticleAction::class)->setName('supprimer_article');

    $app->get('/abonnes', ListerAbonnesAction::class)->setName('lister_abonnes');

    $app->options('/abonnes/{id}', function ($request, $response) {
        return $response;
    });
    $app->delete('/abonnes/{id}', SupprimerAbonneAction::class)->setName('supprimer_abonne');

    $app->options('/campagnes', function ($request, $response) {
        return $response;
    });
    $app->post('/campagnes', CreerCampagneAction::class)->setName('creer_campagne');

    return $app;
};
<?php

use Slim\App;
use toybox\api\actions\AjouterArticleAction;
use toybox\api\actions\CreerCampagneAction;
use toybox\api\actions\ListerAbonnesAction;
use toybox\api\actions\ListerArticlesAction;
use toybox\api\actions\ListerBoxAction;
use toybox\api\actions\ListerCampagneAction;
use toybox\api\actions\ListerToutesLesBoxAction;
use toybox\api\actions\LoginAction;
use toybox\api\actions\RegisterAction;
use toybox\api\actions\SupprimerAbonneAction;
use toybox\api\actions\SupprimerArticleAction;
use toybox\api\actions\ValiderBoxAction;
use toybox\api\middlewares\AjouterArticleMiddleware;
use toybox\api\middlewares\SimpleAuthMiddleware;
use toybox\api\providers\JWTManager;

return function (App $app): App {

    // Auth logic
    $app->options('/auth/register', function ($request, $response) {
        return $response;
    });
    $app->options('/auth/login', function ($request, $response) {
        return $response;
    });
    $app->post('/auth/register', RegisterAction::class)->setName('register');
    $app->post('/auth/login', LoginAction::class)->setName('login');

    // Public Routes (Optional, list articles can be restricted but usually public for browsing)
    $app->get('/articles', ListerArticlesAction::class)->setName('lister_articles');

    // Protected Admin Routes
    $adminGroup = function ($app) {
        $app->post('/articles', AjouterArticleAction::class)->add(AjouterArticleMiddleware::class)->setName('ajouter_article');
        $app->delete('/articles/{id}', SupprimerArticleAction::class)->setName('supprimer_article');
        $app->get('/abonnes', ListerAbonnesAction::class)->setName('lister_abonnes');
        $app->delete('/abonnes/{id}', SupprimerAbonneAction::class)->setName('supprimer_abonne');
        $app->post('/campagnes', CreerCampagneAction::class)->setName('creer_campagne');
        $app->get('/boxes', ListerToutesLesBoxAction::class)->setName('admin_lister_boxes');
        $app->post('/boxes/{id}/valider', ValiderBoxAction::class)->setName('admin_valider_box');
    };

    // Apply Admin Middleware to these routes
    $jwtManager = $app->getContainer()->get(JWTManager::class);
    $app->group('', $adminGroup)->add(new SimpleAuthMiddleware($jwtManager, 'admin'));

    // Subscriber Protected Routes
    $app->get('/abonnes/{id}/box', ListerBoxAction::class)
        ->add(new SimpleAuthMiddleware($jwtManager, 'abonne'))
        ->setName('lister_box_user');
    
    $app->get('/boxes', ListerBoxAction::class)->setName('lister_boxes');
    $app->options('/boxes/{id}', function ($request, $response) {
        return $response;
    });
    $app->put('/boxes/{id}', ValiderBoxAction::class)->setName('valider_box');
    $app->get('/campagnes', ListerCampagneAction::class)->setName('lister_campagnes');

    return $app;
};
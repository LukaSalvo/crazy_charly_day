<?php

use Slim\App;
use toybox\api\actions\ListerArticlesAction;

return function (App $app):App {

    $app->get('/articles', ListerArticlesAction::class)->setName('lister_articles');

    return $app;
};
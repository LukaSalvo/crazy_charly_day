<?php

use Slim\Factory\AppFactory;
use toybox\api\middlewares\CorsMiddleware;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$container = require __DIR__ . '/container.php';

AppFactory::setContainer($container);
$app = AppFactory::create();

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, false, false);

$app->add(new CorsMiddleware());

$app = (require __DIR__ . '/../src/api/routes.php')($app);

return $app;

<?php

use Psr\Container\ContainerInterface;
use toybox\api\actions\ListerBoxAction;
use toybox\api\providers\JWTManager;
use toybox\core\application\usecases\Service;
use toybox\infra\repositories\Repository;
use toybox\infra\repositories\UserRepository;

return [
    'pdo.db' => function (ContainerInterface $container) {
        $db = $container->get('settings')['db'];
        return new PDO(
            "{$db['driver']}:host={$db['host']};dbname={$db['database']}",
            $db['username'],
            $db['password']
        );
    },

    UserRepository::class => function (ContainerInterface $container) {
        return new UserRepository($container->get('pdo.db'));
    },
    
    Repository::class => function (ContainerInterface $container) {
        return new Repository($container->get('pdo.db'));
    },

    Service::class => function (ContainerInterface $container) {
        return new Service($container->get(Repository::class));
    },

    JWTManager::class => function (ContainerInterface $container) {
        return new JWTManager();
    },

    ListerBoxAction::class => function (ContainerInterface $container) {
        return new ListerBoxAction($container->get(Service::class));
    }
];

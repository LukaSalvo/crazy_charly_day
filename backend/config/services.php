<?php

use Psr\Container\ContainerInterface;
use toybox\core\application\usecases\Service;
use toybox\infra\repositories\Repository;

return [
    'pdo.db' => function (ContainerInterface $container) {
        $db = $container->get('settings')['db'];
        return new PDO(
            "{$db['driver']}:host={$db['host']};dbname={$db['database']}",
            $db['username'],
            $db['password']);
    },
    Repository::class => function (ContainerInterface $container) {
        return new Repository($container->get('pdo.db'));
    },

    Service::class => function (ContainerInterface $container) {
        return new Service($container->get(Repository::class));
    }
];

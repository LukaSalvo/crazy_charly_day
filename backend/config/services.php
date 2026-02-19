<?php

use Psr\Container\ContainerInterface;

return [
    'pdo.db' => function (ContainerInterface $container) {
        $db = $container->get('settings')['db']['auth'];
        return new PDO(
            "{$db['driver']}:host={$db['host']};dbname={$db['dbname']}",
            $db['user'],
            $db['password']);
    },
];

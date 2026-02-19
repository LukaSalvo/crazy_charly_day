<?php

return [
    'settings' => [
        'db' => [
            'driver' => $_ENV['db.driver'],
            'host' => $_ENV['db.host'],
            'database' => $_ENV['db.database'],
            'username' => $_ENV['db.username'],
            'password' => $_ENV['db.password'],
        ],
        'cors' => [
            'origin' => [$_ENV['url']],
            'methods' => ['GET', 'POST', 'PUT', 'DELETE'],
            'headers.allow' => ['Content-Type', 'Authorization', 'Access-Control-Allow-Origin', 'X-Requested-With'],
        ]
    ],
];
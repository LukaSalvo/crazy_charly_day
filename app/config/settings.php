<?php

return [
    'settings' => [
        'db' => [
            'driver' => $_ENV['DB_DRIVER'],
            'host' => $_ENV['DB_HOST'],
            'port' => $_ENV['DB_PORT'],
            'database' => $_ENV['DB_DATABASE'],
            'username' => $_ENV['DB_USERNAME'],
            'password' => $_ENV['DB_PASSWORD'],
        ],
        'cors' => [
            'origin' => [$_ENV['url']],
            'methods' => ['GET', 'POST', 'PUT', 'DELETE'],
            'headers.allow' => ['Content-Type', 'Authorization', 'Access-Control-Allow-Origin', 'X-Requested-With'],
        ]
    ],
];
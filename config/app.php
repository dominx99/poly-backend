<?php

return [
    'settings.displayErrorDetails' => true,
    'app'                          => [
        'dev' => true,
    ],
    'db'                           => [
        'driver'   => getenv('DB_DRIVER'),
        'host'     => getenv('DB_HOST'),
        'port'     => getenv('DB_PORT'),
        'dbname'   => getenv('DB_NAME'),
        'user'     => getenv('DB_USER'),
        'password' => getenv('DB_PASSWORD'),
        'charset'  => 'utf8',
    ],
    'orm'                          => [
        'entities_dir' => [__DIR__ . '/../src/Framework/Entities'],
    ],
];

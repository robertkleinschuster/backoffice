<?php

declare(strict_types=1);

return [
    'db' => [
        #  'driver'   => 'Pdo_Sqlite',
        #  'database' => __DIR__ . '/../../../data/sqlite.db',
        'driver' => 'Pdo_Mysql',
        'database' => 'backoffice',
        'username' => 'backoffice',
        'password' => 'backoffice',
        'hostname' => '127.0.0.1',
    ]
];

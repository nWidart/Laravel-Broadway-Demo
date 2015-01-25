<?php

return [
    'default' => 'mysql',
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'broadway-laravel',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ],
        'pgsql' => [
            'driver' => 'pgsql',
            'host' => 'localhost',
            'database' => 'homestead',
            'username' => 'homestead',
            'password' => 'secret',
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
        ],
    ],
];

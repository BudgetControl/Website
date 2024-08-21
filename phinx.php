<?php
require_once __DIR__ . '/config/bootstrap.php';

return [
    'paths' => [
        'migrations' => 'resources/migrations',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog_web',
        'default_database' => 'development',
        'development' => [
            'adapter'   => 'mysql',
            'host' => env('DB_HOST'),
            'name' => env('DB_DATABASE'),
            'user' => env('DB_USERNAME'),
            'pass' => env('DB_PASSWORD'),
            'charset'  => 'utf8',
            'port' => env('DB_PORT')
        ],
    ],
];
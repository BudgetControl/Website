<?php
return [
    'paths' => [
        'migrations' => 'resources/migrations',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'development',
        'development' => [
            'adapter'   => 'mysql',
            'host' => 'budgetcontrol-db',
            'name' => 'budgetV2_website',
            'user' => 'root',
            'pass' => 'rootpasswordmaster',
            'charset'  => 'utf8',
            'port' => 3306
        ],
    ],
];
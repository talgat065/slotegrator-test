<?php

return
[
    'paths' => [
        'migrations' => 'migrations',
//        'seeds' => 'seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'production',
        'production' => [
            'adapter' => 'mysql',
            'host' => 'db',
            'name' => 'random_prizes',
            'user' => 'root',
            'pass' => 'password',
            'port' => '3306',
            'charset' => 'utf8',
        ]
    ],
    'version_order' => 'creation'
];

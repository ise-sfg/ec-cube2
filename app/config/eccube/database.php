<?php
$url = parse_url(getenv("DATABASE_URL"));
return [
    "database" => [
        'driver' => 'pdo_pgsql',
        'host' => $url["host"],
        'dbname' => substr($url["path"],1),
        'port' => $url["port"],
        'user' => $url["user"],
        'password' => $url["pass"],
        'charset' => 'utf8',
        'defaultTableOptions' => [
            'collate' => 'utf8_general_ci'
        ]
    ]
];
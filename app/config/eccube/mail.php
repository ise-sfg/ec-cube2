<?php
return [
    "mail" => [
        'transport' => 'smtp',
        'host' => 'smtp.sendgrid.net',
        'port' => '587',
        'username' => getenv("SENDGRID_USERNAME"),
        'password' => getenv("SENDGRID_PASSWORD"),
        'encryption' => 'tls',
        'auth_mode' => 'plain',
        'charset_iso_2022_jp' => 'false',
        'use_spool' => 'true'
    ]
];
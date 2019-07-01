<?php

return [
    'seed' => [
        'dev_name' => env('DEV_NAME', 'test'),
        'dev_email' => env('DEV_EMAIL', 'test@gmail.com'),
        'dev_password' => env('DEV_PASSWORD', 'secret'),
    ],
    'admin' => [
        'prefix' => env('ADMIN_PREFIX', 'admin'),
        'roles' => [
            'administrator' => 'administrator',
            'moderator' => 'moderator',
            'manager' => 'manager',
        ],
    ],
];
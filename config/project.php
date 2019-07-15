<?php

return [
    'seed' => [
        'dev_name' => env('DEV_NAME', 'test'),
        'dev_email' => env('DEV_EMAIL', 'test@gmail.com'),
        'dev_password' => env('DEV_PASSWORD', 'secret'),

        'users_count' => 50,
        'user_faker_image_width' => 1400,
        'user_faker_image_height' => 1000,
    ],
    'user' => [
        'min_birth_year' => 1950,
        'teen_age' => 16,
        'images_folder' => 'user-images',
        // width, height, padding-bottom, padding-right
        'image_sizes' => [
            "small" => [
                "w" => 70,
                "h" => 70,
                "pb" => 3,
                "pr" => 3,
            ],
            "medium" => [
                "w" => 260,
                "h" => 160,
                "pb" => 5,
                "pr" => 5,
            ],
            "large" => [
                "w" => 642,
                "h" => 440,
                "pb" => 10,
                "pr" => 10,
            ],
        ],
        'image_divisions' => [
            'min' => 1.2,
            'max' => 2,
        ],
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
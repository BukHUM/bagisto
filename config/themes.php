<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Shop Theme Configuration
    |--------------------------------------------------------------------------
    |
    | All the configurations are related to the shop themes.
    |
    */

    'shop-default' => 'default',

    'shop' => [
        'default' => [
            'name' => 'Default',
            'assets_path' => 'public/themes/shop/default',
            'views_path' => 'resources/themes/default/views',

            'vite' => [
                'hot_file' => 'shop-default-vite.hot',
                'build_directory' => 'themes/shop/default/build',
                'package_assets_directory' => 'src/Resources/assets',
            ],
        ],

        'beyondary' => [
            'name' => 'beyondary',
            'assets_path' => 'public/themes/shop/beyondary',
            'views_path' => 'resources/themes/beyondary/views',
            'parent' => 'default',

            'vite' => [
                'hot_file' => 'shop-beyondary-vite.hot',
                'build_directory' => 'themes/shop/beyondary/build',
                'package_assets_directory' => 'assets',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Theme Configuration
    |--------------------------------------------------------------------------
    |
    | All the configurations are related to the admin themes.
    |
    */

    'admin-default' => 'beyondary-admin',

    'admin' => [
        'default' => [
            'name' => 'Default',
            'assets_path' => 'public/themes/admin/default',
            'views_path' => 'resources/admin-themes/default/views',

            'vite' => [
                'hot_file' => 'admin-default-vite.hot',
                'build_directory' => 'themes/admin/default/build',
                'package_assets_directory' => 'src/Resources/assets',
            ],
        ],

        'beyondary-admin' => [
            'name' => 'Beyondary Admin',
            'assets_path' => 'public/themes/admin/beyondary-admin',
            'views_path' => 'resources/admin-themes/beyondary-admin/views',
            'parent' => 'default',

            'vite' => [
                'hot_file' => 'admin-beyondary-vite.hot',
                'build_directory' => 'themes/admin/beyondary-admin/build',
                'package_assets_directory' => 'assets',
            ],
        ],
    ],
];

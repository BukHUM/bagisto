<?php

return [
    [
        'key' => 'beyondary',
        'name' => 'beyondary-storefront::app.acl.beyondary',
        'route' => 'admin.beyondary.storefront.index',
        'sort' => 10,
    ],
    [
        'key' => 'beyondary.storefront',
        'name' => 'beyondary-storefront::app.acl.homepage',
        'route' => 'admin.beyondary.storefront.index',
        'sort' => 1,
    ],
    [
        'key' => 'beyondary.storefront.edit',
        'name' => 'beyondary-storefront::app.acl.edit',
        'route' => [
            'admin.beyondary.storefront.sections.edit',
            'admin.beyondary.storefront.sections.update',
        ],
        'sort' => 2,
    ],
    [
        'key' => 'beyondary.storefront.export',
        'name' => 'beyondary-storefront::app.acl.export',
        'route' => 'admin.beyondary.storefront.export',
        'sort' => 3,
    ],
    [
        'key' => 'beyondary.storefront.import',
        'name' => 'beyondary-storefront::app.acl.import',
        'route' => [
            'admin.beyondary.storefront.import',
            'admin.beyondary.storefront.install-preset',
        ],
        'sort' => 4,
    ],
    [
        'key' => 'beyondary.admin_theme',
        'name' => 'beyondary-storefront::app.acl.admin_theme',
        'route' => [
            'admin.beyondary.admin-theme.index',
            'admin.beyondary.admin-theme.update',
            'admin.beyondary.admin-theme.settings.update',
        ],
        'sort' => 5,
    ],
];

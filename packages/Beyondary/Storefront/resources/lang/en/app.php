<?php

return [
    'menu' => [
        'beyondary' => 'Beyondary',
        'homepage' => 'Storefront',
    ],

    'acl' => [
        'beyondary' => 'Beyondary',
        'homepage' => 'Storefront Homepage',
        'edit' => 'Edit Storefront Sections',
        'export' => 'Export Storefront',
        'import' => 'Import Storefront',
    ],

    'home' => [
        'title' => 'Storefront Homepage',
        'subtitle' => 'Manage beyondary homepage sections for :channel (theme: :theme).',
        'preview' => 'Preview storefront',
        'active' => 'Active',
        'missing' => 'Not set',
        'edit-section' => 'Edit section',
        'footer-note' => 'Header menu and footer are edited below. CLI: export (ZIP), import, install-preset',
    ],

    'edit' => [
        'title' => 'Edit section',
        'subtitle' => 'Channel: :channel',
        'back' => 'Back',
        'save' => 'Save',
        'saved' => 'Section saved successfully.',
        'enabled' => 'Show on homepage',
        'enabled-layout' => 'Show on storefront',
    ],

    'sections' => [
        'hero' => 'Hero banner and slider images.',
        'trust' => 'Trust badges (shipping, authenticity, payment).',
        'categories' => 'Featured category grid.',
        'products' => 'Product carousel (title and filters).',
        'our_story' => 'Our Story block with image and copy.',
        'menu' => 'Announcement bar and main navigation links.',
        'footer' => 'About text, social links, and support column.',
    ],

    'forms' => [
        'common' => [
            'sort' => 'Sort order',
            'limit' => 'Item limit',
            'add_link' => 'Add link',
            'remove_link' => 'Remove',
        ],
        'hero' => [
            'help' => 'Upload one or more slides. Leave image empty to keep the current file.',
            'slide' => 'Slide :n',
            'title' => 'Headline',
            'link' => 'Link URL',
            'image' => 'Image',
        ],
        'trust' => [
            'help' => 'Up to three trust badges shown below the hero.',
            'badge' => 'Badge :n',
            'title' => 'Title',
            'description' => 'Description',
            'icon' => 'Icon',
        ],
        'categories' => [
            'title' => 'Section title',
        ],
        'products' => [
            'title' => 'Section title',
            'category_id' => 'Category ID (optional)',
            'category_help' => 'Leave empty to show products from all categories.',
        ],
        'our_story' => [
            'title' => 'Title',
            'highlight' => 'Highlighted phrase',
            'p1' => 'Paragraph 1',
            'p2' => 'Paragraph 2',
            'cta' => 'Button text',
            'cta_link' => 'Button link',
            'image' => 'Story image',
        ],
        'menu' => [
            'announcement' => 'Announcement bar text',
            'help' => 'Up to 5 main navigation links (desktop and mobile).',
            'link' => 'Link :n',
            'title' => 'Label',
            'url' => 'URL',
        ],
        'footer' => [
            'about' => 'About text',
            'facebook' => 'Facebook URL',
            'instagram' => 'Instagram URL',
            'pinterest' => 'Pinterest URL',
            'support_links' => 'Support links',
            'support_help' => 'Links shown in the Support column (column 2). Use Add link or Remove to change the list.',
            'link_row' => 'Link :n',
            'link_title' => 'Label',
            'link_url' => 'URL',
        ],
    ],

    'transfer' => [
        'export-zip' => 'Export ZIP',
        'export-json' => 'JSON only',
        'import' => 'Import',
        'import-title' => 'Import archive',
        'import-help' => 'Upload a ZIP export (includes theme images) or JSON for another channel / instance.',
        'preset-title' => 'Default preset',
        'preset-help' => 'Reset storefront to the built-in beyondary layout (homepage sections, menu, and footer).',
        'install-preset' => 'Install preset',
        'preset-installed' => 'Installed preset (:count sections).',
        'channel' => 'Target channel',
        'file' => 'ZIP or JSON file',
        'replace' => 'Replace existing homepage sections',
        'imported' => 'Imported :count section(s) successfully.',
    ],
];

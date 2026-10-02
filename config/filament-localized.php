<?php

return [
    'locales' => [
        'ar' => [
            'label' => 'العربية',
            'short' => 'AR',
            'direction' => 'rtl',
            'flag' => 'https://flagcdn.com/w40/dz.png',
        ],

        'fr' => [
            'label' => 'Français',
            'short' => 'FR',
            'direction' => 'ltr',
            'flag' => 'https://flagcdn.com/w40/fr.png',
        ],

        'en' => [
            'label' => 'English',
            'short' => 'EN',
            'direction' => 'ltr',
            'flag' => 'https://flagcdn.com/w40/gb.png',
        ],
    ],

    'default_locale' => 'fr',

    'fallback_locales' => [
        'fr',
        'en',
        'ar',
    ],

    'search_locales' => null,

    'locale_switcher' => [
        'enabled' => true,
        'show_flag' => true,
        'show_label' => true,
        'show_short' => false,
        'flag_fallback' => 'short',
    ],

    'locale_persistence' => [
        'session' => true,
        'user' => [
            'enabled' => false,
            'attribute' => 'locale',
        ],
        'browser' => [
            'enabled' => false,
        ],
    ],

];

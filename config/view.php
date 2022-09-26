<?php

return [

    'smarty' => [

        'pluginsDir' => [
            realpath(base_path('app/Smarty/Plugins'))
        ],

        'smarty' => [
            'debugging' => env('SMARTY_DEBUGGING', false),

            'caching' => env('SMARTY_CACHING', false),

            'cache_lifetime' => env('SMARTY_CACHE_LIFETIME', 120),

            'compile_check' => env('SMARTY_COMPILE_CHECK', false),

            'template_dir' => null,

            'config_dir' => null,

            'compile_dir' => null,

            'cache_dir' => null,
        ],

        'view' => [
            'composers' => [
                '*' => [
                    //'App\Smarty\Composers\AppComposer',
                    //'App\Smarty\Composers\AuthComposer',
                ],
            ],
        ],
    ],
];
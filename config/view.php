<?php

return [

    'smarty' => [

        'pluginsDir' => [
            realpath(base_path('app/Smarty/Plugins'))
        ],

        'smarty' => [
            'debugging' => env('SMARTY_DEBUGGING', true),

            'compile_check' => env('SMARTY_COMPILE_CHECK', true),

            'caching' => env('SMARTY_CACHING', false),

            'cache_lifetime' => env('SMARTY_CACHE_LIFETIME', 120),

            'template_dir' => realpath(resource_path('views/smarty')),

            'config_dir' => realpath(resource_path('views/smarty')),

            'compile_dir' => realpath(storage_path('smarty/compile')),

            'cache_dir' => realpath(storage_path('smarty/cache')),
        ],

        'view' => [
            'composers' => [
                '*' => [
                    \YusamHub\LaravelSmarty\LaravelSmartyDefaultComposer::class
                ],
            ],
        ],
    ],
];
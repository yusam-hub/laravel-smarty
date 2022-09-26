<?php

return [

    'smarty' => [
        'debugging' => env('SMARTY_DEBUGGING', true),

        'compile_check' => env('SMARTY_COMPILE_CHECK', true),

        'caching' => env('SMARTY_CACHING', false),

        'cache_lifetime' => env('SMARTY_CACHE_LIFETIME', 120),

        'template_dir' => realpath(resource_path('views')),

        'config_dir' => realpath(resource_path('views')),

        'compile_dir' => realpath(storage_path('framework/views')),

        'cache_dir' => realpath(storage_path('framework/cache')),
    ],

    'pluginsDir' => [
    ],

    'composers' => [
        '*' => [
            \YusamHub\LaravelSmarty\LaravelSmartyDefaultComposer::class
        ],
    ],
];
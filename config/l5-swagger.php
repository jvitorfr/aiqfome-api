<?php

return [

    'default' => 'default',

    'documentations' => [
        'default' => [
            'api' => [
                'title' => 'aiqfome API',
            ],

            'routes' => [
                /*
                 * Route for accessing api documentation interface
                 */
                'api' => 'api/documentation',

                /*
                 * Route for accessing parsed swagger annotations.
                 */
                'docs' => 'docs',

                /*
                 * Middleware allows to prevent unexpected access to API documentation
                 */
                'middleware' => [],
            ],

            'paths' => [
                /*
                 * Absolute path to location where parsed annotations will be stored
                 */
                'docs_json' => 'api-docs.json',
                'docs_yaml' => 'api-docs.yaml',
                'annotations' => base_path('app/Http/Controllers'),
            ],

            'generate_always' => true,

            'swagger_version' => '3.0',

            'constants' => [
                'L5_SWAGGER_CONST_HOST' => env('SWAGGER_BASE_URL', 'http://localhost:8080'),
            ],

            'info' => [
                'description' => 'Documentação da API RESTful para clientes e favoritos',
                'version' => '1.0.0',
                'title' => 'API aiqfome',
            ],

            'servers' => [
                [
                    'url' => env('SWAGGER_BASE_URL', 'http://localhost:8080'),
                    'description' => 'Localhost',
                ],
            ],
        ],
    ],

];

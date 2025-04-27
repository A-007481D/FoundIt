<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Scout Driver
    |--------------------------------------------------------------------------
    |
    | This value controls the default search connection that gets used while
    | using Laravel Scout. You should adjust this based on your search driver.
    |
    */
    'driver' => env('SCOUT_DRIVER', 'meilisearch'),

    /*
    |--------------------------------------------------------------------------
    | Index Prefix
    |--------------------------------------------------------------------------
    |
    | Here you may specify a string that will be prefixed to all search index
    | names used by Scout. This prefix may be useful if you have multiple
    | "tenants" or if you run multiple applications sharing the same server.
    |
    */
    'prefix' => env('SCOUT_PREFIX', ''),

    /*
    |--------------------------------------------------------------------------
    | Queue Data Syncing
    |--------------------------------------------------------------------------
    |
    | This option allows you to control if your data will be queued for sync
    | when using the database engine. Setting this to false will disable
    | job dispatching automatic. You are responsible for syncing.
    |
    */
    'queue' => env('SCOUT_QUEUE', false),

    /*
    |--------------------------------------------------------------------------
    | After Commit
    |--------------------------------------------------------------------------
    |
    | This option instructs Scout to only dispatch index operations after a
    | successful database transaction commit. This guarantees data integrity.
    |
    */
    'after_commit' => env('SCOUT_AFTER_COMMIT', false),

    /*
    |--------------------------------------------------------------------------
    | Chunk Sizes
    |--------------------------------------------------------------------------
    |
    | These options limit the maximum chunk size when mass importing and
    | removing data from the engine. You should adjust these based on your
    | search engine's capacity.
    |
    */
    'chunk' => [
        'searchable' => 500,
        'unsearchable' => 500,
    ],

    /*
    |--------------------------------------------------------------------------
    | Meilisearch Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Meilisearch settings, such as the host
    | and API key. These values will be read from the environment file.
    |
    */
    'meilisearch' => [
        'host' => env('MEILISEARCH_HOST', 'http://127.0.0.1:7700'),
        'key'  => env('MEILISEARCH_KEY', null),
    ],
];

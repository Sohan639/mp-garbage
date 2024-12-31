<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => public_path(),
            'url' => env('APP_URL'),
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'region' => env('REGION'),
            'key' => env('ACCESS_KEY'),
            'secret' => env('SECRET_KEY'),
            'bucket' => env('SPACES_NAME'),
            'url' => env('ENDPOINT'),
            'endpoint' => env('ENDPOINT'),
            'use_path_style_endpoint' => env('USE_PATH_STYLE_ENDPOINT', true),
        ],
        
        'do_spaces' => [     
            'driver' => 's3',     
            'key' => env('DO_SPACES_KEY'),     
            'secret' => env('DO_SPACES_SECRET'),     
            'endpoint' => env('DO_SPACES_ENDPOINT'),     
            'region' => env('DO_SPACES_REGION'),     
            'bucket' => env('DO_SPACES_BUCKET'), 
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];

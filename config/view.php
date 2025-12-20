<?php

return [

    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    |
    | Most templating systems load templates from disk. Here you may specify
    | an array of paths that should be checked for your views. Of course
    | the usual Laravel view path has already been registered for you.
    |
    */

    'paths' => [
        resource_path('views'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Compiled View Path
    |--------------------------------------------------------------------------
    |
    | This option determines where all the compiled Blade templates will be
    | stored for your application. Typically, this is within the storage
    | directory. However, as usual, you are free to change this value.
    |
    */

    'compiled' => (function() {
        // Check for custom path first
        if ($path = getenv('VIEW_COMPILED_PATH')) {
            return $path;
        }
        
        // Use /tmp for serverless environments (Vercel), otherwise use storage
        if (getenv('VERCEL') || getenv('LAMBDA_TASK_ROOT')) {
            return '/tmp/storage/framework/views';
        }
        
        return storage_path('framework/views');
    })(),

];


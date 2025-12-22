<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Parent Domain Configuration
    |--------------------------------------------------------------------------
    |
    | This is the parent domain that controls all subdomains.
    | Only this domain can access the admin panel.
    |
    */
    'parent_domain' => env('PARENT_DOMAIN', 'dental-admin.helioho.st'),
    
    /*
    |--------------------------------------------------------------------------
    | Base Domain
    |--------------------------------------------------------------------------
    |
    | The base domain for subdomains (e.g., helioho.st)
    |
    */
    'base_domain' => env('BASE_DOMAIN', 'helioho.st'),
];


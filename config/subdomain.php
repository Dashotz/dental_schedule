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
    
    /*
    |--------------------------------------------------------------------------
    | Subdomain Port
    |--------------------------------------------------------------------------
    |
    | The port number for accessing subdomains (e.g., 10000)
    | Subdomains will be accessible via http://127.0.0.1:10000?subdomain=clinic-name
    |
    */
    'subdomain_port' => env('SUBDOMAIN_PORT', 10000),
];


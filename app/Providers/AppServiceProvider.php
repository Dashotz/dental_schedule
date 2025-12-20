<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Ensure view cache directory exists in serverless environments
        if (getenv('VERCEL') || getenv('LAMBDA_TASK_ROOT')) {
            $viewPath = '/tmp/storage/framework/views';
            if (!is_dir($viewPath)) {
                @mkdir($viewPath, 0755, true);
            }
        } else {
            // Ensure storage directories exist in regular environments
            $paths = [
                storage_path('framework/views'),
                storage_path('framework/cache'),
                storage_path('framework/sessions'),
                storage_path('logs'),
            ];
            
            foreach ($paths as $path) {
                if (!is_dir($path)) {
                    @mkdir($path, 0755, true);
                }
            }
        }
    }
}

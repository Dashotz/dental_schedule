<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class MigrationController extends Controller
{
    /**
     * Run database migrations
     * 
     * This endpoint can be called to run migrations manually.
     * For security, you should protect this route or remove it after initial setup.
     * 
     * Usage: POST /api/migrate?token=YOUR_SECRET_TOKEN
     */
    public function migrate(Request $request)
    {
        // Optional: Add a secret token check for security
        $secretToken = env('MIGRATION_SECRET_TOKEN');
        if ($secretToken && $request->get('token') !== $secretToken) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'Invalid migration token'
            ], 401);
        }

        try {
            // Test database connection first
            DB::connection()->getPdo();
            
            // Run migrations
            Artisan::call('migrate', [
                '--force' => true,
                '--no-interaction' => true,
            ]);

            $output = Artisan::output();

            return response()->json([
                'success' => true,
                'message' => 'Migrations completed successfully',
                'output' => $output
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Migration failed',
                'message' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    /**
     * Check migration status
     */
    public function status()
    {
        try {
            // Test database connection
            DB::connection()->getPdo();
            
            // Get migration status
            Artisan::call('migrate:status');
            $output = Artisan::output();

            return response()->json([
                'success' => true,
                'status' => 'Database connected',
                'migrations' => $output
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Database connection failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}


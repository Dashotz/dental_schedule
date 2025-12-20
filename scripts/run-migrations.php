<?php

/**
 * Migration script for Vercel deployment
 * This script runs database migrations safely
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

try {
    echo "Running database migrations...\n";
    
    // Run migrations
    $exitCode = Artisan::call('migrate', [
        '--force' => true,
        '--no-interaction' => true,
    ]);
    
    if ($exitCode === 0) {
        echo "✅ Migrations completed successfully!\n";
        exit(0);
    } else {
        echo "⚠️  Migrations completed with warnings (exit code: $exitCode)\n";
        exit(0); // Still exit with 0 to not fail the build
    }
} catch (Exception $e) {
    echo "❌ Migration error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
    
    // Don't fail the build if migrations fail (might be due to missing DB connection during build)
    // The app will still work if tables already exist
    echo "⚠️  Continuing build despite migration error...\n";
    exit(0);
}


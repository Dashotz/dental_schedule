<?php

namespace App\Services;

use App\Models\Subdomain;
use Illuminate\Support\Facades\Log;

class SubdomainServerService
{
    /**
     * Start a Laravel server for a subdomain on its assigned port
     */
    public static function startServer(Subdomain $subdomain): bool
    {
        if (!$subdomain->port) {
            Log::error("Cannot start server: Subdomain {$subdomain->id} has no port assigned");
            return false;
        }

        $port = $subdomain->port;
        $subdomainName = $subdomain->subdomain;

        // Check if port is already in use
        if (self::isPortInUse($port)) {
            Log::warning("Port {$port} is already in use for subdomain {$subdomainName}");
            return false;
        }

        // Determine OS and start server accordingly
        $os = strtoupper(substr(PHP_OS, 0, 3));
        
        try {
            if ($os === 'WIN') {
                // Windows: Start server in new window using start command
                $command = sprintf(
                    'start "Laravel Server - Port %d (%s)" cmd /k "cd /d %s && php artisan serve --port=%d"',
                    $port,
                    $subdomainName,
                    escapeshellarg(base_path()),
                    $port
                );
                // Use popen for Windows
                pclose(popen($command, 'r'));
            } else {
                // Linux/Mac: Start server in background
                $command = sprintf(
                    'cd %s && php artisan serve --port=%d > /dev/null 2>&1 &',
                    escapeshellarg(base_path()),
                    $port
                );
                exec($command);
            }

            // Wait a moment to ensure server starts
            sleep(1);
            
            Log::info("Started server for subdomain {$subdomainName} on port {$port}");
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to start server for subdomain {$subdomainName}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if a port is already in use
     */
    public static function isPortInUse(int $port): bool
    {
        $connection = @fsockopen('127.0.0.1', $port, $errno, $errstr, 1);
        if ($connection) {
            fclose($connection);
            return true;
        }
        return false;
    }

    /**
     * Get the next available port starting from base port
     */
    public static function getNextAvailablePort(int $basePort = 10000, int $increment = 1000): int
    {
        $subdomains = Subdomain::whereNotNull('port')->orderBy('port', 'desc')->first();
        
        if ($subdomains && $subdomains->port) {
            // Get the highest port and add increment
            $nextPort = $subdomains->port + $increment;
        } else {
            // No subdomains with ports yet, use base port
            $nextPort = $basePort;
        }

        // Ensure port is not in use
        while (self::isPortInUse($nextPort)) {
            $nextPort += $increment;
        }

        return $nextPort;
    }
}


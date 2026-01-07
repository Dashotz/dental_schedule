<?php

namespace App\Console\Commands;

use App\Models\Subdomain;
use App\Services\SubdomainServerService;
use Illuminate\Console\Command;

class AssignPortsToSubdomains extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subdomains:assign-ports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign ports to existing subdomains that don\'t have ports assigned';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $subdomains = Subdomain::whereNull('port')->get();
        
        if ($subdomains->isEmpty()) {
            $this->info('All subdomains already have ports assigned.');
            return 0;
        }

        $this->info("Found {$subdomains->count()} subdomain(s) without ports. Assigning ports...");
        
        $basePort = 10000;
        $increment = 1000;
        $currentPort = Subdomain::whereNotNull('port')->max('port') ?? ($basePort - $increment);
        
        foreach ($subdomains as $subdomain) {
            $currentPort += $increment;
            
            // Ensure port is not in use
            while (SubdomainServerService::isPortInUse($currentPort)) {
                $currentPort += $increment;
            }
            
            $subdomain->port = $currentPort;
            $subdomain->save();
            
            $this->info("  ✓ Assigned port {$currentPort} to subdomain: {$subdomain->subdomain} ({$subdomain->name})");
        }
        
        $this->info("\n✓ All subdomains now have ports assigned!");
        return 0;
    }
}

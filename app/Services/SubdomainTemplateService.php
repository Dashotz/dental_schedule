<?php

namespace App\Services;

use App\Models\Subdomain;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class SubdomainTemplateService
{
    /**
     * Duplicate the subdomain-template folder for a new subdomain
     * Folder name will be: subdomain-{clinic_name} (sanitized)
     */
    public static function duplicateTemplate(Subdomain $subdomain): bool
    {
        $sourcePath = resource_path('views/subdomain-template');
        
        // Use clinic name for folder (sanitize: lowercase, replace spaces with hyphens, remove special chars)
        $clinicName = $subdomain->name ?? $subdomain->subdomain;
        $folderName = 'subdomain-' . preg_replace('/[^a-z0-9\-]/', '', strtolower(str_replace(' ', '-', $clinicName)));
        $targetPath = resource_path('views/' . $folderName);
        
        try {
            // Check if source exists
            if (!File::exists($sourcePath)) {
                Log::error("Source template folder does not exist: {$sourcePath}");
                return false;
            }
            
            // Check if target already exists
            if (File::exists($targetPath)) {
                Log::warning("Target folder already exists: {$targetPath}. Skipping duplication.");
                // Still update settings
                $settings = $subdomain->settings ?? [];
                $settings['template_folder'] = $folderName;
                $subdomain->settings = $settings;
                $subdomain->save();
                return true; // Consider it successful since folder exists
            }
            
            // Copy the entire directory recursively
            File::copyDirectory($sourcePath, $targetPath);
            
            Log::info("Duplicated subdomain template for: {$subdomain->subdomain} to {$targetPath}");
            
            // Update subdomain settings to store the folder name
            $settings = $subdomain->settings ?? [];
            $settings['template_folder'] = $folderName;
            $subdomain->settings = $settings;
            $subdomain->save();
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to duplicate template for subdomain {$subdomain->subdomain}: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get the view path prefix for a subdomain
     */
    public static function getViewPath(Subdomain $subdomain): string
    {
        $settings = $subdomain->settings ?? [];
        $folderName = $settings['template_folder'] ?? null;
        
        // If no folder name in settings, generate it from clinic name
        if (!$folderName) {
            $clinicName = $subdomain->name ?? $subdomain->subdomain;
            $folderName = 'subdomain-' . preg_replace('/[^a-z0-9\-]/', '', strtolower(str_replace(' ', '-', $clinicName)));
        }
        
        // Verify folder exists, fallback to subdomain-template if not
        $folderPath = resource_path('views/' . $folderName);
        if (!File::exists($folderPath)) {
            return 'subdomain-template';
        }
        
        return $folderName;
    }
    
    /**
     * Delete the subdomain template folder when subdomain is deleted
     */
    public static function deleteTemplate(Subdomain $subdomain): bool
    {
        $settings = $subdomain->settings ?? [];
        $folderName = $settings['template_folder'] ?? null;
        
        // If no folder name in settings, generate it from clinic name
        if (!$folderName) {
            $clinicName = $subdomain->name ?? $subdomain->subdomain;
            $folderName = 'subdomain-' . preg_replace('/[^a-z0-9\-]/', '', strtolower(str_replace(' ', '-', $clinicName)));
        }
        
        $targetPath = resource_path('views/' . $folderName);
        
        try {
            if (File::exists($targetPath)) {
                File::deleteDirectory($targetPath);
                Log::info("Deleted subdomain template folder: {$targetPath}");
                return true;
            }
            return true; // Already deleted or doesn't exist
        } catch (\Exception $e) {
            Log::error("Failed to delete template folder for subdomain {$subdomain->subdomain}: " . $e->getMessage());
            return false;
        }
    }
}


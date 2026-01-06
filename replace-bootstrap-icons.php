<?php

/**
 * Script to replace Bootstrap Icons with <x-dental-icon> components
 * 
 * Usage: php replace-bootstrap-icons.php
 */

$viewsPath = __DIR__ . '/resources/views';
$filesProcessed = 0;
$replacementsMade = 0;

// Icon name mappings (Bootstrap Icon name => Heroicon name)
$iconMap = [
    'speedometer2' => 'speedometer2',
    'dashboard' => 'speedometer2',
    'chevron-left' => 'chevron-left',
    'chevron-right' => 'chevron-right',
    'chevron-down' => 'chevron-down',
    'chevron-up' => 'chevron-up',
    'list' => 'list',
    'x' => 'x',
    'x-circle' => 'x-circle',
    'check' => 'check',
    'check-circle' => 'check-circle',
    'plus' => 'plus',
    'plus-circle' => 'plus-circle',
    'minus' => 'minus',
    'arrow-left' => 'arrow-left',
    'arrow-right' => 'arrow-right',
    'arrow-up' => 'arrow-up',
    'arrow-down' => 'arrow-down',
    'people' => 'people',
    'person' => 'person',
    'person-circle' => 'person-circle',
    'person-fill' => 'person',
    'calendar' => 'calendar',
    'calendar-check' => 'calendar-check',
    'calendar3' => 'calendar3',
    'calendar-x' => 'calendar-x',
    'calendar-range' => 'calendar-range',
    'calendar-day' => 'calendar-check',
    'calendar-week' => 'calendar',
    'clock' => 'clock',
    'clock-history' => 'clock-history',
    'tooth' => 'tooth',
    'teeth' => 'tooth',
    'heart-pulse' => 'heart-pulse',
    'credit-card' => 'credit-card',
    'currency-dollar' => 'currency-dollar',
    'building' => 'building',
    'envelope' => 'envelope',
    'telephone' => 'telephone',
    'phone' => 'phone',
    'geo-alt' => 'geo-alt',
    'globe' => 'globe',
    'file-text' => 'file-text',
    'file' => 'file',
    'folder' => 'folder',
    'pencil' => 'pencil',
    'trash' => 'trash',
    'eye' => 'eye',
    'search' => 'search',
    'filter' => 'filter',
    'download' => 'download',
    'upload' => 'upload',
    'print' => 'print',
    'save' => 'save',
    'info-circle' => 'info-circle',
    'exclamation-triangle' => 'exclamation-triangle',
    'question-circle' => 'question-circle',
    'check-circle-fill' => 'check-circle',
    'x-circle-fill' => 'x-circle',
    'bar-chart' => 'bar-chart',
    'pie-chart' => 'pie-chart',
    'graph-up' => 'graph-up',
    'graph-up-arrow' => 'graph-up',
    'link-45deg' => 'link-45deg',
    'clipboard' => 'clipboard',
    'copy' => 'copy',
    'gear' => 'gear',
    'settings' => 'settings',
    'toggle-on' => 'toggle-on',
    'toggle-off' => 'toggle-off',
    'inbox' => 'inbox',
    'star' => 'star',
    'star-fill' => 'star',
    'trophy' => 'trophy',
    'lightning-charge' => 'lightning-charge',
    'box-arrow-right' => 'box-arrow-right',
    'diagram-3' => 'diagram-3',
    'list-ul' => 'list-ul',
    'grid' => 'grid',
    'hourglass-split' => 'clock',
    'arrow-clockwise' => 'arrow-path',
    'shield-check' => 'shield-check',
    'person-exclamation' => 'person',
    'clipboard-pulse' => 'clipboard',
];

/**
 * Recursively get all Blade files
 */
function getBladeFiles($dir) {
    $files = [];
    
    if (!is_dir($dir)) {
        echo "Warning: Directory does not exist: {$dir}\n";
        return $files;
    }
    
    $items = scandir($dir);
    
    if ($items === false) {
        echo "Warning: Could not scan directory: {$dir}\n";
        return $files;
    }
    
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        
        $path = $dir . DIRECTORY_SEPARATOR . $item;
        
        if (is_dir($path)) {
            $files = array_merge($files, getBladeFiles($path));
        } elseif (pathinfo($path, PATHINFO_EXTENSION) === 'php' && strpos($item, '.blade.php') !== false) {
            $files[] = $path;
        }
    }
    
    return $files;
}

/**
 * Extract icon name from Bootstrap Icon class
 */
function extractIconName($classString) {
    // Match bi bi-{iconname} pattern
    if (preg_match('/bi\s+bi-([a-z0-9-]+)/i', $classString, $matches)) {
        return $matches[1];
    }
    return null;
}

/**
 * Extract classes from icon element
 */
function extractClasses($iconElement) {
    $classes = [];
    
    // Extract class attribute
    if (preg_match('/class=["\']([^"\']+)["\']/', $iconElement, $matches)) {
        $classString = $matches[1];
        $classParts = explode(' ', $classString);
        
        foreach ($classParts as $part) {
            $part = trim($part);
            if (!empty($part) && !preg_match('/^bi\s|^bi-/', $part)) {
                $classes[] = $part;
            }
        }
    }
    
    return $classes;
}

/**
 * Convert Bootstrap Icon to <x-dental-icon>
 */
function convertIcon($iconElement, $iconName, $iconMap) {
    // Get mapped icon name
    $mappedName = $iconMap[$iconName] ?? $iconName;
    
    // Extract existing classes (excluding Bootstrap Icon classes)
    $classes = extractClasses($iconElement);
    
    // Determine icon size based on common patterns
    $sizeClass = 'w-5 h-5'; // default
    
    if (preg_match('/text-(xs|sm|lg|xl|2xl|3xl|4xl|5xl|6xl)/', $iconElement)) {
        // Size based on text size
        if (preg_match('/text-6xl/', $iconElement)) {
            $sizeClass = 'w-16 h-16';
        } elseif (preg_match('/text-5xl/', $iconElement)) {
            $sizeClass = 'w-12 h-12';
        } elseif (preg_match('/text-4xl/', $iconElement)) {
            $sizeClass = 'w-10 h-10';
        } elseif (preg_match('/text-3xl/', $iconElement)) {
            $sizeClass = 'w-8 h-8';
        } elseif (preg_match('/text-2xl/', $iconElement)) {
            $sizeClass = 'w-6 h-6';
        } elseif (preg_match('/text-xl/', $iconElement)) {
            $sizeClass = 'w-5 h-5';
        } elseif (preg_match('/text-sm/', $iconElement)) {
            $sizeClass = 'w-4 h-4';
        } elseif (preg_match('/text-xs/', $iconElement)) {
            $sizeClass = 'w-3 h-3';
        }
    }
    
    // Preserve color classes
    $colorClasses = [];
    foreach ($classes as $class) {
        if (preg_match('/^(text-|bg-)/', $class)) {
            $colorClasses[] = $class;
        }
    }
    
    // Build class string
    $finalClasses = array_merge([$sizeClass], $colorClasses);
    $classString = implode(' ', array_unique($finalClasses));
    
    // Check if inline
    $inline = strpos($iconElement, 'inline') !== false || strpos($iconElement, 'mr-') !== false || strpos($iconElement, 'ml-') !== false;
    if ($inline) {
        $classString .= ' inline';
    }
    
    return "<x-dental-icon name=\"{$mappedName}\" class=\"{$classString}\" />";
}

/**
 * Process a single file
 */
function processFile($filePath, $iconMap) {
    $content = file_get_contents($filePath);
    $originalContent = $content;
    $replacements = 0;
    
    // Pattern 1: <i class="bi bi-{icon} ..."></i>
    $pattern1 = '/<i\s+class=["\']([^"\']*bi\s+bi-[a-z0-9-]+[^"\']*)["\'][^>]*><\/i>/i';
    $content = preg_replace_callback($pattern1, function($matches) use ($iconMap, &$replacements) {
        $iconElement = $matches[0];
        $classString = $matches[1];
        $iconName = extractIconName($classString);
        
        if ($iconName) {
            $replacements++;
            return convertIcon($iconElement, $iconName, $iconMap);
        }
        
        return $iconElement;
    }, $content);
    
    // Pattern 2: <i class="bi bi-{icon} ..."></i> with content (shouldn't happen but handle it)
    $pattern2 = '/<i\s+class=["\']([^"\']*bi\s+bi-[a-z0-9-]+[^"\']*)["\'][^>]*>.*?<\/i>/i';
    $content = preg_replace_callback($pattern2, function($matches) use ($iconMap, &$replacements) {
        $iconElement = $matches[0];
        $classString = $matches[1];
        $iconName = extractIconName($classString);
        
        if ($iconName && !preg_match('/<i[^>]*>.*[^<].*<\/i>/', $iconElement)) {
            $replacements++;
            return convertIcon($iconElement, $iconName, $iconMap);
        }
        
        return $iconElement;
    }, $content);
    
    // Pattern 3: Dynamic icons like bi-{{ variable }}
    $pattern3 = '/<i\s+class=["\']([^"\']*bi\s+bi-{{[^}]+}}[^"\']*)["\'][^>]*><\/i>/i';
    $content = preg_replace_callback($pattern3, function($matches) use ($iconMap, &$replacements) {
        $iconElement = $matches[0];
        $classString = $matches[1];
        
        // Extract the variable part
        if (preg_match('/bi-{{([^}]+)}}/', $classString, $varMatch)) {
            $replacements++;
            $varName = trim($varMatch[1]);
            $classes = extractClasses($iconElement);
            $classString = implode(' ', array_merge(['w-4 h-4'], $classes));
            return "<x-dental-icon name=\"{$varName}\" class=\"{$classString}\" />";
        }
        
        return $iconElement;
    }, $content);
    
    // Only write if changes were made
    if ($content !== $originalContent) {
        file_put_contents($filePath, $content);
        return $replacements;
    }
    
    return 0;
}

// Main execution
echo "Starting Bootstrap Icon replacement...\n\n";

// Normalize path
$viewsPath = realpath($viewsPath);
if (!$viewsPath) {
    $viewsPath = __DIR__ . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views';
    $viewsPath = realpath($viewsPath);
}

if (!$viewsPath) {
    die("Error: Could not find resources/views directory. Current directory: " . __DIR__ . "\n");
}

echo "Scanning directory: {$viewsPath}\n\n";

$files = getBladeFiles($viewsPath);

echo "Found " . count($files) . " Blade files\n\n";

foreach ($files as $file) {
    $relativePath = str_replace(__DIR__ . '/', '', $file);
    $replacements = processFile($file, $iconMap);
    
    if ($replacements > 0) {
        $filesProcessed++;
        $replacementsMade += $replacements;
        echo "✓ Processed: {$relativePath} ({$replacements} replacements)\n";
    }
}

echo "\n";
echo "========================================\n";
echo "Replacement Complete!\n";
echo "Files processed: {$filesProcessed}\n";
echo "Total replacements: {$replacementsMade}\n";
echo "========================================\n";
echo "\n";
echo "Note: Please review the changes and test your application.\n";
echo "Some icons may need manual adjustment, especially:\n";
echo "- Icons with dynamic names (using Blade variables)\n";
echo "- Icons with custom sizes or positioning\n";
echo "- Icons inside JavaScript strings\n";


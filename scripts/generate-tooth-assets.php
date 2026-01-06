<?php

/**
 * Generate individual SVG assets for each tooth
 * Universal Numbering System: 11-18, 21-28 (upper), 31-38, 41-48 (lower)
 */

$teethDir = __DIR__ . '/../public/images/dental/teeth';

// Ensure directory exists
if (!is_dir($teethDir)) {
    mkdir($teethDir, 0755, true);
}

// Define tooth types and their numbers
$toothTypes = [
    // Upper Right (11-18)
    'incisor' => [11, 12, 21, 22, 31, 32, 41, 42],      // Central and Lateral Incisors
    'canine' => [13, 23, 33, 43],                        // Canines
    'premolar' => [14, 15, 24, 25, 34, 35, 44, 45],     // First and Second Premolars
    'molar' => [16, 17, 18, 26, 27, 28, 36, 37, 38, 46, 47, 48], // Molars
];

// Function to generate incisor SVG
function generateIncisor($number) {
    return <<<SVG
<svg width="60" height="70" viewBox="0 0 60 70" xmlns="http://www.w3.org/2000/svg">
    <!-- Crown -->
    <path d="M 15 5 L 45 5 Q 50 5 50 10 L 50 30 Q 50 35 45 35 L 15 35 Q 10 35 10 30 L 10 10 Q 10 5 15 5 Z" 
          fill="currentColor" stroke="none"/>
    <!-- Root -->
    <path d="M 25 35 L 25 60 L 35 60 L 35 35 Z" 
          fill="currentColor" stroke="none" opacity="0.8"/>
</svg>
SVG;
}

// Function to generate canine SVG
function generateCanine($number) {
    return <<<SVG
<svg width="60" height="70" viewBox="0 0 60 70" xmlns="http://www.w3.org/2000/svg">
    <!-- Crown (pointed) -->
    <path d="M 30 5 L 50 5 Q 50 5 50 10 L 50 25 Q 50 30 45 30 L 15 30 Q 10 30 10 25 L 10 10 Q 10 5 15 5 Z" 
          fill="currentColor" stroke="none"/>
    <!-- Point -->
    <path d="M 30 5 L 35 15 L 30 20 L 25 15 Z" 
          fill="currentColor" stroke="none"/>
    <!-- Root -->
    <path d="M 25 30 L 25 60 L 35 60 L 35 30 Z" 
          fill="currentColor" stroke="none" opacity="0.8"/>
</svg>
SVG;
}

// Function to generate premolar SVG
function generatePremolar($number) {
    return <<<SVG
<svg width="60" height="70" viewBox="0 0 60 70" xmlns="http://www.w3.org/2000/svg">
    <!-- Crown (rounded with cusps) -->
    <path d="M 10 5 Q 5 5 5 10 L 5 25 Q 5 30 10 30 L 50 30 Q 55 30 55 25 L 55 10 Q 55 5 50 5 Q 45 5 40 8 Q 35 5 30 8 Q 25 5 20 8 Q 15 5 10 5 Z" 
          fill="currentColor" stroke="none"/>
    <!-- Cusps -->
    <circle cx="20" cy="15" r="3" fill="currentColor" opacity="0.9"/>
    <circle cx="30" cy="12" r="3" fill="currentColor" opacity="0.9"/>
    <circle cx="40" cy="15" r="3" fill="currentColor" opacity="0.9"/>
    <!-- Root -->
    <path d="M 20 30 L 20 60 L 40 60 L 40 30 Z" 
          fill="currentColor" stroke="none" opacity="0.8"/>
</svg>
SVG;
}

// Function to generate molar SVG
function generateMolar($number) {
    return <<<SVG
<svg width="60" height="70" viewBox="0 0 60 70" xmlns="http://www.w3.org/2000/svg">
    <!-- Crown (wider with multiple cusps) -->
    <path d="M 5 5 Q 0 5 0 10 L 0 25 Q 0 30 5 30 L 55 30 Q 60 30 60 25 L 60 10 Q 60 5 55 5 Q 50 5 45 8 Q 40 5 35 8 Q 30 5 25 8 Q 20 5 15 8 Q 10 5 5 5 Z" 
          fill="currentColor" stroke="none"/>
    <!-- Multiple cusps -->
    <circle cx="12" cy="15" r="3" fill="currentColor" opacity="0.9"/>
    <circle cx="25" cy="12" r="3" fill="currentColor" opacity="0.9"/>
    <circle cx="35" cy="12" r="3" fill="currentColor" opacity="0.9"/>
    <circle cx="48" cy="15" r="3" fill="currentColor" opacity="0.9"/>
    <!-- Roots (two) -->
    <path d="M 15 30 L 15 60 L 22 60 L 22 30 Z" 
          fill="currentColor" stroke="none" opacity="0.8"/>
    <path d="M 38 30 L 38 60 L 45 60 L 45 30 Z" 
          fill="currentColor" stroke="none" opacity="0.8"/>
</svg>
SVG;
}

// Generate SVG files for each tooth
foreach ($toothTypes as $type => $numbers) {
    foreach ($numbers as $number) {
        $filename = $teethDir . '/tooth-' . $number . '.svg';
        
        switch ($type) {
            case 'incisor':
                $svg = generateIncisor($number);
                break;
            case 'canine':
                $svg = generateCanine($number);
                break;
            case 'premolar':
                $svg = generatePremolar($number);
                break;
            case 'molar':
                $svg = generateMolar($number);
                break;
            default:
                $svg = generateIncisor($number);
        }
        
        file_put_contents($filename, $svg);
        echo "Generated: tooth-{$number}.svg\n";
    }
}

echo "\nAll tooth assets generated successfully!\n";
echo "Total files: " . count(glob($teethDir . '/tooth-*.svg')) . "\n";


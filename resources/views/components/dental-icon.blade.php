{{-- Force recompile: 2025-01-27-14:30:00 - Fixed droplet icon mapping --}}
@props([
    'name' => '',
    'class' => 'w-5 h-5',
    'solid' => false,
])

@php
    // Map Bootstrap Icon names to Heroicons
    $iconMap = [
        'speedometer2' => 'squares-2x2',
        'dashboard' => 'squares-2x2',
        'chevron-left' => 'chevron-left',
        'chevron-right' => 'chevron-right',
        'chevron-down' => 'chevron-down',
        'chevron-up' => 'chevron-up',
        'list' => 'bars-3',
        'x' => 'x-mark',
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
        'arrow-up-circle' => 'arrow-trending-up',
        'people' => 'users',
        'person' => 'user',
        'person-circle' => 'user-circle',
        'person-fill' => 'user',
        'person-gear' => 'cog-6-tooth',
        'calendar' => 'calendar',
        'calendar-check' => 'calendar-days',
        'calendar3' => 'calendar',
        'calendar-x' => 'calendar-days',
        'calendar-range' => 'calendar',
        'calendar-month' => 'calendar-days',
        'clock' => 'clock',
        'clock-history' => 'clock',
        // Dental/Medical - using heart as medical symbol (tooth icon not available in Heroicons)
        'tooth' => 'heart',
        'heart-pulse' => 'heart',
        'credit-card' => 'credit-card',
        'currency-dollar' => 'currency-dollar',
        'building' => 'building-office',
        'envelope' => 'envelope',
        'telephone' => 'phone',
        'phone' => 'phone',
        'geo-alt' => 'map-pin',
        'globe' => 'globe-alt',
        'file-text' => 'document-text',
        'file' => 'document',
        'folder' => 'folder',
        'pencil' => 'pencil',
        'trash' => 'trash',
        'eye' => 'eye',
        'search' => 'magnifying-glass',
        'filter' => 'funnel',
        'download' => 'arrow-down-tray',
        'upload' => 'arrow-up-tray',
        'print' => 'printer',
        'save' => 'bookmark',
        'info-circle' => 'information-circle',
        'exclamation-triangle' => 'exclamation-triangle',
        'question-circle' => 'question-mark-circle',
        'check-circle-fill' => 'check-circle',
        'x-circle-fill' => 'x-circle',
        'bar-chart' => 'chart-bar',
        'pie-chart' => 'chart-pie',
        'graph-up' => 'arrow-trending-up',
        'graph-up-arrow' => 'arrow-trending-up',
        'link-45deg' => 'link',
        'clipboard' => 'clipboard',
        'copy' => 'clipboard-document',
        'gear' => 'cog-6-tooth',
        'settings' => 'cog-6-tooth',
        'toggle-on' => 'check-circle',
        'toggle-off' => 'x-circle',
        'inbox' => 'inbox',
        'star' => 'star',
        'star-fill' => 'star',
        'trophy' => 'trophy',
        'lightning-charge' => 'bolt',
        'box-arrow-right' => 'arrow-right-on-rectangle',
        'box-arrow-in-right' => 'arrow-right-on-rectangle',
        'diagram-3' => 'squares-plus',
        'list-ul' => 'list-bullet',
        // Additional icons for welcome page
        'droplet' => 'wrench-screwdriver', // Changed from sparkles - using wrench-screwdriver instead
        'wrench-screwdriver' => 'wrench-screwdriver',
        'shield-check' => 'shield-check',
        'camera' => 'camera',
        'stars' => 'sparkles',
        'emoji-smile' => 'face-smile',
        'hospital' => 'building-office-2',
        'brush' => 'paint-brush',
        'clipboard-check' => 'clipboard-document-check',
        'cpu' => 'cpu-chip',
        'grid-3x3' => 'squares-2x2',
        'grid' => 'squares-2x2',
        'cash-coin' => 'banknotes',
        'heart' => 'heart',
        'geo-alt-fill' => 'map-pin',
        'info-circle-fill' => 'information-circle',
        'facebook' => 'globe-alt',
        'twitter' => 'globe-alt',
        'instagram' => 'globe-alt',
        'linkedin' => 'globe-alt',
    ];

    // Get the mapped Heroicon name - ensure we always get a valid name
    if (isset($iconMap[$name]) && $iconMap[$name] !== null) {
        $heroiconName = $iconMap[$name];
    } elseif (!empty($name)) {
        $heroiconName = $name;
    } else {
        $heroiconName = 'sparkles'; // Fallback for empty names
    }
    
    // blade-heroicons format: heroicon-o-{name} or heroicon-s-{name}
    $iconSet = $solid ? 'heroicon-s' : 'heroicon-o';
    $iconIdentifier = $iconSet . '-' . $heroiconName;
    
    // Debug logging to trace icon resolution (only in debug)
    if (config('app.debug')) {
        \Log::debug('dental-icon render', [
            'requested_name' => $name,
            'mapped_name' => $heroiconName,
            'icon_set' => $iconSet,
            'icon_identifier' => $iconIdentifier,
        ]);
    }
    
@endphp

{{-- Render the icon using the @svg directive --}}
@svg($iconIdentifier, $class)


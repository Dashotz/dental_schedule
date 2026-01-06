@props([
    'name' => '',
    'class' => 'w-5 h-5',
    'solid' => false,
])

@php
    // Map Bootstrap Icons to Heroicons
    $iconMap = [
        // Navigation & UI
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
        
        // People & Users
        'people' => 'users',
        'person' => 'user',
        'person-circle' => 'user-circle',
        'person-fill' => 'user',
        
        // Calendar & Time
        'calendar' => 'calendar',
        'calendar-check' => 'calendar-days',
        'calendar3' => 'calendar',
        'calendar-x' => 'calendar-days',
        'calendar-range' => 'calendar',
        'clock' => 'clock',
        'clock-history' => 'clock',
        
        // Medical & Dental - using sparkles as a placeholder for tooth
        'tooth' => 'sparkles',
        'heart-pulse' => 'heart',
        
        // Business & Finance
        'credit-card' => 'credit-card',
        'currency-dollar' => 'currency-dollar',
        'building' => 'building-office',
        
        // Communication
        'envelope' => 'envelope',
        'telephone' => 'phone',
        'phone' => 'phone',
        
        // Location
        'geo-alt' => 'map-pin',
        'globe' => 'globe-alt',
        
        // Files & Documents
        'file-text' => 'document-text',
        'file' => 'document',
        'folder' => 'folder',
        
        // Actions
        'pencil' => 'pencil',
        'trash' => 'trash',
        'eye' => 'eye',
        'search' => 'magnifying-glass',
        'filter' => 'funnel',
        'download' => 'arrow-down-tray',
        'upload' => 'arrow-up-tray',
        'print' => 'printer',
        'save' => 'bookmark',
        
        // Status & Info
        'info-circle' => 'information-circle',
        'exclamation-triangle' => 'exclamation-triangle',
        'question-circle' => 'question-mark-circle',
        'check-circle-fill' => 'check-circle',
        'x-circle-fill' => 'x-circle',
        
        // Charts & Analytics
        'bar-chart' => 'chart-bar',
        'pie-chart' => 'chart-pie',
        'graph-up' => 'arrow-trending-up',
        'graph-up-arrow' => 'arrow-trending-up',
        
        // Links & Connections
        'link-45deg' => 'link',
        'clipboard' => 'clipboard',
        'copy' => 'clipboard-document',
        
        // Settings & Tools
        'gear' => 'cog-6-tooth',
        'settings' => 'cog-6-tooth',
        'toggle-on' => 'toggle-right',
        'toggle-off' => 'toggle-left',
        
        // Other
        'inbox' => 'inbox',
        'star' => 'star',
        'star-fill' => 'star',
        'trophy' => 'trophy',
        'lightning-charge' => 'bolt',
        'box-arrow-right' => 'arrow-right-on-rectangle',
        'diagram-3' => 'squares-plus',
        'list-ul' => 'list-bullet',
    ];
    
    // Get the mapped Heroicon name - ensure we always get a valid name
    $heroiconName = isset($iconMap[$name]) ? $iconMap[$name] : $name;
    
    // blade-heroicons format: heroicon-o-{name} or heroicon-s-{name}
    $iconSet = $solid ? 'heroicon-s' : 'heroicon-o';
    $iconIdentifier = $iconSet . '-' . $heroiconName;
@endphp

{{-- Render the icon using the mapped identifier --}}
@svg($iconIdentifier, $class)

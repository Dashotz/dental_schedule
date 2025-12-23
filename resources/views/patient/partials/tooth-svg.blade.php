@php
    $record = null;
    if (isset($teethRecords) && is_object($teethRecords) && method_exists($teethRecords, 'get')) {
        $record = $teethRecords->get($toothNumber);
    } elseif (isset($teethRecords) && is_array($teethRecords)) {
        $toothKey = (string)$toothNumber;
        if (array_key_exists($toothKey, $teethRecords)) {
            $record = $teethRecords[$toothKey];
        }
    }
    $condition = ($record && is_object($record) && property_exists($record, 'condition')) ? $record->condition : '';
    $hasRecord = $record !== null;
    $classes = 'tooth-svg';
    if ($condition) {
        $classes .= ' ' . $condition;
    }
    if ($hasRecord) {
        $classes .= ' has-record';
    }
    $title = 'Tooth #' . $toothNumber;
    if ($record && $condition) {
        $title .= ' - ' . ucfirst(str_replace('_', ' ', $condition));
    }
    if ($record && $record->remarks) {
        $title .= ' - ' . \Illuminate\Support\Str::limit($record->remarks, 50);
    }
    
    // Determine tooth type and position
    $isMolar = in_array($toothNumber, [18,17,16,15,25,26,27,28,48,47,46,45,35,36,37,38]);
    $isIncisor = in_array($toothNumber, [11,12,21,22,31,32,41,42]);
    $isCanine = in_array($toothNumber, [13,23,33,43]);
    $isPremolar = in_array($toothNumber, [14,24,34,44]);
    
    // Color based on condition
    $fillColor = '#ffffff';
    $strokeColor = '#333333';
    if ($condition === 'healthy') {
        $fillColor = '#d4edda';
        $strokeColor = '#28a745';
    } elseif ($condition === 'cavity') {
        $fillColor = '#f8d7da';
        $strokeColor = '#dc3545';
    } elseif ($condition === 'filling') {
        $fillColor = '#cfe2ff';
        $strokeColor = '#0d6efd';
    } elseif ($condition === 'crown') {
        $fillColor = '#e7d4f8';
        $strokeColor = '#6f42c1';
    } elseif ($condition === 'extracted' || $condition === 'missing') {
        $fillColor = '#6c757d';
        $strokeColor = '#495057';
    } elseif ($condition === 'impacted') {
        $fillColor = '#ffebcd';
        $strokeColor = '#ff8c00';
    } elseif ($condition === 'root_canal') {
        $fillColor = '#d1ecf1';
        $strokeColor = '#17a2b8';
    } elseif ($hasRecord) {
        $fillColor = '#fff3cd';
        $strokeColor = '#ffc107';
    }
    
    $opacity = ($condition === 'extracted' || $condition === 'missing') ? '0.5' : '1';
@endphp

<g class="{{ $classes }}" data-tooth="{{ $toothNumber }}" transform="translate({{ $x }}, {{ $y }})" style="cursor: pointer;">
    <title>{{ $title }}</title>
    
    @if($isMolar)
        <!-- Molar icon - simple square with X mark -->
        <g transform="translate(-12, -14)">
            <rect x="0" y="0" width="24" height="28" rx="3" ry="3" 
                  fill="{{ $fillColor }}" 
                  stroke="{{ $strokeColor }}" 
                  stroke-width="2"
                  opacity="{{ $opacity }}"/>
            <path d="M 4,8 L 20,20 M 20,8 L 4,20" 
                  stroke="{{ $strokeColor }}" 
                  stroke-width="1.5"
                  opacity="{{ $opacity }}"/>
        </g>
    @elseif($isIncisor)
        <!-- Incisor icon - simple rectangle -->
        <g transform="translate(-8, -14)">
            <rect x="0" y="0" width="16" height="28" rx="2" ry="2" 
                  fill="{{ $fillColor }}" 
                  stroke="{{ $strokeColor }}" 
                  stroke-width="2"
                  opacity="{{ $opacity }}"/>
        </g>
    @elseif($isCanine)
        <!-- Canine icon - pointed shape -->
        <g transform="translate(-8, -14)">
            <path d="M 8,0 L 0,6 L 0,22 L 8,28 L 16,22 L 16,6 Z" 
                  fill="{{ $fillColor }}" 
                  stroke="{{ $strokeColor }}" 
                  stroke-width="2"
                  opacity="{{ $opacity }}"/>
        </g>
    @elseif($isPremolar)
        <!-- Premolar icon - rounded square with X mark -->
        <g transform="translate(-10, -14)">
            <rect x="0" y="0" width="20" height="28" rx="3" ry="3" 
                  fill="{{ $fillColor }}" 
                  stroke="{{ $strokeColor }}" 
                  stroke-width="2"
                  opacity="{{ $opacity }}"/>
            <path d="M 4,8 L 16,20 M 16,8 L 4,20" 
                  stroke="{{ $strokeColor }}" 
                  stroke-width="1.5"
                  opacity="{{ $opacity }}"/>
        </g>
    @endif
    
    <!-- Tooth number badge -->
    <circle cx="0" cy="-2" r="8" fill="#dc3545" stroke="#fff" stroke-width="1.5"/>
    <text x="0" y="0" text-anchor="middle" dominant-baseline="central" 
          font-size="9" font-weight="bold" 
          fill="#ffffff"
          style="pointer-events: none;">
        {{ $toothNumber }}
    </text>
    
    <!-- Badge indicator for records -->
    @if($hasRecord)
        <circle cx="12" cy="-10" r="4" fill="#ffc107" stroke="#fff" stroke-width="1.5"/>
    @endif
</g>

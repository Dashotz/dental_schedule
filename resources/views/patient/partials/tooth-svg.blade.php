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

<g class="{{ $classes }}" data-tooth="{{ $toothNumber }}" transform="translate({{ $x }}, {{ $y }}) scale(0.9)" style="cursor: pointer;">
    <title>{{ $title }}</title>
    
    @if($isMolar)
        <!-- Molar SVG - larger, multi-cusped tooth with realistic shape -->
        <g transform="translate(-15, -14)">
            <!-- Main body -->
            <path d="M 0,0 
                     C 3,0 5,1 6,3
                     L 6,9
                     C 6,11 5,12 3,12
                     L 0,12
                     C -2,12 -3,11 -3,9
                     L -3,3
                     C -2,1 0,0 0,0 Z" 
                  fill="{{ $fillColor }}" 
                  stroke="{{ $strokeColor }}" 
                  stroke-width="1.5"
                  opacity="{{ $opacity }}"/>
            <!-- Second cusp -->
            <path d="M 6,3 
                     C 7,1 9,0 12,0
                     L 15,0
                     C 17,0 18,1 18,3
                     L 18,9
                     C 18,11 17,12 15,12
                     L 12,12
                     C 10,12 9,11 9,9
                     L 9,3
                     C 8,2 7,2.5 6,3 Z" 
                  fill="{{ $fillColor }}" 
                  stroke="{{ $strokeColor }}" 
                  stroke-width="1.5"
                  opacity="{{ $opacity }}"/>
            <!-- Third cusp (back) -->
            <path d="M 0,12 
                     C 1,12 2,13 2,14
                     L 2,16
                     C 2,17 1,18 0,18
                     L -2,18
                     C -3,18 -4,17 -4,16
                     L -4,14
                     C -4,13 -3,12 -2,12
                     Z" 
                  fill="{{ $fillColor }}" 
                  stroke="{{ $strokeColor }}" 
                  stroke-width="1.5"
                  opacity="{{ $opacity }}"/>
            <path d="M 15,12 
                     C 16,12 17,13 17,14
                     L 17,16
                     C 17,17 16,18 15,18
                     L 13,18
                     C 12,18 11,17 11,16
                     L 11,14
                     C 11,13 12,12 13,12
                     Z" 
                  fill="{{ $fillColor }}" 
                  stroke="{{ $strokeColor }}" 
                  stroke-width="1.5"
                  opacity="{{ $opacity }}"/>
            <!-- Groove line -->
            <path d="M 3,6 L 12,6" 
                  stroke="{{ $strokeColor }}" 
                  stroke-width="0.8"
                  opacity="0.5"/>
        </g>
    @elseif($isIncisor)
        <!-- Incisor SVG - narrow, rectangular with rounded edges -->
        <g transform="translate(-6, -14)">
            <path d="M 0,0 
                     C 1.5,0 2.5,0.5 3,1.5
                     L 3,10
                     C 3,11 2.5,11.5 1.5,11.5
                     L -1.5,11.5
                     C -2.5,11.5 -3,11 -3,10
                     L -3,1.5
                     C -2.5,0.5 -1.5,0 0,0 Z" 
                  fill="{{ $fillColor }}" 
                  stroke="{{ $strokeColor }}" 
                  stroke-width="1.5"
                  opacity="{{ $opacity }}"/>
            <!-- Central line -->
            <path d="M 0,2 L 0,9" 
                  stroke="{{ $strokeColor }}" 
                  stroke-width="0.8"
                  opacity="0.4"/>
        </g>
    @elseif($isCanine)
        <!-- Canine SVG - pointed, sharp tooth -->
        <g transform="translate(-7, -14)">
            <path d="M 0,0 
                     L 3,2
                     L 3.5,4
                     L 3.5,10
                     C 3.5,11 3,11.5 2,11.5
                     L -2,11.5
                     C -3,11.5 -3.5,11 -3.5,10
                     L -3.5,4
                     L -3,2
                     Z" 
                  fill="{{ $fillColor }}" 
                  stroke="{{ $strokeColor }}" 
                  stroke-width="1.5"
                  opacity="{{ $opacity }}"/>
            <!-- Central line -->
            <path d="M 0,2 L 0,9" 
                  stroke="{{ $strokeColor }}" 
                  stroke-width="0.8"
                  opacity="0.4"/>
        </g>
    @elseif($isPremolar)
        <!-- Premolar SVG - between incisor and molar -->
        <g transform="translate(-10, -14)">
            <!-- Main body -->
            <path d="M 0,0 
                     C 2,0 3.5,0.5 4.5,2
                     L 4.5,10
                     C 4.5,11 3.5,11.5 2,11.5
                     L -2,11.5
                     C -3.5,11.5 -4.5,11 -4.5,10
                     L -4.5,2
                     C -3.5,0.5 -2,0 0,0 Z" 
                  fill="{{ $fillColor }}" 
                  stroke="{{ $strokeColor }}" 
                  stroke-width="1.5"
                  opacity="{{ $opacity }}"/>
            <!-- Second cusp -->
            <path d="M 4.5,2 
                     C 5.5,0.5 7,0 9,0
                     L 12,0
                     C 13.5,0 14.5,0.5 14.5,2
                     L 14.5,10
                     C 14.5,11 13.5,11.5 12,11.5
                     L 9,11.5
                     C 7,11.5 6,11 6,10
                     L 6,2
                     C 5.5,1.5 5,2 4.5,2 Z" 
                  fill="{{ $fillColor }}" 
                  stroke="{{ $strokeColor }}" 
                  stroke-width="1.5"
                  opacity="{{ $opacity }}"/>
            <!-- Groove line -->
            <path d="M 2,6 L 12,6" 
                  stroke="{{ $strokeColor }}" 
                  stroke-width="0.8"
                  opacity="0.5"/>
        </g>
    @endif
    
    <!-- Tooth number -->
    <text x="0" y="4" text-anchor="middle" dominant-baseline="central" 
          font-size="10" font-weight="bold" 
          fill="{{ ($condition === 'extracted' || $condition === 'missing') ? '#ffffff' : '#333333' }}"
          style="pointer-events: none;">
        {{ $toothNumber }}
    </text>
    
    <!-- Badge indicator for records -->
    @if($hasRecord)
        <circle cx="10" cy="-8" r="3.5" fill="#ffc107" stroke="#fff" stroke-width="1.5"/>
    @endif
    
    <!-- Highlight effect (light reflection) -->
    @if($condition !== 'extracted' && $condition !== 'missing')
        <ellipse cx="0" cy="-8" rx="7" ry="4" fill="rgba(255,255,255,0.3)" opacity="0.6"/>
    @endif
</g>

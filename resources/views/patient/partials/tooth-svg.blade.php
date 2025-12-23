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
    
    // Determine tooth type
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
    
    $opacity = ($condition === 'extracted' || $condition === 'missing') ? '0.6' : '1';
@endphp

<g class="{{ $classes }}" data-tooth="{{ $toothNumber }}" transform="translate({{ $x }}, {{ $y }})" style="cursor: pointer;">
    <title>{{ $title }}</title>
    
    <!-- SVG icon only -->
    <g transform="translate(-20, -20)">
        <image href="{{ asset('dental/dental.svg') }}" 
               x="0" 
               y="0" 
               width="40" 
               height="40"
               opacity="{{ $opacity }}"
               preserveAspectRatio="xMidYMid meet"/>
    </g>
    
    <!-- Tooth number only -->
    <text x="0" y="8" text-anchor="middle" dominant-baseline="central" 
          font-size="11" font-weight="bold" 
          fill="#333333"
          style="pointer-events: none;">
        {{ $toothNumber }}
    </text>
</g>

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
    
    $opacity = ($condition === 'extracted' || $condition === 'missing') ? '0.5' : '1';
    $showX = ($condition === 'extracted' || $condition === 'missing');
@endphp

<g class="{{ $classes }}" data-tooth="{{ $toothNumber }}" transform="translate({{ $x }}, {{ $y }})" style="cursor: pointer;">
    <title>{{ $title }}</title>
    
    <!-- Use tooth.png image -->
    <g transform="translate(-30, -35)">
        <image href="{{ asset('images/dental/tooth.png') }}" 
               x="0" 
               y="0" 
               width="60" 
               height="70"
               opacity="{{ $opacity }}"
               preserveAspectRatio="xMidYMid meet"/>
        
        <!-- X mark overlay for extracted/missing teeth -->
        @if($showX)
            <path d="M 10,15 L 50,55 M 50,15 L 10,55" 
                  stroke="#000000" 
                  stroke-width="4"
                  opacity="0.8"/>
        @endif
    </g>
    
    <!-- Tooth number below -->
    <text x="0" y="45" text-anchor="middle" dominant-baseline="central" 
          font-size="13" font-weight="bold" 
          fill="#333333"
          style="pointer-events: none;">
        {{ $toothNumber }}
    </text>
</g>

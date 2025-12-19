@php
    $record = $teethRecords[$toothNumber] ?? null;
    $condition = $record->condition ?? '';
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
@endphp

<g class="{{ $classes }}" data-tooth="{{ $toothNumber }}" transform="translate({{ $x }}, {{ $y }})" style="cursor: pointer;">
    <title>{{ $title }}</title>
    
    @if($isMolar)
        <!-- Molar shape (larger, more square) -->
        <path d="M 0,0 Q 5,0 10,5 L 10,20 Q 10,25 5,25 L 5,25 Q 0,25 0,20 Z" 
              fill="{{ $fillColor }}" 
              stroke="{{ $strokeColor }}" 
              stroke-width="1.5"
              opacity="{{ ($condition === 'extracted' || $condition === 'missing') ? '0.7' : '1' }}"/>
        <path d="M 10,5 Q 15,5 20,10 L 20,20 Q 20,25 15,25 L 10,25 Q 10,25 10,20 Z" 
              fill="{{ $fillColor }}" 
              stroke="{{ $strokeColor }}" 
              stroke-width="1.5"
              opacity="{{ ($condition === 'extracted' || $condition === 'missing') ? '0.7' : '1' }}"/>
    @elseif($isIncisor)
        <!-- Incisor shape (narrow, rectangular) -->
        <rect x="0" y="0" width="12" height="28" rx="6" ry="6" 
              fill="{{ $fillColor }}" 
              stroke="{{ $strokeColor }}" 
              stroke-width="1.5"
              opacity="{{ ($condition === 'extracted' || $condition === 'missing') ? '0.7' : '1' }}"/>
    @elseif($isCanine)
        <!-- Canine shape (pointed) -->
        <path d="M 6,0 L 0,8 L 0,20 Q 0,24 4,24 L 8,24 Q 12,24 12,20 L 12,8 Z" 
              fill="{{ $fillColor }}" 
              stroke="{{ $strokeColor }}" 
              stroke-width="1.5"
              opacity="{{ ($condition === 'extracted' || $condition === 'missing') ? '0.7' : '1' }}"/>
    @elseif($isPremolar)
        <!-- Premolar shape (between incisor and molar) -->
        <path d="M 0,0 Q 3,0 6,3 L 6,20 Q 6,24 3,24 L 3,24 Q 0,24 0,20 Z" 
              fill="{{ $fillColor }}" 
              stroke="{{ $strokeColor }}" 
              stroke-width="1.5"
              opacity="{{ ($condition === 'extracted' || $condition === 'missing') ? '0.7' : '1' }}"/>
        <path d="M 6,3 Q 9,3 12,6 L 12,20 Q 12,24 9,24 L 6,24 Q 6,24 6,20 Z" 
              fill="{{ $fillColor }}" 
              stroke="{{ $strokeColor }}" 
              stroke-width="1.5"
              opacity="{{ ($condition === 'extracted' || $condition === 'missing') ? '0.7' : '1' }}"/>
    @endif
    
    <!-- Tooth number -->
    <text x="12" y="18" text-anchor="middle" dominant-baseline="central" 
          font-size="9" font-weight="bold" 
          fill="{{ ($condition === 'extracted' || $condition === 'missing') ? '#ffffff' : '#333333' }}">
        {{ $toothNumber }}
    </text>
    
    <!-- Badge indicator for records -->
    @if($hasRecord)
        <circle cx="20" cy="3" r="4" fill="#ffc107" stroke="#fff" stroke-width="1.5"/>
    @endif
    
    <!-- Highlight effect (light reflection) -->
    <ellipse cx="12" cy="6" rx="6" ry="4" fill="rgba(255,255,255,0.4)" opacity="0.7"/>
</g>


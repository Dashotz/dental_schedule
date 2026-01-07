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
    $title = 'Tooth #' . $toothNumber;
    if ($record && $condition) {
        $title .= ' - ' . ucfirst(str_replace('_', ' ', $condition));
    }
    if ($record && $record->remarks) {
        $title .= ' - ' . \Illuminate\Support\Str::limit($record->remarks, 50);
    }
    
    // Condition-based styling
    $conditionColors = [
        'healthy' => 'bg-green-100 border-green-300 text-green-800',
        'cavity' => 'bg-red-100 border-red-300 text-red-800',
        'filling' => 'bg-blue-100 border-blue-300 text-blue-800',
        'crown' => 'bg-yellow-100 border-yellow-300 text-yellow-800',
        'extracted' => 'bg-gray-100 border-gray-300 text-gray-600 opacity-50',
        'missing' => 'bg-gray-100 border-gray-300 text-gray-600 opacity-50',
        'impacted' => 'bg-purple-100 border-purple-300 text-purple-800',
        'root_canal' => 'bg-orange-100 border-orange-300 text-orange-800',
        'other' => 'bg-indigo-100 border-indigo-300 text-indigo-800',
    ];
    $toothColor = $conditionColors[$condition] ?? 'bg-blue-50 border-blue-200 text-blue-700';
    $isExtractedOrMissing = ($condition === 'extracted' || $condition === 'missing');
@endphp

<button type="button" 
        class="tooth-button relative w-16 h-20 flex flex-col items-center justify-center rounded-lg border-2 {{ $toothColor }} transition-all duration-200 hover:scale-110 hover:shadow-lg hover:z-10 cursor-pointer group {{ $isExtractedOrMissing ? 'opacity-60' : '' }}"
        data-tooth="{{ $toothNumber }}"
        title="{{ $title }}">
    <!-- Tooth Image/Icon -->
    <div class="relative w-12 h-14 flex items-center justify-center mb-1">
        @if($isExtractedOrMissing)
            <!-- X mark for extracted/missing -->
            <div class="absolute inset-0 flex items-center justify-center z-10">
                <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
        @endif
        
        <!-- Individual tooth asset - using asset helper for better performance -->
        <div class="w-12 h-14 text-current transition-transform duration-200 group-hover:scale-110 {{ $isExtractedOrMissing ? 'opacity-50' : '' }} flex items-center justify-center">
            <img src="{{ asset('images/dental/teeth/tooth-' . $toothNumber . '.svg') }}" 
                 alt="Tooth {{ $toothNumber }}"
                 class="w-full h-full object-contain"
                 style="filter: brightness(0) saturate(100%) invert(1); mix-blend-mode: multiply;"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
            <!-- Fallback SVG if asset doesn't exist -->
            <svg class="w-12 h-14 text-current transition-transform duration-200 group-hover:scale-110 {{ $isExtractedOrMissing ? 'opacity-50' : '' }}" 
                 fill="currentColor" 
                 viewBox="0 0 24 24"
                 style="display: none;">
                <path d="M12 2C8.13 2 5 5.13 5 9c0 2.38 1.19 4.47 3 5.74V17c0 .55.45 1 1 1h6c.55 0 1-.45 1-1v-2.26c1.81-1.27 3-3.36 3-5.74 0-3.87-3.13-7-7-7zm-1 13h2v3h-2v-3zm0-8h2v6h-2V7z"/>
            </svg>
        </div>
        
        @if($hasRecord && !$isExtractedOrMissing)
            <!-- Indicator dot for records -->
            <span class="absolute top-0 right-0 w-3 h-3 bg-dental-teal rounded-full border-2 border-white z-10"></span>
        @endif
    </div>
    
    <!-- Tooth Number -->
    <span class="text-xs font-bold text-current">{{ $toothNumber }}</span>
    
    <!-- Condition badge on hover -->
    @if($condition && !$isExtractedOrMissing)
        <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap pointer-events-none z-20">
            {{ ucfirst(str_replace('_', ' ', $condition)) }}
        </div>
    @endif
</button>

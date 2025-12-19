@php
    $record = $teethRecords[$toothNumber] ?? null;
    $condition = $record->condition ?? '';
    $hasRecord = $record !== null;
    $classes = 'tooth';
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
@endphp
<div class="{{ $classes }}" data-tooth="{{ $toothNumber }}" title="{{ $title }}">
    <span class="tooth-number">{{ $toothNumber }}</span>
    @if($hasRecord)
        <span class="tooth-badge"></span>
    @endif
</div>


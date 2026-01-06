@props([
    'id' => 'modal',
    'title' => '',
    'size' => 'md', // sm, md, lg, xl, full
    'showClose' => true,
    'footer' => null
])

@php
    $sizeClasses = [
        'sm' => 'max-w-md',
        'md' => 'max-w-lg',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl',
        'full' => 'max-w-7xl'
    ];
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<div class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center" id="{{ $id }}">
    <div class="bg-white rounded-2xl shadow-2xl {{ $sizeClass }} w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="card-dental-header flex justify-between items-center">
            @if($title)
                <h5 class="text-lg font-semibold">{{ $title }}</h5>
            @else
                <div></div>
            @endif
            @if($showClose)
                <button type="button" class="text-white hover:text-gray-200 text-2xl transition-colors" onclick="closeModal('{{ $id }}')">
                    &times;
                </button>
            @endif
        </div>
        <div class="p-6">
            {{ $slot }}
        </div>
        @if(isset($footer))
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-2">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>



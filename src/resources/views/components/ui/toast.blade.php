@props([
    'type' => 'info',
    'message' => '',
])

@php
    $colors = [
        'success' => 'bg-green-500',
        'error' => 'bg-red-500',
        'warning' => 'bg-yellow-500',
        'info' => 'bg-indigo-500',
    ];
    $icons = [
        'success' => 'bi-check-circle-fill',
        'error' => 'bi-x-circle-fill',
        'warning' => 'bi-exclamation-triangle-fill',
        'info' => 'bi-info-circle-fill',
    ];
    $color = $colors[$type] ?? $colors['info'];
    $icon = $icons[$type] ?? $icons['info'];
@endphp

<div x-data="{ show: false }" 
     x-show="show" 
     x-init="setTimeout(() => show = true, 100); setTimeout(() => show = false, 5000)"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="translate-x-full opacity-0"
     x-transition:enter-end="translate-x-0 opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="translate-x-0 opacity-100"
     x-transition:leave-end="translate-x-full opacity-0"
     class="fixed top-20 right-4 z-50 max-w-sm">
    <div class="{{ $color }} text-white rounded-lg shadow-lg p-4 flex items-center gap-3">
        <i class="bi {{ $icon }} text-xl"></i>
        <p class="font-medium">{{ $message }}</p>
        <button @click="show = false" class="ml-auto text-white/80 hover:text-white">
            <i class="bi bi-x"></i>
        </button>
    </div>
</div>
